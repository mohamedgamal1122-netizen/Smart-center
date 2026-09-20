<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>@yield('title', center_name())</title>
<script src="https://cdn.tailwindcss.com">
// ---- جرس الاشعارات ----
function toggleNotifDropdown(){
  var dd=document.getElementById('notifDropdown');
  dd.classList.toggle('hidden');
  if(!dd.classList.contains('hidden')) loadNotifications();
}
function loadNotifications(){
  fetch('/notifications/json',{headers:{'Accept':'application/json'}})
    .then(function(r){return r.json();})
    .then(function(data){
      var list=document.getElementById('notifList');
      var countEl=document.getElementById('notifCount');
      var c=data.unread_count||0;
      if(c>0){ countEl.textContent=c>99?'99+':c; countEl.classList.remove('hidden'); countEl.classList.add('flex'); }
      else { countEl.classList.add('hidden'); countEl.classList.remove('flex'); }
      if(!data.notifications || data.notifications.length===0){
        list.innerHTML='<div class="p-6 text-center text-sm text-slate-400">لا توجد اشعارات</div>'; return;
      }
      var html='';
      var typeIcon={info:'fa-circle-info text-sky-500',warning:'fa-triangle-exclamation text-amber-500',success:'fa-circle-check text-emerald-500',error:'fa-circle-exclamation text-red-500'};
      data.notifications.forEach(function(n){
        var ic=typeIcon[n.type]||typeIcon.info;
        var unread=n.is_read? '':'bg-violet-50';
        html+='<div class="flex gap-3 px-4 py-3 border-b border-slate-50 '+unread+' hover:bg-slate-50">'
          +'<i class="fa-solid '+ic+' mt-1"></i>'
          +'<div class="flex-1 min-w-0"><div class="text-sm font-bold truncate">'+escHtml(n.title)+'</div><div class="text-xs text-slate-500 line-clamp-2">'+escHtml(n.message)+'</div><div class="text-[10px] text-slate-400 mt-1">'+escHtml(n.created_at||'')+'</div></div>'
          +(n.is_read?'':'<button onclick="markNotifRead('+n.id+',event)" class="text-[11px] text-indigo-600 font-bold shrink-0">قراءة</button>')
          +'</div>';
      });
      list.innerHTML=html;
    }).catch(function(){});
}
function escHtml(s){ var d=document.createElement('div'); d.textContent=s||''; return d.innerHTML; }
function markNotifRead(id,e){
  e.stopPropagation();
  var token=document.querySelector('meta[name="csrf-token"]')?.content;
  fetch('/notifications/'+id+'/api-read',{method:'POST',headers:{'X-CSRF-TOKEN':token,'Accept':'application/json'}}).then(function(){ loadNotifications(); });
}
function markAllNotifRead(){
  var token=document.querySelector('meta[name="csrf-token"]')?.content;
  fetch('/notifications/api-mark-all-read',{method:'POST',headers:{'X-CSRF-TOKEN':token,'Accept':'application/json'}}).then(function(){ loadNotifications(); });
}
document.addEventListener('click',function(e){
  var wrap=document.getElementById('notifBellWrap');
  var dd=document.getElementById('notifDropdown');
  if(wrap && dd && !wrap.contains(e.target)) dd.classList.add('hidden');
});
loadNotifications();
setInterval(loadNotifications, 60000);

