<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;

/**
 * نموذج الحضور
 */
class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'session_id',
        'student_id',
        'status',
        'notes',
    ];

    // ── العلاقات ──

    public function session()
    {
        return $this->belongsTo(Session::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    // ── Accessors ──

    protected function statusLabel(): Attribute
    {
        return Attribute::get(fn () => match ($this->status) {
            'present' => 'حاضر',
            'absent'  => 'غائب',
            'late'    => 'متأخر',
            'excused' => 'معذور',
            default   => $this->status,
        });
    }

    /** هل الطالب كان حاضراً (حاضر أو متأخر يعتبر حضور) */
    protected function isPresent(): Attribute
    {
        return Attribute::get(fn () => in_array($this->status, ['present', 'late']));
    }
}
