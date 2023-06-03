<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AccountsRequest extends FormRequest
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
            'account_type' => 'required',
            'is_parent' => 'required',
            'start_balance_status' => 'required',
            'start_balance' => 'required|min:0',
            'is_archived' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'name.required'=>"اسم الحساب  مطلوب ",
            'account_type.required'=>"نوع الحساب مطلوب",
            'is_parent.required'=>" هل الحساب اب  مطلوب",
            'start_balance_status.required'=>" حاله الحساب اول المده مطلوب",
            'start_balance.required'=>"رصيد اول المده مطلوب",
            'is_archived.required' => 'حاله التفعيل مطلوبة'
        ];
    }
}
