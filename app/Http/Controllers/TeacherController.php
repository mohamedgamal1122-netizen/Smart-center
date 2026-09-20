<?php
namespace App\Http\Controllers;
use App\Models\Teacher;
use App\Models\Subject;
use App\Http\Requests\TeacherRequest;
use Illuminate\Http\Request;

class TeacherController extends Controller
{
    public function index(Request $request)
    {
        $q=Teacher::withCount('groups');
        if($s=$request->input('q')) $q->where(function($qq) use($s){ $qq->where('name','like',"%{$s}%")->orWhere('specialization','like',"%{$s}%"); });
        $teachers=$q->latest()->paginate(20)->withQueryString();
        return view('teachers.index',compact('teachers'));
    }
    public function create(){ return view('teachers.create'); }
    public function store(TeacherRequest $request){ Teacher::create($request->validated()); return redirect()->route('teachers.index')->with('success','تم إضافة المدرس.'); }
    public function show(Teacher $teacher){ $teacher->load(['groups.subject','groups.enrollments']); return view('teachers.show',compact('teacher')); }
    public function edit(Teacher $teacher){ return view('teachers.edit',compact('teacher')); }
    public function update(TeacherRequest $request, Teacher $teacher){ $teacher->update($request->validated()); return redirect()->route('teachers.index')->with('success','تم التحديث.'); }
    public function destroy(Teacher $teacher){ $teacher->delete(); return back()->with('success','تم الحذف.'); }
}
