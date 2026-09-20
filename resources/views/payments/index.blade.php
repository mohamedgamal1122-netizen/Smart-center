@extends('layouts.app')
@section('title','المدفوعات')
@section('page_title','المدفوعات الشهرية')
@section('content')
<div class="flex flex-wrap gap-3 justify-between items-center mb-5">
  <form method="GET" class="flex gap-2 flex-wrap">
    <input name="q" value="{{ request('q') }}" placeholder="بحث باسم الطالب..." class="border rounded-xl px-3 py-2.5 w-48 text-sm">
    <select name="month" class="border rounded-xl px-3 py-2.5 text-sm"><option value="">كل الشهور</option>@for($m=1;$m<=12;$m++)<option value="{{ $m }}" @selected(request('month')==$m)>{{ $m }}</option>@endfor</select>
    <select name="year" class="border rounded-xl px-3 py-2.5 text-sm"><option value="">كل السنوات</option>@for($y=2024;$y<=2027;$y++)<option value="{{ $y }}" @selected(request('year')==$y)>{{ $y }}</option>@endfor</select>
    <select name="status" class="border rounded-xl px-3 py-2.5 text-sm"><option value="">كل الحالات</option><option value="paid" @selected(request('status')=='paid')>مدفوع</option><option value="partial" @selected(request('status')=='partial')>جزئي</option><option value="unpaid" @selected(request('status')=='unpaid')>لم يدفع</option></select>
    <button class="bg-slate-900 text-white px-5 py-2.5 rounded-xl text-sm font-bold">فلترة</button>
  </form>
  <div class="flex gap-2">
    <a href="{{ request()->fullUrlWithQuery(['export'=>'excel']) }}" class="bg-emerald-600 text-white px-5 py-2.5 rounded-xl text-sm font-bold whitespace-nowrap"><i class="fa-solid fa-file-excel ml-1"></i> Excel</a>
    <a href="{{ route('payments.create') }}" class="bg-violet-600 text-white px-5 py-2.5 rounded-xl text-sm font-bold"><i class="fa-solid fa-plus ml-1"></i> تسجيل دفعة</a>
  </div>
</div>
<div class="bg-white rounded-2xl border overflow-hidden">
  <div class="overflow-x-auto"><table class="w-full text-sm">
    <thead class="bg-slate-50 text-slate-500 text-xs"><tr><th class="p-3 text-right">الطالب</th><th class="p-3">المجموعة</th><th class="p-3">الشهر</th><th class="p-3">المطلوب</th><th class="p-3">المدفوع</th><th class="p-3">المتبقي</th><th class="p-3">الحالة</th><th class="p-3">إجراءات</th></tr></thead>
    <tbody>
    @forelse($payments as $p)
      @php $rem = (float)($p->remaining ?? ($p->required_amount - $p->discount - $p->paid_amount)); $isPaid = $rem <= 0.01; $isPartial = !$isPaid && $p->paid_amount > 0; @endphp
      <tr class="border-t hover:bg-slate-50 @if($p->is_cancelled) opacity-50 @endif">
        <td class="p-3 font-bold">{{ $p->student->name ?? '—' }} <span class="text-xs text-slate-400">{{ $p->student->code ?? '' }}</span></td>
        <td class="p-3 text-center text-xs">{{ $p->group->name ?? '—' }}</td>
        <td class="p-3 text-center text-xs">{{ $p->month }}/{{ $p->year }}</td>
        <td class="p-3 text-center text-xs">{{ number_format($p->required_amount - $p->discount) }}</td>
        <td class="p-3 text-center text-xs font-bold text-emerald-700">{{ number_format($p->paid_amount) }}</td>
        <td class="p-3 text-center text-xs font-bold @if($rem>0) text-red-600 @else text-emerald-600 @endif">{{ number_format(max(0,$rem)) }}</td>
        <td class="p-3 text-center">
          @if($p->is_cancelled) <span class="text-xs bg-slate-200 px-2 py-1 rounded-full">ملغى</span>
          @elseif($isPaid) <span class="text-xs bg-emerald-100 text-emerald-700 px-2 py-1 rounded-full font-bold">مدفوع</span>
          @elseif($isPartial) <span class="text-xs bg-amber-100 text-amber-700 px-2 py-1 rounded-full font-bold">جزئي</span>
          @else <span class="text-xs bg-red-100 text-red-600 px-2 py-1 rounded-full font-bold">لم يدفع</span>
          @endif
        </td>
        <td class="p-3"><div class="flex gap-1 flex-wrap">
          <a href="{{ route('payments.show',$p->id) }}" class="w-7 h-7 rounded-lg bg-slate-100 flex items-center justify-center"><i class="fa-solid fa-eye text-xs"></i></a>
          <a href="{{ route('payments.receiptHtml',$p->id) }}" target="_blank" class="text-xs bg-violet-600 text-white px-2 py-1 rounded-full font-bold">إيصال</a>
          @if(!$p->is_cancelled)<form method="POST" action="{{ route('payments.cancel',$p->id) }}" onsubmit="return confirm('إلغاء الدفعة؟')">@csrf<button class="text-xs bg-red-50 text-red-600 px-2 py-1 rounded-full font-bold">إلغاء</button></form>@endif
        </div></td>
      </tr>
    @empty
      <tr><td colspan="8" class="p-8 text-center text-slate-400">لا توجد مدفوعات</td></tr>
    @endforelse
    </tbody>
  </table></div>
  <div class="p-4">{{ $payments->links() }}</div>
</div>
@endsection
