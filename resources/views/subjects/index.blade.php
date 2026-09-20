@extends('layouts.app')
@section('title','المواد')
@section('page_title','المواد الدراسية')
@section('content')
<div class="flex justify-between items-center mb-5">
  <form method="GET" class="flex gap-2"><input name="q" value="{{ request('q') }}" placeholder="بحث..." class="border rounded-xl px-4 py-2.5 w-56 text-sm"><button class="bg-slate-900 text-white px-5 py-2.5 rounded-xl text-sm font-bold">بحث</button></form>
  <a href="{{ route('subjects.create') }}" class="bg-violet-600 text-white px-5 py-2.5 rounded-xl text-sm font-bold"><i class="fa-solid fa-plus ml-1"></i> إضافة مادة</a>
</div>
<div class="grid md:grid-cols-3 gap-4">
  @forelse($subjects as $s)
  <div class="bg-white rounded-2xl border border-slate-200 p-5">
    <div class="flex justify-between items-start">
      <h3 class="font-extrabold">{{ $s->name }}</h3>
      <span class="text-xs px-2.5 py-1 rounded-full font-bold {{ $s->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-500' }}">{{ $s->is_active ? 'نشطة' : 'متوقفة' }}</span>
    </div>
    <p class="text-sm text-slate-500 mt-1">{{ $s->stage ?? 'بدون مرحلة' }}</p>
    <p class="text-xs text-slate-400 mt-2 line-clamp-2">{{ $s->description ?? '—' }}</p>
    <div class="mt-4 flex gap-1">
      <a href="{{ route('subjects.edit',$s->id) }}" class="flex-1 bg-amber-100 text-amber-700 py-2 rounded-xl text-xs font-bold text-center">تعديل</a>
      <form method="POST" action="{{ route('subjects.destroy',$s->id) }}" onsubmit="return confirm('تأكيد الحذف؟')" class="flex-1">@csrf @method('DELETE')<button class="w-full bg-red-50 text-red-600 py-2 rounded-xl text-xs font-bold">حذف</button></form>
    </div>
  </div>
  @empty
    <div class="col-span-3 bg-white rounded-2xl border p-8 text-center text-slate-400">لا توجد مواد</div>
  @endforelse
</div>
<div class="mt-4">{{ $subjects->links() }}</div>
@endsection
