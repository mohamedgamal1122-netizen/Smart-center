<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="utf-8">
<style>
@import url('https://fonts.googleapis.com/css2?family=Cairo:wght@700;800&display=swap');
*{font-family:'Cairo','DejaVu Sans',sans-serif}
body{margin:0;padding:0;background:#fff}
.page{width:100%;height:100%;text-align:center;padding:40px 30px;box-sizing:border-box;border:12px double #0f172a;position:relative}
.badge{display:inline-block;background:#0f172a;color:#fff;padding:6px 16px;border-radius:999px;font-size:12px;letter-spacing:1px}
h1{font-size:36px;margin:14px 0 4px;color:#0f172a;font-weight:800}
h2{font-size:22px;margin:0;color:#334155;font-weight:700}
.rank{margin:18px auto;width:110px;height:110px;border-radius:50%;background:linear-gradient(135deg,#7c3aed,#4f46e5);color:#fff;display:table;line-height:110px;font-size:42px;font-weight:800}
.meta{margin-top:18px;font-size:13px;color:#475569}
.meta strong{color:#0f172a}
.footer{position:absolute;bottom:20px;left:30px;right:30px;display:flex;justify-content:space-between;font-size:11px;color:#64748b;border-top:1px dashed #cbd5e1;padding-top:10px}
.seal{position:absolute;top:30px;left:30px;width:90px;height:90px;border:3px solid #eab308;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:12px;color:#a16207;font-weight:800;transform:rotate(-12deg)}
</style>
</head>
<body>
<div class="page">
  <div class="seal">ختم<br>السنتر</div>
  <div class="badge">شهادة تقدير — سنتر الدروس</div>
  <h1>شهادة تفوق</h1>
  <p style="font-size:13px;color:#64748b;margin:0;">يمنح لـ</p>
  <h2 style="font-size:28px;color:#7c3aed;margin:8px 0;">{{ $student->name }}</h2>
  <p style="font-size:12px;color:#64748b;">كود الطالب: {{ $student->code }} &nbsp;|&nbsp; الصف: {{ $student->grade ?? '—' }}</p>
  <div class="rank">#{{ $rank }}</div>
  <p style="font-size:14px;color:#0f172a;">لتفوقه في اختبار <strong>{{ $exam->title }}</strong></p>
  <p class="meta">
    المادة: <strong>{{ $exam->group->subject->name ?? $exam->subject->name ?? '—' }}</strong> &nbsp;|&nbsp;
    المجموعة: <strong>{{ $exam->group->name ?? '—' }}</strong><br>
    الدرجة: <strong style="font-size:18px;color:#059669;">{{ $result->score }} / {{ $exam->max_score }}</strong>
    &nbsp;|&nbsp; النسبة: <strong>{{ number_format($result->score / max(1,$exam->max_score) *100,1) }}%</strong>
    &nbsp;|&nbsp; التقدير: <strong>{{ $result->grade ?? '—' }}</strong><br>
    تاريخ الاختبار: <strong>{{ \Carbon\Carbon::parse($exam->exam_date)->format('Y/m/d') }}</strong>
  </p>
  <div class="footer">
    <span>توقيع الإدارة: ___________________</span>
    <span>سنتر الدروس — {{ now('Africa/Cairo')->format('Y/m/d') }}</span>
    <span>ختم السنتر</span>
  </div>
</div>
</body>
</html>
