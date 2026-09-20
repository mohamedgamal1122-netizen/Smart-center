@extends('layouts.app')
@section('title','تعديل مجموعة')
@section('page_title','تعديل مجموعة')
@section('content')
<form method="POST" action="{{ route('groups.update',$group->id) }}" class="bg-white rounded-2xl border p-6 space-y-4 max-w-3xl">@csrf @method('PUT')
  <div class="grid md:grid-cols-2 gap-4">
    <div><label class="text-sm font-bold">اسم المجموعة *</label><input name="name" required value="{{ old('name',$group->name) }}" class="mt-1 w-full border rounded-xl px-3 py-2.5 text-sm"></div>
    <div><label class="text-sm font-bold">المادة *</label><select name="subject_id" required class="mt-1 w-full border rounded-xl px-3 py-2.5 text-sm">@foreach($subjects as $s)<option value="{{ $s->id }}" @selected($group->subject_id==$s->id)>{{ $s->name }}</option>@endforeach</select></div>
    <div><label class="text-sm font-bold">الصف</label><input name="grade" value="{{ old('grade',$group->grade) }}" class="mt-1 w-full border rounded-xl px-3 py-2.5 text-sm"></div>
    <div><label class="text-sm font-bold">المدرس *</label><select name="teacher_id" required class="mt-1 w-full border rounded-xl px-3 py-2.5 text-sm">@foreach($teachers as $t)<option value="{{ $t->id }}" @selected($group->teacher_id==$t->id)>{{ $t->name }}</option>@endforeach</select></div>
    <div><label class="text-sm font-bold">القاعة</label><input name="room" value="{{ old('room',$group->room) }}" class="mt-1 w-full border rounded-xl px-3 py-2.5 text-sm"></div>
    <div><label class="text-sm font-bold">الحد الأقصى</label><input name="max_students" type="number" value="{{ old('max_students',$group->max_students) }}" class="mt-1 w-full border rounded-xl px-3 py-2.5 text-sm"></div>
    <div><label class="text-sm font-bold">الاشتراك الشهري</label><input name="monthly_fee" type="number" value="{{ old('monthly_fee',$group->monthly_fee) }}" class="mt-1 w-full border rounded-xl px-3 py-2.5 text-sm"></div>
    <div><label class="text-sm font-bold">الحالة</label><select name="status" class="mt-1 w-full border rounded-xl px-3 py-2.5 text-sm"><option value="open" @selected($group->status=='open')>مفتوحة</option><option value="full" @selected($group->status=='full')>مكتملة</option><option value="paused" @selected($group->status=='paused')>متوقفة</option></select></div>
    <div><label class="text-sm font-bold">وقت البداية</label><input name="start_time" type="time" value="{{ old('start_time',$group->start_time) }}" class="mt-1 w-full border rounded-xl px-3 py-2.5 text-sm"></div>
    <div><label class="text-sm font-bold">وقت النهاية</label><input name="end_time" type="time" value="{{ old('end_time',$group->end_time) }}" class="mt-1 w-full border rounded-xl px-3 py-2.5 text-sm"></div>
  </div>
  <div><label class="text-sm font-bold">ملاحظات</label><textarea name="notes" rows="2" class="mt-1 w-full border rounded-xl px-3 py-2.5 text-sm">{{ old('notes',$group->notes) }}</textarea></div>
  <div class="flex gap-2"><button class="bg-violet-600 text-white px-6 py-2.5 rounded-xl text-sm font-bold">تحديث</button><a href="{{ route('groups.index') }}" class="bg-slate-100 px-6 py-2.5 rounded-xl text-sm font-bold">إلغاء</a></div>
</form>
@endsection
