<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;

/**
 * نموذج المصروفات
 */
class Expense extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'category',
        'amount',
        'expense_date',
        'payment_method',
        'description',
        'receipt_path',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'amount'       => 'decimal:2',
            'expense_date' => 'date',
        ];
    }

    // ── العلاقات ──

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // ── Accessors ──

    protected function categoryLabel(): Attribute
    {
        return Attribute::get(fn () => match ($this->category) {
            'rent'        => 'إيجار',
            'electricity' => 'كهرباء',
            'water'       => 'مياه',
            'salaries'    => 'رواتب',
            'supplies'    => 'مستلزمات',
            'marketing'   => 'تسويق',
            'maintenance' => 'صيانة',
            'other'       => 'أخرى',
            default       => $this->category,
        });
    }

    // ── Scopes ──

    public function scopeCategory($query, string $category)
    {
        return $query->where('category', $category);
    }

    public function scopeBetweenDates($query, $from, $to)
    {
        return $query->whereBetween('expense_date', [$from, $to]);
    }
}
