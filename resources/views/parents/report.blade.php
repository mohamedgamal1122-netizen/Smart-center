<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="utf-8">
<title>تقرير ولي الأمر — {{ $parent->name }}</title>
<style>
  @import url('https://fonts.googleapis.com/css2?family=Cairo:wght@400;700;800&display=swap');
  *{font-family:'DejaVu Sans', Tahoma, sans-serif}
  body{margin:0;padding:0;color:#0f172a;font-size:11px;line-height:1.6}
  .header{background:#0f172a;color:#fff;padding:16px 20px;text-align:center}
  .header h1{margin:0;font-size:20px;font-weight:800}
  .header p{margin:4px 0 0;font-size:11px;opacity:.75}
  .meta{padding:12px 20px;background:#f1f5f9;border-bottom:2px solid #0f172a;font-size:11px}
  .meta strong{color:#334155}
  .section{margin:14px 20px 0}
  .section h2{margin:0 0 6px;font-size:13px;background:#0f172a;color:#fff;padding:6px 10px;border-radius:8px}
  .section h3{margin:10px 0 4px;font-size:12px;color:#4f46e5;border-bottom:1px solid #e2e8f0;padding-bottom:3px}
  table{width:100%;border-collapse:collapse;margin-top:4px;font-size:10.5px}
  th{background:#f1f5f9;color:#334155;padding:6px 6px;text-align:right;border:1px solid #e2e8f0;font-size:10px}
  td{padding:5px 6px;border:1px solid #e2e8f0}
  tr:nth-child(even) td{background:#f8fafc}
  .badge{display:inline-block;padding:1px 7px;border-radius:999px;font-size:10px;font-weight:700}
  .b-green{background:#dcfce7;color:#166534}
  .b-red{background:#fee2e2;color:#991b1b}
  .b-amber{background:#fef3c7;color:#92400e}
  .b-violet{background:#ede9fe;color:#5b21b6}
  .b-slate{background:#f1f5f9;color:#475569}
  .summary{display:flex;gap:8px;flex-wrap:wrap;margin-top:6px}
  .summary .box{flex:1;min-width:90px;background:#f8fafc;border:1px solid #e2e8f0;border-radius:10px;padding:7px 9px;text-align:center}
  .summary .box .num{font-size:15px;font-weight:800;color:#0f172a}
  .summary .box .lbl{font-size:9px;color:#64748b}
  .footer{text-align:center;padding:10px;font-size:9px;color:#94a3b8;border-top:1px solid #e2e8f0;margin-top:14px}
  .page-break{page-break-before:always}
</style>
</head>
<body>
<div class="header">
  <h1>سنتر الدروس — تقرير ولي الأمر</h1>
  <p>تقرير شامل عن حضور ودرجات ومدفوعات أبناء ولي الأمر</p>
</div>
<div class="meta">
  <strong>ولي الأمر:</strong> {{ $parent->name }} &nbsp;|&nbsp;
  <strong>الصلة:</strong> {{ $parent->relation ?? '—' }} &nbsp;|&nbsp;
  <strong>الهاتف:</strong> {{ $parent->phone_primary }} {{ $parent->phone_secondary ? ' / '.$parent->phone_secondary : '' }} &nbsp;|&nbsp;
  <strong>عدد الأبناء:</strong> {{ $students->count() }} &nbsp;|&nbsp;
  <strong>تاريخ التقرير:</strong> {{ now('Africa/Cairo')->format('Y/m/d H:i') }}
  @if($parent->email) <br><strong>البريد:</strong> {{ $parent->email }} @endif
  @if($parent->address) <br><strong>العنوان:</strong> {{ $parent->address }} @endif
</div>

@if($students->isEmpty())
  <div class="section"><p style="text-align:center;color:#94a3b8;padding:20px">لا يوجد طلاب مرتبطون بولي الأمر.</p></div>
@else
@foreach($students as $s)
  @php $atts=$attendances[$s->id] ?? collect(); $results=$examResults[$s->id] ?? collect(); $pays=$payments[$s->id] ?? collect(); $st=$stats[$s->id] ?? []; @endphp
  <div class="section">
    <h2>الطالب: {{ $s->name }} — <span style="font-weight:400;font-size:11px">{{ $s->code }} | {{ $s->grade ?? 'بدون صف' }} | {{ $s->status_label }}</span></h2>

    <div class="summary">
      <div class="box"><div class="num">{{ $st['total'] ?? 0 }}</div><div class="lbl">إجمالي الحضور المسجل</div></div>
      <div class="box"><div class="num" style="color:#059669">{{ $st['present'] ?? 0 }}</div><div class="lbl">حاضر</div></div>
      <div class="box"><div class="num" style="color:#dc2626">{{ $st['absent'] ?? 0 }}</div><div class="lbl">غائب</div></div>
      <div class="box"><div class="num">{{ isset($st['avg_score']) && $st['avg_score']!==null ? number_format($st['avg_score'],1) : '—' }}</div><div class="lbl">متوسط الدرجات</div></div>
      <div class="box"><div class="num">{{ number_format($st['paid'] ?? 0) }} ج.م</div><div class="lbl">إجمالي المدفوع</div></div>
      <div class="box"><div class="num">{{ number_format(max(0, ($st['required']??0)-($st['paid']??0))) }} ج.م</div><div class="lbl">المتبقي</div></div>
    </div>

    <h3>أولاً: الحضور (آخر {{ $atts->count() }} سجل)</h3>
    @if($atts->isEmpty())
      <p style="color:#94a3b8;font-size:10px">لا توجد سجلات حضور.</p>
    @else
    <table>
      <thead><tr><th>التاريخ</th><th>المجموعة</th><th>الحالة</th><th>ملاحظات</th></tr></thead>
      <tbody>
      @foreach($atts->take(30) as $a)
        <tr>
          <td>{{ $a->session->date ?? '—' }}</td>
          <td>{{ $a->session->group->name ?? '—' }}</td>
          <td>
            @if($a->status=='present')<span class="badge b-green">حاضر</span>
            @elseif($a->status=='late')<span class="badge b-amber">متأخر</span>
            @elseif($a->status=='absent')<span class="badge b-red">غائب</span>
            @else<span class="badge b-slate">{{ $a->status_label }}</span>
            @endif
          </td>
          <td>{{ $a->notes ?? '—' }}</td>
        </tr>
      @endforeach
      </tbody>
    </table>
    @endif

    <h3>ثانياً: الدرجات</h3>
    @if($results->isEmpty())
      <p style="color:#94a3b8;font-size:10px">لا توجد نتائج اختبارات.</p>
    @else
    <table>
      <thead><tr><th>الاختبار</th><th>المادة</th><th>التاريخ</th><th>الدرجة</th><th>النسبة</th><th>التقدير</th></tr></thead>
      <tbody>
      @foreach($results as $r)
        <tr>
          <td>{{ $r->exam->title ?? $r->exam->name ?? 'اختبار #'.$r->exam_id }}</td>
          <td>{{ $r->exam->subject->name ?? '—' }}</td>
          <td>{{ $r->exam->exam_date ?? '—' }}</td>
          <td style="font-weight:700">{{ $r->score }} / {{ $r->exam->max_score ?? '—' }}</td>
          <td>{{ $r->percentage }}%</td>
          <td><span class="badge {{ $r->is_passed ? 'b-green' : 'b-red' }}">{{ $r->computed_grade }}</span></td>
        </tr>
      @endforeach
      </tbody>
    </table>
    @endif

    <h3>ثالثاً: المدفوعات</h3>
    @if($pays->isEmpty())
      <p style="color:#94a3b8;font-size:10px">لا توجد مدفوعات.</p>
    @else
    <table>
      <thead><tr><th>الإيصال</th><th>الشهر</th><th>المجموعة</th><th>المطلوب</th><th>المدفوع</th><th>المتبقي</th><th>الحالة</th></tr></thead>
      <tbody>
      @foreach($pays as $p)
        <tr>
          <td style="font-family:monospace;font-size:9px">{{ $p->receipt_number }}</td>
          <td>{{ $p->month }}/{{ $p->year }}</td>
          <td>{{ $p->group->name ?? '—' }}</td>
          <td>{{ number_format($p->required_amount) }}</td>
          <td style="color:#059669;font-weight:700">{{ number_format($p->paid_amount) }}</td>
          <td style="color:{{ $p->remaining>0 ? '#dc2626' : '#059669' }};font-weight:700">{{ number_format(max(0,$p->remaining)) }}</td>
          <td>
            @if($p->is_cancelled)<span class="badge b-red">ملغى</span>
            @elseif($p->remaining<=0)<span class="badge b-green">مكتمل</span>
            @elseif($p->paid_amount>0)<span class="badge b-amber">جزئي</span>
            @else<span class="badge b-slate">غير مدفوع</span>
            @endif
          </td>
        </tr>
      @endforeach
      </tbody>
    </table>
    @endif
  </div>
  @if(!$loop->last)<div style="border-top:2px dashed #e2e8f0;margin:14px 20px 0"></div>@endif
@endforeach
@endif

<div class="footer">
  سنتر الدروس — تقرير ولي الأمر {{ $parent->name }} — {{ now('Africa/Cairo')->format('Y/m/d H:i') }} — صفحة {PAGE_NUM} من {PAGE_COUNT}
</div>
</body>
</html>
