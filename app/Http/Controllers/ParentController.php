<?php
namespace App\Http\Controllers;
use App\Models\ParentModel;
use App\Models\Parent as ParentAlias;
use App\Models\Student;
use App\Http\Requests\ParentRequest;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

// نستخدم ParentModel لتجنب تضارب كلمة parent المحجوزة
class ParentController extends Controller
{
    private function model(){ return class_exists(ParentModel::class) ? ParentModel::class : ParentAlias::class; }

    public function index(Request $request)
    {
        $M = $this->model();
        $q = $M::query();
        if($s=$request->input('q')) $q->where('name','like',"%{$s}%")->orWhere('phone_primary','like',"%{$s}%");
        $parents = $q->latest()->paginate(20)->withQueryString();
        return view('parents.index', compact('parents'));
    }
    public function create(){ $students=Student::select('id','name','code')->get(); return view('parents.create',compact('students')); }
    public function store(ParentRequest $request)
    {
        $M=$this->model();
        $parent=$M::create($request->validated());
        if($request->filled('student_ids')) $parent->students()->sync($request->input('student_ids'));
        return redirect()->route('parents.index')->with('success','تم إضافة ولي الأمر.');
    }
    public function show($id){ $M=$this->model(); $parent=$M::with('students')->findOrFail($id); return view('parents.show',compact('parent')); }
    public function edit($id){ $M=$this->model(); $parent=$M::with('students')->findOrFail($id); $students=Student::select('id','name','code')->get(); return view('parents.edit',compact('parent','students')); }
    public function update(ParentRequest $request,$id){
        $M=$this->model(); $parent=$M::findOrFail($id);
        $parent->update($request->validated());
        if($request->has('student_ids')) $parent->students()->sync($request->input('student_ids',[]));
        return redirect()->route('parents.index')->with('success','تم التحديث.');
    }
    public function destroy($id){ $M=$this->model(); $M::findOrFail($id)->delete(); return back()->with('success','تم الحذف.'); }

    // تقرير ولي الأمر PDF — حضور/درجات/مدفوعات كل الأبناء
    public function report($id)
    {
        $M=$this->model();
        $parent=$M::with(['students.enrollments.group.subject','students.enrollments.group.teacher'])->findOrFail($id);
        $students=$parent->students;
        $studentIds=$students->pluck('id');

        $attendances = \App\Models\Attendance::with(['session.group','student'])
            ->whereIn('student_id',$studentIds)
            ->latest('id')->limit(200)->get()
            ->groupBy('student_id');

        $examResults = \App\Models\ExamResult::with(['exam.subject','exam.group','student'])
            ->whereIn('student_id',$studentIds)
            ->latest('id')->limit(200)->get()
            ->groupBy('student_id');

        $payments = \App\Models\Payment::with(['group.subject','student'])
            ->whereIn('student_id',$studentIds)
            ->orderByDesc('year')->orderByDesc('month')->get()
            ->groupBy('student_id');

        // ملخص إجمالي
        $stats=[];
        foreach($students as $s){
            $atts=$attendances[$s->id] ?? collect();
            $stats[$s->id]=[
                'present'=>$atts->whereIn('status',['present','late'])->count(),
                'absent'=>$atts->where('status','absent')->count(),
                'total'=>$atts->count(),
                'avg_score'=>($examResults[$s->id] ?? collect())->avg(fn($r)=> (float)$r->score) ?? null,
                'paid'=>($payments[$s->id] ?? collect())->where('is_cancelled',false)->sum('paid_amount'),
                'required'=>($payments[$s->id] ?? collect())->where('is_cancelled',false)->sum('required_amount'),
            ];
        }

        $pdf = Pdf::loadView('parents.report', compact('parent','students','attendances','examResults','payments','stats'))
            ->setPaper('a4','portrait')
            ->setOption('isHtml5ParserEnabled', true)
            ->setOption('isRemoteEnabled', false);

        $filename = 'parent-report-'.$parent->id.'-'.now()->format('Ymd').'.pdf';
        return $pdf->stream($filename);
    }
}
