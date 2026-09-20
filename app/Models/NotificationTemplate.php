<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotificationTemplate extends Model
{
    protected $fillable = [
        'name',
        'event_type',
        'channel',
        'subject_ar',
        'subject_en',
        'body_ar',
        'body_en',
        'available_variables',
        'is_active',
        'role_target',
        'description',
    ];

    protected $casts = [
        'available_variables' => 'array',
        'is_active' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeForEvent($query, string $eventType)
    {
        return $query->where('event_type', $eventType);
    }

    public function getSubject($locale = null)
    {
        $locale = $locale ?? app()->getLocale();
        return $locale === 'ar' ? $this->subject_ar : $this->subject_en;
    }

    public function getBody($locale = null)
    {
        $locale = $locale ?? app()->getLocale();
        return $locale === 'ar' ? $this->body_ar : $this->body_en;
    }
}