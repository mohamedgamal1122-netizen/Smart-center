@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="mb-0">📋 سجل التواصلات</h3>
    <a href="{{ route('communications.create') }}" class="btn btn-primary">➕ إضافة تواصل</a>
  </div>

  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  <div class="table-responsive">
    <table class="table table-bordered table-hover">
      <thead class="table-dark">
        <tr>
          <th>#</th>
          <th>النوع</th>
          <th>العنوان</th>
          <th>الوصف</th>
          <th>الأولوية</th>
          <th>الحالة</th>
          <th>التاريخ</th>
        </tr>
      </thead>
      <tbody>
        @forelse($communications as $communication)
        <tr>
          <td>{{ $communication->id }}</td>
          <td>
            @php
              $types = [
                'call' => '📞 مكالمة',
                'message' => '💬 رسالة',
                'meeting' => '🤝 اجتماع',
                'complaint' => '⚠️ شكوى',
                'inquiry' => '❓ استفسار',
                'enrollment_request' => '📥 طلب تسجيل',
                'reschedule_request' => '🔄 طلب إعادة جدولة',
                'followup' => '🔁 متابعة',
              ];
              echo $types[$communication->type] ?? $communication->type;
            @endphp
          </td>
          <td>{{ $communication->subject ?? 'بدون عنوان' }}</td>
          <td>{{ Str::limit($communication->description, 50) }}</td>
          <td>
            @switch($communication->priority)
              @case('high') <span class="badge bg-danger">عالية</span> @break
              @case('medium') <span class="badge bg-warning">متوسطة</span> @break
              @case('low') <span class="badge bg-info">منخفضة</span> @break
            @endswitch
          </td>
          <td>
            @switch($communication->status)
              @case('new') <span class="badge bg-primary">جديد</span> @break
              @case('in_progress') <span class="badge bg-warning">قيد المعالجة</span> @break
              @case('contacted') <span class="badge bg-info">تم التواصل</span> @break
              @case('resolved') <span class="badge bg-success">تم الحل</span> @break
              @case('closed') <span class="badge bg-secondary">مغلق</span> @break
            @endswitch
          </td>
          <td>{{ $communication->created_at->format('Y/m/d') }}</td>
        </tr>
        @empty
        <tr><td colspan="7" class="text-center text-muted">لا توجد تواصلات حالياً</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>

  {{ $communications->links() }}
</div>
@endsection