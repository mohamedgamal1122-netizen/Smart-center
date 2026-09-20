@extends('layouts.app')
@section('title',$user->name)
@section('page_title',$user->name)
@section('content')
<div class="bg-white rounded-2xl border p-6 max-w-lg">
<div class="space-y-2 text-sm"><div class="flex justify-between"><span class="text-slate-500">البريد</span><b>{{ $user->email }}</b></div><div class="flex justify-between"><span class="text-slate-500">الدور</span><b>{{ $user->role }}</b></div><div class="flex justify-between"><span class="text-slate-500">الحالة</span><b>{{ $user->is_active?'نشط':'موقوف' }}</b></div></div>
<div class="mt-4 flex gap-2"><a href="{{ route('users.edit',$user) }}" class="bg-amber-500 text-white px-4 py-2 rounded-xl text-sm font-bold">تعديل</a><a href="{{ route('users.index') }}" class="bg-slate-100 px-4 py-2 rounded-xl text-sm font-bold">رجوع</a></div>
</div>
@endsection
