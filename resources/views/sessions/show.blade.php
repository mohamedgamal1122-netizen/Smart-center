@extends('layouts.app')
@section('title','تفاصيل الحصة')
@section('page_title','تفاصيل الحصة')
@section('content')
<div class="bg-white rounded-2xl border p-6">
  <div class="flex justify-between items-start">
    <div><h2 class="font-extrabold text-lg">{{ $session->group->name ?? '—' }}</h2><p class="text-sm text-slate-500">{{ $session->date }} — {{ $session->start_time }} إلى {{ $session->end_time }}</p></div>
    <span class="text-xs px-3 py-1.5 rounded-full font-bold @if($session->status=='completed') bg-emerald-100 text-emerald-700 @else bg-slate-100 @endif">{{ $session->status }}</span>
  </div>
  <div class="mt-6">
    <div class="flex justify-between items-center mb-3"><h3 class="font-bold">الحضور ({{ $session->attendances->count() }})</h3><a href="{{ route('attendances.create') }}?session_id={{ $session->id }}" class="bg-violet-600 text-white px-4 py-1.5 rounded-full text-xs font-bold">تسجيل حضور</a></div>
    <div class="space-y-2">
      @forelse($session->attendances as $a)
        <div class="flex justify-between items-center border rounded-xl px-4 py-2.5 text-sm">
          <span class="font-bold">{{ $a->student->name ?? '—' }}</span>
          <span class="text-xs px-2 py-1 rounded-full font-bold @if($a->status=='present') bg-emerald-100 text-emerald-700 @elseif($a->status=='absent') bg-red-100 text-red-600 @elseif($a->status=='late') bg-amber-100 text-amber-700 @else bg-slate-100 @endif">{{ $a->status=='present' ? 'حاضر' : ($a->status=='absent' ? 'غائب' : ($a->status=='late' ? 'متأخر' : 'بعذر')) }}</span>
        </div>
      @empty
        <p class="text-slate-400 text-sm">لم يتم تسجيل حضور بعد</p>
      @endforelse
    </div>
  </div>
  <div class="mt-6 flex gap-2"><a href="{{ route('sessions.edit',$session->id) }}" class="bg-amber-500 text-white px-5 py-2 rounded-xl text-sm font-bold">تعديل</a><a href="{{ route('sessions.index') }}" class="bg-slate-100 px-5 py-2 rounded-xl text-sm font-bold">رجوع</a></div>
</div>
@endsection
