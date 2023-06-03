<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class Inv_itemcard_categoryRequest extends FormRequest
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
            'active' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'name.required'=>"اسم الوحدة مطلوب ",
            'active.required' => 'حاله التفعيل  الوحدة مطلوبة',
        ];
    }
}
