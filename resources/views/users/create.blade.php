@extends('layouts.app')
@section('title','إضافة مستخدم')
@section('page_title','إضافة مستخدم')
@section('content')
<form method="POST" action="{{ route('users.store') }}" class="bg-white rounded-2xl border p-6 space-y-4 max-w-xl">@csrf
  <div class="grid md:grid-cols-2 gap-4">
    <div><label class="text-sm font-bold">الاسم *</label><input name="name" required value="{{ old('name') }}" class="mt-1 w-full border rounded-xl px-3 py-2.5 text-sm"></div>
    <div><label class="text-sm font-bold">البريد *</label><input name="email" type="email" required value="{{ old('email') }}" class="mt-1 w-full border rounded-xl px-3 py-2.5 text-sm"></div>
    <div><label class="text-sm font-bold">كلمة المرور *</label><input name="password" type="password" required class="mt-1 w-full border rounded-xl px-3 py-2.5 text-sm"></div>
    <div><label class="text-sm font-bold">تأكيد كلمة المرور *</label><input name="password_confirmation" type="password" required class="mt-1 w-full border rounded-xl px-3 py-2.5 text-sm"></div>
    <div><label class="text-sm font-bold">الدور *</label><select name="role" required class="mt-1 w-full border rounded-xl px-3 py-2.5 text-sm"><option value="admin">مدير</option><option value="receptionist">استقبال</option><option value="accountant">محاسب</option><option value="teacher">مدرس</option></select></div>
    <div><label class="text-sm font-bold">الهاتف</label><input name="phone" value="{{ old('phone') }}" class="mt-1 w-full border rounded-xl px-3 py-2.5 text-sm"></div>
  </div>
  <div class="border-t pt-4"><a href="#" onclick="alert(\'احفظ المستخدم أولاً ثم ادخل صلاحياته من زر المفتاح في القائمة\');return false" class="text-xs text-violet-600 font-bold">+ تخصيص صلاحيات دقيقة بعد الحفظ (من زر المفتاح)</a></div>
  <label class="flex items-center gap-2 text-sm font-bold"><input type="checkbox" name="is_active" value="1" checked> نشط</label>
  <div class="flex gap-2"><button class="bg-violet-600 text-white px-6 py-2.5 rounded-xl text-sm font-bold">حفظ</button><a href="{{ route('users.index') }}" class="bg-slate-100 px-6 py-2.5 rounded-xl text-sm font-bold">إلغاء</a></div>
</form>
@endsection
