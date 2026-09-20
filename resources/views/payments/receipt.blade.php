<!DOCTYPE html><html lang="ar" dir="rtl"><head><meta charset="utf-8"><title>إيصال {{ $payment->receipt_number }}</title>
<style>@import url('https://fonts.googleapis.com/css2?family=Cairo:wght@600;800&display=swap');*{font-family:'Cairo',sans-serif} body{margin:0;padding:20px;background:#f8fafc} .card{max-width:600px;margin:0 auto;background:white;border:2px solid #0f172a;border-radius:16px;overflow:hidden} .head{background:#0f172a;color:white;text-align:center;padding:18px} .head h1{margin:0;font-size:22px} .head p{margin:4px 0 0;font-size:12px;opacity:.7} .body{padding:20px} .row{display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px dashed #e2e8f0;font-size:13px} .row span:first-child{color:#64748b} .row span:last-child{font-weight:800} .total{display:flex;justify-content:space-between;background:#f1f5f9;border-radius:12px;padding:12px 16px;margin-top:12px;font-weight:800} .foot{text-align:center;padding:12px;font-size:11px;color:#94a3b8;border-top:1px solid #e2e8f0} @media print{body{background:white} .card{border:none} .no-print{display:none}}</style>
</head><body>
<div class="card">
  <div class="head"><h1>سنتر الدروس</h1><p>إيصال دفع — {{ $payment->receipt_number }}</p></div>
  <div class="body">
    <div class="row"><span>اسم الطالب</span><span>{{ $payment->student->name }}</span></div>
    <div class="row"><span>كود الطالب</span><span>{{ $payment->student->code }}</span></div>
    <div class="row"><span>المجموعة</span><span>{{ $payment->group->name }}</span></div>
    <div class="row"><span>الشهر</span><span>{{ $payment->month }} / {{ $payment->year }}</span></div>
    <div class="row"><span>المبلغ المطلوب</span><span>{{ number_format($payment->required_amount) }} ج.م</span></div>
    <div class="row"><span>الخصم</span><span>{{ number_format($payment->discount) }} ج.م</span></div>
    <div class="row"><span>المبلغ المدفوع</span><span style="color:#059669">{{ number_format($payment->paid_amount) }} ج.م</span></div>
    <div class="total"><span>المتبقي</span><span style="color:{{ $payment->remaining>0.01 ? '#dc2626' : '#059669' }}">{{ number_format(max(0,$payment->remaining)) }} ج.م</span></div>
    <div class="row"><span>طريقة الدفع</span><span>{{ $payment->payment_method=='cash' ? 'نقدي' : ($payment->payment_method=='transfer' ? 'تحويل' : ($payment->payment_method=='card' ? 'بطاقة' : 'أخرى')) }}</span></div>
    <div class="row"><span>تاريخ الدفع</span><span>{{ $payment->payment_date }}</span></div>
    <div class="row"><span>الموظف</span><span>{{ $payment->creator->name ?? '—' }}</span></div>
  </div>
  <div class="foot">شكراً لكم — {{ now('Africa/Cairo')->format('Y/m/d H:i') }}<br><button onclick="window.print()" class="no-print" style="margin-top:8px;background:#0f172a;color:white;border:none;padding:8px 18px;border-radius:999px;cursor:pointer">طباعة</button></div>
</div>
</body></html>
