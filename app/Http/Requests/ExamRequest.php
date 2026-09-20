<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
class ExamRequest extends FormRequest
{
    public function authorize(): bool { return true; }
    public function rules(): array
    {
        return [
            'group_id' => 'required|exists:groups,id',
            'title' => 'required|string|max:255',
            'exam_date' => 'required|date',
            'total_marks' => 'required|numeric|min:1',
            'pass_marks' => 'nullable|numeric|min:0|lt:total_marks',
            'notes' => 'nullable|string|max:2000',
        ];
    }
    public function messages(): array
    {
        return ['title.required'=>'عنوان الامتحان مطلوب.','group_id.required'=>'المجموعة مطلوبة.','total_marks.required'=>'الدرجة الكلية مطلوبة.'];
    }
}
