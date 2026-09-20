@extends('layouts.app')
@section('title','إضافة اختبار')
@section('page_title','إضافة اختبار')
@section('content')
<form method="POST" action="{{ route('exams.store') }}" class="bg-white rounded-2xl border p-6 space-y-4 max-w-xl">@csrf
  <div><label class="text-sm font-bold">اسم الاختبار *</label><input name="title" required value="{{ old('title') }}" class="mt-1 w-full border rounded-xl px-3 py-2.5 text-sm"></div>
  <div class="grid md:grid-cols-2 gap-4">
    <div><label class="text-sm font-bold">المجموعة *</label><select name="group_id" required class="mt-1 w-full border rounded-xl px-3 py-2.5 text-sm"><option value="">اختر</option>@foreach($groups as $g)<option value="{{ $g->id }}" @selected(old('group_id')==$g->id)>{{ $g->name }}</option>@endforeach</select></div>
    <div><label class="text-sm font-bold">المادة</label><select name="subject_id" class="mt-1 w-full border rounded-xl px-3 py-2.5 text-sm"><option value="">اختر</option>@foreach($subjects as $s)<option value="{{ $s->id }}" @selected(old('subject_id')==$s->id)>{{ $s->name }}</option>@endforeach</select></div>
    <div><label class="text-sm font-bold">التاريخ *</label><input name="exam_date" type="date" required value="{{ old('exam_date', now()->format('Y-m-d')) }}" class="mt-1 w-full border rounded-xl px-3 py-2.5 text-sm"></div>
    <div><label class="text-sm font-bold">الدرجة النهائية *</label><input name="max_score" type="number" min="1" required value="{{ old('max_score', 50) }}" class="mt-1 w-full border rounded-xl px-3 py-2.5 text-sm"></div>
    <div><label class="text-sm font-bold">النوع</label><select name="exam_type" class="mt-1 w-full border rounded-xl px-3 py-2.5 text-sm"><option value="quiz">اختبار قصير</option><option value="monthly">شهري</option><option value="final">نهائي</option></select></div>
  </div>
  <div><label class="text-sm font-bold">ملاحظات</label><textarea name="notes" rows="2" class="mt-1 w-full border rounded-xl px-3 py-2.5 text-sm">{{ old('notes') }}</textarea></div>
  <div class="flex gap-2"><button class="bg-violet-600 text-white px-6 py-2.5 rounded-xl text-sm font-bold">حفظ ومتابعة إدخال الدرجات</button><a href="{{ route('exams.index') }}" class="bg-slate-100 px-6 py-2.5 rounded-xl text-sm font-bold">إلغاء</a></div>
</form>
@endsection
