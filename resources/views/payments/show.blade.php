@extends('layouts.app')
@section('title','تفاصيل الدفعة')
@section('page_title','تفاصيل الدفعة')
@section('content')
<div class="bg-white rounded-2xl border p-6 max-w-2xl">
  <div class="flex justify-between items-start">
    <div><h2 class="font-extrabold">{{ $payment->student->name }}</h2><p class="text-sm text-slate-500">{{ $payment->group->name }} — {{ $payment->month }}/{{ $payment->year }}</p><p class="text-xs text-slate-400 mt-1">إيصال: {{ $payment->receipt_number }}</p></div>
    <span class="text-xs px-3 py-1.5 rounded-full font-bold @if($payment->is_cancelled) bg-slate-200 @elseif($payment->remaining<=0.01) bg-emerald-100 text-emerald-700 @elseif($payment->paid_amount>0) bg-amber-100 text-amber-700 @else bg-red-100 text-red-600 @endif">{{ $payment->is_cancelled ? 'ملغى' : ($payment->remaining<=0.01 ? 'مدفوع' : ($payment->paid_amount>0 ? 'جزئي' : 'لم يدفع')) }}</span>
  </div>
  <div class="mt-4 grid grid-cols-4 gap-3 text-center text-sm">
    <div class="bg-slate-50 rounded-xl p-3"><div class="text-xs text-slate-500">المطلوب</div><div class="font-extrabold">{{ number_format($payment->required_amount) }}</div></div>
    <div class="bg-amber-50 rounded-xl p-3"><div class="text-xs text-amber-600">الخصم</div><div class="font-extrabold text-amber-700">{{ number_format($payment->discount) }}</div></div>
    <div class="bg-emerald-50 rounded-xl p-3"><div class="text-xs text-emerald-600">المدفوع</div><div class="font-extrabold text-emerald-700">{{ number_format($payment->paid_amount) }}</div></div>
    <div class="bg-red-50 rounded-xl p-3"><div class="text-xs text-red-500">المتبقي</div><div class="font-extrabold text-red-600">{{ number_format(max(0,$payment->remaining)) }}</div></div>
  </div>
  <div class="mt-6 flex gap-2"><a href="{{ route('payments.receiptHtml',$payment->id) }}" target="_blank" class="bg-violet-600 text-white px-5 py-2.5 rounded-xl text-sm font-bold">طباعة الإيصال</a><a href="{{ route('payments.edit',$payment->id) }}" class="bg-amber-500 text-white px-5 py-2.5 rounded-xl text-sm font-bold">تعديل</a><a href="{{ route('payments.index') }}" class="bg-slate-100 px-5 py-2.5 rounded-xl text-sm font-bold">رجوع</a></div>
</div>
@endsection
