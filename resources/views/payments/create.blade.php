@extends('layouts.app')
@section('title','تسجيل دفعة')
@section('page_title','تسجيل دفعة')
@section('content')
<form method="POST" action="{{ route('payments.store') }}" class="bg-white rounded-2xl border p-6 space-y-4 max-w-2xl">@csrf
  <div class="grid md:grid-cols-2 gap-4">
    <div><label class="text-sm font-bold">الطالب *</label><select name="student_id" required class="mt-1 w-full border rounded-xl px-3 py-2.5 text-sm"><option value="">اختر</option>@foreach($students as $s)<option value="{{ $s->id }}" @selected(old('student_id')==$s->id)>{{ $s->name }} — {{ $s->code }}</option>@endforeach</select></div>
    <div><label class="text-sm font-bold">المجموعة *</label><select name="group_id" required class="mt-1 w-full border rounded-xl px-3 py-2.5 text-sm"><option value="">اختر</option>@foreach($groups as $g)<option value="{{ $g->id }}" @selected(old('group_id')==$g->id)>{{ $g->name }} ({{ number_format($g->monthly_fee) }} ج.م)</option>@endforeach</select></div>
    <div><label class="text-sm font-bold">الشهر *</label><select name="month" required class="mt-1 w-full border rounded-xl px-3 py-2.5 text-sm">@for($m=1;$m<=12;$m++)<option value="{{ $m }}" @selected(old('month',now()->month)==$m)>{{ $m }}</option>@endfor</select></div>
    <div><label class="text-sm font-bold">السنة *</label><select name="year" required class="mt-1 w-full border rounded-xl px-3 py-2.5 text-sm">@for($y=2024;$y<=2027;$y++)<option value="{{ $y }}" @selected(old('year',now()->year)==$y)>{{ $y }}</option>@endfor</select></div>
    <div><label class="text-sm font-bold">المبلغ المطلوب *</label><input name="required_amount" type="number" step="0.01" min="0" required value="{{ old('required_amount') }}" class="mt-1 w-full border rounded-xl px-3 py-2.5 text-sm"></div>
    <div><label class="text-sm font-bold">الخصم</label><input name="discount" type="number" step="0.01" min="0" value="{{ old('discount',0) }}" class="mt-1 w-full border rounded-xl px-3 py-2.5 text-sm"></div>
    <div><label class="text-sm font-bold">المبلغ المدفوع *</label><input name="paid_amount" type="number" step="0.01" min="0" required value="{{ old('paid_amount') }}" class="mt-1 w-full border rounded-xl px-3 py-2.5 text-sm"></div>
    <div><label class="text-sm font-bold">طريقة الدفع</label><select name="payment_method" class="mt-1 w-full border rounded-xl px-3 py-2.5 text-sm"><option value="cash">نقدي</option><option value="transfer">تحويل</option><option value="card">بطاقة</option><option value="other">أخرى</option></select></div>
    <div><label class="text-sm font-bold">تاريخ الدفع</label><input name="payment_date" type="date" value="{{ old('payment_date', now()->format('Y-m-d')) }}" class="mt-1 w-full border rounded-xl px-3 py-2.5 text-sm"></div>
  </div>
  <div><label class="text-sm font-bold">ملاحظات</label><textarea name="notes" rows="2" class="mt-1 w-full border rounded-xl px-3 py-2.5 text-sm">{{ old('notes') }}</textarea></div>
  <div class="flex gap-2"><button class="bg-violet-600 text-white px-6 py-2.5 rounded-xl text-sm font-bold">حفظ</button><a href="{{ route('payments.index') }}" class="bg-slate-100 px-6 py-2.5 rounded-xl text-sm font-bold">إلغاء</a></div>
</form>
@endsection
