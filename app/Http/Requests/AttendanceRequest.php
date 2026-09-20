<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
class AttendanceRequest extends FormRequest
{
    public function authorize(): bool { return true; }
    public function rules(): array
    {
        return [
            'session_id' => 'required|exists:sessions,id',
            'attendances' => 'required|array',
            'attendances.*.student_id' => 'required|exists:students,id',
            'attendances.*.status' => 'required|in:present,absent,late,excused',
            'attendances.*.notes' => 'nullable|string|max:500',
        ];
    }
    public function messages(): array
    {
        return ['session_id.required'=>'الحصة مطلوبة.','attendances.required'=>'بيانات الحضور مطلوبة.'];
    }
}
