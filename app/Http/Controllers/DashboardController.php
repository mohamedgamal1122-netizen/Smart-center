<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Group;
use App\Models\Teacher;
use App\Models\Payment;
use App\Models\Expense;
use App\Models\Enrollment;
use App\Models\Attendance;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today('Africa/Cairo');
        $month = $today->month;
        $year  = $today->year;

        $activeStudents  = Student::where('status','active')->count();
        $totalGroups     = Group::count();
        $totalTeachers   = Teacher::count();
        $totalEnrollments= Enrollment::where('status','active')->count();

        $revenueMonth    = (float) Payment::where('is_cancelled', false)->where('month',$month)->where('year',$year)->sum('paid_amount');
        $dueAmount       = (float) Payment::where('is_cancelled', false)->where('month',$month)->where('year',$year)
                            ->get()->sum(fn($p)=> max(0, (float)$p->remaining));

        $expensesMonth   = (float) Expense::whereMonth('expense_date',$month)->whereYear('expense_date',$year)->sum('amount');
        $netProfit       = $revenueMonth - $expensesMonth;

        $newStudents     = Student::whereMonth('enrollment_date',$month)->whereYear('enrollment_date',$year)->count();
        $absentToday     = Attendance::whereDate('created_at',$today)->where('status','absent')->count();
        // Fallback: attendances tied to sessions today
        $sessionsToday   = \App\Models\Session::whereDate('session_date',$today)->count();

        $recentPayments  = Payment::with(['student','group'])->latest()->take(6)->get();
        $recentStudents  = Student::latest()->take(6)->get();
        $unpaidStudents  = Payment::with(['student','group'])->where('is_cancelled',false)->where('month',$month)->where('year',$year)
                            ->get()->filter(fn($p)=> $p->remaining > 0)->take(8);

        // شهري revenue last 6 months
        $monthlyRevenue = collect(range(5,0))->map(function($i) use ($today){
            $d = $today->copy()->subMonths($i);
            $rev = (float) Payment::where('is_cancelled',false)->where('month',$d->month)->where('year',$d->year)->sum('paid_amount');
            $exp = (float) Expense::whereMonth('expense_date',$d->month)->whereYear('expense_date',$d->year)->sum('amount');
            return ['label'=>$d->translatedFormat('M Y') ?? $d->format('M Y'),'revenue'=>$rev,'expenses'=>$exp];
        });

        return view('dashboard.index', compact(
            'activeStudents','totalGroups','totalTeachers','totalEnrollments',
            'revenueMonth','dueAmount','expensesMonth','netProfit',
            'newStudents','absentToday','sessionsToday',
            'recentPayments','recentStudents','unpaidStudents','monthlyRevenue','month','year'
        ));
    }
}
