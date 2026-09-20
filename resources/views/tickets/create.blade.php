@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="mb-0">➕ إنشاء تذكرة دعم</h3>
    <a href="{{ route('tickets.index') }}" class="btn btn-secondary">← العودة</a>
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

  <form action="{{ route('tickets.store') }}" method="POST">
    @csrf
    <div class="row g-3">
      <div class="col-12">
        <label class="form-label">العنوان</label>
        <input type="text" name="title" class="form-control" required>
      </div>
      <div class="col-12">
        <label class="form-label">الوصف</label>
        <textarea name="description" rows="4" class="form-control" required></textarea>
      </div>
      <div class="col-md-4">
        <label class="form-label">الفئة</label>
        <select name="category" class="form-select" required>
          <option value="">اختر الفئة...</option>
          <option value="technical">تقنية</option>
          <option value="billing">مالية</option>
          <option value="academic">أكاديمية</option>
          <option value="complaint">شكوى</option>
          <option value="inquiry">استفسار</option>
          <option value="other">أخرى</option>
        </select>
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
      <div class="col-md-4">
        <label class="form-label">الموظف المسؤول (اختياري)</label>
        <select name="assigned_to" class="form-select">
          <option value="">لاتعيين</option>
          @foreach(\App\Models\User::all() as $user)
            <option value="{{ $user->id }}">{{ $user->name }}</option>
          @endforeach
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
      <div class="col-12">
        <button type="submit" class="btn btn-success">🎫 إنشاء التذكرة</button>
      </div>
    </div>
  </form>
</div>
@endsection