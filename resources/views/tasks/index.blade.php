@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="mb-0">📝 المهام</h3>
    <a href="{{ route('tasks.create') }}" class="btn btn-primary">➕ إنشاء مهمة</a>
  </div>

  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  <form method="GET" class="row g-2 mb-3">
    <div class="col-auto">
      <select name="status" class="form-select">
        <option value="">جميع الحالات</option>
        <option value="todo" {{ request('status') == 'todo' ? 'selected' : '' }}>لم تبدأ</option>
        <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>قيد التنفيذ</option>
        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>مكتملة</option>
        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>ملغاة</option>
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
          <th>#</th>
          <th>العنوان</th>
          <th>الموظف المسؤول</th>
          <th>الأولوية</th>
          <th>الحالة</th>
          <th>موعد الاستحقام</th>
          <th>تاريخ الإنشاء</th>
        </tr>
      </thead>
      <tbody>
        @forelse($tasks as $task)
        <tr>
          <td>{{ $task->id }}</td>
          <td>{{ $task->title }}</td>
          <td>{{ $task->assignedTo->name ?? 'غير مخصص' }}</td>
          <td>
            @switch($task->priority)
              @case('urgent') <span class="badge bg-danger">عاجل</span> @break
              @case('high') <span class="badge bg-danger">عالية</span> @break
              @case('medium') <span class="badge bg-warning">متوسطة</span> @break
              @case('low') <span class="badge bg-info">منخفضة</span> @break
            @endswitch
          </td>
          <td>
            @switch($task->status)
              @case('todo') <span class="badge bg-secondary">لم تبدأ</span> @break
              @case('in_progress') <span class="badge bg-warning">قيد التنفيذ</span> @break
              @case('completed') <span class="badge bg-success">مكتملة</span> @break
              @case('cancelled') <span class="badge bg-danger">ملغاة</span> @break
            @endswitch
          </td>
          <td>{{ $task->due_date?->format('Y/m/d') ?? 'غير محدد' }}</td>
          <td>{{ $task->created_at->format('Y/m/d') }}</td>
        </tr>
        @empty
        <tr><td colspan="7" class="text-center text-muted">لا توجد مهام حالياً</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>

  {{ $tasks->appends(request()->except('page'))->links() }}
</div>
@endsection