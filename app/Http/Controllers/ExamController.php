<?php
namespace App\Http\Controllers;
use App\Models\Exam;
use App\Models\Group;
use App\Models\ExamResult;
use App\Models\Student;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class ExamController extends Controller
{
    public function index(Request $request)
    {
        $q=Exam::with(['group.subject']);
        if($g=$request->input('group_id')) $q->where('group_id',$g);
        $exams=$q->orderByDesc('exam_date')->paginate(20)->withQueryString();
        return view('exams.index',compact('exams'));
    }
    public function create(){ $groups=Group::with('subject')->get(); return view('exams.create',compact('groups')); }
    public function store(\App\Http\Requests\ExamRequest $request){ Exam::create($request->validated()); return redirect()->route('exams.index')->with('success','تم إضافة الامتحان.'); }
    public function show(Exam $exam){
        $exam->load(['group.subject','results.student']);
        // stats
        $scores = $exam->results->pluck('score')->filter()->map(fn($v)=>(float)$v);
        $stats = [
            'avg' => $scores->count() ? round($scores->avg(),1) : 0,
            'max' => $scores->count() ? $scores->max() : null,
            'min' => $scores->count() ? $scores->min() : null,
        ];
        $results = $exam->results;
        $resultsMap = $results->keyBy('student_id');
        // ranking top 3
        $ranked = $results->sortByDesc('score')->values();
        $top3 = $ranked->take(3);
        // students in group or all results students + enrollments
        if($exam->group_id){
            $students = Student::whereHas('enrollments',fn($q)=>$q->where('group_id',$exam->group_id)->where('status','active'))
                ->orderBy('name')->get();
            // merge any result students not in enrollment
            $missingIds = $results->pluck('student_id')->diff($students->pluck('id'));
            if($missingIds->count()){
                $extra = Student::whereIn('id',$missingIds)->get();
                $students = $students->merge($extra);
            }
        } else {
            $students = $results->pluck('student')->filter()->unique('id')->values();
            if($students->isEmpty()){
                $students = Student::orderBy('name')->limit(50)->get();
            }
        }
        // declines: compare to previous exam same group/subject
        $declines = []; // student_id => ['prev'=>x,'curr'=>y,'drop'=>percent]
        // find previous exam
        $prevExamQuery = Exam::where('id','!=',$exam->id)
            ->where(function($q) use($exam){
                if($exam->group_id) $q->where('group_id',$exam->group_id);
                if($exam->subject_id) $q->orWhere('subject_id',$exam->subject_id);
                else if(!$exam->group_id) $q->whereNull('group_id');
            })
            ->where('exam_date','<',$exam->exam_date ?? now())
            ->orderByDesc('exam_date');
        $prevExam = (clone $prevExamQuery)->first();
        // also try by latest before current if no date
        if(!$prevExam && $exam->group_id){
            $prevExam = Exam::where('group_id',$exam->group_id)->where('id','!=',$exam->id)->orderByDesc('exam_date')->first();
        }
        if($prevExam){
            $prevMap = ExamResult::where('exam_id',$prevExam->id)->pluck('score','student_id');
            foreach($results as $res){
                $prevScore = $prevMap[$res->student_id] ?? null;
                if($prevScore !== null && (float)$prevScore > 0){
                    $currPct = $exam->max_score ? ((float)$res->score / (float)$exam->max_score * 100) : 0;
                    $prevPct = $prevExam->max_score ? ((float)$prevScore / (float)$prevExam->max_score * 100) : 0;
                    if($prevPct > 0){
                        $dropPct = (($prevPct - $currPct) / $prevPct) * 100;
                        if($dropPct > 15){
                            $declines[$res->student_id] = [
                                'prev'=>$prevScore,
                                'prevPct'=>round($prevPct,1),
                                'currPct'=>round($currPct,1),
                                'drop'=>round($dropPct,1),
                                'prevExam'=>$prevExam->title,
                            ];
                        }
                    }
                }
            }
        }
        return view('exams.show',compact('exam','stats','results','resultsMap','students','ranked','top3','declines','prevExam'));
    }
    public function edit(Exam $exam){ $groups=Group::with('subject')->get(); return view('exams.edit',compact('exam','groups')); }
    public function update(\App\Http\Requests\ExamRequest $request, Exam $exam){ $exam->update($request->validated()); return redirect()->route('exams.index')->with('success','تم التحديث.'); }
    public function destroy(Exam $exam){ $exam->delete(); return back()->with('success','تم الحذف.'); }

    public function certificate(Exam $exam, Student $student)
    {
        $exam->load(['group.subject','results']);
        $result = ExamResult::where('exam_id',$exam->id)->where('student_id',$student->id)->firstOrFail();
        // compute rank
        $ranked = $exam->results->sortByDesc('score')->values();
        $rank = $ranked->search(fn($r)=> $r->student_id == $student->id) + 1;
        if($rank > 3){
            abort(403,'الشهادة متاحة فقط لأوائل 3 طلاب.');
        }
        $pdf = Pdf::loadView('exams.certificate', compact('exam','student','result','rank'));
        $pdf->setPaper('A4','landscape');
        return $pdf->stream('certificate-'.$exam->id.'-'.$student->code.'.pdf');
    }
}
