@extends('layouts.app')
@section('title','تعديل اختبار')
@section('page_title','تعديل اختبار')
@section('content')
<form method="POST" action="{{ route('exams.update',$exam->id) }}" class="bg-white rounded-2xl border p-6 space-y-4 max-w-xl">@csrf @method('PUT')
  <div><label class="text-sm font-bold">اسم الاختبار *</label><input name="title" required value="{{ old('title',$exam->title) }}" class="mt-1 w-full border rounded-xl px-3 py-2.5 text-sm"></div>
  <div class="grid md:grid-cols-2 gap-4">
    <div><label class="text-sm font-bold">المجموعة *</label><select name="group_id" required class="mt-1 w-full border rounded-xl px-3 py-2.5 text-sm">@foreach($groups as $g)<option value="{{ $g->id }}" @selected($exam->group_id==$g->id)>{{ $g->name }}</option>@endforeach</select></div>
    <div><label class="text-sm font-bold">الدرجة النهائية *</label><input name="max_score" type="number" required value="{{ old('max_score',$exam->max_score) }}" class="mt-1 w-full border rounded-xl px-3 py-2.5 text-sm"></div>
    <div><label class="text-sm font-bold">التاريخ</label><input name="exam_date" type="date" value="{{ old('exam_date',$exam->exam_date) }}" class="mt-1 w-full border rounded-xl px-3 py-2.5 text-sm"></div>
    <div><label class="text-sm font-bold">النوع</label><select name="exam_type" class="mt-1 w-full border rounded-xl px-3 py-2.5 text-sm"><option value="quiz" @selected($exam->exam_type=='quiz')>قصير</option><option value="monthly" @selected($exam->exam_type=='monthly')>شهري</option><option value="final" @selected($exam->exam_type=='final')>نهائي</option></select></div>
  </div>
  <div class="flex gap-2"><button class="bg-violet-600 text-white px-6 py-2.5 rounded-xl text-sm font-bold">تحديث</button><a href="{{ route('exams.index') }}" class="bg-slate-100 px-6 py-2.5 rounded-xl text-sm font-bold">إلغاء</a></div>
</form>
@endsection
