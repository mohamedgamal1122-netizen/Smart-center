<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="utf-8">
<title>QR — {{ $student->name }} — {{ $student->code }}</title>
<style>
 @import url('https://fonts.googleapis.com/css2?family=Cairo:wght@700;800&display=swap');
 *{font-family:'Cairo',sans-serif}
 body{margin:0;background:#f1f5f9;display:flex;align-items:center;justify-content:center;min-height:100vh}
 .card{background:white;border-radius:24px;padding:32px;text-align:center;box-shadow:0 10px 30px rgba(0,0,0,.08);max-width:420px;width:90%}
 h1{margin:0;font-size:20px;font-weight:800}
 code{font-size:13px;color:#64748b}
 img{width:340px;height:340px;margin:16px auto;display:block;border:8px solid #f8fafc;border-radius:16px}
 .foot{font-size:11px;color:#94a3b8;margin-top:8px}
 @media print{body{background:white} .no-print{display:none} .card{box-shadow:none;border:2px solid #0f172a}}
</style>
</head>
<body>
<div class="card">
  <h1>{{ $student->name }}</h1>
  <code>{{ $student->code }} — {{ $student->grade ?? '' }}</code>
  <img src="{{ $qrUrl }}" alt="QR">
  <div style="font-size:12px;word-break:break-all;color:#475569;background:#f8fafc;padding:8px;border-radius:10px;">{{ $qrData }}</div>
  <div class="foot">سنتر الدروس — امسح الكود لتسجيل الحضور</div>
  <button onclick="window.print()" class="no-print" style="margin-top:14px;background:#0f172a;color:white;border:none;padding:10px 22px;border-radius:999px;cursor:pointer;font-weight:700;">طباعة</button>
</div>
</body>
</html>
