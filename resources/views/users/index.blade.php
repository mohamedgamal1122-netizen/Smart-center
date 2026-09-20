@extends('layouts.app')
@section('title','المستخدمون')
@section('page_title','المستخدمون والصلاحيات')
@section('content')
<div class="flex justify-between items-center mb-5">
  <form method="GET" class="flex gap-2"><input name="q" value="{{ request('q') }}" placeholder="بحث..." class="border rounded-xl px-4 py-2.5 w-64 text-sm"><button class="bg-slate-900 text-white px-5 py-2.5 rounded-xl text-sm font-bold">بحث</button></form>
  <a href="{{ route('users.create') }}" class="bg-violet-600 text-white px-5 py-2.5 rounded-xl text-sm font-bold"><i class="fa-solid fa-plus ml-1"></i> إضافة مستخدم</a>
</div>
<div class="bg-white rounded-2xl border overflow-hidden">
  <div class="overflow-x-auto"><table class="w-full text-sm">
    <thead class="bg-slate-50 text-slate-500 text-xs"><tr><th class="p-3 text-right">الاسم</th><th class="p-3">البريد</th><th class="p-3">الدور</th><th class="p-3">الحالة</th><th class="p-3">إجراءات</th></tr></thead>
    <tbody>
    @forelse($users as $u)
      <tr class="border-t hover:bg-slate-50">
        <td class="p-3 font-bold">{{ $u->name }}</td>
        <td class="p-3 text-center text-xs">{{ $u->email }}</td>
        <td class="p-3 text-center"><span class="text-xs px-2 py-1 rounded-full font-bold @if($u->role=='admin') bg-violet-100 text-violet-700 @elseif($u->role=='receptionist') bg-sky-100 text-sky-700 @elseif($u->role=='accountant') bg-emerald-100 text-emerald-700 @else bg-amber-100 text-amber-700 @endif">{{ $u->role=='admin' ? 'مدير' : ($u->role=='receptionist' ? 'استقبال' : ($u->role=='accountant' ? 'محاسب' : 'مدرس')) }}</span></td>
        <td class="p-3 text-center"><span class="text-xs px-2 py-1 rounded-full font-bold {{ $u->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-600' }}">{{ $u->is_active ? 'نشط' : 'موقوف' }}</span></td>
        <td class="p-3"><div class="flex gap-1">
          <a href="{{ route('users.permissions',$u->id) }}" class="w-8 h-8 rounded-lg bg-violet-100 flex items-center justify-center" title="الصلاحيات"><i class="fa-solid fa-key text-xs text-violet-700"></i></a>
          <a href="{{ route('users.edit',$u->id) }}" class="w-8 h-8 rounded-lg bg-amber-100 flex items-center justify-center"><i class="fa-solid fa-pen text-xs text-amber-700"></i></a>
          <form method="POST" action="{{ route('users.destroy',$u->id) }}" onsubmit="return confirm('حذف المستخدم؟')">@csrf @method('DELETE')<button class="w-8 h-8 rounded-lg bg-red-100 flex items-center justify-center"><i class="fa-solid fa-trash text-xs text-red-600"></i></button></form>
        </div></td>
      </tr>
    @empty
      <tr><td colspan="5" class="p-8 text-center text-slate-400">لا يوجد مستخدمون</td></tr>
    @endforelse
    </tbody>
  </table></div>
  <div class="p-4">{{ $users->links() }}</div>
</div>
@endsection
