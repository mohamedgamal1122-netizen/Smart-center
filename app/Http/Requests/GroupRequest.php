<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
class GroupRequest extends FormRequest
{
    public function authorize(): bool { return true; }
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'subject_id' => 'required|exists:subjects,id',
            'teacher_id' => 'nullable|exists:teachers,id',
            'grade' => 'nullable|string|max:100',
            'room' => 'nullable|string|max:100',
            'days' => 'nullable|array',
            'days.*' => 'string',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i|after:start_time',
            'max_students' => 'required|integer|min:1|max:500',
            'monthly_fee' => 'required|numeric|min:0',
            'start_date' => 'nullable|date',
            'status' => 'nullable|in:open,full,paused',
            'notes' => 'nullable|string|max:2000',
        ];
    }
    public function messages(): array
    {
        return [
            'name.required'=>'اسم المجموعة مطلوب.','subject_id.required'=>'المادة مطلوبة.','subject_id.exists'=>'المادة غير موجودة.',
            'max_students.required'=>'السعة مطلوبة.','monthly_fee.required'=>'الرسوم مطلوبة.','end_time.after'=>'وقت الانتهاء يجب أن يكون بعد البداية.',
        ];
    }
}
