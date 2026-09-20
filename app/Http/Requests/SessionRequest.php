<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
class SessionRequest extends FormRequest
{
    public function authorize(): bool { return true; }
    public function rules(): array
    {
        return [
            'group_id' => 'required|exists:groups,id',
            'session_date' => 'required|date',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i',
            'topic' => 'nullable|string|max:500',
            'notes' => 'nullable|string|max:2000',
        ];
    }
    public function messages(): array { return ['group_id.required'=>'المجموعة مطلوبة.','session_date.required'=>'تاريخ الحصة مطلوب.']; }
}
