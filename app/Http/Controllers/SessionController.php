<?php
namespace App\Http\Controllers;
use App\Models\Session;
use App\Models\Group;
use App\Http\Requests\SessionRequest;
use Illuminate\Http\Request;
use Carbon\Carbon;

class SessionController extends Controller
{
    public function index(Request $request)
    {
        $q=Session::with(['group.subject','group.teacher']);
        if($g=$request->input('group_id')) $q->where('group_id',$g);
        $sessions=$q->orderByDesc('date')->paginate(20)->withQueryString();
        $groups=Group::with('subject')->get();
        return view('sessions.index',compact('sessions','groups'));
    }
    public function create(){ $groups=Group::with('subject')->get(); return view('sessions.create',compact('groups')); }
    public function store(SessionRequest $request){ $s=Session::create($request->validated()); return redirect()->route('sessions.index')->with('success','تم إضافة الحصة.'); }
    public function show(Session $session){ $session->load(['group.subject','attendances.student']); return view('sessions.show',compact('session')); }
    public function edit(Session $session){ $groups=Group::with('subject')->get(); return view('sessions.edit',compact('session','groups')); }
    public function update(SessionRequest $request, Session $session){ $session->update($request->validated()); return redirect()->route('sessions.index')->with('success','تم التحديث.'); }
    public function destroy(Session $session){ $session->delete(); return back()->with('success','تم الحذف.'); }

    // جدول أسبوعي السبت-الجمعة
    public function schedule(Request $request)
    {
        $weekInput = $request->input('week');
        $ref = $weekInput ? Carbon::parse($weekInput) : Carbon::now('Africa/Cairo');
        // Saturday as start of week
        $weekStart = $ref->copy()->startOfWeek(Carbon::SATURDAY);
        $weekEnd   = $weekStart->copy()->endOfWeek(Carbon::FRIDAY);

        $sessions = Session::with(['group.subject','group.teacher'])
            ->whereBetween('date', [$weekStart->toDateString(), $weekEnd->toDateString()])
            ->orderBy('start_time')
            ->get();

        // group by English day name
        $sessionsByDay = $sessions->groupBy(fn($s)=> Carbon::parse($s->date)->format('l'));

        return view('sessions.schedule', compact('sessionsByDay','weekStart','weekEnd','sessions'));
    }

    // AJAX حفظ سحب وإفلات
    public function reorder(Request $request)
    {
        $request->validate([
            'id'   => 'required|exists:class_sessions,id',
            'date' => 'required|date',
        ]);
        $session = Session::findOrFail($request->input('id'));
        $session->update(['date' => $request->input('date')]);
        return response()->json(['success'=>true,'message'=>'تم تحديث تاريخ الحصة.','date'=>$session->date->format('Y-m-d')]);
    }
}
