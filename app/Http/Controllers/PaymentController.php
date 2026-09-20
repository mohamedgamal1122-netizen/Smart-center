<?php
namespace App\Http\Controllers;
use App\Models\Payment;
use App\Models\Student;
use App\Models\Group;
use App\Http\Requests\PaymentRequest;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PaymentsExport;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        if ($request->input('export') === 'excel') {
            $this->authorizeFinanceView($request);
            return Excel::download(new PaymentsExport($request), 'payments-'.now()->format('Y-m-d').'.xlsx');
        }
        $q=Payment::with(['student','group.subject'])->latest();
        if($s=$request->input('q')) $q->whereHas('student',fn($qq)=>$qq->where('name','like',"%{$s}%"));
        if($m=$request->input('month')) $q->where('month',$m);
        if($y=$request->input('year')) $q->where('year',$y);
        if($request->input('unpaid')=='1') { $payments=$q->get()->filter(fn($p)=>!$p->is_cancelled && $p->remaining>0); $payments=new \Illuminate\Pagination\LengthAwarePaginator($payments->forPage(1,20), $payments->count(), 20, 1, ['path'=>request()->url(),'query'=>request()->query()]); }
        else $payments=$q->paginate(20)->withQueryString();
        return view('payments.index',compact('payments'));
    }
    public function create(){
        $this->authorizeFinanceWrite();
        $students=Student::select('id','name','code')->get(); $groups=Group::with('subject')->get(); return view('payments.create',compact('students','groups')); }
    public function store(PaymentRequest $request){
        $this->authorizeFinanceWrite();
        $data=$request->validated();
        $data['created_by']=auth()->id();
        $data['payment_date']=$data['payment_date']??now()->toDateString();
        $payment=Payment::create($data);
        return redirect()->route('payments.show',$payment)->with('success','تم تسجيل الدفعة.');
    }
    public function show(Payment $payment){ $payment->load(['student','group.subject','creator']); return view('payments.show',compact('payment')); }
    public function edit(Payment $payment){
        $this->authorizeFinanceWrite();
        $students=Student::select('id','name','code')->get(); $groups=Group::with('subject')->get(); return view('payments.edit',compact('payment','students','groups')); }
    public function update(PaymentRequest $request, Payment $payment){
        $this->authorizeFinanceWrite();
        $payment->update($request->validated()); return redirect()->route('payments.show',$payment)->with('success','تم التحديث.'); }
    public function destroy(Payment $payment){
        $this->authorizeFinanceWrite();
        $payment->delete(); return back()->with('success','تم الحذف.'); }
    public function cancel(Request $request, Payment $payment){
        $this->authorizeFinanceWrite();
        $request->validate(['cancel_reason'=>'required|string|max:500'],['cancel_reason.required'=>'سبب الإلغاء مطلوب.']);
        $payment->update(['is_cancelled'=>true,'cancel_reason'=>$request->input('cancel_reason')]);
        return back()->with('success','تم إلغاء الإيصال.');
    }
    public function receipt(Payment $payment){
        $payment->load(['student','group.subject','creator']);
        $pdf=Pdf::loadView('payments.receipt',compact('payment'))->setPaper('a5','portrait');
        return $pdf->stream('receipt-'.$payment->receipt_number.'.pdf');
    }
    public function receiptHtml(Payment $payment){
        $payment->load(['student','group.subject','creator']); return view('payments.receipt',compact('payment'));
    }

    private function authorizeFinanceView(Request $request = null): void
    {
        // reception can view but not edit; this is view so allow
    }

    private function authorizeFinanceWrite(): void
    {
        $user = auth()->user();
        if ($user && $user->role === 'receptionist') {
            abort(403, 'الاستقبال لديه صلاحية العرض فقط — التعديل/الإضافة غير مسموح.');
        }
        if ($user && $user->role === 'teacher') {
            abort(403, 'المدرس لا يملك صلاحية التعديل على المالية.');
        }
    }
}
