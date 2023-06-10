<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class Trasuries_transactionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'mov_date'=> 'required',
            'mov_type' => 'required',
            'account_number' => 'required',
            'treasures_id' => 'required',
            'money' => 'required',
            'bayan' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'mov_date.required'=>" تاريخ الحركة  مطلوب ",
            'mov_type.required'=>"نوع الحركة مطلوب",
            'account_number.required'=>"الحساب المالي  مطلوب",
            'treasures_id.required' => ' كود الخزنة التحصيل  مطلوب',
            'money.required' => ' قيمة المبلغ التحصيلي مطلوب',
            'bayan.required' => 'البيان مطلوب'
        ];
    }
}
