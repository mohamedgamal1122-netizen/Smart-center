@extends('layouts.app')
@section('title','صلاحيات '.$user->name)
@section('page_title','صلاحيات: '.$user->name)
@section('content')
<div class="max-w-4xl">
  <div class="bg-white rounded-2xl border border-slate-200 p-6">
    <div class="flex items-center justify-between mb-6">
      <div class="flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl bg-violet-600 flex items-center justify-center text-white font-bold">{{ mb_substr($user->name,0,1) }}</div>
        <div><div class="font-extrabold">{{ $user->name }}</div><div class="text-xs text-slate-500">{{ $user->email }} — {{ $user->role }}</div></div>
      </div>
      <a href="{{ route('users.index') }}" class="bg-slate-100 px-4 py-2 rounded-xl text-sm font-bold">رجوع</a>
    </div>

    <form method="POST" action="{{ route('users.permissions.update',$user->id) }}">@csrf @method('PUT')
      
      <div class="mb-6">
        <h3 class="font-bold text-sm mb-3 flex items-center gap-2"><i class="fa-solid fa-shield-halved text-violet-600"></i> الأدوار</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
          @foreach($roles as $role)
            <label class="flex items-center gap-2 border rounded-xl px-3 py-2.5 cursor-pointer hover:bg-slate-50 {{ in_array($role->name,$userRoles) ? 'bg-violet-50 border-violet-300' : '' }}">
              <input type="checkbox" name="roles[]" value="{{ $role->name }}" @checked(in_array($role->name,$userRoles)) class="rounded">
              <span class="text-sm font-bold">{{ $role->name=='admin'?'مدير':($role->name=='receptionist'?'استقبال':($role->name=='accountant'?'محاسب':'مدرس')) }}</span>
              <span class="text-[11px] text-slate-400 mr-auto">{{ $role->permissions->count() }} صلاحية</span>
            </label>
          @endforeach
        </div>
        <p class="text-[11px] text-slate-400 mt-2">الدور يعطي حزمة صلاحيات جاهزة — يمكنك إضافة صلاحيات فردية أدناه</p>
      </div>

      <div class="border-t pt-6">
        <div class="flex items-center justify-between mb-3">
          <h3 class="font-bold text-sm flex items-center gap-2"><i class="fa-solid fa-list-check text-violet-600"></i> صلاحيات دقيقة — تحكم في كل صفحة</h3>
          <div class="flex gap-1">
            <button type="button" onclick="toggleAll(true)" class="text-xs bg-slate-900 text-white px-3 py-1 rounded-full">تحديد الكل</button>
            <button type="button" onclick="toggleAll(false)" class="text-xs bg-slate-100 px-3 py-1 rounded-full">إلغاء الكل</button>
          </div>
        </div>

        @php
          $labels = [
            'students'=>'الطلاب','parents'=>'أولياء الأمور','teachers'=>'المدرسون','subjects'=>'المواد',
            'groups'=>'المجموعات','enrollments'=>'التسجيلات','sessions'=>'الحصص','attendances'=>'الحضور',
            'exams'=>'الامتحانات','payments'=>'المدفوعات','expenses'=>'المصروفات','reports'=>'التقارير',
            'users'=>'المستخدمون','settings'=>'الإعدادات','notifications'=>'الإشعارات','dashboard'=>'لوحة التحكم'
          ];
          $permLabels = [
            'view'=>'عرض','create'=>'إضافة','edit'=>'تعديل','delete'=>'حذف','export'=>'تصدير','qr'=>'QR','report'=>'تقرير','manage'=>'إدارة',
            'create'=>'إنشاء','schedule'=>'جدول','scan'=>'مسح','sheet'=>'كشف','certificate'=>'شهادة','cancel'=>'إلغاء','receipt'=>'إيصال',
            'revenue'=>'إيرادات','profit'=>'ربح','attendance'=>'حضور','students'=>'طلاب','permissions'=>'صلاحيات'
          ];
        @endphp

        <div class="space-y-4 max-h-[520px] overflow-y-auto pr-1">
          @foreach($allPerms as $group => $perms)
            <div class="border rounded-xl overflow-hidden">
              <div class="bg-slate-50 px-4 py-2 flex items-center justify-between">
                <span class="font-bold text-sm">{{ $labels[$group] ?? $group }}</span>
                <label class="text-xs flex items-center gap-1 cursor-pointer"><input type="checkbox" class="group-toggle" data-group="{{ $group }}"> تحديد المجموعة</label>
              </div>
              <div class="p-3 grid grid-cols-2 md:grid-cols-3 gap-2">
                @foreach($perms as $perm)
                  <label class="flex items-center gap-2 text-sm border rounded-lg px-2 py-1.5 cursor-pointer hover:bg-slate-50 {{ in_array($perm->name,$userPerms) ? 'bg-violet-50 border-violet-200' : '' }}">
                    <input type="checkbox" name="permissions[]" value="{{ $perm->name }}" @checked(in_array($perm->name,$userPerms)) class="perm-check" data-group="{{ $group }}">
                    <span class="text-xs">{{ $permLabels[explode('.',$perm->name)[1] ?? ''] ?? explode('.',$perm->name)[1] }}</span>
                    <span class="text-[10px] text-slate-400 mr-auto">{{ $perm->name }}</span>
                  </label>
                @endforeach
              </div>
            </div>
          @endforeach
        </div>
      </div>

      <div class="flex gap-2 mt-6">
        <button class="bg-violet-600 hover:bg-violet-700 text-white px-8 py-3 rounded-xl text-sm font-bold">حفظ الصلاحيات</button>
        <a href="{{ route('users.index') }}" class="bg-slate-100 px-6 py-3 rounded-xl text-sm font-bold">إلغاء</a>
      </div>
    </form>
  </div>
  <div class="mt-3 bg-amber-50 border border-amber-200 rounded-xl p-3 text-xs text-amber-800">
    <strong>ملاحظة:</strong> الصلاحيات الفردية تُضاف فوق صلاحيات الدور — لو أعطيت دور "استقبال" + صلاحية "settings.view" سيقدر يشوف الإعدادات.
  </div>
</div>
@push('scripts')
<script>
function toggleAll(v){
  document.querySelectorAll('.perm-check').forEach(c=>c.checked=v);
  document.querySelectorAll('.perm-check').forEach(c=>c.closest('label').classList.toggle('bg-violet-50',c.checked));
}
document.querySelectorAll('.group-toggle').forEach(g=>{
  g.addEventListener('change',()=>{
    let grp=g.dataset.group;
    document.querySelectorAll('.perm-check[data-group="'+grp+'"]').forEach(c=>{
      c.checked=g.checked;
      c.closest('label').classList.toggle('bg-violet-50',c.checked);
      c.closest('label').classList.toggle('border-violet-200',c.checked);
    });
  });
});
document.querySelectorAll('.perm-check').forEach(c=>{
  c.addEventListener('change',()=>{
    c.closest('label').classList.toggle('bg-violet-50',c.checked);
    c.closest('label').classList.toggle('border-violet-200',c.checked);
  });
});
</script>
@endpush
@endsection
