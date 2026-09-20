<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;

/**
 * نموذج المجموعة الدراسية
 */
class Group extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'subject_id',
        'grade',
        'teacher_id',
        'room',
        'days',
        'start_time',
        'end_time',
        'max_students',
        'monthly_fee',
        'start_date',
        'status',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'days'        => 'array',
            'start_time'  => 'datetime:H:i',
            'end_time'    => 'datetime:H:i',
            'monthly_fee' => 'decimal:2',
            'start_date'  => 'date',
        ];
    }

    // ── العلاقات ──

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    public function students()
    {
        return $this->belongsToMany(Student::class, 'enrollments', 'group_id', 'student_id')
            ->withPivot(['enrollment_date', 'agreed_fee', 'discount', 'status', 'notes'])
            ->withTimestamps();
    }

    public function sessions()
    {
        return $this->hasMany(Session::class);
    }

    public function exams()
    {
        return $this->hasMany(Exam::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    // ── Accessors ──

    /** عدد الطلاب النشطين حالياً */
    protected function currentStudentsCount(): Attribute
    {
        return Attribute::get(function () {
            if ($this->relationLoaded('enrollments')) {
                return $this->enrollments->where('status', 'active')->count();
            }
            return $this->enrollments()->where('status', 'active')->count();
        });
    }

    /** هل المجموعة ممتلئة؟ */
    protected function isFull(): Attribute
    {
        return Attribute::get(fn () => $this->current_students_count >= $this->max_students);
    }

    /** المقاعد المتبقية */
    protected function remainingSeats(): Attribute
    {
        return Attribute::get(fn () => max(0, $this->max_students - $this->current_students_count));
    }

    /** حالة المجموعة بالعربية */
    protected function statusLabel(): Attribute
    {
        return Attribute::get(fn () => match ($this->status) {
            'open'   => 'مفتوحة',
            'full'   => 'ممتلئة',
            'paused' => 'موقوفة',
            default  => $this->status,
        });
    }

    // ── Scopes ──

    public function scopeOpen($query)
    {
        return $query->where('status', 'open');
    }
}
