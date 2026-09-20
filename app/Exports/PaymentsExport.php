<?php
namespace App\Exports;

use App\Models\Payment;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PaymentsExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected Request $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function collection()
    {
        $q = Payment::with(['student','group.subject','creator'])->latest();
        if ($s = $this->request->input('q')) $q->whereHas('student', fn($qq) => $qq->where('name', 'like', "%{$s}%"));
        if ($m = $this->request->input('month')) $q->where('month', $m);
        if ($y = $this->request->input('year')) $q->where('year', $y);
        if ($this->request->input('export_scope') === 'revenue') {
            // used from reports/revenue
            $month = $this->request->input('month', now()->month);
            $year = $this->request->input('year', now()->year);
            $q = Payment::with(['student','group.subject'])->where('month', $month)->where('year', $year)->where('is_cancelled', false);
        }
        return $q->get();
    }

    public function headings(): array
    {
        return ['رقم الإيصال','الطالب','كود الطالب','المجموعة','المادة','الشهر','السنة','المطلوب','الخصم','الصافي','المدفوع','المتبقي','طريقة الدفع','تاريخ الدفع','الحالة','المنشئ'];
    }

    public function map($p): array
    {
        return [
            $p->receipt_number ?? '—',
            $p->student->name ?? '—',
            $p->student->code ?? '—',
            $p->group->name ?? '—',
            $p->group->subject->name ?? '—',
            $p->month,
            $p->year,
            (float) $p->required_amount,
            (float) $p->discount,
            (float) $p->net_required ?? ((float)$p->required_amount - (float)$p->discount),
            (float) $p->paid_amount,
            (float) ($p->remaining ?? 0),
            $p->payment_method_label ?? $p->payment_method,
            $p->payment_date ? $p->payment_date->format('Y-m-d') : '—',
            $p->payment_status_label ?? ($p->is_cancelled ? 'ملغى' : '—'),
            $p->creator->name ?? '—',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [1 => ['font' => ['bold' => true, 'size' => 11], 'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => 'D1FAE5']]]];
    }
}
