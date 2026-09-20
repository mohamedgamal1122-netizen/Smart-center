<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Payment;
use App\Models\Enrollment;
use App\Models\Group;
use App\Models\Notification;
use App\Models\Attendance;
use App\Models\Student;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CheckOverdueAndNotify extends Command
{
    protected $signature = 'notify:check-overdue {--dry : عرض فقط بدون إنشاء إشعارات}';
    protected $description = 'فحص المتأخرات والغياب المتكرر والمجموعات المكتملة ومستويات الطلاب النازلة وإنشاء إشعارات داخلية';

    public function handle(): int
    {
        $dry = $this->option('dry');
        $created = 0;

        // 1) متأخرات: مدفوعات غير مكتملة عن أشهر سابقة أو الشهر الحالي بعد يوم 10
        $now = now('Africa/Cairo');
        $currentYear = (int) $now->year;
        $currentMonth = (int) $now->month;

        $overduePayments = Payment::with(['student', 'group'])
            ->where('is_cancelled', false)
            ->where(function ($q) use ($currentYear, $currentMonth, $now) {
                $q->where(function ($qq) use ($currentYear, $currentMonth) {
                    $qq->where('year', '<', $currentYear)
                       ->orWhere(function ($qq2) use ($currentYear, $currentMonth) {
                           $qq2->where('year', $currentYear)->where('month', '<', $currentMonth);
                       });
                });
                // أو الشهر الحالي بعد يوم 10 ولم يسدد بالكامل
                if ($now->day >= 10) {
                    $q->orWhere(function ($qq) use ($currentYear, $currentMonth) {
                        $qq->where('year', $currentYear)->where('month', $currentMonth);
                    });
                }
            })->get()->filter(fn($p) => $p->remaining > 0);

        foreach ($overduePayments as $p) {
            $title = 'متأخرات: ' . ($p->student->name ?? 'طالب');
            $exists = Notification::where('title', $title)
                ->where('type', 'warning')
                ->where('message', 'like', "%{$p->month}/{$p->year}%")
                ->where('created_at', '>=', now()->subDays(7))
                ->exists();
            if ($exists) continue;

            $msg = "الطالب {$p->student->name} عليه متأخرات {$p->remaining} جنيه عن {$p->month}/{$p->year}" . ($p->group ? " - مجموعة {$p->group->name}" : "");
            if (!$dry) {
                Notification::create(['title' => $title, 'message' => $msg, 'type' => 'warning', 'is_read' => false, 'user_id' => null]);
            }
            $created++;
            $this->line("Overdue: {$msg}");
        }

        // 2) غياب متكرر: 3 غيابات خلال آخر 14 يوم
        $absentCounts = DB::table('attendances')
            ->join('class_sessions', 'class_sessions.id', '=', 'attendances.session_id')
            ->where('attendances.status', 'absent')
            ->where('class_sessions.date', '>=', now()->subDays(14)->toDateString())
            ->select('attendances.student_id', DB::raw('count(*) as cnt'))
            ->groupBy('attendances.student_id')
            ->having('cnt', '>=', 3)
            ->get();

        foreach ($absentCounts as $row) {
            $student = Student::find($row->student_id);
            if (!$student) continue;
            $title = 'غياب متكرر: ' . $student->name;
            $exists = Notification::where('title', $title)->where('created_at', '>=', now()->subDays(7))->exists();
            if ($exists) continue;
            $msg = "الطالب {$student->name} غاب {$row->cnt} مرات خلال آخر 14 يوم. يرجى المتابعة مع ولي الأمر.";
            if (!$dry) Notification::create(['title' => $title, 'message' => $msg, 'type' => 'warning', 'is_read' => false, 'user_id' => null]);
            $created++;
            $this->line("Absence: {$msg}");
        }

        // 3) مجموعة مكتملة
        $fullGroups = Group::withCount(['enrollments as active_count' => fn($q) => $q->where('status', 'active')])->get()->filter(fn($g) => $g->active_count >= $g->max_students);
        foreach ($fullGroups as $g) {
            $title = 'مجموعة مكتملة: ' . $g->name;
            $exists = Notification::where('title', $title)->where('created_at', '>=', now()->subDays(7))->exists();
            if ($exists) continue;
            $msg = "المجموعة {$g->name} اكتملت ({$g->active_count}/{$g->max_students}). لا يمكن إضافة طلاب جدد إلا بموافقة المدير.";
            if (!$dry) Notification::create(['title' => $title, 'message' => $msg, 'type' => 'info', 'is_read' => false, 'user_id' => null]);
            $created++;
            $this->line("Full: {$msg}");
        }

        // 4) مستوى نازل: متوسط آخر امتحانين أقل من 50% أو أقل من المتوسط السابق بـ 20%
        // يعتمد على وجود exam_results
        try {
            $students = Student::where('status', 'active')->get();
            foreach ($students as $st) {
                $recent = DB::table('exam_results')->where('student_id', $st->id)->orderByDesc('id')->limit(4)->get();
                if ($recent->count() < 2) continue;
                $avgRecent = $recent->take(2)->avg('score');
                $avgPrev = $recent->slice(2)->avg('score');
                $low = $avgRecent !== null && $avgRecent < 50;
                $drop = $avgPrev && $avgRecent < ($avgPrev * 0.8);
                if ($low || $drop) {
                    $title = 'مستوى نازل: ' . $st->name;
                    $exists = Notification::where('title', $title)->where('created_at', '>=', now()->subDays(14))->exists();
                    if ($exists) continue;
                    $msg = "الطالب {$st->name} مستواه نازل — متوسط آخر درجتين: ".round($avgRecent,1).($avgPrev ? " (السابق ".round($avgPrev,1).")" : "");
                    if (!$dry) Notification::create(['title' => $title, 'message' => $msg, 'type' => 'error', 'is_read' => false, 'user_id' => null]);
                    $created++;
                    $this->line("Level: {$msg}");
                }
            }
        } catch (\Throwable $e) {
            Log::warning('CheckOverdue level check failed: '.$e->getMessage());
        }

        $this->info($dry ? "[DRY] سيُنشأ {$created} إشعار(s)." : "تم إنشاء {$created} إشعار(s).");
        return self::SUCCESS;
    }
}
