@extends('layouts.app')
@section('title','تقرير الطلاب')
@section('page_title','تقرير الطلاب')
@section('content')
<div class="grid md:grid-cols-3 gap-4 mb-4">
  <div class="bg-emerald-50 border border-emerald-200 rounded-2xl p-5 text-center"><div class="text-xs text-emerald-600">نشط</div><div class="font-extrabold text-2xl text-emerald-700">{{ $active ?? 0 }}</div></div>
  <div class="bg-amber-50 border border-amber-200 rounded-2xl p-5 text-center"><div class="text-xs text-amber-600">موقوف</div><div class="font-extrabold text-2xl text-amber-700">{{ $paused ?? 0 }}</div></div>
  <div class="bg-red-50 border border-red-200 rounded-2xl p-5 text-center"><div class="text-xs text-red-600">منسحب</div><div class="font-extrabold text-2xl text-red-600">{{ $withdrawn ?? 0 }}</div></div>
</div>
<div class="bg-white rounded-2xl border overflow-hidden">
  <div class="overflow-x-auto"><table class="w-full text-sm">
    <thead class="bg-slate-50 text-slate-500 text-xs"><tr><th class="p-3 text-right">الاسم</th><th class="p-3">الكود</th><th class="p-3">الصف</th><th class="p-3">الحالة</th><th class="p-3">تاريخ التسجيل</th></tr></thead>
    <tbody>
    @forelse($students ?? [] as $s)
      <tr class="border-t hover:bg-slate-50">
        <td class="p-3 font-bold">{{ $s->name }}</td>
        <td class="p-3 text-center text-xs">{{ $s->code }}</td>
        <td class="p-3 text-center text-xs">{{ $s->grade ?? '—' }}</td>
        <td class="p-3 text-center"><span class="text-xs px-2 py-1 rounded-full font-bold @if($s->status=='active') bg-emerald-100 text-emerald-700 @elseif($s->status=='paused') bg-amber-100 text-amber-700 @else bg-red-100 text-red-600 @endif">{{ $s->status=='active' ? 'نشط' : ($s->status=='paused' ? 'موقوف' : 'منسحب') }}</span></td>
        <td class="p-3 text-center text-xs">{{ $s->enrollment_date ?? $s->created_at->format('Y/m/d') }}</td>
      </tr>
    @empty
      <tr><td colspan="5" class="p-8 text-center text-slate-400">لا توجد بيانات</td></tr>
    @endforelse
    </tbody>
  </table></div>
</div>
<div class='mt-4'><a href="{{ request()->fullUrlWithQuery(['export'=>'excel']) }}" class='bg-emerald-600 text-white px-5 py-2.5 rounded-xl text-sm font-bold'>Excel تصدير الطلاب</a></div>
@endsection
