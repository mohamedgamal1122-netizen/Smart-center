@extends('layouts.app')
@section('title','صافي الربح')
@section('page_title','صافي الربح')
@section('content')
<form method="GET" class="bg-white rounded-2xl border p-4 flex flex-wrap gap-3 items-end mb-4">
  <div><label class="text-xs font-bold">من</label><input name="from" type="date" value="{{ request('from') }}" class="mt-1 border rounded-xl px-3 py-2 text-sm"></div>
  <div><label class="text-xs font-bold">إلى</label><input name="to" type="date" value="{{ request('to') }}" class="mt-1 border rounded-xl px-3 py-2 text-sm"></div>
  <button class="bg-slate-900 text-white px-5 py-2.5 rounded-xl text-sm font-bold">عرض</button>
  <a href="{{ request()->fullUrlWithQuery(['export'=>'excel']) }}" class="bg-emerald-600 text-white px-5 py-2.5 rounded-xl text-sm font-bold">Excel</a>
  <button type="button" onclick="window.print()" class="bg-violet-600 text-white px-5 py-2.5 rounded-xl text-sm font-bold">طباعة</button>
</form>
<div class="grid md:grid-cols-3 gap-4">
  <div class="bg-emerald-50 border border-emerald-200 rounded-2xl p-6 text-center"><div class="text-sm text-emerald-600">الإيرادات</div><div class="font-extrabold text-2xl text-emerald-700">{{ number_format($revenue ?? 0) }} ج.م</div></div>
  <div class="bg-red-50 border border-red-200 rounded-2xl p-6 text-center"><div class="text-sm text-red-600">المصروفات</div><div class="font-extrabold text-2xl text-red-600">{{ number_format($expenses ?? 0) }} ج.م</div></div>
  <div class="bg-slate-900 text-white rounded-2xl p-6 text-center"><div class="text-sm opacity-70">صافي الربح</div><div class="font-extrabold text-2xl @if(($profit ?? 0) >=0) text-emerald-400 @else text-red-400 @endif">{{ number_format($profit ?? 0) }} ج.م</div></div>
</div>
@endsection
