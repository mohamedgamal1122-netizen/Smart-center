@extends('layouts.app')
@section('title','مسح حضور QR')
@section('page_title','مسح حضور QR')
@section('content')
<div class="grid lg:grid-cols-2 gap-4">
  <div class="bg-white rounded-2xl border p-5">
    <h3 class="font-extrabold text-sm mb-3"><i class="fa-solid fa-camera text-violet-600 ml-1"></i> مسح الكود بالكاميرا</h3>
    <div id="reader" class="rounded-xl overflow-hidden border bg-slate-50" style="min-height:260px"></div>
    <p class="text-xs text-slate-400 mt-2">اسمح بالكاميرا ثم وجهها نحو كود QR الخاص بالطالب. إن لم تعمل الكاميرا استخدم الإدخال اليدوي بجانبها.</p>
    <div class="mt-3 flex gap-2">
      <button onclick="startScan()" class="bg-violet-600 text-white px-4 py-2 rounded-xl text-xs font-bold">تشغيل الكاميرا</button>
      <button onclick="stopScan()" class="bg-slate-200 px-4 py-2 rounded-xl text-xs font-bold">إيقاف</button>
    </div>
  </div>
  <div class="bg-white rounded-2xl border p-5">
    <h3 class="font-extrabold text-sm mb-3"><i class="fa-solid fa-keyboard text-slate-700 ml-1"></i> إدخال يدوي / تأكيد</h3>
    @if(isset($student) && $student)
      <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-3 mb-3 flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl bg-emerald-600 text-white flex items-center justify-center font-extrabold">{{ mb_substr($student->name,0,1) }}</div>
        <div><div class="font-bold text-sm">{{ $student->name }}</div><div class="text-xs font-mono text-slate-500">{{ $student->code }}</div></div>
        <span class="mr-auto bg-emerald-600 text-white text-xs px-3 py-1 rounded-full font-bold">تم التعرف</span>
      </div>
    @endif
    <form method="POST" action="{{ route('attendance.scanStore') }}" class="space-y-3">@csrf
      <div>
        <label class="text-xs font-bold">كود الطالب *</label>
        <input name="code" id="code-input" value="{{ $code ?? old('code') }}" required placeholder="STU-2026-0001 أو امسح QR" class="mt-1 w-full border rounded-xl px-3 py-2.5 text-sm font-mono bg-slate-50">
      </div>
      <div>
        <label class="text-xs font-bold">الحصة *</label>
        <select name="session_id" required class="mt-1 w-full border rounded-xl px-3 py-2.5 text-sm">
          <option value="">اختر الحصة</option>
          @foreach($sessions as $s)
            <option value="{{ $s->id }}" @selected(old('session_id')==$s->id)>{{ $s->group->name ?? '—' }} — {{ $s->date }} @if($s->start_time) ({{ \Carbon\Carbon::parse($s->start_time)->format('H:i') }}) @endif</option>
          @endforeach
        </select>
      </div>
      <div>
        <label class="text-xs font-bold">الحالة</label>
        <select name="status" class="mt-1 w-full border rounded-xl px-3 py-2.5 text-sm">
          <option value="present" selected>حاضر</option>
          <option value="late">متأخر</option>
          <option value="absent">غائب</option>
          <option value="excused">معذور</option>
        </select>
      </div>
      <button class="w-full bg-violet-600 text-white py-3 rounded-xl font-bold text-sm"><i class="fa-solid fa-check ml-1"></i> تسجيل الحضور</button>
      <div class="flex gap-2">
        <a href="{{ route('attendances.index') }}" class="flex-1 text-center bg-slate-100 py-2.5 rounded-xl text-xs font-bold">سجل الحضور</a>
        <a href="{{ route('attendances.sheet') }}" class="flex-1 text-center bg-slate-900 text-white py-2.5 rounded-xl text-xs font-bold">كشف الحضور الشهري</a>
      </div>
    </form>
  </div>
</div>

@push('scripts')
<script src="https://unpkg.com/html5-qrcode@2.3.10/html5-qrcode.min.js"></script>
<script>
let html5QrCode=null;
function onScanSuccess(decodedText){
  document.getElementById('code-input').value = decodedText.includes('code=') ? new URL(decodedText).searchParams.get('code') || decodedText : decodedText;
  // auto submit? just highlight
  document.getElementById('code-input').classList.add('ring-2','ring-emerald-400');
  if(navigator.vibrate) navigator.vibrate(120);
}
function startScan(){
  if(html5QrCode) return;
  html5QrCode = new Html5Qrcode("reader");
  html5QrCode.start({facingMode:"environment"}, {fps:10, qrbox:{width:240,height:240}}, onScanSuccess, ()=>{})
  .catch(e=> alert('تعذر تشغيل الكاميرا: '+e));
}
function stopScan(){
  if(html5QrCode){ html5QrCode.stop().then(()=>{html5QrCode.clear(); html5QrCode=null;}).catch(()=>{}); }
}
// auto start if no code prefilled
if(!document.getElementById('code-input').value) { /* user clicks start */ }
</script>
@endpush
@endsection
