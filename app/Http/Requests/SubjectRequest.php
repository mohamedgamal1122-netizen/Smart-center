<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
class SubjectRequest extends FormRequest
{
    public function authorize(): bool { return true; }
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255|unique:subjects,name,'.($this->route('subject')?->id ?? 'NULL'),
            'code' => 'nullable|string|max:50',
            'grade' => 'nullable|string|max:100',
            'is_active' => 'nullable|boolean',
        ];
    }
    public function messages(): array { return ['name.required'=>'اسم المادة مطلوب.','name.unique'=>'المادة موجودة مسبقاً.']; }
}
