@extends('layouts.app')
@section('title','الحضور')
@section('page_title','سجل الحضور')
@section('content')
<div class="flex flex-wrap justify-between items-center mb-5 gap-2">
  <form method="GET" class="flex gap-2 flex-wrap">
    <select name="group_id" class="border rounded-xl px-3 py-2.5 text-sm"><option value="">كل المجموعات</option>@foreach($groups ?? [] as $g)<option value="{{ $g->id }}" @selected(request('group_id')==$g->id)>{{ $g->name }}</option>@endforeach</select>
    <input name="date" type="date" value="{{ request('date') }}" class="border rounded-xl px-3 py-2.5 text-sm">
    <button class="bg-slate-900 text-white px-5 py-2.5 rounded-xl text-sm font-bold">فلترة</button>
  </form>
  <div class="flex gap-2">
    <a href="{{ route('attendance.scan') }}" class="bg-violet-600 text-white px-4 py-2.5 rounded-xl text-sm font-bold"><i class="fa-solid fa-qrcode ml-1"></i> مسح QR</a>
    <a href="{{ route('attendances.sheet') }}" class="bg-slate-900 text-white px-4 py-2.5 rounded-xl text-sm font-bold"><i class="fa-solid fa-table ml-1"></i> كشف شهري</a>
    <a href="{{ route('attendances.create') }}" class="bg-emerald-600 text-white px-5 py-2.5 rounded-xl text-sm font-bold"><i class="fa-solid fa-plus ml-1"></i> تسجيل حضور</a>
  </div>
</div>
<div class="mb-3 flex justify-end"><a href="{{ request()->fullUrlWithQuery(['export'=>'excel']) }}" class="bg-emerald-600 text-white px-5 py-2.5 rounded-xl text-sm font-bold">Excel</a></div>
<div class="bg-white rounded-2xl border overflow-hidden">
  <div class="overflow-x-auto"><table class="w-full text-sm">
    <thead class="bg-slate-50 text-slate-500 text-xs"><tr><th class="p-3 text-right">الحصة</th><th class="p-3">الطالب</th><th class="p-3">الحالة</th><th class="p-3">التاريخ</th><th class="p-3">إجراءات</th></tr></thead>
    <tbody>
    @forelse($attendances as $a)
      <tr class="border-t hover:bg-slate-50">
        <td class="p-3">{{ $a->session->group->name ?? '—' }} — {{ $a->session->date ?? '' }}</td>
        <td class="p-3 font-bold">{{ $a->student->name ?? '—' }}</td>
        <td class="p-3 text-center"><span class="text-xs px-2 py-1 rounded-full font-bold @if($a->status=='present') bg-emerald-100 text-emerald-700 @elseif($a->status=='absent') bg-red-100 text-red-600 @elseif($a->status=='late') bg-amber-100 text-amber-700 @else bg-slate-100 @endif">{{ $a->status=='present' ? 'حاضر' : ($a->status=='absent' ? 'غائب' : ($a->status=='late' ? 'متأخر' : 'بعذر')) }}</span></td>
        <td class="p-3 text-center text-xs">{{ $a->created_at->format('Y/m/d') }}</td>
        <td class="p-3"><form method="POST" action="{{ route('attendances.destroy',$a->id) }}" onsubmit="return confirm('حذف؟')">@csrf @method('DELETE')<button class="text-xs bg-red-50 text-red-600 px-3 py-1 rounded-full font-bold">حذف</button></form></td>
      </tr>
    @empty
      <tr><td colspan="5" class="p-8 text-center text-slate-400">لا يوجد سجل حضور</td></tr>
    @endforelse
    </tbody>
  </table></div>
  <div class="p-4">{{ $attendances->links() }}</div>
</div>
@endsection
