<?php
namespace App\Exports;

use App\Models\Expense;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ExpensesExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected Request $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function collection()
    {
        $q = Expense::with('creator')->latest();
        if ($c = $this->request->input('category')) $q->where('category', $c);
        if ($from = $this->request->input('from')) $q->whereDate('expense_date', '>=', $from);
        if ($to = $this->request->input('to')) $q->whereDate('expense_date', '<=', $to);
        // for reports/expenses whereBetween
        return $q->get();
    }

    public function headings(): array
    {
        return ['#','الفئة','المبلغ (ج.م)','تاريخ المصروف','طريقة الدفع','الوصف','المنشئ'];
    }

    public function map($e): array
    {
        return [
            $e->id,
            $e->category_label ?? $e->category,
            (float) $e->amount,
            $e->expense_date ? $e->expense_date->format('Y-m-d') : '—',
            $e->payment_method ?? '—',
            $e->description ?? '—',
            $e->creator->name ?? '—',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [1 => ['font' => ['bold' => true], 'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => 'FCE7F3']]]];
    }
}
