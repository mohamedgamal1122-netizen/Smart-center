@extends('layouts.app')
@section('title',$student->name)
@section('page_title',$student->name)
@section('content')
<div class="grid lg:grid-cols-3 gap-4">
  <div class="lg:col-span-1 space-y-4">
    <div class="bg-white rounded-2xl border border-slate-200 p-5">
      <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-violet-600 to-indigo-600 flex items-center justify-center text-white text-xl font-extrabold">{{ mb_substr($student->name,0,1) }}</div>
      <h2 class="font-extrabold text-lg mt-3">{{ $student->name }}</h2><p class="text-xs font-mono text-slate-500">{{ $student->code }}</p>
      <div class="mt-4 space-y-2 text-sm">
        <div class="flex justify-between"><span class="text-slate-500">الهاتف</span><span class="font-bold">{{ $student->phone ?? '—' }}</span></div>
        <div class="flex justify-between"><span class="text-slate-500">الصف</span><span class="font-bold">{{ $student->grade ?? '—' }}</span></div>
        <div class="flex justify-between"><span class="text-slate-500">الحالة</span><span class="px-2 py-1 rounded-full text-xs font-bold {{ $student->status==='active'?'bg-emerald-50 text-emerald-700':'bg-slate-100 text-slate-600' }}">{{ $student->status }}</span></div>
        <div class="flex justify-between"><span class="text-slate-500">تاريخ التسجيل</span><span>{{ $student->enrollment_date?->format('Y/m/d') ?? '—' }}</span></div>
      </div>
      <a href="{{ route('students.edit',$student) }}" class="mt-4 block text-center bg-slate-900 text-white py-2.5 rounded-xl text-sm font-bold">تعديل</a>
    </div>
    {{-- QR حضور --}}
    <div class="bg-white rounded-2xl border border-slate-200 p-5 text-center">
      <h3 class="font-bold text-sm mb-3 flex items-center justify-center gap-2"><i class="fa-solid fa-qrcode text-violet-600"></i> كود الحضور QR</h3>
      <div class="bg-slate-50 rounded-xl p-3 border border-dashed border-slate-300 inline-block">
        <img src="{{ $qrUrl }}" alt="QR {{ $student->code }}" class="w-[200px] h-[200px] mx-auto rounded-lg" loading="lazy">
      </div>
      <p class="text-xs font-mono text-slate-500 mt-2 break-all">{{ $qrData }}</p>
      <p class="text-[11px] text-slate-400 mt-1">امسح الكود من <a href="{{ route('attendance.scan') }}?code={{ $student->code }}" class="text-violet-600 font-bold">صفحة المسح</a> لتسجيل الحضور تلقائياً</p>
      <div class="mt-3 flex gap-2 justify-center">
        <a href="{{ route('students.qr',$student) }}" target="_blank" class="bg-violet-600 text-white px-4 py-2 rounded-xl text-xs font-bold"><i class="fa-solid fa-print ml-1"></i> طباعة QR</a>
        <a href="{{ route('attendance.scan') }}" class="bg-slate-900 text-white px-4 py-2 rounded-xl text-xs font-bold"><i class="fa-solid fa-camera ml-1"></i> صفحة المسح</a>
      </div>
    </div>
    <div class="bg-white rounded-2xl border border-slate-200 p-4">
      <h3 class="font-bold text-sm mb-2">أولياء الأمور</h3>
      @forelse($student->parents as $p)<div class="text-sm py-1.5 border-b last:border-0 flex justify-between"><span>{{ $p->name }}</span><span class="text-slate-500">{{ $p->phone_primary ?? $p->phone }}</span></div>@empty <p class="text-xs text-slate-400">لا يوجد</p> @endforelse
    </div>
  </div>
  <div class="lg:col-span-2 space-y-4">
    <div class="bg-white rounded-2xl border border-slate-200 p-4">
      <h3 class="font-bold mb-3">المجموعات</h3>
      @forelse($student->enrollments as $e)<div class="flex items-center justify-between py-2 border-b last:border-0 text-sm"><div><span class="font-bold">{{ $e->group->name ?? '—' }}</span> <span class="text-xs text-slate-500">{{ $e->group->subject->name ?? '' }}</span></div><span class="text-xs px-2 py-1 rounded-full {{ $e->status==='active'?'bg-emerald-50 text-emerald-700':'bg-slate-100' }}">{{ $e->status }}</span></div>@empty <p class="text-sm text-slate-400">غير مسجل في مجموعات</p> @endforelse
    </div>
    <div class="bg-white rounded-2xl border border-slate-200 p-4">
      <h3 class="font-bold mb-3">المدفوعات</h3>
      @forelse($student->payments->take(8) as $p)<div class="flex items-center justify-between py-2 border-b last:border-0 text-sm"><span>{{ $p->month }}/{{ $p->year }} — {{ $p->receipt_number }}</span><span class="font-bold {{ $p->remaining<=0?'text-emerald-600':'text-rose-600' }}">{{ number_format($p->paid_amount,0) }} / {{ number_format($p->net_required,0) }}</span></div>@empty <p class="text-sm text-slate-400">لا يوجد</p> @endforelse
    </div>
    <div class="bg-white rounded-2xl border border-slate-200 p-4">
      <h3 class="font-bold mb-3">الحضور (آخر 10)</h3>
      @forelse($student->attendances->take(10) as $a)<div class="flex justify-between py-1.5 text-sm border-b last:border-0"><span>{{ $a->session->date ?? $a->created_at->format('Y/m/d') }}</span><span class="px-2 py-0.5 rounded-full text-xs font-bold {{ $a->status==='present'?'bg-emerald-50 text-emerald-700':($a->status==='absent'?'bg-rose-50 text-rose-700':'bg-amber-50 text-amber-700') }}">{{ $a->status }}</span></div>@empty <p class="text-sm text-slate-400">لا يوجد</p> @endforelse
    </div>
  </div>
</div>
@endsection
