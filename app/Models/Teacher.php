<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * نموذج المدرس
 */
class Teacher extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'phone',
        'email',
        'specialization',
        'rate_type',
        'rate_value',
        'start_date',
        'is_active',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'rate_value' => 'decimal:2',
            'start_date' => 'date',
            'is_active'  => 'boolean',
        ];
    }

    // ── العلاقات ──

    /** المجموعات التي يدرّسها المدرس */
    public function groups()
    {
        return $this->hasMany(Group::class);
    }

    // ── Scopes ──

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // ── Accessors ──

    /**
     * هل الحساب بالنسبة المئوية؟
     */
    public function getIsPercentageAttribute(): bool
    {
        return $this->rate_type === 'percentage';
    }

    /**
     * هل الحساب بالحصة؟
     */
    public function getIsPerSessionAttribute(): bool
    {
        return $this->rate_type === 'per_session';
    }

    /**
     * وصف نوع الحساب بالعربية
     */
    public function getRateTypeLabelAttribute(): string
    {
        return match ($this->rate_type) {
            'per_session' => 'بالحصة',
            'percentage'  => 'نسبة مئوية',
            default       => $this->rate_type,
        };
    }
}
