<?php

namespace App\Services;

/**
 * حساب خصم الأخوات — 10% تلقائياً لو ولي الأمر عنده أكثر من طالب نشط
 */
class SiblingDiscountService
{
    public const DISCOUNT_RATE = 0.10; // 10%

    /**
     * هل الطالب مستحق لخصم الأخوات؟
     */
    public static function isEligible(int $studentId): bool
    {
        $parentIds = \DB::table('parent_student')->where('student_id', $studentId)->pluck('parent_id');
        if ($parentIds->isEmpty()) return false;

        foreach ($parentIds as $parentId) {
            $activeSiblings = \DB::table('parent_student')
                ->join('students', 'students.id', '=', 'parent_student.student_id')
                ->where('parent_student.parent_id', $parentId)
                ->where('students.status', 'active')
                ->whereNull('students.deleted_at')
                ->count();
            if ($activeSiblings > 1) return true;
        }
        return false;
    }

    /**
     * عدد الأخوة النشطين لولي أمر الطالب (أكبر عدد عبر كل أولياء الأمور المرتبطين)
     */
    public static function siblingCount(int $studentId): int
    {
        $parentIds = \DB::table('parent_student')->where('student_id', $studentId)->pluck('parent_id');
        $max = 0;
        foreach ($parentIds as $parentId) {
            $count = \DB::table('parent_student')
                ->join('students', 'students.id', '=', 'parent_student.student_id')
                ->where('parent_student.parent_id', $parentId)
                ->where('students.status', 'active')
                ->whereNull('students.deleted_at')
                ->count();
            $max = max($max, $count);
        }
        return $max;
    }

    /**
     * احسب قيمة الخصم
     */
    public static function calculateDiscount(float $amount, int $studentId): float
    {
        if (!self::isEligible($studentId)) return 0.0;
        return round($amount * self::DISCOUNT_RATE, 2);
    }

    /**
     * طبّق الخصم وأرجع [discount, netAmount]
     */
    public static function apply(float $amount, int $studentId): array
    {
        $discount = self::calculateDiscount($amount, $studentId);
        return [
            'discount'   => $discount,
            'net'        => round($amount - $discount, 2),
            'eligible'   => $discount > 0,
            'rate'       => self::DISCOUNT_RATE,
            'siblings'   => self::siblingCount($studentId),
        ];
    }
}
