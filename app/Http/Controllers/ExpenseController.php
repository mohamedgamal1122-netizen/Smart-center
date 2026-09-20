<?php
namespace App\Http\Controllers;
use App\Models\Expense;
use App\Http\Requests\ExpenseRequest;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ExpensesExport;

class ExpenseController extends Controller
{
    public function index(Request $request)
    {
        if ($request->input('export') === 'excel') {
            // reception read-only allowed for export view
            return Excel::download(new ExpensesExport($request), 'expenses-'.now()->format('Y-m-d').'.xlsx');
        }
        $q=Expense::with('creator')->latest();
        if($c=$request->input('category')) $q->where('category',$c);
        if($from=$request->input('from')) $q->whereDate('expense_date','>=',$from);
        if($to=$request->input('to')) $q->whereDate('expense_date','<=',$to);
        $expenses=$q->paginate(20)->withQueryString();
        $total=(float) (clone $q)->sum('amount');
        return view('expenses.index',compact('expenses','total'));
    }
    public function create(){
        $this->authorizeFinanceWrite();
        return view('expenses.create'); }
    public function store(ExpenseRequest $request){
        $this->authorizeFinanceWrite();
        $data=$request->validated(); $data['created_by']=auth()->id(); Expense::create($data); return redirect()->route('expenses.index')->with('success','تم إضافة المصروف.'); }
    public function show(Expense $expense){ return view('expenses.show',compact('expense')); }
    public function edit(Expense $expense){
        $this->authorizeFinanceWrite();
        return view('expenses.edit',compact('expense')); }
    public function update(ExpenseRequest $request, Expense $expense){
        $this->authorizeFinanceWrite();
        $expense->update($request->validated()); return redirect()->route('expenses.index')->with('success','تم التحديث.'); }
    public function destroy(Expense $expense){
        $this->authorizeFinanceWrite();
        $expense->delete(); return back()->with('success','تم الحذف.'); }

    private function authorizeFinanceWrite(): void
    {
        $user = auth()->user();
        if ($user && $user->role === 'receptionist') {
            abort(403, 'الاستقبال لديه صلاحية العرض فقط — لا يمكن تعديل المصروفات.');
        }
        if ($user && $user->role === 'teacher') {
            abort(403, 'المدرس لا يملك صلاحية التعديل على المالية.');
        }
    }
}
