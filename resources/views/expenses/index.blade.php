@extends('layouts.app')
@section('title','المصروفات')
@section('page_title','مصروفات السنتر')
@section('content')
<div class="flex justify-between items-center mb-5">
  <form method="GET" class="flex gap-2">
    <select name="category" class="border rounded-xl px-3 py-2.5 text-sm"><option value="">كل الأنواع</option><option value="rent" @selected(request('category')=='rent')>إيجار</option><option value="electricity" @selected(request('category')=='electricity')>كهرباء</option><option value="water" @selected(request('category')=='water')>مياه</option><option value="salaries" @selected(request('category')=='salaries')>رواتب</option><option value="supplies" @selected(request('category')=='supplies')>أدوات</option><option value="marketing" @selected(request('category')=='marketing')>تسويق</option><option value="maintenance" @selected(request('category')=='maintenance')>صيانة</option><option value="other" @selected(request('category')=='other')>أخرى</option></select>
    <input name="from" type="date" value="{{ request('from') }}" class="border rounded-xl px-3 py-2.5 text-sm">
    <input name="to" type="date" value="{{ request('to') }}" class="border rounded-xl px-3 py-2.5 text-sm">
    <button class="bg-slate-900 text-white px-5 py-2.5 rounded-xl text-sm font-bold">فلترة</button>
  </form>
  <a href="{{ request()->fullUrlWithQuery(['export'=>'excel']) }}" class="bg-emerald-600 text-white px-5 py-2.5 rounded-xl text-sm font-bold">Excel</a>
  <a href="{{ route('expenses.create') }}" class="bg-violet-600 text-white px-5 py-2.5 rounded-xl text-sm font-bold"><i class="fa-solid fa-plus ml-1"></i> إضافة مصروف</a>
</div>
<div class="bg-white rounded-2xl border overflow-hidden">
  <div class="overflow-x-auto"><table class="w-full text-sm">
    <thead class="bg-slate-50 text-slate-500 text-xs"><tr><th class="p-3 text-right">النوع</th><th class="p-3">القيمة</th><th class="p-3">التاريخ</th><th class="p-3">الوصف</th><th class="p-3">الموظف</th><th class="p-3">إجراءات</th></tr></thead>
    <tbody>
    @forelse($expenses as $ex)
      <tr class="border-t hover:bg-slate-50">
        <td class="p-3"><span class="text-xs bg-slate-100 px-2 py-1 rounded-full font-bold">{{ $ex->category=='rent' ? 'إيجار' : ($ex->category=='electricity' ? 'كهرباء' : ($ex->category=='water' ? 'مياه' : ($ex->category=='salaries' ? 'رواتب' : ($ex->category=='supplies' ? 'أدوات' : ($ex->category=='marketing' ? 'تسويق' : ($ex->category=='maintenance' ? 'صيانة' : 'أخرى')))))) }}</span></td>
        <td class="p-3 text-center font-bold text-red-600">{{ number_format($ex->amount) }} ج.م</td>
        <td class="p-3 text-center text-xs">{{ $ex->expense_date }}</td>
        <td class="p-3 text-xs">{{ Str::limit($ex->description,40) ?? '—' }}</td>
        <td class="p-3 text-center text-xs">{{ $ex->creator->name ?? '—' }}</td>
        <td class="p-3"><div class="flex gap-1">
          <a href="{{ route('expenses.edit',$ex->id) }}" class="w-8 h-8 rounded-lg bg-amber-100 flex items-center justify-center"><i class="fa-solid fa-pen text-xs text-amber-700"></i></a>
          <form method="POST" action="{{ route('expenses.destroy',$ex->id) }}" onsubmit="return confirm('حذف؟')">@csrf @method('DELETE')<button class="w-8 h-8 rounded-lg bg-red-100 flex items-center justify-center"><i class="fa-solid fa-trash text-xs text-red-600"></i></button></form>
        </div></td>
      </tr>
    @empty
      <tr><td colspan="6" class="p-8 text-center text-slate-400">لا توجد مصروفات</td></tr>
    @endforelse
    </tbody>
  </table></div>
  <div class="p-4 flex justify-between items-center"><span class="text-sm font-bold">الإجمالي: <span class="text-red-600">{{ number_format($expenses->sum('amount')) }} ج.م</span></span><span>{{ $expenses->links() }}</span></div>
</div>
@endsection
