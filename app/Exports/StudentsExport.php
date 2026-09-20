<?php
namespace App\Exports;

use App\Models\Student;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class StudentsExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected Request $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function collection()
    {
        $q = Student::query();
        if ($s = $this->request->input('q')) {
            $q->where(function ($qq) use ($s) {
                $qq->where('name', 'like', "%{$s}%")->orWhere('code', 'like', "%{$s}%")->orWhere('phone', 'like', "%{$s}%");
            });
        }
        if ($status = $this->request->input('status')) $q->where('status', $status);
        if ($grade = $this->request->input('grade')) $q->where('grade', $grade);
        return $q->withCount('enrollments')->latest()->get();
    }

    public function headings(): array
    {
        return ['الكود','الاسم','الهاتف','الصف/المرحلة','الجنس','تاريخ الميلاد','الحالة','تاريخ التسجيل','عدد المجموعات'];
    }

    public function map($student): array
    {
        return [
            $student->code,
            $student->name,
            $student->phone ?? '—',
            $student->grade ?? '—',
            $student->gender === 'female' ? 'أنثى' : 'ذكر',
            $student->birth_date ? $student->birth_date->format('Y-m-d') : '—',
            $student->statusLabel ?? $student->status,
            $student->enrollment_date ? $student->enrollment_date->format('Y-m-d') : '—',
            $student->enrollments_count ?? 0,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [1 => ['font' => ['bold' => true, 'size' => 12], 'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => 'EDE9FE']]]];
    }
}
