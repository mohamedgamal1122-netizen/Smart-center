<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
class TeacherRequest extends FormRequest
{
    public function authorize(): bool { return true; }
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'subject_id' => 'nullable|exists:subjects,id',
            'salary_type' => 'nullable|in:monthly,per_session,per_student,percentage',
            'salary_value' => 'nullable|numeric|min:0',
            'is_active' => 'nullable|boolean',
            'notes' => 'nullable|string|max:2000',
        ];
    }
    public function messages(): array
    {
        return ['name.required' => 'اسم المدرس مطلوب.','email.email'=>'البريد غير صحيح.','subject_id.exists'=>'المادة غير موجودة.'];
    }
}
