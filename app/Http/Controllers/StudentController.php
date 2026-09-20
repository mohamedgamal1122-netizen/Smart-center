<?php
namespace App\Http\Controllers;
use App\Models\Student;
use App\Http\Requests\StudentRequest;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\StudentsExport;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        if ($request->input('export') === 'excel') {
            return Excel::download(new StudentsExport($request), 'students-'.now()->format('Y-m-d').'.xlsx');
        }
        $q = Student::query();
        if ($s = $request->input('q')) {
            $q->where(function($qq) use($s){
                $qq->where('name','like',"%{$s}%")->orWhere('code','like',"%{$s}%")->orWhere('phone','like',"%{$s}%");
            });
        }
        if ($status = $request->input('status')) $q->where('status',$status);
        if ($grade = $request->input('grade')) $q->where('grade',$grade);
        $students = $q->latest()->paginate(20)->withQueryString();
        return view('students.index', compact('students'));
    }

    public function create()
    {
        return view('students.create');
    }

    public function store(StudentRequest $request)
    {
        $this->authorizeFinanceWrite();
        $student = Student::create($request->validated());
        return redirect()->route('students.show',$student)->with('success','تم إضافة الطالب بنجاح.');
    }

    public function show(Student $student)
    {
        $student->load(['enrollments.group.subject','enrollments.group.teacher','parents','payments','attendances.session']);
        $qrData = $student->code;
        $qrUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=240x240&data='.urlencode($qrData);
        return view('students.show', compact('student','qrUrl','qrData'));
    }

    public function qr(Student $student)
    {
        $qrData = $student->code;
        $qrUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=400x400&data='.urlencode($qrData);
        return view('students.qr', compact('student','qrUrl','qrData'));
    }

    public function edit(Student $student)
    {
        $this->authorizeTeacherGroupAccess($student);
        return view('students.edit', compact('student'));
    }

    public function update(StudentRequest $request, Student $student)
    {
        $this->authorizeTeacherGroupAccess($student);
        $student->update($request->validated());
        return redirect()->route('students.show',$student)->with('success','تم تحديث بيانات الطالب.');
    }

    public function destroy(Student $student)
    {
        $this->authorizeFinanceWrite();
        $student->delete();
        return redirect()->route('students.index')->with('success','تم حذف الطالب (حذف مؤقت).');
    }

    public function trashed(Request $request)
    {
        $students = Student::onlyTrashed()->latest()->paginate(20);
        return view('students.trashed', compact('students'));
    }

    public function restore($id)
    {
        $this->authorizeFinanceWrite();
        Student::onlyTrashed()->findOrFail($id)->restore();
        return back()->with('success','تم استرجاع الطالب.');
    }

    public function forceDelete($id)
    {
        $this->authorizeFinanceWrite();
        Student::onlyTrashed()->findOrFail($id)->forceDelete();
        return back()->with('success','تم الحذف النهائي.');
    }

    private function authorizeFinanceWrite(): void
    {
        $user = auth()->user();
        if ($user && $user->role === 'receptionist') {
            abort(403, 'الاستقبال لديه صلاحية العرض فقط — التعديل غير مسموح.');
        }
    }

    private function authorizeTeacherGroupAccess(Student $student): void
    {
        $user = auth()->user();
        if ($user && $user->role === 'teacher') {
            // المدرس لا يعدل إلا طلاب مجموعاته
            $teacher = \App\Models\Teacher::where('email', $user->email)->first();
            if (!$teacher) abort(403, 'حساب المدرس غير مرتبط بسجل مدرس.');
            $groupIds = $teacher->groups()->pluck('id');
            $hasAccess = $student->enrollments()->whereIn('group_id', $groupIds)->exists();
            if (!$hasAccess) abort(403, 'لا تملك صلاحية تعديل هذا الطالب — ليس في مجموعاتك.');
        }
        // receptionist also read-only for write ops already handled
        $this->authorizeFinanceWrite();
    }
}
