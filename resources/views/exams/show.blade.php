@extends('layouts.app')
@section('title',$exam->title)
@section('page_title',$exam->title)
@section('content')
<div class="bg-white rounded-2xl border p-5 mb-4">
  <div class="flex justify-between items-start">
    <div><h2 class="font-extrabold">{{ $exam->title }}</h2><p class="text-sm text-slate-500 mt-1">{{ $exam->group->name ?? '' }} — {{ $exam->exam_date }} — {{ $exam->max_score }} درجة — {{ $exam->exam_type }}</p>
      @if(isset($prevExam) && $prevExam)<p class="text-xs text-slate-400 mt-1">مقارنة بـ: {{ $prevExam->title }} ({{ $prevExam->exam_date }}) — تنبيه عند نزول &gt;15%</p>@endif
    </div>
    <a href="{{ route('exams.edit',$exam->id) }}" class="bg-amber-500 text-white px-4 py-1.5 rounded-full text-xs font-bold">تعديل</a>
  </div>
  <div class="mt-4 grid grid-cols-4 gap-3 text-center text-sm">
    <div class="bg-slate-50 rounded-xl p-3"><div class="text-xs text-slate-500">المتوسط</div><div class="font-extrabold">{{ number_format($stats['avg'] ?? 0,1) }}</div></div>
    <div class="bg-emerald-50 rounded-xl p-3"><div class="text-xs text-emerald-600">أعلى درجة</div><div class="font-extrabold text-emerald-700">{{ $stats['max'] ?? '—' }}</div></div>
    <div class="bg-red-50 rounded-xl p-3"><div class="text-xs text-red-500">أقل درجة</div><div class="font-extrabold text-red-600">{{ $stats['min'] ?? '—' }}</div></div>
    <div class="bg-violet-50 rounded-xl p-3"><div class="text-xs text-violet-600">عدد الطلاب</div><div class="font-extrabold text-violet-700">{{ count($results) }}</div></div>
  </div>
</div>

{{-- ترتيب وشهادات Top 3 --}}
@if(isset($top3) && $top3->count())
<div class="bg-gradient-to-br from-violet-600 to-indigo-700 rounded-2xl p-5 mb-4 text-white">
  <h3 class="font-extrabold text-sm mb-3 flex items-center gap-2"><i class="fa-solid fa-trophy text-amber-300"></i> أوائل الاختبار — شهادات PDF</h3>
  <div class="grid md:grid-cols-3 gap-3">
    @foreach($top3 as $idx=>$tr)
      @php $stu = $tr->student; $rank = $idx+1; @endphp
      <div class="bg-white rounded-xl p-3 text-slate-800 flex items-center gap-3">
        <div class="w-10 h-10 rounded-full flex items-center justify-center font-extrabold text-white {{ $rank==1?'bg-amber-500':($rank==2?'bg-slate-400':'bg-amber-700') }}">#{{ $rank }}</div>
        <div class="flex-1 min-w-0">
          <div class="font-bold text-sm truncate">{{ $stu->name ?? '—' }}</div>
          <div class="text-xs text-slate-500">{{ $tr->score }} / {{ $exam->max_score }} — {{ number_format($tr->score / max(1,$exam->max_score)*100,1) }}%</div>
        </div>
        <a href="{{ route('exams.certificate',[$exam->id,$stu->id]) }}" target="_blank" class="bg-violet-600 text-white px-3 py-1.5 rounded-full text-xs font-bold whitespace-nowrap"><i class="fa-solid fa-certificate ml-1"></i> شهادة PDF</a>
      </div>
    @endforeach
  </div>
</div>
@endif

