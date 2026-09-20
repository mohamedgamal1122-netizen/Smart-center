@extends('layouts.app')
@section('title','المجموعات')
@section('page_title','المجموعات')
@section('content')
<div class="flex flex-wrap gap-3 justify-between items-center mb-5">
  <form method="GET" class="flex gap-2 flex-wrap">
    <input name="q" value="{{ request('q') }}" placeholder="بحث..." class="border rounded-xl px-3 py-2.5 w-48 text-sm">
    <select name="subject_id" class="border rounded-xl px-3 py-2.5 text-sm"><option value="">كل المواد</option>@foreach($subjects ?? [] as $s)<option value="{{ $s->id }}" @selected(request('subject_id')==$s->id)>{{ $s->name }}</option>@endforeach</select>
    <select name="status" class="border rounded-xl px-3 py-2.5 text-sm"><option value="">كل الحالات</option><option value="open" @selected(request('status')=='open')>مفتوحة</option><option value="full" @selected(request('status')=='full')>مكتملة</option><option value="paused" @selected(request('status')=='paused')>متوقفة</option></select>
    <button class="bg-slate-900 text-white px-5 py-2.5 rounded-xl text-sm font-bold">فلترة</button>
  </form>
  <a href="{{ route('groups.create') }}" class="bg-violet-600 text-white px-5 py-2.5 rounded-xl text-sm font-bold"><i class="fa-solid fa-plus ml-1"></i> إضافة مجموعة</a>
</div>
<div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4">
  @forelse($groups as $g)
    @php $cnt = $g->enrollments_count ?? $g->enrollments->count(); $pct = $g->max_students ? round($cnt / $g->max_students * 100) : 0; @endphp
    <div class="bg-white rounded-2xl border border-slate-200 p-5 hover:shadow-lg transition">
      <div class="flex justify-between items-start">
        <h3 class="font-extrabold text-sm">{{ $g->name }}</h3>
        <span class="text-xs px-2.5 py-1 rounded-full font-bold @if($g->status=='open') bg-emerald-100 text-emerald-700 @elseif($g->status=='full') bg-amber-100 text-amber-700 @else bg-slate-100 text-slate-500 @endif">{{ $g->status=='open' ? 'مفتوحة' : ($g->status=='full' ? 'مكتملة' : 'متوقفة') }}</span>
      </div>
      <p class="text-xs text-slate-500 mt-1">{{ $g->subject->name ?? '' }} — {{ $g->grade }} — {{ $g->teacher->name ?? '' }}</p>
      <p class="text-xs text-slate-400 mt-1"><i class="fa-solid fa-location-dot ml-1"></i>{{ $g->room ?? 'بدون قاعة' }} — {{ $g->start_time ?? '' }} - {{ $g->end_time ?? '' }}</p>
      <div class="mt-3">
        <div class="flex justify-between text-xs mb-1"><span>{{ $cnt }} / {{ $g->max_students }} طالب</span><span class="font-bold">{{ $pct }}%</span></div>
        <div class="h-2 bg-slate-100 rounded-full overflow-hidden"><div class="h-full rounded-full @if($pct>=100) bg-red-500 @elseif($pct>=80) bg-amber-500 @else bg-violet-600 @endif" style="width: {{ min($pct,100) }}%"></div></div>
      </div>
      <div class="mt-3 flex items-center justify-between">
        <span class="text-xs font-bold text-violet-700">{{ number_format($g->monthly_fee) }} ج.م /شهر</span>
        <div class="flex gap-1">
          <a href="{{ route('groups.show',$g->id) }}" class="w-8 h-8 rounded-lg bg-slate-100 flex items-center justify-center"><i class="fa-solid fa-eye text-xs"></i></a>
          <a href="{{ route('groups.edit',$g->id) }}" class="w-8 h-8 rounded-lg bg-amber-100 flex items-center justify-center"><i class="fa-solid fa-pen text-xs text-amber-700"></i></a>
        </div>
      </div>
    </div>
  @empty
    <div class="col-span-3 bg-white rounded-2xl border p-8 text-center text-slate-400">لا توجد مجموعات</div>
  @endforelse
</div>
<div class="mt-4">{{ $groups->links() }}</div>
@endsection
