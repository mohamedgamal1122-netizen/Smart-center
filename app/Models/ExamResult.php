<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;

/**
 * نموذج نتيجة الاختبار
 */
class ExamResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'exam_id',
        'student_id',
        'score',
        'grade',
        'rank',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'score' => 'decimal:2',
        ];
    }

    // ── العلاقات ──

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    // ── Accessors (محسوبة تلقائياً) ──

    /**
     * النسبة المئوية = (الدرجة / الدرجة العظمى) * 100
     */
    protected function percentage(): Attribute
    {
        return Attribute::get(function () {
            $max = $this->exam?->max_score ?? 100;
            if ((float) $max == 0) return 0;
            return round(((float) $this->score / (float) $max) * 100, 2);
        });
    }

    /**
     * التقدير التلقائي حسب النسبة (إن لم يكن مدخلاً يدوياً)
     */
    protected function computedGrade(): Attribute
    {
        return Attribute::get(function () {
            if (!empty($this->grade)) return $this->grade;
            $p = $this->percentage;
            return match (true) {
                $p >= 90 => 'ممتاز',
                $p >= 80 => 'جيد جداً',
                $p >= 70 => 'جيد',
                $p >= 60 => 'مقبول',
                $p >= 50 => 'ضعيف',
                default  => 'راسب',
            };
        });
    }

    /** هل الطالب ناجح؟ (>= 50%) */
    protected function isPassed(): Attribute
    {
        return Attribute::get(fn () => $this->percentage >= 50);
    }

    // ── Scopes ──

    public function scopePassed($query)
    {
        // لا يمكن فلترة accessor مباشرة، لكن نفلتر بالتقدير
        return $query->where('grade', '!=', 'راسب');
    }
}
