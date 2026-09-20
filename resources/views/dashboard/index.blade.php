@extends('layouts.app')
@section('title','لوحة التحكم')
@section('page_title','لوحة التحكم')
@section('content')
<div class="grid grid-cols-2 lg:grid-cols-4 gap-3 lg:gap-4">
  <div class="bg-white rounded-2xl p-4 border border-slate-200"><div class="text-xs text-slate-500">طلاب نشطون</div><div class="text-2xl font-extrabold mt-1">{{ number_format($activeStudents) }}</div><div class="text-[11px] text-emerald-600 mt-1"><i class="fa-solid fa-user-graduate"></i> إجمالي النشطين</div></div>
  <div class="bg-white rounded-2xl p-4 border border-slate-200"><div class="text-xs text-slate-500">المجموعات</div><div class="text-2xl font-extrabold mt-1">{{ $totalGroups }}</div><div class="text-[11px] text-violet-600 mt-1"><i class="fa-solid fa-users"></i> عدد المجموعات</div></div>
  <div class="bg-white rounded-2xl p-4 border border-slate-200"><div class="text-xs text-slate-500">المدرسون</div><div class="text-2xl font-extrabold mt-1">{{ $totalTeachers }}</div><div class="text-[11px] text-amber-600 mt-1"><i class="fa-solid fa-person-chalkboard"></i> مدرس</div></div>
  <div class="bg-white rounded-2xl p-4 border border-slate-200"><div class="text-xs text-slate-500">تسجيلات نشطة</div><div class="text-2xl font-extrabold mt-1">{{ $totalEnrollments }}</div><div class="text-[11px] text-sky-600 mt-1">تسجيل حالي</div></div>

  <div class="bg-gradient-to-br from-emerald-500 to-teal-600 rounded-2xl p-4 text-white"><div class="text-xs text-white/80">إيرادات الشهر</div><div class="text-2xl font-extrabold mt-1">{{ number_format($revenueMonth,0) }} <span class="text-sm font-semibold">ج.م</span></div><div class="text-[11px] text-white/70 mt-1">{{ $month }}/{{ $year }}</div></div>
  <div class="bg-gradient-to-br from-rose-500 to-pink-600 rounded-2xl p-4 text-white"><div class="text-xs text-white/80">متأخرات الشهر</div><div class="text-2xl font-extrabold mt-1">{{ number_format($dueAmount,0) }} <span class="text-sm font-semibold">ج.م</span></div><div class="text-[11px] text-white/70 mt-1">مبالغ غير محصلة</div></div>
  <div class="bg-white rounded-2xl p-4 border border-slate-200"><div class="text-xs text-slate-500">مصروفات الشهر</div><div class="text-2xl font-extrabold mt-1">{{ number_format($expensesMonth,0) }} ج.م</div><div class="text-[11px] {{ $netProfit>=0?'text-emerald-600':'text-rose-600' }} mt-1">صافي: {{ number_format($netProfit,0) }} ج.م</div></div>
  <div class="bg-white rounded-2xl p-4 border border-slate-200"><div class="text-xs text-slate-500">طلاب جدد / غياب اليوم</div><div class="text-2xl font-extrabold mt-1">{{ $newStudents }} <span class="text-sm text-slate-400">/ {{ $absentToday }} غائب</span></div><div class="text-[11px] text-slate-400 mt-1">{{ $sessionsToday }} حصة اليوم</div></div>
</div>

<div class="grid lg:grid-cols-3 gap-4 mt-4">
  <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200 p-4">
    <h3 class="font-extrabold mb-3">الإيرادات والمصروفات (آخر 6 أشهر)</h3>
    <div class="space-y-2">
      @foreach($monthlyRevenue as $m)
      <div class="flex items-center gap-2 text-sm">
        <span class="w-20 text-xs text-slate-500">{{ $m['label'] }}</span>
        <div class="flex-1 h-3 bg-slate-100 rounded-full overflow-hidden flex">
          <div class="bg-emerald-500 h-full" style="width: {{ $m['revenue']>0? min(100, $m['revenue']/max(1,collect($monthlyRevenue)->max('revenue'))*100):0 }}%"></div>
        </div>
        <span class="text-xs font-bold w-24 text-left">{{ number_format($m['revenue'],0) }} / {{ number_format($m['expenses'],0) }}</span>
      </div>
      @endforeach
    </div>
  </div>
  <div class="bg-white rounded-2xl border border-slate-200 p-4">
    <h3 class="font-extrabold mb-3">متأخرات هذا الشهر</h3>
    @forelse($unpaidStudents as $p)
      <div class="flex items-center justify-between py-2 border-b border-slate-100 last:border-0 text-sm">
        <div><div class="font-bold">{{ $p->student->name ?? '—' }}</div><div class="text-xs text-slate-500">{{ $p->group->name ?? '' }}</div></div>
        <span class="bg-rose-50 text-rose-700 px-2.5 py-1 rounded-full text-xs font-bold">{{ number_format($p->remaining,0) }} ج.م</span>
      </div>
    @empty <p class="text-sm text-slate-400 text-center py-6">لا يوجد متأخرات 🎉</p> @endforelse
  </div>
</div>

<div class="grid lg:grid-cols-2 gap-4 mt-4">
  <div class="bg-white rounded-2xl border border-slate-200 p-4">
    <h3 class="font-extrabold mb-3">آخر المدفوعات</h3>
    @forelse($recentPayments as $p)
      <div class="flex items-center justify-between py-2 border-b border-slate-100 last:border-0 text-sm">
        <div><span class="font-bold">{{ $p->student->name ?? '—' }}</span> <span class="text-slate-400 text-xs">{{ $p->receipt_number }}</span></div>
        <span class="font-bold {{ $p->remaining<=0?'text-emerald-600':'text-amber-600' }}">{{ number_format($p->paid_amount,0) }} ج.م</span>
      </div>
    @empty <p class="text-sm text-slate-400 text-center py-4">لا يوجد</p> @endforelse
  </div>
  <div class="bg-white rounded-2xl border border-slate-200 p-4">
    <h3 class="font-extrabold mb-3">آخر الطلاب</h3>
    @forelse($recentStudents as $s)
      <div class="flex items-center justify-between py-2 border-b border-slate-100 last:border-0 text-sm">
        <div class="font-bold">{{ $s->name }} <span class="text-xs text-slate-400">{{ $s->code }}</span></div>
        <span class="text-xs px-2 py-1 rounded-full {{ $s->status==='active'?'bg-emerald-50 text-emerald-700':'bg-slate-100 text-slate-600' }}">{{ $s->status }}</span>
      </div>
    @empty <p class="text-sm text-slate-400 text-center py-4">لا يوجد</p> @endforelse
  </div>
</div>
@endsection