</script>
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<script>tailwind.config={darkMode:'class',theme:{extend:{fontFamily:{cairo:['Cairo','sans-serif']}}}}
// ---- جرس الاشعارات ----
function toggleNotifDropdown(){
  var dd=document.getElementById('notifDropdown');
  dd.classList.toggle('hidden');
  if(!dd.classList.contains('hidden')) loadNotifications();
}
function loadNotifications(){
  fetch('/notifications/json',{headers:{'Accept':'application/json'}})
    .then(function(r){return r.json();})
    .then(function(data){
      var list=document.getElementById('notifList');
      var countEl=document.getElementById('notifCount');
      var c=data.unread_count||0;
      if(c>0){ countEl.textContent=c>99?'99+':c; countEl.classList.remove('hidden'); countEl.classList.add('flex'); }
      else { countEl.classList.add('hidden'); countEl.classList.remove('flex'); }
      if(!data.notifications || data.notifications.length===0){
        list.innerHTML='<div class="p-6 text-center text-sm text-slate-400">لا توجد اشعارات</div>'; return;
      }
      var html='';
      var typeIcon={info:'fa-circle-info text-sky-500',warning:'fa-triangle-exclamation text-amber-500',success:'fa-circle-check text-emerald-500',error:'fa-circle-exclamation text-red-500'};
      data.notifications.forEach(function(n){
        var ic=typeIcon[n.type]||typeIcon.info;
        var unread=n.is_read? '':'bg-violet-50';
        html+='<div class="flex gap-3 px-4 py-3 border-b border-slate-50 '+unread+' hover:bg-slate-50">'
          +'<i class="fa-solid '+ic+' mt-1"></i>'
          +'<div class="flex-1 min-w-0"><div class="text-sm font-bold truncate">'+escHtml(n.title)+'</div><div class="text-xs text-slate-500 line-clamp-2">'+escHtml(n.message)+'</div><div class="text-[10px] text-slate-400 mt-1">'+escHtml(n.created_at||'')+'</div></div>'
          +(n.is_read?'':'<button onclick="markNotifRead('+n.id+',event)" class="text-[11px] text-indigo-600 font-bold shrink-0">قراءة</button>')
          +'</div>';
      });
      list.innerHTML=html;
    }).catch(function(){});
}
function escHtml(s){ var d=document.createElement('div'); d.textContent=s||''; return d.innerHTML; }
function markNotifRead(id,e){
  e.stopPropagation();
  var token=document.querySelector('meta[name="csrf-token"]')?.content;
  fetch('/notifications/'+id+'/api-read',{method:'POST',headers:{'X-CSRF-TOKEN':token,'Accept':'application/json'}}).then(function(){ loadNotifications(); });
}
function markAllNotifRead(){
  var token=document.querySelector('meta[name="csrf-token"]')?.content;
  fetch('/notifications/api-mark-all-read',{method:'POST',headers:{'X-CSRF-TOKEN':token,'Accept':'application/json'}}).then(function(){ loadNotifications(); });
}
document.addEventListener('click',function(e){
  var wrap=document.getElementById('notifBellWrap');
  var dd=document.getElementById('notifDropdown');
  if(wrap && dd && !wrap.contains(e.target)) dd.classList.add('hidden');
});
loadNotifications();
setInterval(loadNotifications, 60000);

</script>
<script>
// وضع ليلي — يطبّق قبل الرسم لتجنب الوميض
(function(){try{var t=localStorage.getItem('theme');var prefers=window.matchMedia('(prefers-color-scheme: dark)').matches;if(t==='dark'||(!t&&prefers))document.documentElement.classList.add('dark');}catch(e){}})();

