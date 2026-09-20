@extends('layouts.app')
@section('title','تعديل طالب')
@section('page_title','تعديل طالب')
@section('content')
<div class="max-w-2xl bg-white rounded-2xl border border-slate-200 p-6">
<form method="POST" action="{{ route('students.update',$student) }}">@csrf @method('PUT')
  <div class="grid md:grid-cols-2 gap-4">
    <div class="md:col-span-2"><label class="text-sm font-bold">الاسم *</label><input name="name" value="{{ old('name',$student->name) }}" required class="mt-1 w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm"></div>
    <div><label class="text-sm font-bold">الهاتف</label><input name="phone" value="{{ old('phone',$student->phone) }}" class="mt-1 w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm"></div>
    <div><label class="text-sm font-bold">الصف</label><input name="grade" value="{{ old('grade',$student->grade) }}" class="mt-1 w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm"></div>
    <div><label class="text-sm font-bold">النوع</label><select name="gender" class="mt-1 w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm"><option value="">—</option><option value="male" @selected($student->gender=='male')>ذكر</option><option value="female" @selected($student->gender=='female')>أنثى</option></select></div>
    <div><label class="text-sm font-bold">الحالة</label><select name="status" class="mt-1 w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm"><option value="active" @selected($student->status=='active')>نشط</option><option value="inactive" @selected($student->status=='inactive')>غير نشط</option><option value="graduated" @selected($student->status=='graduated')>متخرج</option><option value="suspended" @selected($student->status=='suspended')>موقوف</option></select></div>
    <div><label class="text-sm font-bold">المدرسة</label><input name="school" value="{{ old('school',$student->school) }}" class="mt-1 w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm"></div>
    <div class="md:col-span-2"><label class="text-sm font-bold">ملاحظات</label><textarea name="notes" rows="3" class="mt-1 w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm">{{ old('notes',$student->notes) }}</textarea></div>
  </div>
  <button class="mt-6 bg-[#0f172a] text-white px-6 py-3 rounded-xl font-bold w-full md:w-auto">تحديث</button>
</form>
</div>
@endsection
