@extends('layouts.app')
@section('title',$subject->name)
@section('page_title',$subject->name)
@section('content')
<div class="bg-white rounded-2xl border p-6 max-w-lg">
<p class="text-sm">الكود: <b>{{ $subject->code ?? '—' }}</b> — الصف: <b>{{ $subject->grade ?? '—' }}</b></p>
<div class="mt-4 flex gap-2"><a href="{{ route('subjects.edit',$subject) }}" class="bg-amber-500 text-white px-4 py-2 rounded-xl text-sm font-bold">تعديل</a><a href="{{ route('subjects.index') }}" class="bg-slate-100 px-4 py-2 rounded-xl text-sm font-bold">رجوع</a></div>
</div>
@endsection
