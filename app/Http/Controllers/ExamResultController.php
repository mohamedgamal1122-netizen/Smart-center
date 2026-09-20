<?php
namespace App\Http\Controllers;
use App\Models\Exam;
use App\Models\ExamResult;
use Illuminate\Http\Request;

class ExamResultController extends Controller
{
    public function store(Request $request, Exam $exam)
    {
        // Accept both formats: results[student_id][score] (from updated view) and results[][marks]
        $data = $request->input('results', []);
        if(empty($data)){
            return back()->withErrors(['results'=>'لا توجد درجات للحفظ.']);
        }
        $saved = 0;
        foreach($data as $key => $row){
            // normalize: key may be student_id
            $studentId = $row['student_id'] ?? $key;
            $score = $row['score'] ?? $row['marks'] ?? null;
            if($score === null || $score === '') continue;
            $score = (float)$score;
            if($score < 0 || $score > (float)$exam->max_score) continue;
            ExamResult::updateOrCreate(
                ['exam_id'=>$exam->id,'student_id'=>$studentId],
                ['score'=>$score,'grade'=>$row['grade'] ?? null,'notes'=>$row['notes']??null]
            );
            $saved++;
        }
        return back()->with('success',"تم حفظ {$saved} نتيجة.");
    }

    public function update(Request $request, ExamResult $examResult)
    {
        $request->validate(['score'=>'nullable|numeric|min:0','marks'=>'nullable|numeric|min:0','notes'=>'nullable|string|max:500','grade'=>'nullable|string|max:20']);
        $score = $request->input('score', $request->input('marks'));
        $payload = $request->only(['notes','grade']);
        if($score !== null) $payload['score']=$score;
        $examResult->update($payload);
        return back()->with('success','تم التحديث.');
    }

    public function destroy(ExamResult $examResult){ $examResult->delete(); return back()->with('success','تم الحذف.'); }
}
