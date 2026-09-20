@extends('layouts.app')
@section('title','المحذوفون')
@section('page_title','الطلاب المحذوفون')
@section('content')
<div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
<table class="w-full text-sm"><thead class="bg-slate-50"><tr><th class="px-4 py-3 text-right">الاسم</th><th class="px-4 py-3 text-right">الكود</th><th class="px-4 py-3"></th></tr></thead>
<tbody>@forelse($students as $s)<tr class="border-t"><td class="px-4 py-3 font-bold">{{ $s->name }}</td><td class="px-4 py-3 font-mono text-xs">{{ $s->code }}</td><td class="px-4 py-3 flex gap-2"><form method="POST" action="{{ route('students.restore',$s->id) }}">@csrf<button class="bg-emerald-600 text-white px-3 py-1.5 rounded-xl text-xs font-bold">استرجاع</button></form><form method="POST" action="{{ route('students.forceDelete',$s->id) }}" onsubmit="return confirm('حذف نهائي؟')">@csrf @method('DELETE')<button class="bg-rose-600 text-white px-3 py-1.5 rounded-xl text-xs font-bold">حذف نهائي</button></form></td></tr>@empty<tr><td colspan="3" class="text-center py-8 text-slate-400">لا يوجد</td></tr>@endforelse</tbody></table>
<div class="p-4">{{ $students->links() }}</div></div>
@endsection
