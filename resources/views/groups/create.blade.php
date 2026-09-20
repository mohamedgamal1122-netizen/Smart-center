@extends('layouts.app')
@section('title','إضافة مجموعة')
@section('page_title','إضافة مجموعة')
@section('content')
<form method="POST" action="{{ route('groups.store') }}" class="bg-white rounded-2xl border p-6 space-y-4 max-w-3xl">@csrf
  <div class="grid md:grid-cols-2 gap-4">
    <div><label class="text-sm font-bold">اسم المجموعة *</label><input name="name" required value="{{ old('name') }}" class="mt-1 w-full border rounded-xl px-3 py-2.5 text-sm"></div>
    <div><label class="text-sm font-bold">المادة *</label><select name="subject_id" required class="mt-1 w-full border rounded-xl px-3 py-2.5 text-sm"><option value="">اختر</option>@foreach($subjects as $s)<option value="{{ $s->id }}" @selected(old('subject_id')==$s->id)>{{ $s->name }}</option>@endforeach</select></div>
    <div><label class="text-sm font-bold">الصف *</label><input name="grade" required value="{{ old('grade') }}" placeholder="مثال: الصف الثالث الإعدادي" class="mt-1 w-full border rounded-xl px-3 py-2.5 text-sm"></div>
    <div><label class="text-sm font-bold">المدرس *</label><select name="teacher_id" required class="mt-1 w-full border rounded-xl px-3 py-2.5 text-sm"><option value="">اختر</option>@foreach($teachers as $t)<option value="{{ $t->id }}" @selected(old('teacher_id')==$t->id)>{{ $t->name }}</option>@endforeach</select></div>
    <div><label class="text-sm font-bold">القاعة</label><input name="room" value="{{ old('room') }}" class="mt-1 w-full border rounded-xl px-3 py-2.5 text-sm"></div>
    <div><label class="text-sm font-bold">الحد الأقصى للطلاب *</label><input name="max_students" type="number" min="1" required value="{{ old('max_students',20) }}" class="mt-1 w-full border rounded-xl px-3 py-2.5 text-sm"></div>
    <div><label class="text-sm font-bold">قيمة الاشتراك الشهري *</label><input name="monthly_fee" type="number" min="0" required value="{{ old('monthly_fee') }}" class="mt-1 w-full border rounded-xl px-3 py-2.5 text-sm"></div>
    <div><label class="text-sm font-bold">الحالة</label><select name="status" class="mt-1 w-full border rounded-xl px-3 py-2.5 text-sm"><option value="open">مفتوحة</option><option value="full">مكتملة</option><option value="paused">متوقفة</option></select></div>
    <div><label class="text-sm font-bold">وقت البداية</label><input name="start_time" type="time" value="{{ old('start_time') }}" class="mt-1 w-full border rounded-xl px-3 py-2.5 text-sm"></div>
    <div><label class="text-sm font-bold">وقت النهاية</label><input name="end_time" type="time" value="{{ old('end_time') }}" class="mt-1 w-full border rounded-xl px-3 py-2.5 text-sm"></div>
    <div><label class="text-sm font-bold">تاريخ البداية</label><input name="start_date" type="date" value="{{ old('start_date') }}" class="mt-1 w-full border rounded-xl px-3 py-2.5 text-sm"></div>
    <div><label class="text-sm font-bold">الأيام</label><input name="days" value="{{ old('days') }}" placeholder="السبت، الثلاثاء، الخميس" class="mt-1 w-full border rounded-xl px-3 py-2.5 text-sm"></div>
  </div>
  <div><label class="text-sm font-bold">ملاحظات</label><textarea name="notes" rows="2" class="mt-1 w-full border rounded-xl px-3 py-2.5 text-sm">{{ old('notes') }}</textarea></div>
  <div class="flex gap-2"><button class="bg-violet-600 text-white px-6 py-2.5 rounded-xl text-sm font-bold">حفظ</button><a href="{{ route('groups.index') }}" class="bg-slate-100 px-6 py-2.5 rounded-xl text-sm font-bold">إلغاء</a></div>
</form>
@endsection
