<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DelegatesRequestAdd extends FormRequest
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
            'name' => 'required',
            'start_balance_status' => 'required',
            'start_balance' => 'required|min:0',
            'active' => 'required',
            'percent_type' => 'required',
            'percent_sales_commission_kataei' => 'required',
            'percent_sales_commission_nosjomla' => 'required',
            'percent_sales_commission_jomla' => 'required',
            'percent_collect_commiission' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => "اسم الحساب  مطلوب ",
            'active.required' => "احتر حاله التفقعيل ",
            'start_balance_status.required' => " حاله الحساب اول المده مطلوب",
            'start_balance.required' => "رصيد اول المده مطلوب",
            'percent_type.required' => 'نوع العمولة بالفواتير',
            'percent_sales_commission_kataei.required' => 'نسبة العمولة المندوب بالمبيعات قطاعي  ',
            'percent_sales_commission_nosjomla.required' => ' نسبة العمولة المندوب بالمبيعات نص جملة',
            'percent_sales_commission_jomla.required' => ' نسبة العمولة المندوب بالمبيعات جملة ',
            'percent_collect_commiission.required' => 'نسبة المندوب بالتحصيل الفواتير اجل ',
        ];
    }
}
