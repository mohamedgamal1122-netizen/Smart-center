<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>تسجيل الدخول — {{ center_name() }}</title>
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@600;700;800&display=swap" rel="stylesheet">
<style>*{font-family:'Cairo',sans-serif}</style>
</head>
<body class="min-h-screen bg-gradient-to-br from-slate-900 via-indigo-950 to-slate-900 flex items-center justify-center p-4">
<div class="w-full max-w-[420px] bg-white rounded-[24px] p-8 shadow-2xl">
  <div class="text-center mb-6">
    @php $logo = center_logo(); @endphp
    @if($logo)
      <img src="{{ $logo }}" class="w-16 h-16 rounded-2xl object-contain bg-white border mx-auto mb-3 p-1">
    @else
      <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-violet-600 to-indigo-600 flex items-center justify-center text-white text-xl font-extrabold mx-auto mb-3">{{ mb_substr(center_name(),0,1) }}</div>
    @endif
    <h1 class="text-xl font-extrabold">{{ center_name() }}</h1><p class="text-sm text-slate-500 mt-1">{{ setting('center_slogan','سجّل دخولك للمتابعة') }}</p>
  </div>
  @if($errors->any())<div class="bg-red-50 border border-red-200 text-red-700 rounded-xl px-4 py-3 text-sm mb-4">{{ $errors->first() }}</div>@endif
  <form method="POST" action="{{ route('login.post') }}" class="space-y-4">@csrf
    <div><label class="text-sm font-bold">البريد الإلكتروني</label><input name="email" type="email" value="{{ old('email') }}" required class="mt-1 w-full rounded-xl border border-slate-200 px-4 py-3 text-sm focus:ring-2 focus:ring-violet-500 focus:border-violet-500 outline-none" placeholder="admin@center.com"></div>
    <div><label class="text-sm font-bold">كلمة المرور</label><input name="password" type="password" required class="mt-1 w-full rounded-xl border border-slate-200 px-4 py-3 text-sm focus:ring-2 focus:ring-violet-500 outline-none" placeholder="••••••••"></div>
    <label class="flex items-center gap-2 text-sm"><input type="checkbox" name="remember" class="rounded"> تذكرني</label>
    <button class="w-full bg-[#0f172a] text-white py-3 rounded-xl font-bold hover:bg-black transition">دخول</button>
  </form>
</div>
</body>
</html>
