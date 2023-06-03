<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
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
            // 'phone' => 'required',
            // 'address' => 'required',
            'active' => 'required',

        ];
    }

    public function messages()
    {
        return [
            'name.required'=>"اسم المخزن مطلوب ",
            // 'phone.required' => ' الهاتف مطلوب',
            // 'address.required' => 'العنوان مطلوب',
            'active.required' => 'حاله التفعيل مطلوبة',
        ];
    }
}
