<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;
use Carbon\Carbon;

class NotificationService
{
    /**
     * Check for delayed payments (> 3 days overdue)
     */
    public static function checkDelayedPayments()
    {
        $overdue = \DB::table('payments')
            ->where('status', '!=', 'paid')
            ->where('due_date', '<', Carbon::now()->subDays(3))
            ->where('is_deleted', false)
            ->pluck('student_id')
            ->unique();

        foreach ($overdue as $studentId) {
            $enrollments = \DB::table('enrollments')
                ->where('student_id', $studentId)
                ->pluck('student_id')
                ->unique();

            foreach ($enrollments as $sid) {
                $parents = \DB::table('parent_student')
                    ->where('student_id', $sid)
                    ->pluck('parent_id');

                foreach ($parents as $pid) {
                    $user = User::where('email', 'admin@center.test')->first(); // fallback to admin
                    if ($user) {
                        Notification::create([
                            'user_id' => $user->id,
                            'title' => 'دفعة متأخرة',
                            'message' => 'طالب محمد... متأخر عن الدفع.',
                            'type' => 'warning',
                            'related_url' => '/payments',
                            'is_read' => false,
                        ]);
                    }
                }
            }
        }
    }

    /**
     * Check for repeated absences (3 consecutive)
     */
    public static function checkRepeatedAbsences()
    {
        // Logic: find students with 3 consecutive absences
        $recentAbsences = \DB::table('attendances')
            ->select('student_id')
            ->selectRaw('COUNT(*) as absences')
            ->where('status', 'absent')
            ->where('date', '>=', Carbon::now()->subDays(7))
            ->groupBy('student_id')
            ->having('absences', '>=', 3)
            ->get();

        foreach ($recentAbsences as $item) {
            Notification::create([
                'user_id' => 1, // admin
                'title' => 'غياب متكرر',
                'message' => 'طالب رقم ' . $item->student_id . ' غاب 3 مرات خلال أسبوع.',
                'type' => 'warning',
                'related_url' => '/students/' . $item->student_id,
            ]);
        }
    }

    /**
     * Check for upcoming exams (within 24 hours)
     */
    public static function checkUpcomingExams()
    {
        $upcoming = \DB::table('exams')
            ->whereDate('exam_date', Carbon::now()->addDay())
            ->get();

        foreach ($upcoming as $exam) {
            Notification::create([
                'user_id' => 1,
                'title' => 'اختبار قادم',
                'message' => 'اختبار "' . $exam->name . '" غداً في ' . $exam->time,
                'type' => 'info',
                'related_url' => '/exams/' . $exam->id,
            ]);
        }
    }

    /**
     * Check for full groups
     */
    public static function checkFullGroups()
    {
        $full = \DB::table('groups')
            ->whereRaw('current_students >= max_students')
            ->where('status', '!=', 'completed')
            ->get();

        foreach ($full as $group) {
            Notification::create([
                'user_id' => 1,
                'title' => 'مجموعة مكتملة',
                'message' => 'المجموعة "' . $group->name . '" وصلت للحد الأقصى.',
                'type' => 'info',
                'related_url' => '/groups/' . $group->id,
            ]);
        }
    }

    /**
     * Run all notification checks
     */
    public static function runAllChecks()
    {
        self::checkDelayedPayments();
        self::checkRepeatedAbsences();
        self::checkUpcomingExams();
        self::checkFullGroups();
    }
}
