<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
class PaymentRequest extends FormRequest
{
    public function authorize(): bool { return true; }
    public function rules(): array
    {
        return [
            'student_id' => 'required|exists:students,id',
            'group_id' => 'nullable|exists:groups,id',
            'month' => 'required|integer|min:1|max:12',
            'year' => 'required|integer|min:2020|max:2035',
            'required_amount' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'paid_amount' => 'required|numeric|min:0',
            'payment_method' => 'nullable|in:cash,transfer,card,other',
            'payment_date' => 'nullable|date',
            'notes' => 'nullable|string|max:2000',
        ];
    }
    public function messages(): array
    {
        return ['student_id.required'=>'الطالب مطلوب.','required_amount.required'=>'المبلغ المطلوب حقل إجباري.','paid_amount.required'=>'المبلغ المدفوع مطلوب.'];
    }
}
