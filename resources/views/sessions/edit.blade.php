@extends('layouts.app')
@section('title','تعديل حصة')
@section('page_title','تعديل حصة')
@section('content')
<form method="POST" action="{{ route('sessions.update',$session->id) }}" class="bg-white rounded-2xl border p-6 space-y-4 max-w-xl">@csrf @method('PUT')
  <div><label class="text-sm font-bold">المجموعة *</label><select name="group_id" required class="mt-1 w-full border rounded-xl px-3 py-2.5 text-sm">@foreach($groups as $g)<option value="{{ $g->id }}" @selected($session->group_id==$g->id)>{{ $g->name }}</option>@endforeach</select></div>
  <div class="grid md:grid-cols-2 gap-4">
    <div><label class="text-sm font-bold">التاريخ *</label><input name="date" type="date" required value="{{ old('date',$session->date) }}" class="mt-1 w-full border rounded-xl px-3 py-2.5 text-sm"></div>
    <div><label class="text-sm font-bold">الحالة</label><select name="status" class="mt-1 w-full border rounded-xl px-3 py-2.5 text-sm"><option value="completed" @selected($session->status=='completed')>تمت</option><option value="cancelled" @selected($session->status=='cancelled')>أُلغيت</option><option value="postponed" @selected($session->status=='postponed')>مؤجلة</option><option value="makeup" @selected($session->status=='makeup')>تعويضية</option></select></div>
    <div><label class="text-sm font-bold">وقت البداية</label><input name="start_time" type="time" value="{{ old('start_time',$session->start_time) }}" class="mt-1 w-full border rounded-xl px-3 py-2.5 text-sm"></div>
    <div><label class="text-sm font-bold">وقت النهاية</label><input name="end_time" type="time" value="{{ old('end_time',$session->end_time) }}" class="mt-1 w-full border rounded-xl px-3 py-2.5 text-sm"></div>
  </div>
  <div><label class="text-sm font-bold">ملاحظات</label><textarea name="notes" rows="2" class="mt-1 w-full border rounded-xl px-3 py-2.5 text-sm">{{ old('notes',$session->notes) }}</textarea></div>
  <div class="flex gap-2"><button class="bg-violet-600 text-white px-6 py-2.5 rounded-xl text-sm font-bold">تحديث</button><a href="{{ route('sessions.index') }}" class="bg-slate-100 px-6 py-2.5 rounded-xl text-sm font-bold">إلغاء</a></div>
</form>
@endsection
