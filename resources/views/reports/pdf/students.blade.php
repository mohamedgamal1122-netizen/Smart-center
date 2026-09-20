<!DOCTYPE html><html lang="ar" dir="rtl"><head><meta charset="utf-8"><style>*{font-family:'DejaVu Sans',sans-serif} body{direction:rtl; font-size:11px} table{width:100%; border-collapse:collapse; margin-top:10px} th,td{border:1px solid #cbd5e1; padding:5px 6px; text-align:right} th{background:#f1f5f9} h1{text-align:center}</style></head><body>
<h1>تقرير الطلاب</h1>
<table><thead><tr><th>الاسم</th><th>الكود</th><th>الصف</th><th>الحالة</th></tr></thead><tbody>@foreach($students as $s)<tr><td>{{ $s->name }}</td><td>{{ $s->code }}</td><td>{{ $s->grade ?? '—' }}</td><td>{{ $s->status }}</td></tr>@endforeach</tbody></table>
</body></html>
