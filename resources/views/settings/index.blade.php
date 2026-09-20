@extends('layouts.app')
@section('title','إعدادات السنتر')
@section('page_title','إعدادات السنتر')
@section('content')
<div class="max-w-3xl">
  <div class="bg-white rounded-2xl border border-slate-200 p-6">
    <div class="flex items-center gap-3 mb-6">
      <div class="w-10 h-10 rounded-xl bg-violet-600 flex items-center justify-center text-white"><i class="fa-solid fa-gear"></i></div>
      <div><h2 class="font-extrabold">إعدادات سمارت سنتر</h2><p class="text-xs text-slate-500">غيّر اسم السنتر واللوجو — يظهر فوراً في كل الصفحات والإيصالات</p></div>
    </div>

    <form method="POST" action="{{ route('settings.update') }}" enctype="multipart/form-data" class="space-y-5">@csrf @method('PUT')
      <div class="grid md:grid-cols-2 gap-4">
        <div class="md:col-span-2">
          <label class="text-sm font-bold">اسم السنتر *</label>
          <input name="center_name" required value="{{ old('center_name',$center_name) }}" placeholder="مثال: سمارت سنتر - فرع المعادي" class="mt-1 w-full border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold focus:ring-2 focus:ring-violet-500 outline-none">
          <p class="text-[11px] text-slate-400 mt-1">يظهر في الهيدر والايصال والتقارير</p>
        </div>
        <div>
          <label class="text-sm font-bold">الشعار (سلوجان)</label>
          <input name="center_slogan" value="{{ old('center_slogan',$center_slogan) }}" placeholder="نظام إدارة السنتر الذكي" class="mt-1 w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-violet-500 outline-none">
        </div>
        <div>
          <label class="text-sm font-bold">رقم الهاتف</label>
          <input name="center_phone" value="{{ old('center_phone',$center_phone) }}" placeholder="01000000000" class="mt-1 w-full border border-slate-200 rounded-xl px-4 py-3 text-sm dir-ltr text-right focus:ring-2 focus:ring-violet-500 outline-none">
        </div>
        <div class="md:col-span-2">
          <label class="text-sm font-bold">العنوان</label>
          <input name="center_address" value="{{ old('center_address',$center_address) }}" placeholder="القاهرة - المعادي - شارع..." class="mt-1 w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-violet-500 outline-none">
        </div>
      </div>

      <div class="border-t pt-5">
        <label class="text-sm font-bold">لوجو السنتر</label>
        <div class="mt-3 flex items-start gap-4">
          <div class="w-24 h-24 rounded-2xl border-2 border-dashed border-slate-200 bg-slate-50 flex items-center justify-center overflow-hidden">
            @if($center_logo)
              <img src="{{ asset('storage/'.$center_logo) }}" class="w-full h-full object-contain">
            @else
              <span class="text-2xl font-extrabold text-violet-600">{{ mb_substr($center_name,0,1) }}</span>
            @endif
          </div>
          <div class="flex-1">
            <input type="file" name="center_logo" accept="image/*" class="w-full border border-slate-200 rounded-xl px-3 py-2 text-sm">
            <p class="text-[11px] text-slate-400 mt-1">PNG/JPG/WEBP/SVG — حد أقصى 2MB — مربع يفضل 500×500</p>
            @if($center_logo)
              <label class="flex items-center gap-2 mt-2 text-xs"><input type="checkbox" name="remove_logo" value="1"> حذف اللوجو الحالي</label>
            @endif
          </div>
        </div>
      </div>

      <div class="flex gap-2 pt-2">
        <button class="bg-violet-600 hover:bg-violet-700 text-white px-8 py-3 rounded-xl text-sm font-bold">حفظ الإعدادات</button>
        <a href="{{ route('dashboard') }}" class="bg-slate-100 hover:bg-slate-200 px-6 py-3 rounded-xl text-sm font-bold">إلغاء</a>
      </div>
    </form>
  </div>

  <div class="mt-4 bg-amber-50 border border-amber-200 rounded-xl p-4 text-xs text-amber-800">
    <strong>معاينة:</strong> الاسم واللوجو هيظهروا فوراً في القائمة الجانبية، الهيدر، الإيصالات، والتقارير PDF.
  </div>
</div>
@endsection
