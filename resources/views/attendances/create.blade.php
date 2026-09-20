@extends('layouts.app')
@section('title','تسجيل حضور')
@section('page_title','تسجيل حضور جماعي')
@section('content')
<form method="POST" action="{{ route('attendances.store') }}" class="bg-white rounded-2xl border p-6 space-y-4">@csrf
  <div class="grid md:grid-cols-2 gap-4">
    <div><label class="text-sm font-bold">الحصة *</label><select name="session_id" required id="session-select" class="mt-1 w-full border rounded-xl px-3 py-2.5 text-sm"><option value="">اختر الحصة</option>@foreach($sessions as $s)<option value="{{ $s->id }}" @selected(request('session_id')==$s->id || old('session_id')==$s->id)>{{ $s->group->name }} — {{ $s->date }} ({{ $s->start_time }})</option>@endforeach</select></div>
    <div class="flex items-end"><button type="button" onclick="markAll('present')" class="bg-emerald-600 text-white px-4 py-2.5 rounded-xl text-xs font-bold">الكل حاضر</button><button type="button" onclick="markAll('absent')" class="mr-2 bg-red-500 text-white px-4 py-2.5 rounded-xl text-xs font-bold">الكل غائب</button></div>
  </div>
  <div class="border rounded-xl overflow-hidden">
    <div class="bg-slate-50 px-4 py-3 font-bold text-sm">الطلاب</div>
    <div class="divide-y">
      @if(isset($students))
        @foreach($students as $stu)
          <div class="flex items-center justify-between px-4 py-3 hover:bg-slate-50">
            <span class="font-bold text-sm">{{ $stu->name }} <span class="text-xs text-slate-400">{{ $stu->code }}</span></span>
            <div class="flex gap-1">
              @foreach(['present'=>'حاضر','absent'=>'غائب','late'=>'متأخر','excused'=>'بعذر'] as $val=>$label)
                <label class="flex items-center gap-1 text-xs cursor-pointer"><input type="radio" name="attendances[{{ $stu->id }}][status]" value="{{ $val }}" @checked($val=='present')> {{ $label }}</label>
              @endforeach
              <input type="text" name="attendances[{{ $stu->id }}][notes]" placeholder="ملاحظة" class="border rounded-lg px-2 py-1 text-xs w-24">
            </div>
          </div>
        @endforeach
      @else
        <div class="p-6 text-center text-slate-400 text-sm">اختر الحصة أولاً ثم سيظهر الطلاب. أو <a href="{{ route('sessions.index') }}" class="text-violet-600 font-bold">اذهب للحصص</a></div>
      @endif
    </div>
  </div>
  <div class="flex gap-2"><button class="bg-violet-600 text-white px-6 py-2.5 rounded-xl text-sm font-bold">حفظ الحضور</button><a href="{{ route('attendances.index') }}" class="bg-slate-100 px-6 py-2.5 rounded-xl text-sm font-bold">إلغاء</a></div>
</form>
@push('scripts')
<script>function markAll(v){document.querySelectorAll(`input[value="${v}"]`).forEach(i=>i.checked=true)}
document.getElementById('session-select')?.addEventListener('change',function(){ if(this.value) window.location='?session_id='+this.value; });
</script>
@endpush
@endsection
