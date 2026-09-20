@extends('layouts.app')
@section('title','أولياء الأمور')
@section('page_title','أولياء الأمور')
@section('content')
<div class="flex flex-wrap gap-3 justify-between items-center mb-5">
  <form method="GET" class="flex gap-2">
    <input name="q" value="{{ request('q') }}" placeholder="بحث بالاسم أو الهاتف..." class="border border-slate-300 rounded-xl px-4 py-2.5 w-64 text-sm focus:ring-2 focus:ring-violet-500 outline-none">
    <button class="bg-slate-900 text-white px-5 py-2.5 rounded-xl text-sm font-bold">بحث</button>
  </form>
  <a href="{{ route('parents.create') }}" class="bg-violet-600 hover:bg-violet-700 text-white px-5 py-2.5 rounded-xl text-sm font-bold"><i class="fa-solid fa-plus ml-1"></i> إضافة ولي أمر</a>
</div>
<div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
  <div class="overflow-x-auto">
  <table class="w-full text-sm">
    <thead class="bg-slate-50 text-slate-500 text-xs"><tr><th class="p-3 text-right">#</th><th class="p-3 text-right">الاسم</th><th class="p-3">الهاتف</th><th class="p-3">صلة القرابة</th><th class="p-3">عدد الطلاب</th><th class="p-3">إجراءات</th></tr></thead>
    <tbody>
    @forelse($parents as $p)
      <tr class="border-t hover:bg-slate-50">
        <td class="p-3">{{ $loop->iteration + ($parents->firstItem()-1) }}</td>
        <td class="p-3 font-bold">{{ $p->name }}</td>
        <td class="p-3 text-center dir-ltr">{{ $p->phone_primary }} @if($p->phone_secondary) <br><span class="text-xs text-slate-400">{{ $p->phone_secondary }}</span> @endif</td>
        <td class="p-3 text-center">{{ $p->relation ?? '—' }}</td>
        <td class="p-3 text-center"><span class="bg-violet-100 text-violet-700 px-2.5 py-1 rounded-full text-xs font-bold">{{ $p->students_count ?? $p->students->count() }}</span></td>
        <td class="p-3"><div class="flex gap-1">
          <a href="{{ route('parents.show',['id'=>$p->id]) }}" class="w-8 h-8 rounded-lg bg-slate-100 hover:bg-slate-200 flex items-center justify-center"><i class="fa-solid fa-eye text-xs"></i></a>
          <a href="{{ route('parents.edit',['id'=>$p->id]) }}" class="w-8 h-8 rounded-lg bg-amber-100 hover:bg-amber-200 flex items-center justify-center"><i class="fa-solid fa-pen text-xs text-amber-700"></i></a>
          <form method="POST" action="{{ route('parents.destroy',['id'=>$p->id]) }}" onsubmit="return confirm('تأكيد الحذف؟')">@csrf @method('DELETE')<button class="w-8 h-8 rounded-lg bg-red-100 hover:bg-red-200 flex items-center justify-center"><i class="fa-solid fa-trash text-xs text-red-600"></i></button></form>
        </div></td>
      </tr>
    @empty
      <tr><td colspan="6" class="p-8 text-center text-slate-400">لا يوجد أولياء أمور</td></tr>
    @endforelse
    </tbody>
  </table>
  </div>
  <div class="p-4">{{ $parents->links() }}</div>
</div>
@endsection
