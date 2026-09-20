@extends('layouts.app')
@section('title','التقارير')
@section('page_title','التقارير')
@section('content')
<div class="grid md:grid-cols-3 gap-4">
  <a href="{{ route('reports.revenue') }}" class="bg-white rounded-2xl border p-6 hover:shadow-lg transition"><div class="w-10 h-10 rounded-xl bg-emerald-100 flex items-center justify-center"><i class="fa-solid fa-money-bill-trend-up text-emerald-600"></i></div><h3 class="font-extrabold mt-3">تقرير الإيرادات</h3><p class="text-xs text-slate-500 mt-1">حسب الشهر والمجموعة والمادة والمدرس</p></a>
  <a href="{{ route('reports.expenses') }}" class="bg-white rounded-2xl border p-6 hover:shadow-lg transition"><div class="w-10 h-10 rounded-xl bg-red-100 flex items-center justify-center"><i class="fa-solid fa-receipt text-red-600"></i></div><h3 class="font-extrabold mt-3">تقرير المصروفات</h3><p class="text-xs text-slate-500 mt-1">تفصيلي حسب النوع والفترة</p></a>
  <a href="{{ route('reports.profit') }}" class="bg-white rounded-2xl border p-6 hover:shadow-lg transition"><div class="w-10 h-10 rounded-xl bg-violet-100 flex items-center justify-center"><i class="fa-solid fa-chart-line text-violet-600"></i></div><h3 class="font-extrabold mt-3">صافي الربح</h3><p class="text-xs text-slate-500 mt-1">إيرادات - مصروفات</p></a>
  <a href="{{ route('reports.attendance') }}" class="bg-white rounded-2xl border p-6 hover:shadow-lg transition"><div class="w-10 h-10 rounded-xl bg-amber-100 flex items-center justify-center"><i class="fa-solid fa-clipboard-check text-amber-600"></i></div><h3 class="font-extrabold mt-3">تقرير الحضور</h3><p class="text-xs text-slate-500 mt-1">نسب الحضور والغياب المتكرر</p></a>
  <a href="{{ route('reports.students') }}" class="bg-white rounded-2xl border p-6 hover:shadow-lg transition"><div class="w-10 h-10 rounded-xl bg-sky-100 flex items-center justify-center"><i class="fa-solid fa-users text-sky-600"></i></div><h3 class="font-extrabold mt-3">تقرير الطلاب</h3><p class="text-xs text-slate-500 mt-1">الجدد والمنسحبون والمتأخرون</p></a>
  <div class="bg-slate-900 text-white rounded-2xl p-6"><h3 class="font-extrabold">تصدير</h3><p class="text-xs text-white/60 mt-1">كل تقرير يدعم الطباعة و PDF</p><p class="text-xs mt-3 opacity-50">كل تقرير يدعم Excel + PDF + طباعة</p></div>
</div>
@endsection
