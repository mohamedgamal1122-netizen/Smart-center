<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;

/**
 * نموذج تسجيل الطالب في مجموعة
 */
class Enrollment extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'group_id',
        'enrollment_date',
        'agreed_fee',
        'discount',
        'status',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'enrollment_date' => 'date',
            'agreed_fee'      => 'decimal:2',
            'discount'        => 'decimal:2',
        ];
    }

    // ── العلاقات ──

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    // ── Accessors ──

    /** صافي الرسوم بعد الخصم */
    protected function netFee(): Attribute
    {
        return Attribute::get(fn () => (float) $this->agreed_fee - (float) $this->discount);
    }

    /** حالة التسجيل بالعربية */
    protected function statusLabel(): Attribute
    {
        return Attribute::get(fn () => match ($this->status) {
            'active'    => 'نشط',
            'paused'    => 'موقوف',
            'cancelled' => 'ملغى',
            default     => $this->status,
        });
    }

    // ── Scopes ──

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
