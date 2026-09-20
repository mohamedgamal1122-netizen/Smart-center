<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
class ParentRequest extends FormRequest
{
    public function authorize(): bool { return true; }
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'phone2' => 'nullable|string|max:20',
            'relation' => 'nullable|in:father,mother,guardian,other',
            'address' => 'nullable|string|max:500',
            'notes' => 'nullable|string|max:2000',
        ];
    }
    public function messages(): array
    {
        return ['name.required'=>'اسم ولي الأمر مطلوب.','phone.required'=>'رقم الهاتف مطلوب.'];
    }
}
