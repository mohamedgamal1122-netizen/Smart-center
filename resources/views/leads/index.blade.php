@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="mb-0">🚀 العملاء المحتمين</h3>
    <a href="{{ route('leads.create') }}" class="btn btn-primary">➕ إضافة عميل محتمى</a>
  </div>

  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  <div class="table-responsive">
    <table class="table table-bordered table-hover">
      <thead class="table-dark">
        <tr>
          <th>#</th>
          <th>الاسم</th>
          <th>الولد/الابنة</th>
          <th>الهاتف</th>
          <th>البريد الإلكتروني</th>
          <th>المصدر</th>
          <th>الحالة</th>
          <th>موعد المتابعة</th>
          <th>تاريخ الإنشاء</th>
          <th>الإجراءات</th>
        </tr>
      </thead>
      <tbody>
        @forelse($leads as $lead)
        <tr>
          <td>{{ $lead->id }}</td>
          <td>{{ $lead->name }}</td>
          <td>{{ $lead->child_name ?? '-' }}</td>
          <td>{{ $lead->phone ?? '-' }}</td>
          <td>{{ $lead->email ?? '-' }}</td>
          <td>
            @switch($lead->source)
              @case('referral') <span class="badge bg-info">إحالة</span> @break
              @case('social_media') <span class="badge bg-primary">وسائل التواصل</span> @break
              @case('ad') <span class="badge bg-warning">إعلان</span> @break
              @case('walk_in') <span class="badge bg-success">زيارة مباشرة</span> @break
              @default <span class="badge bg-secondary">{{ $lead->source }}</span>
            @endswitch
          </td>
          <td>
            @switch($lead->status)
              @case('new') <span class="badge bg-primary">جديد</span> @break
              @case('contacted') <span class="badge bg-info">تم التواصل</span> @break
              @case('qualified') <span class="badge bg-warning">مؤهل</span> @break
              @case('converted') <span class="badge bg-success">محوَّل</span> @break
              @case('rejected') <span class="badge bg-danger">مرفوض</span> @break
              @default <span class="badge bg-secondary">{{ $lead->status }}</span>
            @endswitch
          </td>
          <td>{{ $lead->followup_date?->format('Y/m/d') ?? '-' }}</td>
          <td>{{ $lead->created_at->format('Y/m/d') }}</td>
          <td>
            @if($lead->status != 'converted')
              <form action="{{ route('leads.convert', $lead->id) }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('هل أنت متأكد من تحويل هذا العميل المحتمى إلى طالب؟')">🔄 تحويل</button>
              </form>
            @else
              <span class="text-success">✅ تم التحويل</span>
            @endif
          </td>
        </tr>
        @empty
        <tr><td colspan="10" class="text-center text-muted">لا يوجد عملاء محتمين حالياً</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>

  {{ $leads->links() }}
</div>
@endsection