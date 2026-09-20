@extends('layouts.app')
@section('title','تقرير المصروفات')
@section('page_title','تقرير المصروفات')
@section('content')
<form method="GET" class="bg-white rounded-2xl border p-4 flex flex-wrap gap-3 items-end mb-4">
  <div><label class="text-xs font-bold">من</label><input name="from" type="date" value="{{ request('from') }}" class="mt-1 border rounded-xl px-3 py-2 text-sm"></div>
  <div><label class="text-xs font-bold">إلى</label><input name="to" type="date" value="{{ request('to') }}" class="mt-1 border rounded-xl px-3 py-2 text-sm"></div>
  <button class="bg-slate-900 text-white px-5 py-2.5 rounded-xl text-sm font-bold">عرض</button>
  <button type="button" onclick="window.print()" class="bg-violet-600 text-white px-5 py-2.5 rounded-xl text-sm font-bold">طباعة</button>
  <a href="{{ request()->fullUrlWithQuery(['export'=>'excel']) }}" class="bg-emerald-600 text-white px-5 py-2.5 rounded-xl text-sm font-bold">Excel</a>
</form>
<div class="bg-white rounded-2xl border p-5">
  <div class="text-center mb-4"><div class="text-sm text-slate-500">إجمالي المصروفات</div><div class="font-extrabold text-2xl text-red-600">{{ number_format($total ?? 0) }} ج.م</div></div>
  <div class="overflow-x-auto"><table class="w-full text-sm">
    <thead class="bg-slate-50 text-slate-500 text-xs"><tr><th class="p-2 text-right">النوع</th><th class="p-2">القيمة</th><th class="p-2">التاريخ</th><th class="p-2">الوصف</th></tr></thead>
    <tbody>
    @forelse($expenses ?? [] as $e)
      <tr class="border-t"><td class="p-2">{{ $e->category }}</td><td class="p-2 text-center font-bold text-red-600">{{ number_format($e->amount) }}</td><td class="p-2 text-center text-xs">{{ $e->expense_date }}</td><td class="p-2 text-xs">{{ $e->description ?? '—' }}</td></tr>
    @empty
      <tr><td colspan="4" class="p-8 text-center text-slate-400">لا توجد مصروفات</td></tr>
    @endforelse
    </tbody>
  </table></div>
</div>
@endsection