// ---- جرس الاشعارات ----
function toggleNotifDropdown(){
  var dd=document.getElementById('notifDropdown');
  dd.classList.toggle('hidden');
  if(!dd.classList.contains('hidden')) loadNotifications();
}
function loadNotifications(){
  fetch('/notifications/json',{headers:{'Accept':'application/json'}})
    .then(function(r){return r.json();})
    .then(function(data){
      var list=document.getElementById('notifList');
      var countEl=document.getElementById('notifCount');
      var c=data.unread_count||0;
      if(c>0){ countEl.textContent=c>99?'99+':c; countEl.classList.remove('hidden'); countEl.classList.add('flex'); }
      else { countEl.classList.add('hidden'); countEl.classList.remove('flex'); }
      if(!data.notifications || data.notifications.length===0){
        list.innerHTML='<div class="p-6 text-center text-sm text-slate-400">لا توجد اشعارات</div>'; return;
      }
      var html='';
      var typeIcon={info:'fa-circle-info text-sky-500',warning:'fa-triangle-exclamation text-amber-500',success:'fa-circle-check text-emerald-500',error:'fa-circle-exclamation text-red-500'};
      data.notifications.forEach(function(n){
        var ic=typeIcon[n.type]||typeIcon.info;
        var unread=n.is_read? '':'bg-violet-50';
        html+='<div class="flex gap-3 px-4 py-3 border-b border-slate-50 '+unread+' hover:bg-slate-50">'
          +'<i class="fa-solid '+ic+' mt-1"></i>'
          +'<div class="flex-1 min-w-0"><div class="text-sm font-bold truncate">'+escHtml(n.title)+'</div><div class="text-xs text-slate-500 line-clamp-2">'+escHtml(n.message)+'</div><div class="text-[10px] text-slate-400 mt-1">'+escHtml(n.created_at||'')+'</div></div>'
          +(n.is_read?'':'<button onclick="markNotifRead('+n.id+',event)" class="text-[11px] text-indigo-600 font-bold shrink-0">قراءة</button>')
          +'</div>';
      });
      list.innerHTML=html;
    }).catch(function(){});
}
function escHtml(s){ var d=document.createElement('div'); d.textContent=s||''; return d.innerHTML; }
function markNotifRead(id,e){
  e.stopPropagation();
  var token=document.querySelector('meta[name="csrf-token"]')?.content;
  fetch('/notifications/'+id+'/api-read',{method:'POST',headers:{'X-CSRF-TOKEN':token,'Accept':'application/json'}}).then(function(){ loadNotifications(); });
}
function markAllNotifRead(){
  var token=document.querySelector('meta[name="csrf-token"]')?.content;
  fetch('/notifications/api-mark-all-read',{method:'POST',headers:{'X-CSRF-TOKEN':token,'Accept':'application/json'}}).then(function(){ loadNotifications(); });
}
document.addEventListener('click',function(e){
  var wrap=document.getElementById('notifBellWrap');
  var dd=document.getElementById('notifDropdown');
  if(wrap && dd && !wrap.contains(e.target)) dd.classList.add('hidden');
});
loadNotifications();
setInterval(loadNotifications, 60000);

