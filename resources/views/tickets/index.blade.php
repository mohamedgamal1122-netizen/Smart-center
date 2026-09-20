@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="mb-0">🎫 تذاكر الدعم</h3>
    <a href="{{ route('tickets.create') }}" class="btn btn-primary">➕ إنشاء تذكرة</a>
  </div>

  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  <form method="GET" class="row g-2 mb-3">
    <div class="col-auto">
      <select name="status" class="form-select">
        <option value="">جميع الحالات</option>
        <option value="new" {{ request('status') == 'new' ? 'selected' : '' }}>جديدة</option>
        <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>قيد المعالجة</option>
        <option value="resolved" {{ request('status') == 'resolved' ? 'selected' : '' }}>تم الحل</option>
        <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>مغلقة</option>
      </select>
    </div>
    <div class="col-auto">
      <button type="submit" class="btn btn-outline-primary">تطبيق</button>
    </div>
  </form>

  <div class="table-responsive">
    <table class="table table-bordered table-hover">
      <thead class="table-dark">
        <tr>
          <th>رقم التذكرة</th>
          <th>العنوان</th>
          <th>الطالب</th>
          <th>ولي الأمر</th>
          <th>الموظف المخصص</th>
          <th>الأولوية</th>
          <th>الحالة</th>
          <th>تاريخ الإنشاء</th>
        </tr>
      </thead>
      <tbody>
        @forelse($tickets as $ticket)
        <tr>
          <td><a href="{{ route('tickets.show', $ticket->id) }}">{{ $ticket->ticket_number }}</a></td>
          <td>{{ $ticket->title }}</td>
          <td>{{ $ticket->student->name ?? 'غير محدد' }}</td>
          <td>{{ $ticket->parent->name ?? 'غير محدد' }}</td>
          <td>{{ $ticket->assignedTo->name ?? 'غير مخصص' }}</td>
          <td>
            @switch($ticket->priority)
              @case('urgent') <span class="badge bg-danger">عاجل</span> @break
              @case('high') <span class="badge bg-danger">عالية</span> @break
              @case('medium') <span class="badge bg-warning">متوسطة</span> @break
              @case('low') <span class="badge bg-info">منخفضة</span> @break
            @endswitch
          </td>
          <td>
            @switch($ticket->status)
              @case('new') <span class="badge bg-primary">جديدة</span> @break
              @case('in_progress') <span class="badge bg-warning">قيد المعالجة</span> @break
              @case('contacted') <span class="badge bg-info">تم التواصل</span> @break
              @case('resolved') <span class="badge bg-success">تم الحل</span> @break
              @case('closed') <span class="badge bg-secondary">مغلقة</span> @break
            @endswitch
          </td>
          <td>{{ $ticket->created_at->format('Y/m/d') }}</td>
        </tr>
        @empty
        <tr><td colspan="8" class="text-center text-muted">لا توجد تذاكر حالياً</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>

  {{ $tickets->appends(request()->except('page'))->links() }}
</div>
@endsection