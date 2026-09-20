@extends('layouts.app')
@section('title','تفاصيل المصروف')
@section('page_title','تفاصيل المصروف')
@section('content')
<div class="bg-white rounded-2xl border p-6 max-w-xl">
  <h2 class="font-extrabold">{{ $expense->category }} — {{ number_format($expense->amount) }} ج.م</h2>
  <p class="text-sm text-slate-500 mt-1">{{ $expense->expense_date }} — {{ $expense->payment_method }}</p>
  <p class="text-sm mt-3 bg-slate-50 rounded-xl p-3">{{ $expense->description ?? 'بدون وصف' }}</p>
  @if($expense->receipt_path)<div class="mt-3"><img src="{{ asset('storage/'.$expense->receipt_path) }}" class="max-h-64 rounded-xl border"></div>@endif
  <div class="mt-4 flex gap-2"><a href="{{ route('expenses.edit',$expense->id) }}" class="bg-amber-500 text-white px-5 py-2 rounded-xl text-sm font-bold">تعديل</a><a href="{{ route('expenses.index') }}" class="bg-slate-100 px-5 py-2 rounded-xl text-sm font-bold">رجوع</a></div>
</div>
@endsection
