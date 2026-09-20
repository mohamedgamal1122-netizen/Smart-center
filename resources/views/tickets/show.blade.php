@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="mb-0">🎫 تذكرة: {{ $ticket->title }}</h3>
    <a href="{{ route('tickets.index') }}" class="btn btn-secondary">← العودة</a>
  </div>

  <div class="card mb-4">
    <div class="card-header">
      <div class="row">
        <div class="col"><strong>رقم التذكرة:</strong> {{ $ticket->ticket_number }}</div>
        <div class="col"><strong>الأولوية:</strong> {{ $ticket->priority }}</div>
        <div class="col"><strong>الحالة:</strong> {{ $ticket->status }}</div>
        <div class="col"><strong>تاريخ الإنشاء:</strong> {{ $ticket->created_at->format('Y/m/d') }}</div>
      </div>
    </div>
    <div class="card-body">
      <p><strong>الوصف:</strong></p>
      <p>{{ $ticket->description }}</p>

      <div class="row mt-3">
        <div class="col"><strong>الطالب:</strong> {{ $ticket->student->name ?? 'غير محدد' }}</div>
        <div class="col"><strong>ولي الأمر:</strong> {{ $ticket->parent->name ?? 'غير محدد' }}</div>
        <div class="col"><strong>الموظف المسؤول:</strong> {{ $ticket->assignedTo->name ?? 'غير مخصص' }}</div>
      </div>
    </div>
  </div>

  <div class="card mb-4">
    <div class="card-header">التعليقات</div>
    <div class="card-body">
      @foreach($ticket->comments as $comment)
        <div class="border-bottom pb-2 mb-2">
          <div class="d-flex justify-content-between">
            <strong>{{ $comment->user->name ?? 'مجهول' }}</strong>
            <small class="text-muted">{{ $comment->created_at->format('Y/m/d H:i') }} @if($comment->is_internal)<span class="badge bg-info">داخلي</span>@endif</small>
          </div>
          <p class="mb-0">{{ $comment->comment }}</p>
        </div>
      @endforeach
    </div>
  </div>

  <div class="card">
    <div class="card-header">إضافة تعليق</div>
    <div class="card-body">
      <form action="{{ route('tickets.comments.store', $ticket->id) }}" method="POST">
        @csrf
        <div class="mb-2">
          <label class="form-label">نص التعليق</label>
          <textarea name="comment" rows="3" class="form-control" required></textarea>
        </div>
        <div class="mb-2">
          <div class="form-check">
            <input type="checkbox" name="is_internal" id="is_internal" class="form-check-input">
            <label for="is_internal" class="form-check-label">تعليق داخلي (لا يراه الطلاب/أولياء الأمر)</label>
          </div>
        </div>
        <button type="submit" class="btn btn-success">إرسال</button>
      </form>
    </div>
  </div>
</div>
@endsection