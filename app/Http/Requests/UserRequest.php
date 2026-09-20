<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
class UserRequest extends FormRequest
{
    public function authorize(): bool { return true; }
    public function rules(): array
    {
        $id = $this->route('user')?->id;
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,'.($id??'NULL'),
            'password' => [$this->isMethod('post') ? 'required' : 'nullable','string','min:8','confirmed'],
            'role' => 'required|in:admin,receptionist,teacher,accountant',
            'phone' => 'nullable|string|max:20',
            'is_active' => 'nullable|boolean',
        ];
    }
    public function messages(): array
    {
        return ['name.required'=>'الاسم مطلوب.','email.required'=>'البريد مطلوب.','email.unique'=>'البريد مستخدم من قبل.','password.required'=>'كلمة المرور مطلوبة.','password.min'=>'كلمة المرور قصيرة.','role.required'=>'الدور مطلوب.'];
    }
}
