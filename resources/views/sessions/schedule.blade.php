@extends('layouts.app')
@section('title','الجدول الأسبوعي')
@section('page_title','الجدول الأسبوعي — السبت إلى الجمعة')
@section('content')
<div class="flex flex-wrap items-center gap-3 mb-4">
  <form method="GET" class="flex items-center gap-2">
    <input type="date" name="week" value="{{ $weekStart->format('Y-m-d') }}" class="border rounded-xl px-3 py-2 text-sm">
    <button class="bg-slate-900 text-white px-4 py-2 rounded-xl text-sm font-bold">عرض الأسبوع</button>
  </form>
  <span class="text-xs text-slate-500">من {{ $weekStart->format('Y/m/d') }} إلى {{ $weekEnd->format('Y/m/d') }}</span>
  <a href="{{ route('sessions.index') }}" class="mr-auto text-xs bg-slate-100 px-4 py-2 rounded-full font-bold hover:bg-slate-200">قائمة الحصص</a>
  <span id="saveStatus" class="text-xs text-emerald-600 font-bold hidden"><i class="fa-solid fa-check"></i> تم الحفظ</span>
</div>

<div class="grid grid-cols-1 lg:grid-cols-7 gap-3" id="weekGrid">
@php $days=['Saturday'=>'السبت','Sunday'=>'الأحد','Monday'=>'الاثنين','Tuesday'=>'الثلاثاء','Wednesday'=>'الأربعاء','Thursday'=>'الخميس','Friday'=>'الجمعة']; $order=['Saturday','Sunday','Monday','Tuesday','Wednesday','Thursday','Friday']; @endphp
@foreach($order as $en)
  @php $date = $weekStart->copy()->addDays(array_search($en,$order)); $daySessions = $sessionsByDay[$en] ?? collect(); @endphp
  <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden flex flex-col min-h-[260px]">
    <div class="px-3 py-2.5 border-b flex items-center justify-between {{ $date->isToday() ? 'bg-violet-600 text-white' : 'bg-slate-50' }}">
      <span class="font-extrabold text-sm">{{ $days[$en] }}</span>
      <span class="text-xs {{ $date->isToday() ? 'text-white/80' : 'text-slate-500' }}">{{ $date->format('m/d') }}</span>
    </div>
    <div class="flex-1 p-2 space-y-2 sortable-day bg-slate-50/40 min-h-[200px]" data-day="{{ $en }}" data-date="{{ $date->format('Y-m-d') }}">
      @forelse($daySessions as $s)
        <div class="session-card bg-white border border-slate-200 rounded-xl p-3 shadow-sm cursor-grab active:cursor-grabbing hover:shadow-md transition" data-id="{{ $s->id }}">
          <div class="flex items-start justify-between gap-2">
            <span class="text-[13px] font-bold leading-tight">{{ $s->group->name ?? '—' }}</span>
            <span class="text-[11px] px-2 py-0.5 rounded-full font-bold shrink-0
              @if($s->status=='completed') bg-emerald-100 text-emerald-700
              @elseif($s->status=='cancelled') bg-red-100 text-red-600
              @elseif($s->status=='postponed') bg-amber-100 text-amber-700
              @else bg-violet-100 text-violet-700 @endif">{{ $s->status_label }}</span>
          </div>
          <div class="mt-1.5 flex items-center gap-2 text-xs text-slate-500">
            <span><i class="fa-regular fa-clock ml-1"></i>{{ $s->start_time ? \Carbon\Carbon::parse($s->start_time)->format('H:i') : '—' }} - {{ $s->end_time ? \Carbon\Carbon::parse($s->end_time)->format('H:i') : '—' }}</span>
          </div>
          <div class="mt-1 text-xs text-slate-400 truncate">{{ $s->group->subject->name ?? '' }} — {{ $s->group->teacher->name ?? '' }}</div>
          <div class="mt-2 flex gap-1">
            <a href="{{ route('sessions.show',$s->id) }}" class="text-[11px] bg-slate-900 text-white px-2.5 py-1 rounded-full">عرض</a>
            <span class="text-[11px] text-slate-400 flex items-center gap-1"><i class="fa-solid fa-grip text-[10px]"></i> اسحب</span>
          </div>
        </div>
      @empty
        <div class="text-center py-8 text-xs text-slate-400 empty-hint">لا توجد حصص<br><span class="text-[11px]">اسحب حصة إلى هنا</span></div>
      @endforelse
    </div>
  </div>
@endforeach
</div>

<p class="mt-4 text-xs text-slate-400"><i class="fa-solid fa-circle-info ml-1"></i> اسحب الحصة بين الأيام لتغيير تاريخها تلقائياً — يتم الحفظ فورياً عبر AJAX.</p>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>
<script>
(function(){
  var token=document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}';
  var statusEl=document.getElementById('saveStatus');
  function flash(msg, isError){
    statusEl.textContent=msg;
    statusEl.className='text-xs font-bold '+(isError?'text-red-600':'text-emerald-600');
    statusEl.classList.remove('hidden');
    setTimeout(function(){ statusEl.classList.add('hidden'); },2200);
  }
  document.querySelectorAll('.sortable-day').forEach(function(el){
    new Sortable(el,{
      group:'week',
      animation:180,
      ghostClass:'opacity-40',
      chosenClass:'ring-2 ring-violet-300',
      onEnd: function(evt){
        var card=evt.item;
        var id=card.dataset.id;
        var newDate=evt.to.dataset.date;
        if(!id || !newDate) return;
        // hide empty hint when dropping first card
        evt.to.querySelector('.empty-hint')?.classList.add('hidden');
        if(evt.from!==evt.to && evt.from.children.length===1) { /* left empty, keep hint hidden until reload */ }
        fetch('{{ route("sessions.reorder") }}',{
          method:'POST',
          headers:{'Content-Type':'application/json','X-CSRF-TOKEN':token,'Accept':'application/json'},
          body: JSON.stringify({id:id, date:newDate})
        }).then(function(r){ return r.json().then(function(j){ return {ok:r.ok, j:j}; }); })
        .then(function(res){
          if(res.ok && res.j.success) flash('تم نقل الحصة إلى '+newDate);
          else flash(res.j.message || 'فشل الحفظ', true);
        }).catch(function(){ flash('خطأ اتصال', true); });
      }
    });
  });
})();
</script>
@endpush
