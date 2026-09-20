<?php
namespace App\Http\Controllers;
use App\Models\Subject;
use App\Http\Requests\SubjectRequest;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function index(Request $request)
    {
        $q=Subject::query();
        if($s=$request->input('q')) $q->where('name','like',"%{$s}%");
        $subjects=$q->latest()->paginate(20)->withQueryString();
        return view('subjects.index',compact('subjects'));
    }
    public function create(){ return view('subjects.create'); }
    public function store(SubjectRequest $request){ Subject::create($request->validated()); return redirect()->route('subjects.index')->with('success','تم إضافة المادة.'); }
    public function show(Subject $subject){ return view('subjects.show',compact('subject')); }
    public function edit(Subject $subject){ return view('subjects.edit',compact('subject')); }
    public function update(SubjectRequest $request, Subject $subject){ $subject->update($request->validated()); return redirect()->route('subjects.index')->with('success','تم التحديث.'); }
    public function destroy(Subject $subject){ $subject->delete(); return back()->with('success','تم الحذف.'); }
}
