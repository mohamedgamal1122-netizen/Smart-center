@extends('layouts.app')
@section('title','الإشعارات')
@section('page_title','الإشعارات')
@section('content')
<div class="flex items-center justify-between mb-4">
  <div class="flex gap-2">
    <a href="{{ route('notifications.index') }}" class="px-4 py-2 rounded-xl text-sm font-bold {{ !request('filter')?'bg-slate-900 text-white':'bg-white border' }}">الكل</a>
    <a href="{{ route('notifications.index',['filter'=>'unread']) }}" class="px-4 py-2 rounded-xl text-sm font-bold {{ request('filter')=='unread'?'bg-slate-900 text-white':'bg-white border' }}">غير مقروءة</a>
  </div>
  <form method="POST" action="{{ route('notifications.markAllRead') }}">@csrf<button class="text-sm text-indigo-600 font-bold hover:underline">تعليم الكل كمقروء</button></form>
</div>
<div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
  @forelse($notifications as $n)
    <div class="flex gap-3 px-5 py-4 border-b border-slate-100 {{ $n->is_read?'':'bg-violet-50/60' }}">
      <div class="w-8 h-8 rounded-full flex items-center justify-center shrink-0
        @if($n->type=='warning') bg-amber-100 text-amber-600 @elseif($n->type=='error') bg-red-100 text-red-600 @elseif($n->type=='success') bg-emerald-100 text-emerald-600 @else bg-sky-100 text-sky-600 @endif">
        <i class="fa-solid @if($n->type=='warning') fa-triangle-exclamation @elseif($n->type=='error') fa-circle-exclamation @elseif($n->type=='success') fa-circle-check @else fa-circle-info @endif text-sm"></i>
      </div>
      <div class="flex-1 min-w-0">
        <div class="font-bold text-sm">{{ $n->title }}</div>
        <div class="text-sm text-slate-600 mt-1">{{ $n->message }}</div>
        <div class="text-xs text-slate-400 mt-1">{{ $n->created_at->diffForHumans() }} — {{ $n->created_at->format('Y/m/d H:i') }}</div>
      </div>
      <div class="flex flex-col gap-1 shrink-0">
        @if(!$n->is_read)
          <form method="POST" action="{{ route('notifications.markRead',$n) }}">@csrf<button class="text-xs bg-indigo-600 text-white px-3 py-1.5 rounded-full font-bold hover:bg-indigo-700">قراءة</button></form>
        @endif
        <form method="POST" action="{{ route('notifications.destroy',$n) }}">@csrf @method('DELETE')<button class="text-xs text-slate-400 hover:text-red-600"><i class="fa-solid fa-trash"></i></button></form>
      </div>
    </div>
  @empty
    <div class="p-10 text-center text-slate-400">لا توجد إشعارات</div>
  @endforelse
</div>
<div class="mt-4">{{ $notifications->links() }}</div>
@endsection
