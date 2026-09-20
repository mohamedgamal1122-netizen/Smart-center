@extends('layouts.app')
@section('title','تعديل مستخدم')
@section('page_title','تعديل مستخدم')
@section('content')
<form method="POST" action="{{ route('users.update',$user->id) }}" class="bg-white rounded-2xl border p-6 space-y-4 max-w-xl">@csrf @method('PUT')
  <div class="grid md:grid-cols-2 gap-4">
    <div><label class="text-sm font-bold">الاسم *</label><input name="name" required value="{{ old('name',$user->name) }}" class="mt-1 w-full border rounded-xl px-3 py-2.5 text-sm"></div>
    <div><label class="text-sm font-bold">البريد *</label><input name="email" type="email" required value="{{ old('email',$user->email) }}" class="mt-1 w-full border rounded-xl px-3 py-2.5 text-sm"></div>
    <div><label class="text-sm font-bold">كلمة المرور (اتركها فارغة لعدم التغيير)</label><input name="password" type="password" class="mt-1 w-full border rounded-xl px-3 py-2.5 text-sm"></div>
    <div><label class="text-sm font-bold">تأكيد كلمة المرور</label><input name="password_confirmation" type="password" class="mt-1 w-full border rounded-xl px-3 py-2.5 text-sm"></div>
    <div><label class="text-sm font-bold">الدور *</label><select name="role" required class="mt-1 w-full border rounded-xl px-3 py-2.5 text-sm"><option value="admin" @selected($user->role=='admin')>مدير</option><option value="receptionist" @selected($user->role=='receptionist')>استقبال</option><option value="accountant" @selected($user->role=='accountant')>محاسب</option><option value="teacher" @selected($user->role=='teacher')>مدرس</option></select></div>
    <div><label class="text-sm font-bold">الهاتف</label><input name="phone" value="{{ old('phone',$user->phone) }}" class="mt-1 w-full border rounded-xl px-3 py-2.5 text-sm"></div>
  </div>
  <div class="border-t pt-4"><a href="#" onclick="alert(\'احفظ المستخدم أولاً ثم ادخل صلاحياته من زر المفتاح في القائمة\');return false" class="text-xs text-violet-600 font-bold">+ تخصيص صلاحيات دقيقة بعد الحفظ (من زر المفتاح)</a></div>
  <label class="flex items-center gap-2 text-sm font-bold"><input type="checkbox" name="is_active" value="1" @checked($user->is_active)> نشط</label>
  <div class="flex gap-2"><button class="bg-violet-600 text-white px-6 py-2.5 rounded-xl text-sm font-bold">تحديث</button><a href="{{ route('users.index') }}" class="bg-slate-100 px-6 py-2.5 rounded-xl text-sm font-bold">إلغاء</a></div>
</form>
@endsection
