<?php
namespace App\Http\Controllers;
use App\Models\Attendance;
use App\Models\Session;
use App\Models\Group;
use App\Http\Requests\AttendanceRequest;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AttendanceExport;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        if ($request->input('export') === 'excel') {
            return Excel::download(new AttendanceExport($request), 'attendance-'.now()->format('Y-m-d').'.xlsx');
        }
        $q=Attendance::with(['student','session.group']);
        if($sid=$request->input('session_id')) $q->where('session_id',$sid);
        if($status=$request->input('status')) $q->where('status',$status);
        if($date=$request->input('date')) $q->whereHas('session',fn($qq)=>$qq->whereDate('session_date',$date));
        $attendances=$q->latest()->paginate(30)->withQueryString();
        return view('attendances.index',compact('attendances'));
    }

    // نموذج تسجيل حضور جماعي لحصة
    public function create(Request $request)
    {
        $groups=Group::with('subject')->get();
        $session=null; $students=[];
        if($sid=$request->input('session_id')){
            $session=Session::with(['group.enrollments.student','attendances'])->find($sid);
            if($session){
                // if teacher, restrict to own groups
                $this->authorizeTeacherGroup($session->group_id);
                $students=$session->group->enrollments()->where('status','active')->with('student')->get()->pluck('student');
            }
        }
        $sessions=Session::with('group.subject')->orderByDesc('session_date')->take(50)->get();
        // filter sessions for teacher
        $user = auth()->user();
        if ($user && $user->role === 'teacher') {
            $teacher = \App\Models\Teacher::where('email', $user->email)->first();
            if ($teacher) {
                $groupIds = $teacher->groups()->pluck('id');
                $sessions = $sessions->whereIn('group_id', $groupIds->toArray());
            }
        }
        return view('attendances.create',compact('groups','sessions','session','students'));
    }

    public function store(AttendanceRequest $request)
    {
        $this->authorizeTeacherGroup($request->input('session_id') ? Session::find($request->input('session_id'))->group_id ?? null : null);
        foreach($request->input('attendances') as $row){
            Attendance::updateOrCreate(
                ['session_id'=>$request->input('session_id'),'student_id'=>$row['student_id']],
                ['status'=>$row['status'],'notes'=>$row['notes']??null]
            );
        }
        return redirect()->route('attendances.index')->with('success','تم حفظ الحضور.');
    }

    public function destroy(Attendance $attendance){
        $this->authorizeTeacherGroup($attendance->session->group_id ?? null);
        $attendance->delete(); return back()->with('success','تم الحذف.'); }

    /**
     * صفحة مسح QR لتسجيل الحضور
     */
    public function scan(Request $request)
    {
        $groups = Group::with('subject')->get();
        $sessions = Session::with('group.subject')->orderByDesc('session_date')->take(50)->get();

        // للمدرس: ترشيح الجلسات لمجموعاته فقط
        $user = auth()->user();
        if ($user && $user->role === 'teacher') {
            $teacher = \App\Models\Teacher::where('email', $user->email)->first();
            if ($teacher) {
                $groupIds = $teacher->groups()->pluck('id')->toArray();
                $sessions = $sessions->whereIn('group_id', $groupIds);
            }
        }

        // إذا كان هناك كود موجود أو QR ممسوح
        $student = null;
        $code = $request->query('code', $request->query('student_code'));

        if ($code) {
            $student = \App\Models\Student::where('student_code', $code)
                ->orWhere('code', $code)
                ->first();
        }

        return view('attendances.scan', compact('groups', 'sessions', 'student', 'code'));
    }

    /**
     * حفظ التسجيل من مسح QR
     */
    public function scanStore(Request $request)
    {
        $validated = $request->validate([
            'session_id' => 'required|exists:sessions,id',
            'code' => 'required|string',
            'status' => 'required|in:present,absent,late,excused',
        ]);

        // البحث عن الطالب بالكود
        $student = \App\Models\Student::where('code', $validated['code'])->first();

        if (!$student) {
            return response()->json(['success' => false, 'message' => 'الطالب غير موجود.']);
        }

        $session = Session::findOrFail($validated['session_id']);
        $this->authorizeTeacherGroup($session->group_id);

        Attendance::updateOrCreate(
            ['session_id' => $validated['session_id'], 'student_id' => $student->id],
            ['status' => $validated['status'], 'notes' => $request->input('notes')]
        );

        return back()->with('success', 'تم تسجيل حضور ' . $student->name . ' بنجاح.');
    }

    /**
     * صفحة الكشف الشهري
     */
    public function sheet(Request $request)
    {
        $month = (int) $request->input('month', now()->month);
        $year = (int) $request->input('year', now()->year);
        $groupId = $request->input('group_id');
        $groups = Group::with('subject')->get();

        // جلب كل الجلسات في الشهر/السنة المحددة
        $sessions = Session::whereYear('date', $year)
            ->whereMonth('date', $month);
        if ($groupId) {
            $sessions->where('group_id', $groupId);
        }
        $sessions = $sessions->with('group.subject')->get();

        // بناء مصفوفة الأيام
        $daysInMonth = \Carbon\Carbon::create($year, $month, 1)->daysInMonth;
        $days = range(1, $daysInMonth);

        // بناء الـ matrix: student_id => day => status
        $matrix = [];
        $sessionsByDay = [];
        foreach ($sessions as $s) {
            $day = (int) \Carbon\Carbon::parse($s->date)->format('j');
            $sessionsByDay[$day] = true;
            foreach ($s->attendances as $att) {
                $matrix[$att->student_id][$day] = $att->status;
            }
        }

        // جلب الطلاب حسب الفلاتر
        $studentQuery = \App\Models\Student::with('attendances.session');
        if ($groupId) {
            $studentQuery->whereHas('enrollments', function ($q) use ($groupId) {
                $q->where('group_id', $groupId)->where('status', 'active');
            });
        } else {
            $studentQuery->whereHas('enrollments', function ($q) {
                $q->where('status', 'active');
            });
        }
        $students = $studentQuery->get();

        return view('attendances.sheet', compact(
            'month', 'year', 'days', 'matrix', 'students', 'sessions', 'sessionsByDay', 'groups', 'groupId'
        ));
    }

    private function authorizeTeacherGroup($groupId): void
    {
        $user = auth()->user();
        if ($user && $user->role === 'teacher' && $groupId) {
            $teacher = \App\Models\Teacher::where('email', $user->email)->first();
            if (!$teacher) abort(403, 'حساب المدرس غير مرتبط.');
            if (!$teacher->groups()->where('id', $groupId)->exists()) {
                abort(403, 'لا تملك صلاحية التعديل إلا على مجموعاتك.');
            }
        }
    }
}
