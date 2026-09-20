@extends('layouts.app')
@section('title',$teacher->name)
@section('page_title','بيانات المدرس')
@section('content')
<div class="bg-white rounded-2xl border p-6">
  <h2 class="text-lg font-extrabold">{{ $teacher->name }} <span class="text-sm font-normal text-slate-500">— {{ $teacher->specialization ?? 'بدون تخصص' }}</span></h2>
  <div class="mt-4 grid md:grid-cols-4 gap-4 text-sm">
    <div class="bg-slate-50 rounded-xl p-3"><div class="text-xs text-slate-500">الهاتف</div><div class="font-bold">{{ $teacher->phone ?? '—' }}</div></div>
    <div class="bg-slate-50 rounded-xl p-3"><div class="text-xs text-slate-500">البريد</div><div class="font-bold">{{ $teacher->email ?? '—' }}</div></div>
    <div class="bg-slate-50 rounded-xl p-3"><div class="text-xs text-slate-500">الأجر</div><div class="font-bold">{{ $teacher->rate_value }} {{ $teacher->rate_type=='percentage' ? '%' : 'ج.م/حصة' }}</div></div>
    <div class="bg-slate-50 rounded-xl p-3"><div class="text-xs text-slate-500">المجموعات</div><div class="font-bold">{{ $teacher->groups->count() }} مجموعة</div></div>
  </div>
  <h3 class="font-bold mt-6 mb-3">المجموعات ({{ $teacher->groups->count() }})</h3>
  <div class="grid md:grid-cols-2 gap-3">
    @forelse($teacher->groups as $g)
      <a href="{{ route('groups.show',$g->id) }}" class="border rounded-xl p-4 hover:bg-violet-50">
        <div class="font-bold">{{ $g->name }}</div>
        <div class="text-xs text-slate-500">{{ $g->subject->name ?? '' }} — {{ $g->grade }}</div>
        <div class="text-xs mt-1">{{ $g->enrollments_count ?? $g->enrollments->count() }} طالب / {{ $g->max_students }}</div>
      </a>
    @empty
      <p class="text-slate-400 text-sm">لا توجد مجموعات</p>
    @endforelse
  </div>
</div>
@endsection
