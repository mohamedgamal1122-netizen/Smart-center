@extends('layouts.app')
@section('title','الامتحانات')
@section('page_title','الامتحانات')
@section('content')
<div class="flex justify-between items-center mb-5">
  <form method="GET" class="flex gap-2"><select name="group_id" class="border rounded-xl px-3 py-2.5 text-sm"><option value="">كل المجموعات</option>@foreach($groups ?? [] as $g)<option value="{{ $g->id }}" @selected(request('group_id')==$g->id)>{{ $g->name }}</option>@endforeach</select><button class="bg-slate-900 text-white px-5 py-2.5 rounded-xl text-sm font-bold">فلترة</button></form>
  <a href="{{ route('exams.create') }}" class="bg-violet-600 text-white px-5 py-2.5 rounded-xl text-sm font-bold"><i class="fa-solid fa-plus ml-1"></i> إضافة اختبار</a>
</div>
<div class="bg-white rounded-2xl border overflow-hidden">
  <div class="overflow-x-auto"><table class="w-full text-sm">
    <thead class="bg-slate-50 text-slate-500 text-xs"><tr><th class="p-3 text-right">الاختبار</th><th class="p-3">المجموعة</th><th class="p-3">التاريخ</th><th class="p-3">الدرجة النهائية</th><th class="p-3">النوع</th><th class="p-3">إجراءات</th></tr></thead>
    <tbody>
    @forelse($exams as $ex)
      <tr class="border-t hover:bg-slate-50">
        <td class="p-3 font-bold">{{ $ex->title }}</td>
        <td class="p-3 text-center">{{ $ex->group->name ?? '—' }}</td>
        <td class="p-3 text-center text-xs">{{ $ex->exam_date }}</td>
        <td class="p-3 text-center">{{ $ex->max_score }}</td>
        <td class="p-3 text-center"><span class="text-xs bg-slate-100 px-2 py-1 rounded-full">{{ $ex->exam_type=='quiz' ? 'اختبار قصير' : ($ex->exam_type=='monthly' ? 'شهري' : 'نهائي') }}</span></td>
        <td class="p-3"><div class="flex gap-1">
          <a href="{{ route('exams.show',$ex->id) }}" class="w-8 h-8 rounded-lg bg-violet-100 flex items-center justify-center"><i class="fa-solid fa-pen-to-square text-xs text-violet-700"></i></a>
          <a href="{{ route('exams.edit',$ex->id) }}" class="w-8 h-8 rounded-lg bg-amber-100 flex items-center justify-center"><i class="fa-solid fa-pen text-xs text-amber-700"></i></a>
          <form method="POST" action="{{ route('exams.destroy',$ex->id) }}" onsubmit="return confirm('حذف الاختبار؟')">@csrf @method('DELETE')<button class="w-8 h-8 rounded-lg bg-red-100 flex items-center justify-center"><i class="fa-solid fa-trash text-xs text-red-600"></i></button></form>
        </div></td>
      </tr>
    @empty
      <tr><td colspan="6" class="p-8 text-center text-slate-400">لا توجد اختبارات</td></tr>
    @endforelse
    </tbody>
  </table></div>
  <div class="p-4">{{ $exams->links() }}</div>
</div>
@endsection
