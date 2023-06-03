<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SuppliersRequest extends FormRequest
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
            'name'=> 'required',
            'suppliers_categories_id'=> 'required',
            'start_balance_status' => 'required',
            'start_balance' => 'required|min:0',
            'active' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'name.required'=>"اسم الحساب المورد مطلوب ",
            'suppliers_categories_id.required'=>"اسم فئة المورد  مطلوب ",
            'start_balance_status.required'=>" حاله الحساب اول المده مطلوب",
            'start_balance.required'=>"رصيد اول المده مطلوب",
            'active.required' => 'حاله التفعيل مطلوبة'
        ];
    }
}
