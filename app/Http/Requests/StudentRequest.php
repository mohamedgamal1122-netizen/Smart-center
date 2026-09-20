<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
class StudentRequest extends FormRequest
{
    public function authorize(): bool { return true; }
    public function rules(): array
    {
        $id = $this->route('student')?->id;
        return [
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'birth_date' => 'nullable|date',
            'gender' => 'nullable|in:male,female',
            'grade' => 'nullable|string|max:100',
            'school' => 'nullable|string|max:255',
            'status' => 'nullable|in:active,inactive,graduated,suspended',
            'enrollment_date' => 'nullable|date',
            'notes' => 'nullable|string|max:2000',
        ];
    }
    public function messages(): array
    {
        return [
            'name.required' => 'اسم الطالب مطلوب.',
            'name.max' => 'الاسم طويل جداً.',
            'phone.max' => 'رقم الهاتف طويل.',
            'birth_date.date' => 'تاريخ الميلاد غير صحيح.',
            'gender.in' => 'النوع يجب أن يكون ذكر أو أنثى.',
        ];
    }
}
