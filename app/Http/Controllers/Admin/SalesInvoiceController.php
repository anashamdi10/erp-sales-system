<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Sales_invoices;
use App\Models\Sales_material_type;
use App\Models\Inv_itemCard;
use App\Models\Inv_ums;
use App\Models\Customer;

use Illuminate\Http\Request;

class SalesInvoiceController extends Controller
{
    public function index(){
        $com_code = auth()->user()->com_code;
        $data = get_cols_where_p(new Sales_invoices(),array('*'), array('com_code'=>$com_code),'id','DESC', PAGINATEION_COUNT);
        if(!empty($data)){
            foreach($data as $info){
                $info->material_types_name = get_field_value(new Sales_material_type(), 'name', array('com_code'=>$com_code,'id'=>$info->sales_material_type));
                if($info->is_has_customer == 1){
                    $info->customer_name = get_field_value(new Customer(),'name',array('customer_code'=>$info->customer_code, 'com_code'=>$com_code));
                }else{
                    $info->customer_name = 'بدون عميل ';
                }
            }
        }


        return view('admin.sales_invoices.index', ['data' => $data ]);
    }

    public function get_item_uoms(Request $request)
    {
        if ($request->ajax()) {
            $com_code = auth()->user()->com_code;
            $item_code = $request->item_code;

            $item_card_data = get_cols_where_row(
                new Inv_itemCard(),
                array('does_has_retailunit', 'retail_uom_id', 'uom_id'),
                array('item_code' => $item_code, 'com_code' => $com_code)
            );

            if (!empty($item_card_data['does_has_retailunit'] == 1)) {
                $item_card_data['parent_uom_name'] = \get_field_value(new Inv_ums(), 'name', array('id' => $item_card_data['uom_id']));
                $item_card_data['retail_uom_name'] = \get_field_value(new Inv_ums(), 'name', array('id' => $item_card_data['retail_uom_id']));
            } else {
                $item_card_data['parent_uom_name'] = \get_field_value(new Inv_ums(), 'name', array('id' => $item_card_data['uom_id']));
            }
        }

        return view("admin.sales_invoices.get_item_uoms", ['item_card_data' => $item_card_data]);
    }

    public function load_model_add(Request $request){
        $com_code = auth()->user()->com_code;
        if ($request->ajax()) {
            $items_cards = get_cols_where(new Inv_itemCard(), array('item_code', 'name'), array('com_code'=>$com_code, 'active'=>1 ),'id','DESC');
            return view('admin.sales_invoices.load_add_invoice', ['items_cards' => $items_cards ]);
            
        }
    }

    
}
