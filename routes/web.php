<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\ParentController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\ExamResultController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\SettingController;

// Auth routes (Laravel Breeze-style simple)
Route::get('/login', function(){ return view('auth.login'); })->name('login')->middleware('guest');
Route::post('/login', function(\Illuminate\Http\Request $r){
    $r->validate(['email'=>'required|email','password'=>'required'],['email.required'=>'البريد مطلوب.','password.required'=>'كلمة المرور مطلوبة.']);
    if(auth()->attempt($r->only('email','password'), $r->boolean('remember'))){
        $r->session()->regenerate();
        if(!auth()->user()->is_active){ auth()->logout(); return back()->withErrors(['email'=>'حسابك موقوف.']); }
        return redirect()->intended(route('dashboard'));
    }
    return back()->withErrors(['email'=>'بيانات الدخول غير صحيحة.']);
})->middleware('guest')->name('login.post');

Route::post('/logout', function(\Illuminate\Http\Request $r){
    auth()->logout(); $r->session()->invalidate(); $r->session()->regenerateToken();
    return redirect()->route('login');
})->middleware('auth')->name('logout');

Route::get('/', fn()=> redirect()->route('dashboard'));

Route::middleware(['auth','active'])->group(function(){

    Route::get('/dashboard', [DashboardController::class,'index'])->name('dashboard');

    // الطلاب
    Route::get('students/trashed', [StudentController::class,'trashed'])->name('students.trashed')->middleware('role:admin');
    Route::post('students/{id}/restore', [StudentController::class,'restore'])->name('students.restore')->middleware('role:admin');
    Route::delete('students/{id}/force', [StudentController::class,'forceDelete'])->name('students.forceDelete')->middleware('role:admin');
    Route::get('students/{student}/qr', [StudentController::class,'qr'])->name('students.qr');
    Route::resource('students', StudentController::class);

    Route::get('parents/{id}/report', [ParentController::class,'report'])->name('parents.report');
    Route::resource('parents', ParentController::class)->parameters(['parents'=>'id']);
    Route::resource('teachers', TeacherController::class);
    Route::resource('subjects', SubjectController::class);
    Route::resource('groups', GroupController::class);
    Route::resource('enrollments', EnrollmentController::class)->only(['index','create','store','destroy']);
    Route::delete('enrollments/{enrollment}/force', [EnrollmentController::class,'forceDestroy'])->name('enrollments.forceDestroy')->middleware('role:admin');

    Route::post('sessions/reorder', [SessionController::class,'reorder'])->name('sessions.reorder');
    Route::get('sessions/schedule', [SessionController::class,'schedule'])->name('sessions.schedule');
    Route::resource('sessions', SessionController::class);
    // Attendance: custom routes before resource
    Route::get('attendance/scan', [AttendanceController::class,'scan'])->name('attendance.scan');
    Route::post('attendance/scan', [AttendanceController::class,'scanStore'])->name('attendance.scanStore');
    Route::get('attendances/sheet', [AttendanceController::class,'sheet'])->name('attendances.sheet');
    Route::resource('attendances', AttendanceController::class)->only(['index','create','store','destroy']);

    Route::get('exams/{exam}/certificate/{student}', [ExamController::class,'certificate'])->name('exams.certificate');
    Route::resource('exams', ExamController::class);
    Route::post('exams/{exam}/results', [ExamResultController::class,'store'])->name('exam-results.store');
    Route::patch('exam-results/{examResult}', [ExamResultController::class,'update'])->name('exam-results.update');
    Route::delete('exam-results/{examResult}', [ExamResultController::class,'destroy'])->name('exam-results.destroy');

    Route::get('payments/{payment}/receipt', [PaymentController::class,'receipt'])->name('payments.receipt');
    Route::get('payments/{payment}/receipt-html', [PaymentController::class,'receiptHtml'])->name('payments.receiptHtml');
    Route::post('payments/{payment}/cancel', [PaymentController::class,'cancel'])->name('payments.cancel')->middleware('role:admin|accountant');
    Route::resource('payments', PaymentController::class)->middleware('finance.write');

    Route::resource('expenses', ExpenseController::class)->middleware('finance.write');

    Route::prefix('reports')->name('reports.')->group(function(){
        Route::get('/', [ReportController::class,'index'])->name('index');
        Route::get('/revenue', [ReportController::class,'revenue'])->name('revenue');
        Route::get('/expenses', [ReportController::class,'expenses'])->name('expenses');
        Route::get('/profit', [ReportController::class,'profit'])->name('profit');
        Route::get('/attendance', [ReportController::class,'attendanceReport'])->name('attendance');
        Route::get('/students', [ReportController::class,'studentsReport'])->name('students');
    });

    // إدارة المستخدمين - admin فقط
    Route::middleware('role:admin')->group(function(){
        Route::post('users/{user}/toggle-active', [UserController::class,'toggleActive'])->name('users.toggleActive');
        Route::get('users/{user}/permissions', [UserController::class,'permissions'])->name('users.permissions');
        Route::put('users/{user}/permissions', [UserController::class,'updatePermissions'])->name('users.permissions.update');
        Route::resource('users', UserController::class);
        Route::get('settings', [SettingController::class,'index'])->name('settings.index');
        Route::put('settings', [SettingController::class,'update'])->name('settings.update');
    });

    // البحث السريع + api/search
    Route::get('/api/search', [SearchController::class,'index'])->name('api.search');

    // الإشعارات
    Route::get('notifications', [NotificationController::class,'index'])->name('notifications.index');
    Route::get('notifications/json', [NotificationController::class,'json'])->name('notifications.json');
    Route::post('notifications/{notification}/read', [NotificationController::class,'markRead'])->name('notifications.markRead');
    Route::post('notifications/mark-all-read', [NotificationController::class,'markAllRead'])->name('notifications.markAllRead');
    Route::delete('notifications/{notification}', [NotificationController::class,'destroy'])->name('notifications.destroy');
    Route::post('notifications/{notification}/api-read', [NotificationController::class,'apiMarkRead'])->name('notifications.apiMarkRead');
    Route::post('notifications/api-mark-all-read', [NotificationController::class,'apiMarkAllRead'])->name('notifications.apiMarkAllRead');

    // نظام التواصل والتذاكر والمهام والعملاء المحتمين
    Route::get('communications', [\App\Http\Controllers\CommunicationController::class, 'communicationsIndex'])->name('communications.index');
    Route::get('communications/create', [\App\Http\Controllers\CommunicationController::class, 'createCommunication'])->name('communications.create');
    Route::post('communications', [\App\Http\Controllers\CommunicationController::class, 'storeCommunication'])->name('communications.store');

    Route::get('tickets', [\App\Http\Controllers\CommunicationController::class, 'ticketsIndex'])->name('tickets.index');
    Route::get('tickets/create', [\App\Http\Controllers\CommunicationController::class, 'createTicket'])->name('tickets.create');
    Route::post('tickets', [\App\Http\Controllers\CommunicationController::class, 'storeTicket'])->name('tickets.store');
    Route::get('tickets/{ticket}', [\App\Http\Controllers\CommunicationController::class, 'ticketShow'])->name('tickets.show');
    Route::post('tickets/{ticket}/comments', [\App\Http\Controllers\CommunicationController::class, 'addTicketComment'])->name('tickets.comments.store');

    Route::get('tasks', [\App\Http\Controllers\CommunicationController::class, 'tasksIndex'])->name('tasks.index');
    Route::get('tasks/create', [\App\Http\Controllers\CommunicationController::class, 'createTask'])->name('tasks.create');
    Route::post('tasks', [\App\Http\Controllers\CommunicationController::class, 'storeTask'])->name('tasks.store');
    Route::patch('tasks/{task}/status', [\App\Http\Controllers\CommunicationController::class, 'updateTaskStatus'])->name('tasks.status.update');

    Route::get('leads', [\App\Http\Controllers\CommunicationController::class, 'leadsIndex'])->name('leads.index');
    Route::get('leads/create', [\App\Http\Controllers\CommunicationController::class, 'createLead'])->name('leads.create');
    Route::post('leads', [\App\Http\Controllers\CommunicationController::class, 'storeLead'])->name('leads.store');
    Route::post('leads/{lead}/convert', [\App\Http\Controllers\CommunicationController::class, 'convertLeadToStudent'])->name('leads.convert');
});