<form method="POST" action="{{ route('exam-results.store',$exam->id) }}" class="bg-white rounded-2xl border overflow-hidden">@csrf
  <div class="px-5 py-3 border-b flex justify-between items-center"><h3 class="font-bold text-sm">إدخال / تعديل الدرجات (الدرجة النهائية: {{ $exam->max_score }})</h3><span class="text-xs text-slate-400">النسبة تُحسب تلقائياً — تنبيه أحمر عند نزول &gt;15% عن الاختبار السابق</span></div>
  <div class="overflow-x-auto"><table class="w-full text-sm">
    <thead class="bg-slate-50 text-slate-500 text-xs"><tr><th class="p-3 text-right">الطالب</th><th class="p-3">الدرجة</th><th class="p-3">النسبة</th><th class="p-3">التقدير</th><th class="p-3">حالة المستوى</th><th class="p-3">شهادة</th><th class="p-3">ملاحظات</th><th class="p-3">حفظ</th></tr></thead>
    <tbody>
    @foreach($students as $stu)
      @php
        $res = $resultsMap[$stu->id] ?? null;
        $isTop3 = isset($top3) && $top3->contains(fn($r)=> $r->student_id==$stu->id);
        $decline = $declines[$stu->id] ?? null;
        $rankForStu = null;
        if($res && isset($ranked)){
          $pos = $ranked->search(fn($r)=> $r->student_id==$stu->id);
          $rankForStu = $pos !== false ? $pos+1 : null;
        }
      @endphp
      <tr class="border-t hover:bg-slate-50 {{ $decline ? 'bg-rose-50/60' : '' }}">
        <td class="p-3 font-bold">
          <span class="inline-flex items-center gap-1">
            @if($rankForStu==1)<i class="fa-solid fa-crown text-amber-500"></i>@endif
            {{ $stu->name }} <span class="text-xs text-slate-400 font-normal">{{ $stu->code }}</span>
            @if($rankForStu && $rankForStu<=3)<span class="bg-violet-100 text-violet-700 text-[10px] px-1.5 py-0.5 rounded-full font-extrabold">#{{ $rankForStu }}</span>@endif
          </span>
        </td>
        <td class="p-3"><input name="results[{{ $stu->id }}][score]" type="number" min="0" max="{{ $exam->max_score }}" step="0.5" value="{{ $res?->score }}" placeholder="—" class="w-20 border rounded-lg px-2 py-1.5 text-sm text-center"></td>
        <td class="p-3 text-center text-xs font-bold">@if($res) {{ number_format($res->percentage ?? ($res->score/$exam->max_score*100),1) }}% @else — @endif</td>
        <td class="p-3"><input name="results[{{ $stu->id }}][grade]" value="{{ $res?->grade }}" placeholder="ممتاز/جيد..." class="w-24 border rounded-lg px-2 py-1.5 text-xs"></td>
        <td class="p-3 text-center">
          @if($decline)
            <span class="inline-flex items-center gap-1 bg-rose-600 text-white px-2 py-1 rounded-full text-[11px] font-extrabold" title="كان {{ $decline['prevPct'] }}% في {{ $decline['prevExam'] }} → الآن {{ $decline['currPct'] }}%">
              <i class="fa-solid fa-arrow-trend-down"></i> نازل {{ $decline['drop'] }}% <span class="opacity-80">({{ $decline['prevPct'] }}%→{{ $decline['currPct'] }}%)</span>
            </span>
          @elseif($res && isset($prevExam))
            <span class="text-emerald-600 text-xs">مستقر ✓</span>
          @else
            <span class="text-slate-300 text-xs">—</span>
          @endif
        </td>
        <td class="p-3 text-center">
          @if($isTop3 && $res)
            <a href="{{ route('exams.certificate',[$exam->id,$stu->id]) }}" target="_blank" class="text-violet-600 font-bold text-xs underline">PDF</a>
          @else
            <span class="text-slate-300 text-xs">—</span>
          @endif
        </td>
        <td class="p-3"><input name="results[{{ $stu->id }}][notes]" value="{{ $res?->notes }}" placeholder="—" class="w-28 border rounded-lg px-2 py-1.5 text-xs"></td>
        <td class="p-3 text-center text-xs">@if($res) <span class="text-emerald-600">✓ محفوظ</span> @else <span class="text-slate-400">—</span> @endif</td>
      </tr>
    @endforeach
    </tbody>
  </table></div>
  <div class="p-4 bg-slate-50 flex gap-2"><button class="bg-violet-600 text-white px-6 py-2.5 rounded-xl text-sm font-bold">حفظ الدرجات</button><a href="{{ route('exams.index') }}" class="bg-white border px-6 py-2.5 rounded-xl text-sm font-bold">رجوع</a></div>
</form>
@endsection
