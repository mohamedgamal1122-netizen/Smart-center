@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="mb-0">➕ إضافة عميل محتمى جديد</h3>
    <a href="{{ route('leads.index') }}" class="btn btn-secondary">← العودة</a>
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

  <form action="{{ route('leads.store') }}" method="POST">
    @csrf
    <div class="row g-3">
      <div class="col-md-6">
        <label class="form-label">اسم العميل المحتمى <span class="text-danger">*</span></label>
        <input type="text" name="name" class="form-control" required>
      </div>
      <div class="col-md-6">
        <label class="form-label">رقم الهاتف</label>
        <input type="text" name="phone" class="form-control">
      </div>
      <div class="col-md-6">
        <label class="form-label">البريد الإلكتروني</label>
        <input type="email" name="email" class="form-control">
      </div>
      <div class="col-md-6">
        <label class="form-label">اسم الطالب</label>
        <input type="text" name="child_name" class="form-control">
      </div>
      <div class="col-md-6">
        <label class="form-label">الصف الدراسي للطالب</label>
        <input type="text" name="child_level" class="form-control">
      </div>
      <div class="col-md-6">
        <label class="form-label">المادة المطلوبة</label>
        <input type="text" name="subject" class="form-control">
      </div>
      <div class="col-md-6">
        <label class="form-label">الفرع المطلوب</label>
        <input type="text" name="branch" class="form-control">
      </div>
      <div class="col-md-6">
        <label class="form-label">مصدر العميل <span class="text-danger">*</span></label>
        <select name="source" class="form-select" required>
          <option value="">اختر المصدر...</option>
          <option value="referral">إحالة</option>
          <option value="social_media">وسائل التواصل الاجتماعي</option>
          <option value="ad">إعلان</option>
          <option value="walk_in">زيارة مباشرة</option>
          <option value="other">أخرى</option>
        </select>
      </div>
      <div class="col-md-6">
        <label class="form-label">موعد المتابعة</label>
        <input type="date" name="followup_date" class="form-control">
      </div>
      <div class="col-md-6">
        <label class="form-label">الموظف المسؤول</label>
        <select name="user_id" class="form-select">
          <option value="">لاتعيين</option>
          @foreach(\App\Models\User::all() as $user)
            <option value="{{ $user->id }}">{{ $user->name }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-12">
        <label class="form-label">ملاحظات</label>
        <textarea name="notes" rows="3" class="form-control"></textarea>
      </div>
      <div class="col-12">
        <button type="submit" class="btn btn-success">✅ إضافة العميل المحتمى</button>
      </div>
    </div>
  </form>
</div>
@endsection