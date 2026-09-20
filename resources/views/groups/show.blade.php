@extends('layouts.app')
@section('title',$group->name)
@section('page_title',$group->name)
@section('content')
@php $cnt = $group->enrollments->count(); $pct = $group->max_students ? round($cnt/$group->max_students*100) : 0; @endphp
<div class="grid lg:grid-cols-3 gap-4">
  <div class="lg:col-span-2 space-y-4">
    <div class="bg-white rounded-2xl border p-5">
      <div class="flex justify-between items-start">
        <div><h2 class="font-extrabold text-lg">{{ $group->name }}</h2><p class="text-sm text-slate-500 mt-1">{{ $group->subject->name ?? '' }} — {{ $group->grade }} — {{ $group->teacher->name ?? '' }}</p><p class="text-xs text-slate-400 mt-1">{{ $group->room ?? 'بدون قاعة' }} — {{ $group->start_time }} إلى {{ $group->end_time }} — {{ $group->days ?? '' }}</p></div>
        <span class="text-xs px-3 py-1.5 rounded-full font-bold @if($group->status=='open') bg-emerald-100 text-emerald-700 @elseif($group->status=='full') bg-amber-100 text-amber-700 @else bg-slate-100 @endif">{{ $group->status=='open' ? 'مفتوحة' : ($group->status=='full' ? 'مكتملة' : 'متوقفة') }}</span>
      </div>
      <div class="mt-4 grid grid-cols-3 gap-3 text-center text-sm">
        <div class="bg-slate-50 rounded-xl p-3"><div class="text-xs text-slate-500">الطلاب</div><div class="font-extrabold text-lg">{{ $cnt }} / {{ $group->max_students }}</div><div class="h-1.5 bg-slate-200 rounded-full mt-2"><div class="h-full bg-violet-600 rounded-full" style="width: {{ min($pct,100) }}%"></div></div></div>
        <div class="bg-slate-50 rounded-xl p-3"><div class="text-xs text-slate-500">الاشتراك</div><div class="font-extrabold text-lg">{{ number_format($group->monthly_fee) }} <span class="text-xs">ج.م</span></div><div class="text-xs text-slate-400">شهرياً</div></div>
        <div class="bg-slate-50 rounded-xl p-3"><div class="text-xs text-slate-500">المتأخرات</div><div class="font-extrabold text-lg text-red-600">{{ number_format($group->payments_sum ?? 0) }} ج.م</div></div>
      </div>
      <div class="mt-4 flex gap-2"><a href="{{ route('groups.edit',$group->id) }}" class="bg-amber-500 text-white px-5 py-2 rounded-xl text-sm font-bold">تعديل</a><a href="{{ route('enrollments.create') }}?group_id={{ $group->id }}" class="bg-violet-600 text-white px-5 py-2 rounded-xl text-sm font-bold">تسجيل طالب</a></div>
    </div>
    <div class="bg-white rounded-2xl border p-5">
      <h3 class="font-bold mb-3">الطلاب المسجلون ({{ $cnt }})</h3>
      <div class="space-y-2 max-h-96 overflow-y-auto">
        @forelse($group->enrollments as $en)
          <div class="flex items-center justify-between border rounded-xl px-4 py-3 hover:bg-slate-50">
            <a href="{{ route('students.show',$en->student->id) }}" class="font-bold text-sm">{{ $en->student->name }} <span class="text-xs text-slate-400">{{ $en->student->code }}</span></a>
            <div class="flex items-center gap-2">
              <span class="text-xs bg-slate-100 px-2 py-1 rounded-full">{{ $en->status=='active' ? 'نشط' : $en->status }}</span>
              <form method="POST" action="{{ route('enrollments.destroy',$en->id) }}" onsubmit="return confirm('إلغاء التسجيل؟')">@csrf @method('DELETE')<button class="text-xs text-red-600">إلغاء</button></form>
            </div>
          </div>
        @empty
          <p class="text-slate-400 text-sm">لا يوجد طلاب</p>
        @endforelse
      </div>
    </div>
  </div>
  <div class="space-y-4">
    <div class="bg-white rounded-2xl border p-5">
      <h3 class="font-bold mb-3">الحصص القادمة</h3>
      @forelse($group->classSessions ?? $group->sessions ?? [] as $s)
        <div class="border rounded-xl px-3 py-2.5 mb-2 flex justify-between items-center text-sm"><span>{{ $s->date }} — {{ $s->start_time }}</span><span class="text-xs px-2 py-1 rounded-full @if($s->status=='completed') bg-emerald-100 text-emerald-700 @else bg-slate-100 @endif">{{ $s->status }}</span></div>
      @empty
        <p class="text-slate-400 text-sm">لا توجد حصص</p>
      @endforelse
      <a href="{{ route('sessions.create') }}?group_id={{ $group->id }}" class="mt-3 block text-center bg-slate-900 text-white py-2 rounded-xl text-sm font-bold">إضافة حصة</a>
    </div>
    <div class="bg-white rounded-2xl border p-5">
      <h3 class="font-bold mb-3">الاختبارات</h3>
      @forelse($group->exams ?? [] as $ex)
        <a href="{{ route('exams.show',$ex->id) }}" class="block border rounded-xl px-3 py-2.5 mb-2 hover:bg-violet-50 text-sm"><div class="font-bold">{{ $ex->title }}</div><div class="text-xs text-slate-500">{{ $ex->exam_date }} — {{ $ex->max_score }} درجة</div></a>
      @empty
        <p class="text-slate-400 text-sm">لا توجد اختبارات</p>
      @endforelse
    </div>
  </div>
</div>
@endsection
