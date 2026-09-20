<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;

/**
 * نموذج الاختبار
 */
class Exam extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'group_id',
        'subject_id',
        'exam_date',
        'max_score',
        'exam_type',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'exam_date' => 'date',
            'max_score' => 'decimal:2',
        ];
    }

    // ── العلاقات ──

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function results()
    {
        return $this->hasMany(ExamResult::class);
    }

    // ── Accessors ──

    protected function examTypeLabel(): Attribute
    {
        return Attribute::get(fn () => match ($this->exam_type) {
            'quiz'    => 'اختبار قصير',
            'monthly' => 'اختبار شهري',
            'final'   => 'اختبار نهائي',
            default   => $this->exam_type,
        });
    }
}
