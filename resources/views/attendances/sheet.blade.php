@extends('layouts.app')
@section('title','كشف الحضور الشهري')
@section('page_title','كشف حضور شهري — قابل للطباعة A4')
@section('content')
<div class="bg-white rounded-2xl border p-4 mb-4 print:shadow-none">
  <form method="GET" class="flex flex-wrap gap-2 items-end">
    <div><label class="text-xs font-bold">المجموعة</label><select name="group_id" class="mt-1 border rounded-xl px-3 py-2 text-sm"><option value="">الكل (80 طالب)</option>@foreach($groups as $g)<option value="{{ $g->id }}" @selected($groupId==$g->id)>{{ $g->name }} — {{ $g->subject->name ?? '' }}</option>@endforeach</select></div>
    <div><label class="text-xs font-bold">الشهر</label><select name="month" class="mt-1 border rounded-xl px-3 py-2 text-sm">@for($m=1;$m<=12;$m++)<option value="{{ $m }}" @selected($month==$m)>{{ $m }}</option>@endfor</select></div>
    <div><label class="text-xs font-bold">السنة</label><input name="year" type="number" value="{{ $year }}" class="mt-1 border rounded-xl px-3 py-2 text-sm w-24"></div>
    <button class="bg-violet-600 text-white px-5 py-2.5 rounded-xl text-sm font-bold">عرض</button>
    <button type="button" onclick="window.print()" class="bg-slate-900 text-white px-5 py-2.5 rounded-xl text-sm font-bold no-print"><i class="fa-solid fa-print ml-1"></i> طباعة A4</button>
    <a href="{{ route('attendance.scan') }}" class="bg-emerald-600 text-white px-5 py-2.5 rounded-xl text-sm font-bold no-print">مسح QR</a>
  </form>
</div>

<div class="bg-white rounded-2xl border overflow-hidden print:border print:shadow-none" id="sheet">
  <div class="px-5 py-3 border-b flex justify-between items-center print:py-2">
    <div>
      <h2 class="font-extrabold text-sm">كشف حضور — {{ $year }}/{{ str_pad($month,2,'0',STR_PAD_LEFT) }} <span class="text-slate-500 font-normal">({{ \Carbon\Carbon::create($year,$month,1)->locale('ar')->translatedFormat('F Y') }})</span></h2>
      <p class="text-xs text-slate-500">@if($groupId) مجموعة: {{ $groups->firstWhere('id',$groupId)->name ?? '' }} @else كل المجموعات @endif — عدد الطلاب: {{ $students->count() }} — عدد الحصص: {{ $sessions->count() }}</p>
    </div>
    <div class="text-[11px] text-slate-400">سنتر الدروس — {{ now('Africa/Cairo')->format('Y/m/d') }}</div>
  </div>
  <div class="overflow-x-auto">
    <table class="w-full text-[11px] print:text-[9px] border-collapse">
      <thead>
        <tr class="bg-slate-900 text-white print:bg-slate-900">
          <th class="p-1.5 text-right border border-slate-700 w-8">#</th>
          <th class="p-1.5 text-right border border-slate-700 min-w-[140px]">الطالب</th>
          <th class="p-1.5 text-center border border-slate-700 font-mono">الكود</th>
          @foreach($days as $d)
            <th class="p-1 border border-slate-700 text-center w-7 @if(isset($sessionsByDay[$d])) bg-violet-700 @endif">{{ $d }}</th>
          @endforeach
          <th class="p-1 border border-slate-700 text-center w-10">حاضر</th>
          <th class="p-1 border border-slate-700 text-center w-10">غائب</th>
        </tr>
        <tr class="bg-slate-100 text-[9px] text-slate-500">
          <th colspan="3" class="p-1 border text-center">اليوم →</th>
          @foreach($days as $d)
            <th class="p-0.5 border text-center font-normal">{{ \Carbon\Carbon::create($year,$month,$d)->locale('ar')->translatedFormat('D') }}</th>
          @endforeach
          <th colspan="2" class="border"></th>
        </tr>
      </thead>
      <tbody>
        @forelse($students as $idx=>$stu)
          @php $presentCount=0; $absentCount=0; @endphp
          <tr class="{{ $idx%2==0?'bg-white':'bg-slate-50' }} print:bg-white">
            <td class="p-1 border text-center font-mono">{{ $idx+1 }}</td>
            <td class="p-1 border font-bold truncate max-w-[150px]">{{ $stu->name }}</td>
            <td class="p-1 border text-center font-mono text-[10px]">{{ $stu->code }}</td>
            @foreach($days as $d)
              @php
                $st = $matrix[$stu->id][$d] ?? null;
                if($st==='present'||$st==='late') $presentCount++;
                if($st==='absent') $absentCount++;
                $hasSession = isset($sessionsByDay[$d]);
              @endphp
              <td class="p-0 border text-center h-6 align-middle @if(!$hasSession) bg-slate-100 print:bg-slate-100 @endif">
                @if(!$hasSession)
                  <span class="text-slate-300">—</span>
                @elseif($st==='present')
                  <span class="inline-flex w-5 h-5 items-center justify-center bg-emerald-600 text-white rounded-full text-[10px]">✓</span>
                @elseif($st==='late')
                  <span class="inline-flex w-5 h-5 items-center justify-center bg-amber-500 text-white rounded-full text-[10px]">ت</span>
                @elseif($st==='absent')
                  <span class="inline-flex w-5 h-5 items-center justify-center bg-rose-600 text-white rounded-full text-[10px]">×</span>
                @elseif($st==='excused')
                  <span class="inline-flex w-5 h-5 items-center justify-center bg-sky-600 text-white rounded-full text-[10px]">ع</span>
                @else
                  <span class="text-slate-300">·</span>
                @endif
              </td>
            @endforeach
            <td class="p-1 border text-center font-extrabold text-emerald-700">{{ $presentCount }}</td>
            <td class="p-1 border text-center font-extrabold text-rose-700">{{ $absentCount }}</td>
          </tr>
        @empty
          <tr><td colspan="{{ 3+count($days)+2 }}" class="p-8 text-center text-slate-400">لا يوجد طلاب</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
  <div class="px-4 py-2 bg-slate-50 border-t flex gap-4 text-[11px] print:bg-white">
    <span class="flex items-center gap-1"><span class="w-3 h-3 bg-emerald-600 rounded-full inline-block"></span> حاضر</span>
    <span class="flex items-center gap-1"><span class="w-3 h-3 bg-amber-500 rounded-full inline-block"></span> متأخر</span>
    <span class="flex items-center gap-1"><span class="w-3 h-3 bg-rose-600 rounded-full inline-block"></span> غائب</span>
    <span class="flex items-center gap-1"><span class="w-3 h-3 bg-sky-600 rounded-full inline-block"></span> معذور</span>
    <span class="mr-auto text-slate-400">— لا توجد حصة · لم يسجل</span>
  </div>
</div>

<style>
@media print{
  @page{size:A4 landscape; margin:10mm}
  body{background:white}
  .no-print, header, aside, #overlay{display:none !important}
  .lg\:mr-\[270px\]{margin-right:0 !important}
  main{padding:0 !important}
  #sheet{border:1px solid #0f172a}
}
</style>
@endsection
