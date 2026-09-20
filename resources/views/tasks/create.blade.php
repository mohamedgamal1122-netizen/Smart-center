@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="mb-0">➕ إنشاء مهمة جديدة</h3>
    <a href="{{ route('tasks.index') }}" class="btn btn-secondary">← العودة</a>
  </div>

  @if($errors->any())
    <div class="alert alert-danger">
      <ul class="mb-0">
        @foreach($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <form action="{{ route('tasks.store') }}" method="POST">
    @csrf
    <div class="row g-3">
      <div class="col-12">
        <label class="form-label">عنوان المهمة</label>
        <input type="text" name="title" class="form-control" required>
      </div>
      <div class="col-12">
        <label class="form-label">وصف المهمة (اختياري)</label>
        <textarea name="description" rows="3" class="form-control"></textarea>
      </div>
      <div class="col-md-4">
        <label class="form-label">تعيين إلى (اختياري)</label>
        <select name="assigned_to" class="form-select">
          <option value="">لاتعيين</option>
          @foreach($users as $user)
            <option value="{{ $user->id }}">{{ $user->name }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-4">
        <label class="form-label">موعد الاستحقام (اختياري)</label>
        <input type="date" name="due_date" class="form-control">
      </div>
      <div class="col-md-4">
        <label class="form-label">الأولوية</label>
        <select name="priority" class="form-select" required>
          <option value="medium">متوسطة</option>
          <option value="low">منخفضة</option>
          <option value="high">عالية</option>
          <option value="urgent">عاجل</option>
        </select>
      </div>
      <div class="col-md-6">
        <label class="form-label">الطالب (اختياري)</label>
        <select name="student_id" class="form-select">
          <option value="">اختر طالب...</option>
          @foreach($students as $student)
            <option value="{{ $student->id }}">{{ $student->name }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-6">
        <label class="form-label">ولي الأمر (اختياري)</label>
        <select name="parent_id" class="form-select">
          <option value="">اختر ولي أمر...</option>
          @foreach($parents as $parent)
            <option value="{{ $parent->id }}">{{ $parent->name }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-6">
        <label class="form-label">التذكرة ذات الصلة (اختياري)</label>
        <select name="ticket_id" class="form-select">
          <option value="">اختر تذكرة...</option>
          @foreach($tickets as $ticket)
            <option value="{{ $ticket->id }}">{{ $ticket->ticket_number }} - {{ $ticket->title }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-6">
        <label class="form-label">العميل المحتمى ذو الصلة (اختياري)</label>
        <select name="lead_id" class="form-select">
          <option value="">اختر عميل محتمى...</option>
          @foreach($leads as $lead)
            <option value="{{ $lead->id }}">{{ $lead->name }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-12">
        <button type="submit" class="btn btn-success">✅ إنشاء المهمة</button>
      </div>
    </div>
  </form>
</div>
@endsection