<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * نموذج سجل التدقيق
 */
class AuditLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'action',
        'model_type',
        'model_id',
        'old_values',
        'new_values',
        'ip',
    ];

    protected function casts(): array
    {
        return [
            'old_values' => 'array',
            'new_values' => 'array',
        ];
    }

    // ── العلاقات ──

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /** العلاقة المورفية مع النموذج المتأثر (polymorphic) */
    public function auditable()
    {
        return $this->morphTo('auditable', 'model_type', 'model_id');
    }

    /**
     * مساعد لإنشاء سجل تدقيق بسرعة
     */
    public static function record(
        ?int $userId,
        string $action,
        string $modelType,
        int|string $modelId,
        ?array $oldValues = null,
        ?array $newValues = null,
        ?string $ip = null
    ): static {
        return static::create([
            'user_id'    => $userId ?? auth()->id(),
            'action'     => $action,
            'model_type' => $modelType,
            'model_id'   => $modelId,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'ip'         => $ip ?? request()->ip(),
        ]);
    }
}
