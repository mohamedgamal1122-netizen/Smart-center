@extends('layouts.app')
@section('title','الحصص')
@section('page_title','الحصص')
@section('content')
<div class="flex justify-between items-center mb-5">
  <form method="GET" class="flex gap-2">
    <select name="group_id" class="border rounded-xl px-3 py-2.5 text-sm"><option value="">كل المجموعات</option>@foreach($groups ?? [] as $g)<option value="{{ $g->id }}" @selected(request('group_id')==$g->id)>{{ $g->name }}</option>@endforeach</select>
    <select name="status" class="border rounded-xl px-3 py-2.5 text-sm"><option value="">كل الحالات</option><option value="completed">تمت</option><option value="cancelled">أُلغيت</option><option value="postponed">مؤجلة</option><option value="makeup">تعويضية</option></select>
    <button class="bg-slate-900 text-white px-5 py-2.5 rounded-xl text-sm font-bold">فلترة</button>
  </form>
  <a href="{{ route('sessions.create') }}" class="bg-violet-600 text-white px-5 py-2.5 rounded-xl text-sm font-bold"><i class="fa-solid fa-plus ml-1"></i> إضافة حصة</a>
</div>
<div class="bg-white rounded-2xl border overflow-hidden">
  <div class="overflow-x-auto"><table class="w-full text-sm">
    <thead class="bg-slate-50 text-slate-500 text-xs"><tr><th class="p-3 text-right">المجموعة</th><th class="p-3">التاريخ</th><th class="p-3">الوقت</th><th class="p-3">الحالة</th><th class="p-3">إجراءات</th></tr></thead>
    <tbody>
    @forelse($sessions as $s)
      <tr class="border-t hover:bg-slate-50">
        <td class="p-3 font-bold">{{ $s->group->name ?? '—' }}</td>
        <td class="p-3 text-center">{{ $s->date }}</td>
        <td class="p-3 text-center text-xs">{{ $s->start_time ?? '—' }} - {{ $s->end_time ?? '—' }}</td>
        <td class="p-3 text-center"><span class="text-xs px-2 py-1 rounded-full font-bold @if($s->status=='completed') bg-emerald-100 text-emerald-700 @elseif($s->status=='cancelled') bg-red-100 text-red-600 @elseif($s->status=='postponed') bg-amber-100 text-amber-700 @else bg-violet-100 text-violet-700 @endif">{{ $s->status=='completed' ? 'تمت' : ($s->status=='cancelled' ? 'أُلغيت' : ($s->status=='postponed' ? 'مؤجلة' : 'تعويضية')) }}</span></td>
        <td class="p-3"><div class="flex gap-1">
          <a href="{{ route('sessions.show',$s->id) }}" class="w-8 h-8 rounded-lg bg-slate-100 flex items-center justify-center"><i class="fa-solid fa-eye text-xs"></i></a>
          <a href="{{ route('sessions.edit',$s->id) }}" class="w-8 h-8 rounded-lg bg-amber-100 flex items-center justify-center"><i class="fa-solid fa-pen text-xs text-amber-700"></i></a>
          <a href="{{ route('attendances.create') }}?session_id={{ $s->id }}" class="text-xs bg-violet-600 text-white px-3 py-1.5 rounded-full font-bold">تسجيل حضور</a>
        </div></td>
      </tr>
    @empty
      <tr><td colspan="5" class="p-8 text-center text-slate-400">لا توجد حصص</td></tr>
    @endforelse
    </tbody>
  </table></div>
  <div class="p-4">{{ $sessions->links() }}</div>
</div>
@endsection
