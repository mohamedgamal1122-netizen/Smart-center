<?php
namespace App\Exports;

use App\Models\Attendance;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AttendanceExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected Request $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function collection()
    {
        $from = $this->request->input('from', now()->startOfMonth()->toDateString());
        $to = $this->request->input('to', now()->toDateString());
        $groupId = $this->request->input('group_id');
        $q = Attendance::with(['student','session.group.subject']);
        // supports both reports/attendance and attendances/index
        if ($this->request->has('from') || $this->request->has('to') || $this->request->routeIs('reports.*')) {
            $q->whereHas('session', fn($qq) => $qq->whereBetween('session_date', [$from, $to]));
            if ($groupId) $q->whereHas('session', fn($qq) => $qq->where('group_id', $groupId));
        } else {
            if ($sid = $this->request->input('session_id')) $q->where('session_id', $sid);
            if ($status = $this->request->input('status')) $q->where('status', $status);
            if ($date = $this->request->input('date')) $q->whereHas('session', fn($qq) => $qq->whereDate('session_date', $date));
        }
        return $q->latest()->get();
    }

    public function headings(): array
    {
        return ['الطالب','كود الطالب','المجموعة','المادة','تاريخ الحصة','الحالة','ملاحظات'];
    }

    public function map($a): array
    {
        return [
            $a->student->name ?? '—',
            $a->student->code ?? '—',
            $a->session->group->name ?? '—',
            $a->session->group->subject->name ?? '—',
            $a->session->session_date ? \Carbon\Carbon::parse($a->session->session_date)->format('Y-m-d') : '—',
            $a->status_label ?? $a->status,
            $a->notes ?? '—',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [1 => ['font' => ['bold' => true], 'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => 'E0E7FF']]]];
    }
}
