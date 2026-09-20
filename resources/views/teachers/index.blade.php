@extends('layouts.app')
@section('title','المدرسون')
@section('page_title','المدرسون')
@section('content')
<div class="flex justify-between items-center mb-5">
  <form method="GET" class="flex gap-2"><input name="q" value="{{ request('q') }}" placeholder="بحث بالاسم أو التخصص..." class="border rounded-xl px-4 py-2.5 w-64 text-sm"><button class="bg-slate-900 text-white px-5 py-2.5 rounded-xl text-sm font-bold">بحث</button></form>
  <a href="{{ route('teachers.create') }}" class="bg-violet-600 text-white px-5 py-2.5 rounded-xl text-sm font-bold"><i class="fa-solid fa-plus ml-1"></i> إضافة مدرس</a>
</div>
<div class="bg-white rounded-2xl border overflow-hidden">
  <div class="overflow-x-auto"><table class="w-full text-sm">
    <thead class="bg-slate-50 text-slate-500 text-xs"><tr><th class="p-3 text-right">الاسم</th><th class="p-3">التخصص</th><th class="p-3">الهاتف</th><th class="p-3">المجموعات</th><th class="p-3">الحالة</th><th class="p-3">إجراءات</th></tr></thead>
    <tbody>
    @forelse($teachers as $t)
      <tr class="border-t hover:bg-slate-50">
        <td class="p-3 font-bold">{{ $t->name }}</td>
        <td class="p-3 text-center">{{ $t->specialization ?? '—' }}</td>
        <td class="p-3 text-center dir-ltr">{{ $t->phone ?? '—' }}</td>
        <td class="p-3 text-center"><span class="bg-violet-100 text-violet-700 px-2 py-1 rounded-full text-xs font-bold">{{ $t->groups_count ?? $t->groups->count() }}</span></td>
        <td class="p-3 text-center"><span class="text-xs px-2 py-1 rounded-full font-bold {{ $t->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-600' }}">{{ $t->is_active ? 'نشط' : 'موقوف' }}</span></td>
        <td class="p-3"><div class="flex gap-1">
          <a href="{{ route('teachers.show',$t->id) }}" class="w-8 h-8 rounded-lg bg-slate-100 flex items-center justify-center"><i class="fa-solid fa-eye text-xs"></i></a>
          <a href="{{ route('teachers.edit',$t->id) }}" class="w-8 h-8 rounded-lg bg-amber-100 flex items-center justify-center"><i class="fa-solid fa-pen text-xs text-amber-700"></i></a>
          <form method="POST" action="{{ route('teachers.destroy',$t->id) }}" onsubmit="return confirm('حذف المدرس؟')">@csrf @method('DELETE')<button class="w-8 h-8 rounded-lg bg-red-100 flex items-center justify-center"><i class="fa-solid fa-trash text-xs text-red-600"></i></button></form>
        </div></td>
      </tr>
    @empty
      <tr><td colspan="6" class="p-8 text-center text-slate-400">لا يوجد مدرسون</td></tr>
    @endforelse
    </tbody>
  </table></div>
  <div class="p-4">{{ $teachers->links() }}</div>
</div>
@endsection
