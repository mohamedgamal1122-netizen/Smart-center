@extends('layouts.app')
@section('title','التسجيلات')
@section('page_title','تسجيل الطلاب في المجموعات')
@section('content')
<div class="flex justify-between items-center mb-5">
  <form method="GET" class="flex gap-2"><input name="q" value="{{ request('q') }}" placeholder="بحث باسم الطالب..." class="border rounded-xl px-4 py-2.5 w-64 text-sm"><button class="bg-slate-900 text-white px-5 py-2.5 rounded-xl text-sm font-bold">بحث</button></form>
  <a href="{{ route('enrollments.create') }}" class="bg-violet-600 text-white px-5 py-2.5 rounded-xl text-sm font-bold">تسجيل جديد</a>
</div>
<div class="bg-white rounded-2xl border overflow-hidden">
  <div class="overflow-x-auto"><table class="w-full text-sm">
    <thead class="bg-slate-50 text-slate-500 text-xs"><tr><th class="p-3 text-right">الطالب</th><th class="p-3">المجموعة</th><th class="p-3">تاريخ التسجيل</th><th class="p-3">الرسوم</th><th class="p-3">الحالة</th><th class="p-3">إجراءات</th></tr></thead>
    <tbody>
    @forelse($enrollments as $en)
      <tr class="border-t hover:bg-slate-50">
        <td class="p-3 font-bold">{{ $en->student->name }} <span class="text-xs text-slate-400">{{ $en->student->code }}</span></td>
        <td class="p-3 text-center">{{ $en->group->name }}</td>
        <td class="p-3 text-center text-xs">{{ $en->enrollment_date }}</td>
        <td class="p-3 text-center text-xs">{{ number_format($en->agreed_fee) }} @if($en->discount) <span class="text-emerald-600">(-{{ $en->discount }})</span> @endif</td>
        <td class="p-3 text-center"><span class="text-xs px-2 py-1 rounded-full font-bold {{ $en->status=='active' ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100' }}">{{ $en->status=='active' ? 'نشط' : $en->status }}</span></td>
        <td class="p-3"><form method="POST" action="{{ route('enrollments.destroy',$en->id) }}" onsubmit="return confirm('إلغاء التسجيل؟')">@csrf @method('DELETE')<button class="text-xs bg-red-50 text-red-600 px-3 py-1.5 rounded-full font-bold">إلغاء</button></form></td>
      </tr>
    @empty
      <tr><td colspan="6" class="p-8 text-center text-slate-400">لا توجد تسجيلات</td></tr>
    @endforelse
    </tbody>
  </table></div>
  <div class="p-4">{{ $enrollments->links() }}</div>
</div>
@endsection
