@extends('layouts.app')
@section('title','إضافة مدرس')
@section('page_title','إضافة مدرس')
@section('content')
<form method="POST" action="{{ route('teachers.store') }}" class="bg-white rounded-2xl border p-6 space-y-4 max-w-2xl">@csrf
  <div class="grid md:grid-cols-2 gap-4">
    <div><label class="text-sm font-bold">الاسم *</label><input name="name" required value="{{ old('name') }}" class="mt-1 w-full border rounded-xl px-3 py-2.5 text-sm"></div>
    <div><label class="text-sm font-bold">التخصص</label><input name="specialization" value="{{ old('specialization') }}" class="mt-1 w-full border rounded-xl px-3 py-2.5 text-sm"></div>
    <div><label class="text-sm font-bold">الهاتف</label><input name="phone" value="{{ old('phone') }}" class="mt-1 w-full border rounded-xl px-3 py-2.5 text-sm dir-ltr text-right"></div>
    <div><label class="text-sm font-bold">البريد</label><input name="email" type="email" value="{{ old('email') }}" class="mt-1 w-full border rounded-xl px-3 py-2.5 text-sm"></div>
    <div><label class="text-sm font-bold">نوع الأجر</label><select name="rate_type" class="mt-1 w-full border rounded-xl px-3 py-2.5 text-sm"><option value="per_session">بالحصة</option><option value="percentage">نسبة</option></select></div>
    <div><label class="text-sm font-bold">القيمة</label><input name="rate_value" type="number" step="0.01" value="{{ old('rate_value') }}" class="mt-1 w-full border rounded-xl px-3 py-2.5 text-sm"></div>
    <div><label class="text-sm font-bold">تاريخ بداية العمل</label><input name="start_date" type="date" value="{{ old('start_date') }}" class="mt-1 w-full border rounded-xl px-3 py-2.5 text-sm"></div>
    <div class="flex items-end"><label class="flex items-center gap-2 text-sm font-bold"><input type="checkbox" name="is_active" value="1" checked> نشط</label></div>
  </div>
  <div><label class="text-sm font-bold">ملاحظات</label><textarea name="notes" rows="3" class="mt-1 w-full border rounded-xl px-3 py-2.5 text-sm">{{ old('notes') }}</textarea></div>
  <div class="flex gap-2"><button class="bg-violet-600 text-white px-6 py-2.5 rounded-xl text-sm font-bold">حفظ</button><a href="{{ route('teachers.index') }}" class="bg-slate-100 px-6 py-2.5 rounded-xl text-sm font-bold">إلغاء</a></div>
</form>
@endsection
