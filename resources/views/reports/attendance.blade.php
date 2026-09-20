@extends('layouts.app')
@section('title','تقرير الحضور')
@section('page_title','تقرير الحضور')
@section('content')
<form method="GET" class="bg-white rounded-2xl border p-4 flex flex-wrap gap-3 items-end mb-4">
  <div><label class="text-xs font-bold">المجموعة</label><select name="group_id" class="mt-1 border rounded-xl px-3 py-2 text-sm"><option value="">الكل</option>@foreach($groups ?? [] as $g)<option value="{{ $g->id }}" @selected(request('group_id')==$g->id)>{{ $g->name }}</option>@endforeach</select></div>
  <div><label class="text-xs font-bold">من</label><input name="from" type="date" value="{{ request('from') }}" class="mt-1 border rounded-xl px-3 py-2 text-sm"></div>
  <div><label class="text-xs font-bold">إلى</label><input name="to" type="date" value="{{ request('to') }}" class="mt-1 border rounded-xl px-3 py-2 text-sm"></div>
  <button class="bg-slate-900 text-white px-5 py-2.5 rounded-xl text-sm font-bold">عرض</button>
  <a href="{{ request()->fullUrlWithQuery(['export'=>'excel']) }}" class="bg-emerald-600 text-white px-5 py-2.5 rounded-xl text-sm font-bold">Excel</a>
</form>
<div class="bg-white rounded-2xl border overflow-hidden">
  <div class="overflow-x-auto"><table class="w-full text-sm">
    <thead class="bg-slate-50 text-slate-500 text-xs"><tr><th class="p-3 text-right">الطالب</th><th class="p-3">المجموعة</th><th class="p-3">حاضر</th><th class="p-3">غائب</th><th class="p-3">متأخر</th><th class="p-3">النسبة</th></tr></thead>
    <tbody>
    @forelse($stats ?? [] as $row)
      <tr class="border-t hover:bg-slate-50">
        <td class="p-3 font-bold">{{ $row['student']->name ?? $row['student_name'] }}</td>
        <td class="p-3 text-center text-xs">{{ $row['group_name'] ?? '' }}</td>
        <td class="p-3 text-center text-emerald-700 font-bold">{{ $row['present'] ?? 0 }}</td>
        <td class="p-3 text-center text-red-600 font-bold">{{ $row['absent'] ?? 0 }}</td>
        <td class="p-3 text-center text-amber-600 font-bold">{{ $row['late'] ?? 0 }}</td>
        <td class="p-3 text-center font-bold">{{ number_format($row['rate'] ?? 0,1) }}%</td>
      </tr>
    @empty
      <tr><td colspan="6" class="p-8 text-center text-slate-400">لا توجد بيانات</td></tr>
    @endforelse
    </tbody>
  </table></div>
</div>
@endsection
