@extends('layouts.app')
@section('title','إضافة طالب')
@section('page_title','إضافة طالب')
@section('content')
<div class="max-w-2xl bg-white rounded-2xl border border-slate-200 p-6">
<form method="POST" action="{{ route('students.store') }}">@csrf
  <div class="grid md:grid-cols-2 gap-4">
    <div class="md:col-span-2"><label class="text-sm font-bold">الاسم *</label><input name="name" value="{{ old('name') }}" required class="mt-1 w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm outline-none focus:ring-2 focus:ring-violet-500"></div>
    <div><label class="text-sm font-bold">الهاتف</label><input name="phone" value="{{ old('phone') }}" class="mt-1 w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm"></div>
    <div><label class="text-sm font-bold">الصف / السنة</label><input name="grade" value="{{ old('grade') }}" class="mt-1 w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm"></div>
    <div><label class="text-sm font-bold">النوع</label><select name="gender" class="mt-1 w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm"><option value="">—</option><option value="male">ذكر</option><option value="female">أنثى</option></select></div>
    <div><label class="text-sm font-bold">تاريخ الميلاد</label><input type="date" name="birth_date" value="{{ old('birth_date') }}" class="mt-1 w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm"></div>
    <div><label class="text-sm font-bold">المدرسة</label><input name="school" value="{{ old('school') }}" class="mt-1 w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm"></div>
    <div><label class="text-sm font-bold">تاريخ التسجيل</label><input type="date" name="enrollment_date" value="{{ old('enrollment_date', date('Y-m-d')) }}" class="mt-1 w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm"></div>
    <div class="md:col-span-2"><label class="text-sm font-bold">ملاحظات</label><textarea name="notes" rows="3" class="mt-1 w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm">{{ old('notes') }}</textarea></div>
  </div>
  <button class="mt-6 bg-[#0f172a] text-white px-6 py-3 rounded-xl font-bold w-full md:w-auto">حفظ</button>
  <a href="{{ route('students.index') }}" class="inline-block mt-3 md:mr-2 text-sm text-slate-500">إلغاء</a>
</form>
</div>
@endsection
