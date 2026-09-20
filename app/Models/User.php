<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

use App\Models\Notification;

/**
 * نموذج المستخدم — يدعم الأدوار المختلفة في السنتر
 */
class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    /**
     * الحقول القابلة للتعبئة
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'is_active',
    ];

    /**
     * الحقول المخفية عند التسلسل
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * التحويلات
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'is_active'         => 'boolean',
        ];
    }

    // ── العلاقات ──

    /** المدفوعات التي أنشأها المستخدم */
    public function payments()
    {
        return $this->hasMany(Payment::class, 'created_by');
    }

    /** المصروفات التي أنشأها المستخدم */
    public function expenses()
    {
        return $this->hasMany(Expense::class, 'created_by');
    }

    /** سجلات التدقيق الخاصة بالمستخدم */
    public function auditLogs()
    {
        return $this->hasMany(AuditLog::class);
    }

    /** الإشعارات الخاصة بالمستخدم */
    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    /** الإشعارات غير المقروءة */
    public function unreadNotifications()
    {
        return $this->hasMany(Notification::class)->where('is_read', false);
    }

    // ── Scopes ──

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeRole($query, string $role)
    {
        return $query->where('role', $role);
    }

    // ── Helpers ──

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isReceptionist(): bool
    {
        return $this->role === 'receptionist';
    }
}
