@extends('layouts.app')
@section('title','الطلاب')
@section('page_title','الطلاب')
@section('content')
<div class="flex flex-wrap gap-2 mb-4">
  <form method="GET" class="flex gap-2 flex-1 min-w-[260px]">
    <input name="q" value="{{ request('q') }}" placeholder="بحث بالاسم / الكود / الهاتف" class="flex-1 rounded-xl border border-slate-200 px-4 py-2.5 text-sm outline-none focus:ring-2 focus:ring-violet-500">
    <select name="status" class="rounded-xl border border-slate-200 px-3 py-2.5 text-sm"><option value="">كل الحالات</option><option value="active" @selected(request('status')=='active')>نشط</option><option value="inactive" @selected(request('status')=='inactive')>غير نشط</option></select>
    <button class="bg-white border border-slate-200 px-4 py-2.5 rounded-xl text-sm font-bold">بحث</button>
  </form>
  <a href="{{ request()->fullUrlWithQuery(['export'=>'excel']) }}" class="bg-emerald-600 text-white px-5 py-2.5 rounded-xl text-sm font-bold whitespace-nowrap"><i class="fa-solid fa-file-excel ml-1"></i> تصدير Excel</a>
  <a href="{{ route('students.create') }}" class="bg-[#0f172a] text-white px-5 py-2.5 rounded-xl text-sm font-bold whitespace-nowrap"><i class="fa-solid fa-plus ml-1"></i> طالب جديد</a>
</div>
<div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
  <div class="overflow-x-auto">
  <table class="w-full text-sm">
    <thead class="bg-slate-50 text-slate-500 text-xs"><tr><th class="px-4 py-3 text-right">الكود</th><th class="px-4 py-3 text-right">الاسم</th><th class="px-4 py-3 text-right">الهاتف</th><th class="px-4 py-3 text-right">الصف</th><th class="px-4 py-3 text-right">الحالة</th><th class="px-4 py-3"></th></tr></thead>
    <tbody class="divide-y divide-slate-100">
    @forelse($students as $s)
      <tr class="hover:bg-slate-50"><td class="px-4 py-3 font-mono text-xs">{{ $s->code }}</td><td class="px-4 py-3 font-bold"><a href="{{ route('students.show',$s) }}" class="hover:text-violet-600">{{ $s->name }}</a></td><td class="px-4 py-3">{{ $s->phone ?? '—' }}</td><td class="px-4 py-3">{{ $s->grade ?? '—' }}</td><td class="px-4 py-3"><span class="px-2.5 py-1 rounded-full text-xs font-bold {{ $s->status==='active'?'bg-emerald-50 text-emerald-700':'bg-slate-100 text-slate-600' }}">{{ $s->status }}</span></td><td class="px-4 py-3 flex gap-1"><a href="{{ route('students.edit',$s) }}" class="w-8 h-8 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center"><i class="fa-solid fa-pen text-xs"></i></a><form method="POST" action="{{ route('students.destroy',$s) }}" onsubmit="return confirm('حذف الطالب؟')">@csrf @method('DELETE')<button class="w-8 h-8 rounded-lg bg-rose-50 text-rose-600 flex items-center justify-center"><i class="fa-solid fa-trash text-xs"></i></button></form></td></tr>
    @empty <tr><td colspan="6" class="text-center py-10 text-slate-400">لا يوجد طلاب</td></tr> @endforelse
    </tbody>
  </table>
  </div>
  <div class="p-4 border-t">{{ $students->links() }}</div>
</div>
@endsection