</script>
<style>
*{font-family:'Cairo',sans-serif}
.scrollbar-thin::-webkit-scrollbar{width:5px;height:5px}
.scrollbar-thin::-webkit-scrollbar-thumb{background:#cbd5e1;border-radius:99px}
/* dark mode overrides */
html.dark body{background:#0b1220!important;color:#e2e8f0!important}
html.dark header{background:#0f172a!important;border-color:#1e293b!important}
html.dark header h1{color:#f1f5f9!important}
html.dark main{background:transparent}
html.dark .card-white{background:#1e293b!important;border-color:#334155!important;color:#e2e8f0}
html.dark .bg-white{background:#1e293b!important}
html.dark .border-slate-200{border-color:#334155!important}
html.dark .text-slate-800{color:#e2e8f0!important}
html.dark .text-slate-500,html.dark .text-slate-400{color:#94a3b8!important}
html.dark input,html.dark select,html.dark textarea{background:#0f172a!important;color:#e2e8f0!important;border-color:#334155!important}
html.dark .bg-slate-50{background:#0f172a!important}
html.dark .bg-slate-100{background:#1e293b!important}
html.dark table thead{background:#0f172a!important}
html.dark table tbody tr:hover{background:#1e293b!important}
</style>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/nprogress/0.2.0/nprogress.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/nprogress/0.2.0/nprogress.min.js"></script>
<style>#nprogress .bar{background:#7c3aed;height:3px}#nprogress .peg{box-shadow:0 0 10px #7c3aed,0 0 5px #7c3aed}</style>
<link rel="prefetch" href="/dashboard">

</head>
<body class="bg-[#f1f5f9] text-slate-800 min-h-screen flex dark:bg-[#0b1220]">
<!-- Sidebar -->
<aside id="sidebar" class="fixed inset-y-0 right-0 z-40 w-[270px] bg-[#0f172a] text-white flex flex-col transition-transform duration-300 lg:translate-x-0 translate-x-full">
  <div class="px-6 py-5 flex items-center gap-3 border-b border-white/10">
    @php $logo = center_logo(); $cname = center_name(); @endphp
    @if($logo)
      <img src="{{ $logo }}" class="w-10 h-10 rounded-xl object-contain bg-white p-1">
    @else
      <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-violet-500 to-indigo-600 flex items-center justify-center text-lg font-extrabold">{{ mb_substr($cname,0,1) }}</div>
    @endif
    <div><div class="font-extrabold text-[15px]">{{ $cname }}</div><div class="text-[11px] text-white/50">{{ setting('center_slogan','نظام إدارة السنتر') }}</div></div>
    <button onclick="toggleSidebar()" class="lg:hidden mr-auto text-white/70"><i class="fa-solid fa-xmark text-xl"></i></button>
  </div>
  <nav class="flex-1 overflow-y-auto scrollbar-thin px-3 py-4 space-y-1 text-[13.5px]">
    @php $r=request()->routeIs(...); @endphp
    <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl {{ request()->routeIs('dashboard')?'bg-white text-slate-900 font-bold':'text-white/75 hover:bg-white/10 hover:text-white' }}"><i class="fa-solid fa-chart-pie w-5 text-center"></i> لوحة التحكم</a>
    <div class="pt-3 pb-1 text-[11px] font-bold tracking-widest text-white/30 pr-2">الطلاب والمجموعات</div>
    <a href="{{ route('students.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl {{ request()->routeIs('students.*')?'bg-white text-slate-900 font-bold':'text-white/70 hover:bg-white/10 hover:text-white' }}"><i class="fa-solid fa-user-graduate w-5 text-center"></i> الطلاب</a>
    <a href="{{ route('parents.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl {{ request()->routeIs('parents.*')?'bg-white text-slate-900 font-bold':'text-white/70 hover:bg-white/10 hover:text-white' }}"><i class="fa-solid fa-people-roof w-5 text-center"></i> أولياء الأمور</a>
    <a href="{{ route('groups.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl {{ request()->routeIs('groups.*')?'bg-white text-slate-900 font-bold':'text-white/70 hover:bg-white/10 hover:text-white' }}"><i class="fa-solid fa-users w-5 text-center"></i> المجموعات</a>
    <a href="{{ route('enrollments.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl {{ request()->routeIs('enrollments.*')?'bg-white text-slate-900 font-bold':'text-white/70 hover:bg-white/10 hover:text-white' }}"><i class="fa-solid fa-user-plus w-5 text-center"></i> التسجيلات</a>
    <a href="{{ route('sessions.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl {{ request()->routeIs('sessions.*')||request()->routeIs('sessions.schedule')?'bg-white text-slate-900 font-bold':'text-white/70 hover:bg-white/10 hover:text-white' }}"><i class="fa-solid fa-chalkboard w-5 text-center"></i> الحصص</a>
    <a href="{{ route('sessions.schedule') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl {{ request()->routeIs('sessions.schedule')?'bg-white text-slate-900 font-bold':'text-white/50 hover:bg-white/10 hover:text-white' }} text-[12px] mr-4"><i class="fa-solid fa-table-columns w-5 text-center"></i> الجدول الأسبوعي</a>
    <a href="{{ route('attendances.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl {{ request()->routeIs('attendances.*')||request()->routeIs('attendance.scan')?'bg-white text-slate-900 font-bold':'text-white/70 hover:bg-white/10 hover:text-white' }}"><i class="fa-solid fa-clipboard-check w-5 text-center"></i> الحضور</a>
    <a href="{{ route('attendance.scan') }}" class="flex items-center gap-3 px-3 py-1.5 rounded-xl {{ request()->routeIs('attendance.scan')?'bg-white/15 text-white font-bold':'text-white/50 hover:bg-white/10 hover:text-white' }} text-[12px] mr-4"><i class="fa-solid fa-qrcode w-5 text-center"></i> مسح QR</a>
    <a href="{{ route('attendances.sheet') }}" class="flex items-center gap-3 px-3 py-1.5 rounded-xl {{ request()->routeIs('attendances.sheet')?'bg-white/15 text-white font-bold':'text-white/50 hover:bg-white/10 hover:text-white' }} text-[12px] mr-4"><i class="fa-solid fa-table w-5 text-center"></i> كشف شهري</a>
    <div class="pt-3 pb-1 text-[11px] font-bold tracking-widest text-white/30 pr-2">الدراسة</div>
    <a href="{{ route('subjects.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl {{ request()->routeIs('subjects.*')?'bg-white text-slate-900 font-bold':'text-white/70 hover:bg-white/10 hover:text-white' }}"><i class="fa-solid fa-book w-5 text-center"></i> المواد</a>
    <a href="{{ route('teachers.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl {{ request()->routeIs('teachers.*')?'bg-white text-slate-900 font-bold':'text-white/70 hover:bg-white/10 hover:text-white' }}"><i class="fa-solid fa-person-chalkboard w-5 text-center"></i> المدرسون</a>
    <a href="{{ route('exams.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl {{ request()->routeIs('exams.*')?'bg-white text-slate-900 font-bold':'text-white/70 hover:bg-white/10 hover:text-white' }}"><i class="fa-solid fa-file-pen w-5 text-center"></i> الامتحانات</a>
    <div class="pt-3 pb-1 text-[11px] font-bold tracking-widest text-white/30 pr-2">المالية</div>
    <a href="{{ route('payments.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl {{ request()->routeIs('payments.*')?'bg-white text-slate-900 font-bold':'text-white/70 hover:bg-white/10 hover:text-white' }}"><i class="fa-solid fa-money-bill-wave w-5 text-center"></i> المدفوعات</a>
    <a href="{{ route('expenses.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl {{ request()->routeIs('expenses.*')?'bg-white text-slate-900 font-bold':'text-white/70 hover:bg-white/10 hover:text-white' }}"><i class="fa-solid fa-receipt w-5 text-center"></i> المصروفات</a>
    <a href="{{ route('reports.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl {{ request()->routeIs('reports.*')?'bg-white text-slate-900 font-bold':'text-white/70 hover:bg-white/10 hover:text-white' }}"><i class="fa-solid fa-chart-line w-5 text-center"></i> التقارير</a>
    @can('users.view')
    <div class="pt-3 pb-1 text-[11px] font-bold tracking-widest text-white/30 pr-2">الإدارة</div>
    <a href="{{ route('users.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl {{ request()->routeIs('users.*')?'bg-white text-slate-900 font-bold':'text-white/70 hover:bg-white/10 hover:text-white' }}"><i class="fa-solid fa-shield-halved w-5 text-center"></i> المستخدمون</a>
    @endcan
    @can('settings.view')
    <a href="{{ route('settings.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl {{ request()->routeIs('settings.*')?'bg-white text-slate-900 font-bold':'text-white/70 hover:bg-white/10 hover:text-white' }}"><i class="fa-solid fa-gear w-5 text-center"></i> إعدادات السنتر</a>
    @endcan
  </nav>
  <div class="p-3 border-t border-white/10">
    <div class="flex items-center gap-3 px-2 py-2">
      <div class="w-9 h-9 rounded-full bg-white/15 flex items-center justify-center font-bold text-sm">{{ mb_substr(auth()->user()->name??'م',0,1) }}</div>
      <div class="flex-1 min-w-0"><div class="text-sm font-bold truncate">{{ auth()->user()->name }}</div><div class="text-[11px] text-white/50 truncate">{{ auth()->user()->email }}</div></div>
    </div>
    <form method="POST" action="{{ route('logout') }}">@csrf<button class="w-full mt-2 flex items-center justify-center gap-2 py-2 rounded-xl bg-white/10 hover:bg-red-500 text-sm font-semibold transition"> <i class="fa-solid fa-right-from-bracket"></i> تسجيل خروج</button></form>
  </div>
</aside>
<!-- Overlay -->
<div id="overlay" onclick="toggleSidebar()" class="fixed inset-0 bg-black/40 z-30 hidden lg:hidden"></div>
<!-- Main -->
<div class="flex-1 lg:mr-[270px] min-w-0">
  <!-- Topbar -->
  <header class="sticky top-0 z-20 bg-white border-b border-slate-200 flex items-center gap-3 px-4 lg:px-6 h-[60px]">
    <button onclick="toggleSidebar()" class="lg:hidden w-9 h-9 rounded-xl bg-slate-100 flex items-center justify-center"><i class="fa-solid fa-bars"></i></button>
    <h1 class="font-extrabold text-[16px] lg:text-[18px] text-slate-800">@yield('page_title','لوحة التحكم')</h1>

    <!-- بحث سريع Autocomplete -->
    <div class="flex-1 max-w-[380px] mr-4 relative hidden md:block">
      <div class="relative">
        <i class="fa-solid fa-magnifying-glass absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
        <input id="globalSearch" type="text" placeholder="بحث سريع: طالب، ولي أمر، مجموعة..." autocomplete="off"
          class="w-full pr-9 pl-3 py-2 rounded-xl border border-slate-200 bg-slate-50 text-sm focus:outline-none focus:ring-2 focus:ring-violet-400 focus:bg-white transition">
      </div>
      <div id="searchResults" class="hidden absolute top-full mt-2 w-full bg-white border border-slate-200 rounded-2xl shadow-xl overflow-hidden z-50 max-h-[380px] overflow-y-auto"></div>
    </div>

    <div class="mr-auto flex items-center gap-2">
      {{-- جرس الإشعارات --}}
      <div class="relative" id="notifBellWrap">
        <button onclick="toggleNotifDropdown()" class="relative w-9 h-9 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 flex items-center justify-center transition">
          <i class="fa-solid fa-bell text-slate-600 dark:text-slate-300"></i>
          <span id="notifCount" class="hidden absolute -top-1 -right-1 bg-red-500 text-white text-[10px] font-bold min-w-[18px] h-[18px] flex items-center justify-center rounded-full px-1">0</span>
        </button>
        <div id="notifDropdown" class="hidden absolute left-0 mt-2 w-[360px] max-w-[90vw] bg-white dark:bg-slate-800 rounded-2xl shadow-2xl border border-slate-200 dark:border-slate-700 overflow-hidden z-50">
          <div class="flex items-center justify-between px-4 py-3 border-b border-slate-100 dark:border-slate-700">
            <span class="font-bold text-sm">الإشعارات</span>
            <button onclick="markAllNotifRead()" class="text-xs text-indigo-600 hover:text-indigo-800 font-semibold">تعليم الكل كمقروء</button>
          </div>
          <div id="notifList" class="max-h-[340px] overflow-y-auto scrollbar-thin">
            <div class="p-6 text-center text-sm text-slate-400">جاري التحميل...</div>
          </div>
          <a href="{{ route('notifications.index') }}" class="block text-center text-xs font-bold text-indigo-600 hover:bg-slate-50 dark:hover:bg-slate-700 py-3 border-t border-slate-100 dark:border-slate-700">عرض كل الإشعارات</a>
        </div>
      </div>
      <button id="darkToggle" onclick="toggleDark()" class="w-9 h-9 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 flex items-center justify-center transition" title="وضع ليلي">
        <i id="darkIcon" class="fa-solid fa-moon text-slate-600 dark:text-yellow-300"></i>
      </button>
      <span class="hidden sm:inline text-xs bg-slate-100 px-3 py-1.5 rounded-full font-semibold">{{ auth()->user()->role==='admin'?'مدير':(auth()->user()->role==='receptionist'?'استقبال':auth()->user()->role) }}</span>
      <span class="text-xs text-slate-400">{{ now('Africa/Cairo')->format('Y/m/d') }}</span>
    </div>
  </header>
  <main class="p-4 lg:p-6">
    @if(session('success'))<div class="mb-4 bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl text-sm flex items-center gap-2"><i class="fa-solid fa-circle-check"></i> {{ session('success') }}</div>@endif
    @if($errors->any())<div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm"><ul class="list-disc pr-5 space-y-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif
    @yield('content')
  </main>
</div>
<script>
function toggleSidebar(){document.getElementById('sidebar').classList.toggle('translate-x-full');document.getElementById('overlay').classList.toggle('hidden')}
// ── وضع ليلي ──
function toggleDark(){
  var isDark=document.documentElement.classList.toggle('dark');
  try{localStorage.setItem('theme',isDark?'dark':'light');}catch(e){}
  updateDarkIcon(isDark);
}
function updateDarkIcon(isDark){
  var ic=document.getElementById('darkIcon');
  if(!ic) return;
  ic.className = isDark ? 'fa-solid fa-sun text-yellow-400' : 'fa-solid fa-moon text-slate-600';
}
(function(){ updateDarkIcon(document.documentElement.classList.contains('dark')); })();

// ── بحث سريع Autocomplete ──
(function(){
  var input=document.getElementById('globalSearch');
  var box=document.getElementById('searchResults');
  if(!input||!box) return;
  var timer=null, lastQ='';
  input.addEventListener('input', function(){
    var q=this.value.trim();
    lastQ=q;
    clearTimeout(timer);
    if(q.length<2){ box.classList.add('hidden'); box.innerHTML=''; return; }
    timer=setTimeout(function(){
      fetch('/api/search?q='+encodeURIComponent(q),{headers:{'Accept':'application/json'}})
        .then(function(r){return r.json();})
        .then(function(data){
          if(input.value.trim()!==lastQ) return;
          renderResults(data);
        }).catch(function(){ box.classList.add('hidden'); });
    },280);
  });
  input.addEventListener('keydown',function(e){
    if(e.key==='Escape'){box.classList.add('hidden');}
  });
  document.addEventListener('click',function(e){
    if(!input.contains(e.target) && !box.contains(e.target)) box.classList.add('hidden');
  });
  function esc(s){ var d=document.createElement('div'); d.textContent=s; return d.innerHTML; }
  function renderResults(data){
    var students=data.students||[], groups=data.groups||[], parents=data.parents||[];
    var total=students.length+groups.length+parents.length;
    if(total===0){
      box.innerHTML='<div class="p-4 text-center text-sm text-slate-400">لا توجد نتائج</div>';
      box.classList.remove('hidden'); return;
    }
    var html='';
    if(students.length){
      html+='<div class="px-3 pt-3 pb-1 text-[11px] font-bold text-slate-400 tracking-widest">الطلاب</div>';
      students.forEach(function(s){
        html+='<a href="'+esc(s.url)+'" class="flex items-center gap-3 px-3 py-2.5 hover:bg-violet-50 border-b border-slate-50 last:border-0">'
          +'<span class="w-8 h-8 rounded-full bg-violet-100 text-violet-700 flex items-center justify-center text-xs font-bold"><i class="fa-solid fa-user-graduate"></i></span>'
          +'<span class="flex-1 min-w-0"><span class="block text-sm font-bold truncate">'+esc(s.name)+'</span><span class="block text-xs text-slate-500">'+esc(s.code||'')+' — '+esc(s.grade||'')+'</span></span>'
          +'<i class="fa-solid fa-chevron-left text-slate-300 text-xs"></i></a>';
      });
    }
    if(groups.length){
      html+='<div class="px-3 pt-3 pb-1 text-[11px] font-bold text-slate-400 tracking-widest">المجموعات</div>';
      groups.forEach(function(g){
        html+='<a href="'+esc(g.url)+'" class="flex items-center gap-3 px-3 py-2.5 hover:bg-emerald-50 border-b border-slate-50">'
          +'<span class="w-8 h-8 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center text-xs"><i class="fa-solid fa-users"></i></span>'
          +'<span class="flex-1 min-w-0"><span class="block text-sm font-bold truncate">'+esc(g.name)+'</span><span class="block text-xs text-slate-500">'+esc(g.subject||'')+'</span></span>'
          +'<i class="fa-solid fa-chevron-left text-slate-300 text-xs"></i></a>';
      });
    }
    if(parents.length){
      html+='<div class="px-3 pt-3 pb-1 text-[11px] font-bold text-slate-400 tracking-widest">أولياء الأمور</div>';
      parents.forEach(function(p){
        html+='<a href="'+esc(p.url)+'" class="flex items-center gap-3 px-3 py-2.5 hover:bg-amber-50 border-b border-slate-50">'
          +'<span class="w-8 h-8 rounded-full bg-amber-100 text-amber-700 flex items-center justify-center text-xs"><i class="fa-solid fa-people-roof"></i></span>'
          +'<span class="flex-1 min-w-0"><span class="block text-sm font-bold truncate">'+esc(p.name)+'</span><span class="block text-xs text-slate-500">'+esc(p.phone||'')+'</span></span>'
          +'<i class="fa-solid fa-chevron-left text-slate-300 text-xs"></i></a>';
      });
    }
    box.innerHTML=html;
    box.classList.remove('hidden');
  }
})();

// ---- جرس الاشعارات ----
function toggleNotifDropdown(){
  var dd=document.getElementById('notifDropdown');
  dd.classList.toggle('hidden');
  if(!dd.classList.contains('hidden')) loadNotifications();
}
function loadNotifications(){
  fetch('/notifications/json',{headers:{'Accept':'application/json'}})
    .then(function(r){return r.json();})
    .then(function(data){
      var list=document.getElementById('notifList');
      var countEl=document.getElementById('notifCount');
      var c=data.unread_count||0;
      if(c>0){ countEl.textContent=c>99?'99+':c; countEl.classList.remove('hidden'); countEl.classList.add('flex'); }
      else { countEl.classList.add('hidden'); countEl.classList.remove('flex'); }
      if(!data.notifications || data.notifications.length===0){
        list.innerHTML='<div class="p-6 text-center text-sm text-slate-400">لا توجد اشعارات</div>'; return;
      }
      var html='';
      var typeIcon={info:'fa-circle-info text-sky-500',warning:'fa-triangle-exclamation text-amber-500',success:'fa-circle-check text-emerald-500',error:'fa-circle-exclamation text-red-500'};
      data.notifications.forEach(function(n){
        var ic=typeIcon[n.type]||typeIcon.info;
        var unread=n.is_read? '':'bg-violet-50';
        html+='<div class="flex gap-3 px-4 py-3 border-b border-slate-50 '+unread+' hover:bg-slate-50">'
          +'<i class="fa-solid '+ic+' mt-1"></i>'
          +'<div class="flex-1 min-w-0"><div class="text-sm font-bold truncate">'+escHtml(n.title)+'</div><div class="text-xs text-slate-500 line-clamp-2">'+escHtml(n.message)+'</div><div class="text-[10px] text-slate-400 mt-1">'+escHtml(n.created_at||'')+'</div></div>'
          +(n.is_read?'':'<button onclick="markNotifRead('+n.id+',event)" class="text-[11px] text-indigo-600 font-bold shrink-0">قراءة</button>')
          +'</div>';
      });
      list.innerHTML=html;
    }).catch(function(){});
}
function escHtml(s){ var d=document.createElement('div'); d.textContent=s||''; return d.innerHTML; }
function markNotifRead(id,e){
  e.stopPropagation();
  var token=document.querySelector('meta[name="csrf-token"]')?.content;
  fetch('/notifications/'+id+'/api-read',{method:'POST',headers:{'X-CSRF-TOKEN':token,'Accept':'application/json'}}).then(function(){ loadNotifications(); });
}
function markAllNotifRead(){
  var token=document.querySelector('meta[name="csrf-token"]')?.content;
  fetch('/notifications/api-mark-all-read',{method:'POST',headers:{'X-CSRF-TOKEN':token,'Accept':'application/json'}}).then(function(){ loadNotifications(); });
}
document.addEventListener('click',function(e){
  var wrap=document.getElementById('notifBellWrap');
  var dd=document.getElementById('notifDropdown');
  if(wrap && dd && !wrap.contains(e.target)) dd.classList.add('hidden');
});
loadNotifications();
setInterval(loadNotifications, 60000);

</script>
@stack('scripts')

<script>
// تسريع التنقل: prefetch عند مرور الماوس + شريط تحميل + cache
NProgress.configure({showSpinner:false, trickleSpeed:100});
let prefetchCache = new Set();
document.addEventListener('DOMContentLoaded', ()=>{
  // prefetch عند hover على أي رابط داخلي
  document.querySelectorAll('a[href^="/"]').forEach(a=>{
    a.addEventListener('mouseenter', ()=>{
      let href = a.getAttribute('href');
      if(href && !prefetchCache.has(href) && !href.includes('logout') && !href.includes('delete')){
        prefetchCache.add(href);
        let l=document.createElement('link'); l.rel='prefetch'; l.href=href; document.head.appendChild(l);
      }
    }, {once:true});
    // شريط تحميل عند النقر
    a.addEventListener('click', (e)=>{
      if(a.target==='_blank' || e.ctrlKey || e.metaKey) return;
      if(a.href && a.href.startsWith(window.location.origin)) NProgress.start();
    });
  });
  window.addEventListener('pageshow', ()=> NProgress.done());
  window.addEventListener('load', ()=> NProgress.done());
  // prefetch أهم الصفحات مسبقاً بعد ثانيتين
  setTimeout(()=>{
    ['/students','/groups','/payments','/attendances'].forEach(u=>{
      if(!prefetchCache.has(u)){ prefetchCache.add(u); let l=document.createElement('link'); l.rel='prefetch'; l.href=u; document.head.appendChild(l); }
    });
  }, 2000);
});
document.addEventListener('readystatechange', ()=>{ if(document.readyState==='interactive') NProgress.start(); if(document.readyState==='complete') NProgress.done(); });
</script>

</body>
</html>
