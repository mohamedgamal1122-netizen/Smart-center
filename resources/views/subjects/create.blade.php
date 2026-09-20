@extends('layouts.app')
@section('title','إضافة مادة')
@section('page_title','إضافة مادة')
@section('content')
<form method="POST" action="{{ route('subjects.store') }}" class="bg-white rounded-2xl border p-6 space-y-4 max-w-xl">@csrf
  <div><label class="text-sm font-bold">اسم المادة *</label><input name="name" required value="{{ old('name') }}" class="mt-1 w-full border rounded-xl px-3 py-2.5 text-sm"></div>
  <div><label class="text-sm font-bold">المرحلة / الصف</label><input name="stage" value="{{ old('stage') }}" placeholder="مثال: الصف الثالث الإعدادي" class="mt-1 w-full border rounded-xl px-3 py-2.5 text-sm"></div>
  <div><label class="text-sm font-bold">الوصف</label><textarea name="description" rows="3" class="mt-1 w-full border rounded-xl px-3 py-2.5 text-sm">{{ old('description') }}</textarea></div>
  <label class="flex items-center gap-2 text-sm font-bold"><input type="checkbox" name="is_active" value="1" checked> نشطة</label>
  <div class="flex gap-2"><button class="bg-violet-600 text-white px-6 py-2.5 rounded-xl text-sm font-bold">حفظ</button><a href="{{ route('subjects.index') }}" class="bg-slate-100 px-6 py-2.5 rounded-xl text-sm font-bold">إلغاء</a></div>
</form>
@endsection
