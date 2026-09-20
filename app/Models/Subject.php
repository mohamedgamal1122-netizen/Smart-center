<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * نموذج المادة الدراسية
 */
class Subject extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'stage',
        'description',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    // ── العلاقات ──

    /** المجموعات التابعة للمادة */
    public function groups()
    {
        return $this->hasMany(Group::class);
    }

    /** الاختبارات التابعة للمادة */
    public function exams()
    {
        return $this->hasMany(Exam::class);
    }

    // ── Scopes ──

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
