@extends('layouts.app')
@section('title','تعديل ولي أمر')
@section('page_title','تعديل ولي أمر')
@section('content')
<form method="POST" action="{{ route('parents.update',['id'=>$parent->id]) }}" class="bg-white rounded-2xl border border-slate-200 p-6 space-y-4 max-w-2xl">@csrf @method('PUT')
  <div class="grid md:grid-cols-2 gap-4">
    <div><label class="text-sm font-bold">الاسم *</label><input name="name" value="{{ old('name',$parent->name) }}" required class="mt-1 w-full border rounded-xl px-3 py-2.5 text-sm"></div>
    <div><label class="text-sm font-bold">صلة القرابة</label><select name="relation" class="mt-1 w-full border rounded-xl px-3 py-2.5 text-sm"><option value="">اختر</option>@foreach(['أب','أم','أخ','أخت','ولي أمر','أخرى'] as $r)<option value="{{ $r }}" @selected($parent->relation==$r)>{{ $r }}</option>@endforeach</select></div>
    <div><label class="text-sm font-bold">الهاتف الأساسي *</label><input name="phone_primary" value="{{ old('phone_primary',$parent->phone_primary) }}" required class="mt-1 w-full border rounded-xl px-3 py-2.5 text-sm"></div>
    <div><label class="text-sm font-bold">هاتف إضافي</label><input name="phone_secondary" value="{{ old('phone_secondary',$parent->phone_secondary) }}" class="mt-1 w-full border rounded-xl px-3 py-2.5 text-sm"></div>
    <div><label class="text-sm font-bold">البريد</label><input name="email" type="email" value="{{ old('email',$parent->email) }}" class="mt-1 w-full border rounded-xl px-3 py-2.5 text-sm"></div>
    <div><label class="text-sm font-bold">العنوان</label><input name="address" value="{{ old('address',$parent->address) }}" class="mt-1 w-full border rounded-xl px-3 py-2.5 text-sm"></div>
  </div>
  <div><label class="text-sm font-bold">ملاحظات</label><textarea name="notes" rows="3" class="mt-1 w-full border rounded-xl px-3 py-2.5 text-sm">{{ old('notes',$parent->notes) }}</textarea></div>
  <div class="flex gap-2"><button class="bg-violet-600 text-white px-6 py-2.5 rounded-xl text-sm font-bold">تحديث</button><a href="{{ route('parents.index') }}" class="bg-slate-100 px-6 py-2.5 rounded-xl text-sm font-bold">إلغاء</a></div>
</form>
@endsection
