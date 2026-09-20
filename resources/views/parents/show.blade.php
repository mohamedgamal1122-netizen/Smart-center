@extends('layouts.app')
@section('title',$parent->name)
@section('page_title','بيانات ولي الأمر')
@section('content')
<div class="bg-white rounded-2xl border border-slate-200 p-6">
  <h2 class="text-lg font-extrabold">{{ $parent->name }} <span class="text-sm font-normal text-slate-500">— {{ $parent->relation ?? 'ولي أمر' }}</span></h2>
  <div class="mt-4 grid md:grid-cols-3 gap-4 text-sm">
    <div class="bg-slate-50 rounded-xl p-3"><div class="text-xs text-slate-500">الهاتف الأساسي</div><div class="font-bold dir-ltr text-right">{{ $parent->phone_primary }}</div></div>
    <div class="bg-slate-50 rounded-xl p-3"><div class="text-xs text-slate-500">هاتف إضافي</div><div class="font-bold">{{ $parent->phone_secondary ?? '—' }}</div></div>
    <div class="bg-slate-50 rounded-xl p-3"><div class="text-xs text-slate-500">البريد</div><div class="font-bold">{{ $parent->email ?? '—' }}</div></div>
  </div>
  <div class="mt-6">
    <h3 class="font-bold mb-3">الطلاب المرتبطون ({{ $parent->students->count() }})</h3>
    <div class="space-y-2">
      @forelse($parent->students as $s)
        <a href="{{ route('students.show',$s->id) }}" class="flex items-center justify-between bg-slate-50 hover:bg-violet-50 border rounded-xl px-4 py-3">
          <span class="font-bold">{{ $s->name }} <span class="text-xs text-slate-500">— {{ $s->code }}</span></span>
          <span class="text-xs bg-white border px-2 py-1 rounded-full">{{ $s->grade ?? 'بدون صف' }}</span>
        </a>
      @empty
        <p class="text-slate-400 text-sm">لا يوجد طلاب مرتبطون</p>
      @endforelse
    </div>
  </div>
  <div class="mt-6 flex gap-2"><a href="{{ route('parents.edit',['id'=>$parent->id]) }}" class="bg-amber-500 text-white px-5 py-2 rounded-xl text-sm font-bold">تعديل</a><a href="{{ route('parents.report',['id'=>$parent->id]) }}" target="_blank" class="bg-red-600 text-white px-5 py-2 rounded-xl text-sm font-bold"><i class="fa-solid fa-file-pdf ml-1"></i> تقرير PDF</a><a href="{{ route('parents.index') }}" class="bg-slate-100 px-5 py-2 rounded-xl text-sm font-bold">رجوع</a></div>
</div>
@endsection
