<!DOCTYPE html><html lang="ar" dir="rtl"><head><meta charset="utf-8"><style>*{font-family:'DejaVu Sans',sans-serif} body{direction:rtl; font-size:11px} table{width:100%; border-collapse:collapse; margin-top:10px} th,td{border:1px solid #cbd5e1; padding:5px 6px; text-align:right} th{background:#f1f5f9} h1{text-align:center} .meta{text-align:center; color:#64748b}</style></head><body>
<h1>تقرير الحضور — {{ $from }} إلى {{ $to }}</h1>
<table><thead><tr><th>الطالب</th><th>المجموعة</th><th>التاريخ</th><th>الحالة</th></tr></thead><tbody>@foreach($attendances as $a)<tr><td>{{ $a->student->name ?? '—' }}</td><td>{{ $a->session->group->name ?? '—' }}</td><td>{{ $a->session->session_date?->format('Y/m/d') }}</td><td>{{ $a->status }}</td></tr>@endforeach</tbody></table>
</body></html>
