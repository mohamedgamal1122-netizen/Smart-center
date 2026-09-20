<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;

/**
 * نموذج الحصة
 */
class Session extends Model
{
    use HasFactory;

    protected $table = 'class_sessions';

    protected $fillable = [
        'group_id',
        'date',
        'start_time',
        'end_time',
        'status',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'date'       => 'date',
            'start_time' => 'datetime:H:i',
            'end_time'   => 'datetime:H:i',
        ];
    }

    // ── العلاقات ──

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    /** الطلاب عبر الحضور */
    public function students()
    {
        return $this->belongsToMany(Student::class, 'attendances', 'session_id', 'student_id')
            ->withPivot(['status', 'notes'])
            ->withTimestamps();
    }

    // ── Accessors ──

    protected function statusLabel(): Attribute
    {
        return Attribute::get(fn () => match ($this->status) {
            'completed' => 'مكتملة',
            'cancelled' => 'ملغاة',
            'postponed' => 'مؤجلة',
            'makeup'    => 'تعويضية',
            default     => $this->status,
        });
    }

    // ── Scopes ──

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }
}
