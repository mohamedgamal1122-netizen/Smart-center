@extends('layouts.app')
@section('title','تعديل مصروف')
@section('page_title','تعديل مصروف')
@section('content')
<form method="POST" action="{{ route('expenses.update',$expense->id) }}" enctype="multipart/form-data" class="bg-white rounded-2xl border p-6 space-y-4 max-w-xl">@csrf @method('PUT')
  <div class="grid md:grid-cols-2 gap-4">
    <div><label class="text-sm font-bold">النوع *</label><select name="category" required class="mt-1 w-full border rounded-xl px-3 py-2.5 text-sm"><option value="rent" @selected($expense->category=='rent')>إيجار</option><option value="electricity" @selected($expense->category=='electricity')>كهرباء</option><option value="water" @selected($expense->category=='water')>مياه</option><option value="salaries" @selected($expense->category=='salaries')>رواتب</option><option value="supplies" @selected($expense->category=='supplies')>أدوات</option><option value="marketing" @selected($expense->category=='marketing')>تسويق</option><option value="maintenance" @selected($expense->category=='maintenance')>صيانة</option><option value="other" @selected($expense->category=='other')>أخرى</option></select></div>
    <div><label class="text-sm font-bold">القيمة *</label><input name="amount" type="number" step="0.01" required value="{{ old('amount',$expense->amount) }}" class="mt-1 w-full border rounded-xl px-3 py-2.5 text-sm"></div>
    <div><label class="text-sm font-bold">التاريخ *</label><input name="expense_date" type="date" required value="{{ old('expense_date',$expense->expense_date) }}" class="mt-1 w-full border rounded-xl px-3 py-2.5 text-sm"></div>
    <div><label class="text-sm font-bold">طريقة الدفع</label><select name="payment_method" class="mt-1 w-full border rounded-xl px-3 py-2.5 text-sm"><option value="cash" @selected($expense->payment_method=='cash')>نقدي</option><option value="transfer" @selected($expense->payment_method=='transfer')>تحويل</option><option value="card" @selected($expense->payment_method=='card')>بطاقة</option><option value="other" @selected($expense->payment_method=='other')>أخرى</option></select></div>
  </div>
  <div><label class="text-sm font-bold">الوصف</label><textarea name="description" rows="3" class="mt-1 w-full border rounded-xl px-3 py-2.5 text-sm">{{ old('description',$expense->description) }}</textarea></div>
  <div><label class="text-sm font-bold">صورة الإيصال</label><input name="receipt" type="file" accept="image/*" class="mt-1 w-full border rounded-xl px-3 py-2.5 text-sm"></div>
  <div class="flex gap-2"><button class="bg-violet-600 text-white px-6 py-2.5 rounded-xl text-sm font-bold">تحديث</button><a href="{{ route('expenses.index') }}" class="bg-slate-100 px-6 py-2.5 rounded-xl text-sm font-bold">إلغاء</a></div>
</form>
@endsection
