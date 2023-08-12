<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class Admin_panel_settings_Request extends FormRequest
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
            'system_name'=> 'required',
            'address' => 'required',
            'phone' => 'required',
            'customer_parent_account_number' => 'required',
            'suppliers_parent_account_number' => 'required',
            'delegates_parent_account_number' => 'required',
            'employees_parent_account_number' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'system_name.required'=>"اسم الشركة مطلوب ",
            'address.required'=>"عنوان الشؤكة  مطلوبه",
            'phone.required'=>" هاتف الشركة  مطلوب",
            'customer_parent_account_number.required'=>" رقم الحساب المالي للعملاء الاب مطلوب",
            'suppliers_parent_account_number.required'=>" رقم الحساب المالي للموردين الاب مطلوب",
            'delegates_parent_account_number.required'=>" رقم الحساب المالي للمناديب الاب مطلوب",
            'employees_parent_account_number.required'=>" رقم الحساب المالي للموظفين الاب مطلوب",
        ];
        
    }
    
    
}
