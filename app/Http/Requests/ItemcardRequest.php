<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ItemcardRequest extends FormRequest
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
            'item_type' => 'required',
            'inv_itemcard_categories_id' => 'required',
            'uom_id' => 'required',
            'does_has_retailunit' => 'required',
            'retail_uom_id' => 'required_if:does_has_retailunit,1',
            'retail_uom_quantityToParent' => 'required_if:does_has_retailunit,1',
            'price'=>'required',
            'nos_gomla_price'=>'required',
            'gomla_price'=>'required',
            'cost_price'=>'required',
            'price_retail' => 'required_if:does_has_retailunit,1',
            'nos_gomla_price_retail' => 'required_if:does_has_retailunit,1',
            'gomla_price_retail' => 'required_if:does_has_retailunit,1',
            'cost_price_retail' => 'required_if:does_has_retailunit,1',
            'has_fixed_price' => 'required',
            'active' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'name.required'=>"اسم الصنف مطلوب ",
            'item_type.required'=>"نوع الصنف مطلوب ",
            'inv_itemcard_categories_id.required'=>"فئة الصنف مطلوب ",
            'uom_id.required' => '   الوحدة الاساسية للصنف مطلوبة',
            'does_has_retailunit.required' => ' حاله هل للصنف وحده تجزئة مطلوب',
            'retail_uom_id.required_if' => 'وحده التجزئة مطلوبة',
            'retail_uom_quantityToParent.required_if' => 'عدد وحدات التجزئة مطلوبة',
            'price.required'=>" سعر القاعي للوحده الاب مطلوب ",
            'nos_gomla_price.required'=>" سعرالنص الحملة للوحده الاب مطلوب ",
            'gomla_price.required'=>" سعر حملة للوحده الاب  مطلوب ",
            'cost_price.required'=>" تكلفة الشؤاء لوحده الاب مطلوب ",
            'price_retail.required_if'=>" سعر القطاعي لوحده التجزئة مطلوب ",
            'nos_gomla_price_retail.required_if'=>" سعر النص جملة لوحده التجزئة مطلوب ",
            'gomla_price_retail.required_if'=>" سعر الجملة لوحده التجزئة مطلوب ",
            'cost_price_retail.required_if'=>" سعر شراء لوحده التجزئة مطلوب ",
            'has_fixed_price.required'=>" هل للصنف سعر ثابت مطلوب ",
            'active.required'=>" سعر القطاعي لوحده التجزئة مطلوب ",

        ];
    }
}
