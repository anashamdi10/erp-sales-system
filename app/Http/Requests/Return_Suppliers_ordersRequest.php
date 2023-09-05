<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class Return_Suppliers_ordersRequest extends FormRequest
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
            'supplier_code' => 'required',
            'pill_type' => 'required',
            'order_date' => 'required',
        

        ];
    }

    public function messages()
    {
        return [
            'supplier_code.required' => "اختر اسم المورد  ",
            'pill_type.required' => " اختر نوع الفاتورة  ",
            'order_date.required' => "تاريخ  الفاتورة  ",

        ];
    }
}
