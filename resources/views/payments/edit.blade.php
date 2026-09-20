@extends('layouts.app')
@section('title','تعديل دفعة')
@section('page_title','تعديل دفعة')
@section('content')
<form method="POST" action="{{ route('payments.update',$payment->id) }}" class="bg-white rounded-2xl border p-6 space-y-4 max-w-2xl">@csrf @method('PUT')
  <div class="grid md:grid-cols-2 gap-4">
    <div><label class="text-sm font-bold">الطالب *</label><select name="student_id" required class="mt-1 w-full border rounded-xl px-3 py-2.5 text-sm">@foreach($students as $s)<option value="{{ $s->id }}" @selected($payment->student_id==$s->id)>{{ $s->name }}</option>@endforeach</select></div>
    <div><label class="text-sm font-bold">المجموعة *</label><select name="group_id" required class="mt-1 w-full border rounded-xl px-3 py-2.5 text-sm">@foreach($groups as $g)<option value="{{ $g->id }}" @selected($payment->group_id==$g->id)>{{ $g->name }}</option>@endforeach</select></div>
    <div><label class="text-sm font-bold">الشهر *</label><select name="month" required class="mt-1 w-full border rounded-xl px-3 py-2.5 text-sm">@for($m=1;$m<=12;$m++)<option value="{{ $m }}" @selected($payment->month==$m)>{{ $m }}</option>@endfor</select></div>
    <div><label class="text-sm font-bold">السنة *</label><input name="year" type="number" required value="{{ old('year',$payment->year) }}" class="mt-1 w-full border rounded-xl px-3 py-2.5 text-sm"></div>
    <div><label class="text-sm font-bold">المطلوب *</label><input name="required_amount" type="number" step="0.01" required value="{{ old('required_amount',$payment->required_amount) }}" class="mt-1 w-full border rounded-xl px-3 py-2.5 text-sm"></div>
    <div><label class="text-sm font-bold">الخصم</label><input name="discount" type="number" step="0.01" value="{{ old('discount',$payment->discount) }}" class="mt-1 w-full border rounded-xl px-3 py-2.5 text-sm"></div>
    <div><label class="text-sm font-bold">المدفوع *</label><input name="paid_amount" type="number" step="0.01" required value="{{ old('paid_amount',$payment->paid_amount) }}" class="mt-1 w-full border rounded-xl px-3 py-2.5 text-sm"></div>
    <div><label class="text-sm font-bold">طريقة الدفع</label><select name="payment_method" class="mt-1 w-full border rounded-xl px-3 py-2.5 text-sm"><option value="cash" @selected($payment->payment_method=='cash')>نقدي</option><option value="transfer" @selected($payment->payment_method=='transfer')>تحويل</option><option value="card" @selected($payment->payment_method=='card')>بطاقة</option><option value="other" @selected($payment->payment_method=='other')>أخرى</option></select></div>
  </div>
  <div class="flex gap-2"><button class="bg-violet-600 text-white px-6 py-2.5 rounded-xl text-sm font-bold">تحديث</button><a href="{{ route('payments.index') }}" class="bg-slate-100 px-6 py-2.5 rounded-xl text-sm font-bold">إلغاء</a></div>
</form>
@endsection
