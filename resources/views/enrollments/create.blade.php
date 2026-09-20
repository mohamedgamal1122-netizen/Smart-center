@extends('layouts.app')
@section('title','تسجيل طالب')
@section('page_title','تسجيل طالب في مجموعة')
@section('content')
<form method="POST" action="{{ route('enrollments.store') }}" class="bg-white rounded-2xl border p-6 space-y-4 max-w-xl">@csrf
  <div><label class="text-sm font-bold">الطالب *</label><select name="student_id" required class="mt-1 w-full border rounded-xl px-3 py-2.5 text-sm"><option value="">اختر الطالب</option>@foreach($students as $s)<option value="{{ $s->id }}" @selected(request('student_id')==$s->id || old('student_id')==$s->id)>{{ $s->name }} — {{ $s->code }} ({{ $s->grade }})</option>@endforeach</select></div>
  <div><label class="text-sm font-bold">المجموعة *</label><select name="group_id" required class="mt-1 w-full border rounded-xl px-3 py-2.5 text-sm"><option value="">اختر المجموعة</option>@foreach($groups as $g)<option value="{{ $g->id }}" @selected(request('group_id')==$g->id || old('group_id')==$g->id)>{{ $g->name }} — {{ $g->subject->name }} ({{ $g->enrollments_count ?? $g->enrollments->count() }}/{{ $g->max_students }})</option>@endforeach</select></div>
  <div class="grid md:grid-cols-2 gap-4">
    <div><label class="text-sm font-bold">تاريخ التسجيل</label><input name="enrollment_date" type="date" value="{{ old('enrollment_date', now()->format('Y-m-d')) }}" class="mt-1 w-full border rounded-xl px-3 py-2.5 text-sm"></div>
    <div><label class="text-sm font-bold">الرسوم المتفق عليها</label><input name="agreed_fee" type="number" step="0.01" value="{{ old('agreed_fee') }}" placeholder="اتركه فارغاً لاستخدام رسوم المجموعة" class="mt-1 w-full border rounded-xl px-3 py-2.5 text-sm"></div>
    <div><label class="text-sm font-bold">الخصم</label><input name="discount" type="number" step="0.01" value="{{ old('discount',0) }}" class="mt-1 w-full border rounded-xl px-3 py-2.5 text-sm"></div>
    <div><label class="text-sm font-bold">الحالة</label><select name="status" class="mt-1 w-full border rounded-xl px-3 py-2.5 text-sm"><option value="active">نشط</option><option value="paused">متوقف</option></select></div>
  </div>
  <div><label class="text-sm font-bold">ملاحظات</label><textarea name="notes" rows="2" class="mt-1 w-full border rounded-xl px-3 py-2.5 text-sm">{{ old('notes') }}</textarea></div>
  <div class="flex gap-2"><button class="bg-violet-600 text-white px-6 py-2.5 rounded-xl text-sm font-bold">تسجيل</button><a href="{{ route('enrollments.index') }}" class="bg-slate-100 px-6 py-2.5 rounded-xl text-sm font-bold">إلغاء</a></div>
</form>
@endsection
