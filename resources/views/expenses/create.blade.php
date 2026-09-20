@extends('layouts.app')
@section('title','إضافة مصروف')
@section('page_title','إضافة مصروف')
@section('content')
<form method="POST" action="{{ route('expenses.store') }}" enctype="multipart/form-data" class="bg-white rounded-2xl border p-6 space-y-4 max-w-xl">@csrf
  <div class="grid md:grid-cols-2 gap-4">
    <div><label class="text-sm font-bold">النوع *</label><select name="category" required class="mt-1 w-full border rounded-xl px-3 py-2.5 text-sm"><option value="rent">إيجار</option><option value="electricity">كهرباء</option><option value="water">مياه</option><option value="salaries">رواتب</option><option value="supplies">أدوات مكتبية</option><option value="marketing">تسويق</option><option value="maintenance">صيانة</option><option value="other">أخرى</option></select></div>
    <div><label class="text-sm font-bold">القيمة *</label><input name="amount" type="number" step="0.01" min="0" required value="{{ old('amount') }}" class="mt-1 w-full border rounded-xl px-3 py-2.5 text-sm"></div>
    <div><label class="text-sm font-bold">التاريخ *</label><input name="expense_date" type="date" required value="{{ old('expense_date', now()->format('Y-m-d')) }}" class="mt-1 w-full border rounded-xl px-3 py-2.5 text-sm"></div>
    <div><label class="text-sm font-bold">طريقة الدفع</label><select name="payment_method" class="mt-1 w-full border rounded-xl px-3 py-2.5 text-sm"><option value="cash">نقدي</option><option value="transfer">تحويل</option><option value="card">بطاقة</option><option value="other">أخرى</option></select></div>
  </div>
  <div><label class="text-sm font-bold">الوصف</label><textarea name="description" rows="3" class="mt-1 w-full border rounded-xl px-3 py-2.5 text-sm">{{ old('description') }}</textarea></div>
  <div><label class="text-sm font-bold">صورة الإيصال (اختياري)</label><input name="receipt" type="file" accept="image/*" class="mt-1 w-full border rounded-xl px-3 py-2.5 text-sm"></div>
  <div class="flex gap-2"><button class="bg-violet-600 text-white px-6 py-2.5 rounded-xl text-sm font-bold">حفظ</button><a href="{{ route('expenses.index') }}" class="bg-slate-100 px-6 py-2.5 rounded-xl text-sm font-bold">إلغاء</a></div>
</form>
@endsection
