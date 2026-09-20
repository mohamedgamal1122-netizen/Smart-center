<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
class ExpenseRequest extends FormRequest
{
    public function authorize(): bool { return true; }
    public function rules(): array
    {
        return [
            'category' => 'required|in:rent,electricity,water,salaries,supplies,marketing,maintenance,other',
            'amount' => 'required|numeric|min:0.01',
            'expense_date' => 'required|date',
            'payment_method' => 'nullable|string|max:50',
            'description' => 'nullable|string|max:2000',
        ];
    }
    public function messages(): array
    {
        return ['category.required'=>'التصنيف مطلوب.','amount.required'=>'المبلغ مطلوب.','expense_date.required'=>'التاريخ مطلوب.'];
    }
}
