<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;

/**
 * نموذج الطالب
 */
class Student extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'code',
        'name',
        'birth_date',
        'gender',
        'grade',
        'school',
        'phone',
        'status',
        'enrollment_date',
        'notes',
        'photo',
    ];

    protected function casts(): array
    {
        return [
            'birth_date'      => 'date',
            'enrollment_date' => 'date',
        ];
    }

    // ── Boot: توليد كود تلقائي ──
    protected static function booted(): void
    {
        static::creating(function (Student $student) {
            if (empty($student->code)) {
                // كود بصيغة STU-YYYY-XXXX
                $year = date('Y');
                $last = static::where('code', 'like', "STU-{$year}-%")->orderByDesc('id')->first();
                $seq = $last ? ((int) substr($last->code, -4) + 1) : 1;
                $student->code = sprintf('STU-%s-%04d', $year, $seq);
            }
        });
    }

    // ── العلاقات ──

    /** أولياء الأمور */
    public function parents()
    {
        return $this->belongsToMany(ParentModel::class, 'parent_student', 'student_id', 'parent_id')
            ->withTimestamps();
    }

    /** التسجيلات في المجموعات */
    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    /** المجموعات عبر التسجيلات */
    public function groups()
    {
        return $this->belongsToMany(Group::class, 'enrollments', 'student_id', 'group_id')
            ->withPivot(['enrollment_date', 'agreed_fee', 'discount', 'status', 'notes'])
            ->withTimestamps();
    }

    /** سجلات الحضور */
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    /** نتائج الاختبارات */
    public function examResults()
    {
        return $this->hasMany(ExamResult::class);
    }

    /** المدفوعات */
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    // ── Accessors ──

    /** عمر الطالب بالسنوات */
    protected function age(): Attribute
    {
        return Attribute::get(fn () => $this->birth_date ? $this->birth_date->age : null);
    }

    /** حالة الطالب بالعربية */
    protected function statusLabel(): Attribute
    {
        return Attribute::get(fn () => match ($this->status) {
            'active'    => 'نشط',
            'paused'    => 'موقوف',
            'withdrawn' => 'منسحب',
            default     => $this->status,
        });
    }

    // ── Scopes ──

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
