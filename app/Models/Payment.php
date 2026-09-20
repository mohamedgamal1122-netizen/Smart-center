<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;

/**
 * نموذج المدفوعات
 */
class Payment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'student_id',
        'group_id',
        'month',
        'year',
        'required_amount',
        'discount',
        'paid_amount',
        'payment_date',
        'payment_method',
        'receipt_number',
        'created_by',
        'notes',
        'is_cancelled',
        'cancel_reason',
    ];

    protected function casts(): array
    {
        return [
            'required_amount' => 'decimal:2',
            'discount'        => 'decimal:2',
            'paid_amount'     => 'decimal:2',
            'payment_date'    => 'date',
            'is_cancelled'    => 'boolean',
        ];
    }

    // ── Boot: توليد رقم إيصال تلقائي ──
    protected static function booted(): void
    {
        static::creating(function (Payment $payment) {
            if (empty($payment->receipt_number)) {
                $year = date('Y');
                $last = static::withTrashed()->where('receipt_number', 'like', "REC-{$year}-%")->orderByDesc('id')->first();
                $seq = $last ? ((int) substr($last->receipt_number, -6) + 1) : 1;
                $payment->receipt_number = sprintf('REC-%s-%06d', $year, $seq);
            }
        });
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

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // ── Accessors (محسوبة تلقائياً) ──

    /** المبلغ المتبقي = (المطلوب - الخصم) - المدفوع */
    protected function remaining(): Attribute
    {
        return Attribute::get(function () {
            $net = (float) $this->required_amount - (float) $this->discount;
            return round($net - (float) $this->paid_amount, 2);
        });
    }

    /** صافي المبلغ المطلوب بعد الخصم */
    protected function netRequired(): Attribute
    {
        return Attribute::get(fn () => round((float) $this->required_amount - (float) $this->discount, 2));
    }

    /** هل المدفوع مكتمل؟ */
    protected function isFullyPaid(): Attribute
    {
        return Attribute::get(fn () => $this->remaining <= 0);
    }

    /** حالة الدفع بالعربية */
    protected function paymentStatusLabel(): Attribute
    {
        return Attribute::get(function () {
            if ($this->is_cancelled) return 'ملغى';
            if ($this->remaining <= 0) return 'مدفوع بالكامل';
            if ((float) $this->paid_amount > 0) return 'مدفوع جزئياً';
            return 'غير مدفوع';
        });
    }

    protected function paymentMethodLabel(): Attribute
    {
        return Attribute::get(fn () => match ($this->payment_method) {
            'cash'     => 'نقدي',
            'transfer' => 'تحويل بنكي',
            'card'     => 'بطاقة',
            'other'    => 'أخرى',
            default    => $this->payment_method,
        });
    }

    // ── Scopes ──

    public function scopeNotCancelled($query)
    {
        return $query->where('is_cancelled', false);
    }

    public function scopeForMonth($query, int $month, int $year)
    {
        return $query->where('month', $month)->where('year', $year);
    }
}
