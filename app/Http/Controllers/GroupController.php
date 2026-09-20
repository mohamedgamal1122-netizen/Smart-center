<?php
namespace App\Http\Controllers;
use App\Models\Group;
use App\Models\Subject;
use App\Models\Teacher;
use App\Http\Requests\GroupRequest;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    public function index(Request $request)
    {
        $q=Group::with(['subject','teacher'])->withCount(['enrollments as active_count'=>fn($qq)=>$qq->where('status','active')]);
        if($s=$request->input('q')) $q->where('name','like',"%{$s}%");
        if($sub=$request->input('subject_id')) $q->where('subject_id',$sub);
        // teacher sees only his groups
        $user = auth()->user();
        if ($user && $user->role === 'teacher') {
            $teacher = Teacher::where('email', $user->email)->first();
            if ($teacher) $q->where('teacher_id', $teacher->id);
            else $q->whereRaw('1=0');
        }
        $groups=$q->latest()->paginate(20)->withQueryString();
        $subjects=Subject::all();
        return view('groups.index',compact('groups','subjects'));
    }
    public function create(){
        $this->authorizeAdminOrReception();
        $subjects=Subject::all(); $teachers=Teacher::all(); return view('groups.create',compact('subjects','teachers')); }
    public function store(GroupRequest $request){
        $this->authorizeAdminOrReception();
        Group::create($request->validated()); return redirect()->route('groups.index')->with('success','تم إنشاء المجموعة.'); }
    public function show(Group $group){
        $this->authorizeTeacherGroup($group);
        $group->load(['subject','teacher','enrollments.student','sessions']); return view('groups.show',compact('group')); }
    public function edit(Group $group){
        $this->authorizeTeacherGroup($group);
        $subjects=Subject::all(); $teachers=Teacher::all(); return view('groups.edit',compact('group','subjects','teachers')); }
    public function update(GroupRequest $request, Group $group){
        $this->authorizeTeacherGroup($group);
        $group->update($request->validated()); return redirect()->route('groups.index')->with('success','تم التحديث.'); }
    public function destroy(Group $group){
        // only admin can delete groups
        if (auth()->user()->role !== 'admin') abort(403, 'الحذف للمدير فقط.');
        $group->delete(); return back()->with('success','تم الحذف.'); }

    private function authorizeTeacherGroup(Group $group): void
    {
        $user = auth()->user();
        if ($user && $user->role === 'teacher') {
            $teacher = Teacher::where('email', $user->email)->first();
            if (!$teacher || $group->teacher_id !== $teacher->id) {
                abort(403, 'المدرس لا يعدل إلا مجموعاته.');
            }
        }
        if ($user && $user->role === 'receptionist') {
            // reception can view but not edit groups? allow view, block edit
            $action = request()->route()->getActionMethod();
            if (in_array($action, ['edit','update','destroy','create','store'])) {
                // allow if needed but spec says reception view only for finance, so groups allowed?
                // keep groups editable for reception
            }
        }
    }

    private function authorizeAdminOrReception(): void
    {
        $user = auth()->user();
        if ($user && $user->role === 'teacher') abort(403, 'المدرس لا ينشئ مجموعات جديدة.');
    }
}
