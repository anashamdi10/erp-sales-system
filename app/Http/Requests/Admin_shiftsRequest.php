<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class Admin_shiftsRequest extends FormRequest
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
            'treasures_id'=> 'required',
           
        ];
    }

    public function messages()
    {
        return [
            'treasures_id.required'=>"اختر الخزنة  !! ",
            
        ];
    }
}
