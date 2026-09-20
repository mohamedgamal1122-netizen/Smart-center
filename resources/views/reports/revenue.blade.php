@extends('layouts.app')
@section('title','تقرير الإيرادات')
@section('page_title','تقرير الإيرادات')
@section('content')
<form method="GET" class="bg-white rounded-2xl border p-4 flex flex-wrap gap-3 items-end mb-4">
  <div><label class="text-xs font-bold">من</label><input name="from" type="date" value="{{ request('from') }}" class="mt-1 border rounded-xl px-3 py-2 text-sm"></div>
  <div><label class="text-xs font-bold">إلى</label><input name="to" type="date" value="{{ request('to') }}" class="mt-1 border rounded-xl px-3 py-2 text-sm"></div>
  <div><label class="text-xs font-bold">المجموعة</label><select name="group_id" class="mt-1 border rounded-xl px-3 py-2 text-sm"><option value="">الكل</option>@foreach($groups ?? [] as $g)<option value="{{ $g->id }}" @selected(request('group_id')==$g->id)>{{ $g->name }}</option>@endforeach</select></div>
  <button class="bg-slate-900 text-white px-5 py-2.5 rounded-xl text-sm font-bold">عرض</button>
  <button type="button" onclick="window.print()" class="bg-violet-600 text-white px-5 py-2.5 rounded-xl text-sm font-bold">طباعة</button>
  <a href="{{ request()->fullUrlWithQuery(['export'=>'excel']) }}" class="bg-emerald-600 text-white px-5 py-2.5 rounded-xl text-sm font-bold">Excel</a>
</form>
<div class="bg-white rounded-2xl border p-5">
  <div class="grid md:grid-cols-3 gap-4 mb-6 text-center">
    <div class="bg-emerald-50 rounded-xl p-4"><div class="text-xs text-emerald-600">إجمالي المحصل</div><div class="font-extrabold text-xl text-emerald-700">{{ number_format($totalPaid ?? 0) }} ج.م</div></div>
    <div class="bg-amber-50 rounded-xl p-4"><div class="text-xs text-amber-600">المتأخرات</div><div class="font-extrabold text-xl text-amber-700">{{ number_format($totalDue ?? 0) }} ج.م</div></div>
    <div class="bg-slate-50 rounded-xl p-4"><div class="text-xs text-slate-500">عدد المدفوعات</div><div class="font-extrabold text-xl">{{ $count ?? 0 }}</div></div>
  </div>
  <div class="overflow-x-auto"><table class="w-full text-sm">
    <thead class="bg-slate-50 text-slate-500 text-xs"><tr><th class="p-2 text-right">الطالب</th><th class="p-2">المجموعة</th><th class="p-2">الشهر</th><th class="p-2">المطلوب</th><th class="p-2">المدفوع</th><th class="p-2">المتبقي</th></tr></thead>
    <tbody>
    @forelse($payments ?? [] as $p)
      <tr class="border-t"><td class="p-2">{{ $p->student->name ?? '' }}</td><td class="p-2 text-center">{{ $p->group->name ?? '' }}</td><td class="p-2 text-center">{{ $p->month }}/{{ $p->year }}</td><td class="p-2 text-center">{{ number_format($p->required_amount) }}</td><td class="p-2 text-center text-emerald-700 font-bold">{{ number_format($p->paid_amount) }}</td><td class="p-2 text-center text-red-600 font-bold">{{ number_format(max(0,$p->remaining)) }}</td></tr>
    @empty
      <tr><td colspan="6" class="p-8 text-center text-slate-400">لا توجد بيانات</td></tr>
    @endforelse
    </tbody>
  </table></div>
</div>
@endsection
