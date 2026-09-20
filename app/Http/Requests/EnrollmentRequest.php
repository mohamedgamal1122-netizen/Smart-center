<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
class EnrollmentRequest extends FormRequest
{
    public function authorize(): bool { return true; }
    public function rules(): array
    {
        return [
            'student_id' => 'required|exists:students,id',
            'group_id' => 'required|exists:groups,id',
            'agreed_fee' => 'nullable|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'enrollment_date' => 'nullable|date',
            'notes' => 'nullable|string|max:2000',
        ];
    }
    public function messages(): array { return ['student_id.required'=>'الطالب مطلوب.','group_id.required'=>'المجموعة مطلوبة.']; }
}
