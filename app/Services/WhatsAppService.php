<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

/**
 * خدمة إرسال رسائل واتساب
 * حالياً stub يطبع في الـ log، جاهزة للربط مع API (UltraMsg / Twilio / Meta Cloud) لاحقاً
 */
class WhatsAppService
{
    protected bool $enabled;
    protected ?string $apiUrl;
    protected ?string $apiToken;
    protected ?string $apiInstance;
    protected string $from;

    public function __construct()
    {
        $cfg = config('services.whatsapp');
        $this->enabled     = (bool) ($cfg['enabled'] ?? false);
        $this->apiUrl      = $cfg['api_url'] ?? null;
        $this->apiToken    = $cfg['api_token'] ?? null;
        $this->apiInstance = $cfg['instance_id'] ?? null;
        $this->from        = $cfg['from'] ?? '';
    }

    /**
     * إرسال رسالة واتساب
     *
     * @param string $to رقم الهاتف بصيغة دولية (مثال: 2010xxxxxxx بدون +)
     * @param string $message نص الرسالة
     * @param array  $options خيارات إضافية (media_url, etc.)
     * @return array{success:bool, logged:bool, response:mixed}
     */
    public function send(string $to, string $message, array $options = []): array
    {
        $to = $this->normalizePhone($to);

        // سجل دائماً للمراجعة
        Log::info('[WhatsApp] send attempt', [
            'to'      => $to,
            'message' => mb_substr($message, 0, 500),
            'enabled' => $this->enabled,
        ]);

        // إذا الخدمة معطلة — اكتفِ بالـ log (stub mode)
        if (!$this->enabled || empty($this->apiUrl) || empty($this->apiToken)) {
            Log::info('[WhatsApp][STUB] message logged (API not configured)', [
                'to' => $to,
                'message' => $message,
            ]);
            return ['success' => true, 'logged' => true, 'response' => 'stub_logged'];
        }

        // وضع API الحقيقي — يدعم UltraMsg / Meta Cloud / Twilio حسب الإعداد
        try {
            $response = Http::timeout(15)->withToken($this->apiToken)->post($this->apiUrl, array_merge([
                'to'      => $to,
                'from'    => $this->from,
                'body'    => $message,
                'instance'=> $this->apiInstance,
            ], $options));

            $ok = $response->successful();
            Log::info('[WhatsApp] API response', ['status' => $response->status(), 'body' => $response->body()]);

            return ['success' => $ok, 'logged' => true, 'response' => $response->json() ?? $response->body()];
        } catch (\Throwable $e) {
            Log::error('[WhatsApp] send failed', ['to' => $to, 'error' => $e->getMessage()]);
            return ['success' => false, 'logged' => true, 'response' => $e->getMessage()];
        }
    }

    /**
     * إرسال نفس الرسالة لقائمة أرقام
     */
    public function sendBulk(array $phones, string $message, array $options = []): array
    {
        $results = [];
        foreach ($phones as $phone) {
            $results[$phone] = $this->send($phone, $message, $options);
        }
        return $results;
    }

    /**
     * قوالب جاهزة
     */
    public function notifyOverdue(string $phone, string $studentName, float $amount, string $monthYear): array
    {
        $msg = "تنبيه من سنتر الدروس 📚\nالطالب: {$studentName}\nيوجد متأخرات: ".number_format($amount, 2)." جنيه عن {$monthYear}\nيرجى التوجه للسنتر لإتمام السداد.";
        return $this->send($phone, $msg);
    }

    public function notifyAbsence(string $phone, string $studentName, string $groupName, string $date): array
    {
        $msg = "تنبيه غياب ⚠️\nالطالب {$studentName} غائب عن حصة {$groupName} بتاريخ {$date}.\nيرجى المتابعة.";
        return $this->send($phone, $msg);
    }

    public function notifyGroupFull(string $phone, string $groupName): array
    {
        $msg = "تنبيه: المجموعة {$groupName} اكتملت ✅\nلا يمكن إضافة طلاب جدد إلا بموافقة الإدارة.";
        return $this->send($phone, $msg);
    }

    public function notifyLevelDrop(string $phone, string $studentName, string $details): array
    {
        $msg = "تنبيه مستوى 📉\nالطالب {$studentName} — {$details}\nيرجى متابعة ولي الأمر.";
        return $this->send($phone, $msg);
    }

    /**
     * تطبيع رقم الهاتف لصيغة دولية بدون +
     */
    protected function normalizePhone(string $phone): string
    {
        $phone = preg_replace('/[^0-9]/', '', $phone);
        // تحويل 01xxxxxxxxx إلى 201xxxxxxxxx
        if (str_starts_with($phone, '01') && strlen($phone) === 11) {
            $phone = '2' . $phone;
        }
        if (str_starts_with($phone, '0') && strlen($phone) === 11) {
            $phone = '2' . ltrim($phone, '0');
        }
        return $phone;
    }
}
