<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\Expense;
use App\Models\Student;
use App\Models\Enrollment;
use App\Models\Attendance;
use App\Models\Group;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PaymentsExport;
use App\Exports\ExpensesExport;
use App\Exports\AttendanceExport;
use App\Exports\StudentsExport;

class ReportController extends Controller
{
    public function index(){ return view('reports.index'); }

    public function revenue(Request $request)
    {
        $month=$request->input('month', now()->month);
        $year=$request->input('year', now()->year);
        $payments=Payment::with(['student','group.subject'])->where('month',$month)->where('year',$year)->where('is_cancelled',false)->get();
        $totalPaid=(float)$payments->sum('paid_amount');
        $totalRequired=(float)$payments->sum(fn($p)=>(float)$p->required_amount - (float)$p->discount);
        $totalDue=max(0,$totalRequired-$totalPaid);
        $totalDiscount=(float)$payments->sum('discount');
        if($request->input('export')==='pdf'){
            $pdf=Pdf::loadView('reports.pdf.revenue',compact('payments','month','year','totalPaid','totalRequired','totalDue','totalDiscount'));
            return $pdf->download("revenue-{$year}-{$month}.pdf");
        }
        if($request->input('export')==='excel'){
            return Excel::download(new PaymentsExport($request), "revenue-{$year}-{$month}.xlsx");
        }
        return view('reports.revenue',compact('payments','month','year','totalPaid','totalRequired','totalDue','totalDiscount'));
    }

    public function expenses(Request $request)
    {
        $from=$request->input('from', now()->startOfMonth()->toDateString());
        $to=$request->input('to', now()->toDateString());
        $expenses=Expense::whereBetween('expense_date',[$from,$to])->get();
        $total=(float)$expenses->sum('amount');
        $byCategory=$expenses->groupBy('category')->map(fn($g)=> (float)$g->sum('amount'));
        if($request->input('export')==='pdf'){
            $pdf=Pdf::loadView('reports.pdf.expenses',compact('expenses','from','to','total','byCategory'));
            return $pdf->download("expenses-{$from}-{$to}.pdf");
        }
        if($request->input('export')==='excel'){
            return Excel::download(new ExpensesExport($request), "expenses-{$from}-{$to}.xlsx");
        }
        return view('reports.expenses',compact('expenses','from','to','total','byCategory'));
    }

    public function profit(Request $request)
    {
        $month=$request->input('month', now()->month);
        $year=$request->input('year', now()->year);
        $revenue=(float)Payment::where('month',$month)->where('year',$year)->where('is_cancelled',false)->sum('paid_amount');
        $expenses=(float)Expense::whereMonth('expense_date',$month)->whereYear('expense_date',$year)->sum('amount');
        $profit=$revenue-$expenses;
        if($request->input('export')==='excel'){
            // simple profit sheet via payments+expenses exports combined inline
            return Excel::download(new PaymentsExport($request), "profit-{$year}-{$month}.xlsx");
        }
        return view('reports.profit',compact('revenue','expenses','profit','month','year'));
    }

    public function attendanceReport(Request $request)
    {
        $from=$request->input('from', now()->startOfMonth()->toDateString());
        $to=$request->input('to', now()->toDateString());
        $groupId=$request->input('group_id');
        $q=Attendance::with(['student','session.group']);
        $q->whereHas('session',fn($qq)=>$qq->whereBetween('session_date',[$from,$to]));
        if($groupId) $q->whereHas('session',fn($qq)=>$qq->where('group_id',$groupId));
        $attendances=$q->get();
        $groups=Group::with('subject')->get();
        if($request->input('export')==='pdf'){
            $pdf=Pdf::loadView('reports.pdf.attendance',compact('attendances','from','to'));
            return $pdf->download("attendance-{$from}-{$to}.pdf");
        }
        if($request->input('export')==='excel'){
            return Excel::download(new AttendanceExport($request), "attendance-{$from}-{$to}.xlsx");
        }
        return view('reports.attendance',compact('attendances','from','to','groups','groupId'));
    }

    public function studentsReport(Request $request)
    {
        $q=Student::query();
        if($s=$request->input('status')) $q->where('status',$s);
        if($g=$request->input('grade')) $q->where('grade',$g);
        $students=$q->withCount('enrollments')->get();
        if($request->input('export')==='pdf'){
            $pdf=Pdf::loadView('reports.pdf.students',compact('students'));
            return $pdf->download("students-report.pdf");
        }
        if($request->input('export')==='excel'){
            return Excel::download(new StudentsExport($request), "students-report-".now()->format('Y-m-d').".xlsx");
        }
        return view('reports.students',compact('students'));
    }
}
