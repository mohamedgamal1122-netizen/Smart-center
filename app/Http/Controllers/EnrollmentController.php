<?php
namespace App\Http\Controllers;
use App\Models\Enrollment;
use App\Models\Group;
use App\Models\Student;
use App\Http\Requests\EnrollmentRequest;
use App\Services\SiblingDiscountService;
use Illuminate\Http\Request;

class EnrollmentController extends Controller
{
    public function index(Request $request)
    {
        $q=Enrollment::with(['student','group.subject']);
        if($g=$request->input('group_id')) $q->where('group_id',$g);
        if($s=$request->input('student_id')) $q->where('student_id',$s);
        $enrollments=$q->latest()->paginate(20)->withQueryString();
        return view('enrollments.index',compact('enrollments'));
    }
    public function create(Request $request)
    {
        $groups=Group::with('subject')->get(); $students=Student::select('id','name','code')->get();
        return view('enrollments.create',compact('groups','students'));
    }
    public function store(EnrollmentRequest $request)
    {
        $data=$request->validated();
        $group=Group::findOrFail($data['group_id']);
        $activeCount=$group->enrollments()->where('status','active')->count();
        $isAdmin = auth()->user()?->isAdmin() || auth()->user()?->hasRole('admin');
        if($activeCount >= $group->max_students && !$isAdmin){
            return back()->withErrors(['group_id'=>'المجموعة ممتلئة. فقط المدير يمكنه التجاوز.'])->withInput();
        }
        if(Enrollment::where('student_id',$data['student_id'])->where('group_id',$data['group_id'])->where('status','active')->exists()){
            return back()->withErrors(['student_id'=>'الطالب مسجل بالفعل في هذه المجموعة.'])->withInput();
        }
        $data['status']=$data['status']??'active';
        // خصم الأخوات تلقائي 10% لو ولي الأمر عنده أكثر من طالب نشط
        // لا نتجاوز خصم مُدخل يدوياً — لو لم يُدخل خصم نحسبه تلقائيا
        if (empty($data['discount']) || (float)$data['discount'] == 0) {
            $groupFee = (float)($data['agreed_fee'] ?? $group->monthly_fee ?? 0);
            if ($groupFee > 0) {
                $auto = SiblingDiscountService::calculateDiscount($groupFee, (int)$data['student_id']);
                if ($auto > 0) {
                    $data['discount'] = $auto;
                }
            }
        }
        // لو agreed_fee فارغ خذه من المجموعة
        if (empty($data['agreed_fee'])) {
            $data['agreed_fee'] = $group->monthly_fee;
        }
        Enrollment::create($data);
        return redirect()->route('enrollments.index')->with('success','تم تسجيل الطالب في المجموعة.');
    }
    public function destroy(Enrollment $enrollment){ $enrollment->update(['status'=>'cancelled']); return back()->with('success','تم إلغاء التسجيل.'); }
    public function forceDestroy(Enrollment $enrollment){ $enrollment->delete(); return back()->with('success','تم الحذف.'); }
}
