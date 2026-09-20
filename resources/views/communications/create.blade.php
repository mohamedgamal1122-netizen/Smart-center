@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="mb-0">➕ إضافة تواصل جديد</h3>
    <a href="{{ route('communications.index') }}" class="btn btn-secondary">← العودة</a>
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

  <form action="{{ route('communications.store') }}" method="POST">
    @csrf
    <div class="row g-3">
      <div class="col-md-6">
        <label class="form-label">نوع التواصل</label>
        <select name="type" class="form-select" required>
          <option value="">اختر النوع...</option>
          <option value="call">📞 مكالمة</option>
          <option value="message">💬 رسالة</option>
          <option value="meeting">🤝 اجتماع</option>
          <option value="complaint">⚠️ شكوى</option>
          <option value="inquiry">❓ استفسار</option>
          <option value="enrollment_request">📥 طلب تسجيل</option>
          <option value="reschedule_request">🔄 طلب إعادة جدولة</option>
          <option value="followup">🔁 متابعة</option>
        </select>
      </div>
      <div class="col-md-6">
        <label class="form-label">الأولوية</label>
        <select name="priority" class="form-select" required>
          <option value="medium">متوسطة</option>
          <option value="high">عالية</option>
          <option value="low">منخفضة</option>
        </select>
      </div>
      <div class="col-12">
        <label class="form-label">العنوان (اختياري)</label>
        <input type="text" name="subject" class="form-control" placeholder="أدخل العنوان...">
      </div>
      <div class="col-12">
        <label class="form-label">الوصف</label>
        <textarea name="description" rows="4" class="form-control" required placeholder="أدخل تفاصيل التواصل..."></textarea>
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
        <button type="submit" class="btn btn-success">💾 حفظ التواصل</button>
      </div>
    </div>
  </form>
</div>
@endsection