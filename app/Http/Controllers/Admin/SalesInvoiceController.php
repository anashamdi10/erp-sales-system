<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Sales_invoices;
use App\Models\Sales_material_type;
use App\Models\Inv_itemCard;
use App\Models\Inv_ums;
use App\Models\Customer;
use App\Models\Inv_itemcard_batches;
use App\Models\Store;
use Illuminate\Http\Request;

class SalesInvoiceController extends Controller
{
    public function index()
    {
        $com_code = auth()->user()->com_code;
        $data = get_cols_where_p(new Sales_invoices(), array('*'), array('com_code' => $com_code), 'id', 'DESC', PAGINATEION_COUNT);
        if (!empty($data)) {
            foreach ($data as $info) {
                $info->material_types_name = get_field_value(new Sales_material_type(), 'name', array('com_code' => $com_code, 'id' => $info->sales_material_type));
                if ($info->is_has_customer == 1) {
                    $info->customer_name = get_field_value(new Customer(), 'name', array('customer_code' => $info->customer_code, 'com_code' => $com_code));
                } else {
                    $info->customer_name = 'بدون عميل ';
                }
            }
        }


        return view('admin.sales_invoices.index', ['data' => $data]);
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

    public function load_model_add(Request $request)
    {
        $com_code = auth()->user()->com_code;
        if ($request->ajax()) {
            $items_cards = get_cols_where(new Inv_itemCard(), array('item_code', 'name', 'item_type'), array('com_code' => $com_code, 'active' => 1), 'id', 'DESC');
            $stores = get_cols_where(new Store(), array('name', 'id'), array('com_code' => $com_code), 'id', 'DESC');
            return view('admin.sales_invoices.load_add_invoice', ['items_cards' => $items_cards, 'stores' => $stores]);
        }
    }

    public function get_item_batches(Request $request)
    {

        $com_code = auth()->user()->com_code;

        if ($request->ajax()) {
            $item_card_Data = get_cols_where_row(new Inv_itemCard(), array("item_type", "uom_id", "retail_uom_quantityToParent"), array("com_code" => $com_code, "item_code" => $request->item_code));
            if (!empty($item_card_Data)) {

                $requesed['uom_id'] = $request->uom_id;

                $requesed['store_id'] = $request->store_id;
                $requesed['item_code'] = $request->item_code;
                $parent_uom = $item_card_Data['uom_id'];
                $uom_Data = get_cols_where_row(new Inv_ums(), array("name", "is_master"), array("com_code" => $com_code, "id" => $requesed['uom_id']));

                if (!empty($uom_Data)) {
                    //لو صنف مخزني يبقي ههتم بالتواريخ
                    if ($item_card_Data['item_type'] == 2) {

                        $inv_itemcard_batches = get_cols_where(
                            new Inv_itemcard_batches(),
                            array("unit_cost_price", "quantity", "production_date", "expired_date", "auto_serial"),
                            array("com_code" => $com_code, "store_id" => $requesed['store_id'], "item_code" => $requesed['item_code'], "inv_uoms_id" => $parent_uom),
                            'production_date',
                            'ASC'
                        );
                    } else {

                        $inv_itemcard_batches = get_cols_where(
                            new Inv_itemcard_batches(),
                            array("unit_cost_price", "quantity", "auto_serial"),
                            array("com_code" => $com_code, "store_id" => $requesed['store_id'], "item_code" => $requesed['item_code'], "inv_uoms_id" => $parent_uom),
                            'id',
                            'ASC'
                        );
                    }






                    return view("admin.sales_invoices.get_item_batches", ['item_card_Data' => $item_card_Data, 'requesed' => $requesed, 'uom_Data' => $uom_Data, 'inv_itemcard_batches' => $inv_itemcard_batches]);
                }
            }
        }
    }


    public function get_item_price(Request $request)
    {   
        $com_code = auth()->user()->com_code;

        if ($request->ajax()) {
            $item_card_Data = get_cols_where_row(new Inv_itemCard(), array("uom_id","price", "nos_gomla_price", "gomla_price",
            "price_retail","nos_gomla_price_retail","gomla_price_retail","does_has_retailunit","retail_uom_id"),
            array("com_code" => $com_code, "item_code" => $request->item_code));

                       
            if (!empty($item_card_Data)) {

                $uom_id = $request->uom_id;

                $sales_item_type = $request->sales_item_type;
                $uom_Data = get_cols_where_row(new Inv_ums(), array("is_master"), array("com_code" => $com_code, "id" => $uom_id));
               
              
                if (!empty($uom_Data)) {
                  
                    //لو صنف مخزني يبقي ههتم بالتواريخ
                    if ($uom_Data['is_master'] == 1) {
                       
                        if( $item_card_Data['uom_id']== $uom_id){
                          
                            if( $sales_item_type == 1){
                                
                                echo json_decode( $item_card_Data['price']);
                            }elseif( $sales_item_type == 2){
                                
                                echo json_decode($item_card_Data['nos_gomla_price']);
                            }else{
                               
                                echo json_decode( $item_card_Data['gomla_price']);
                            }
                        }
                       
                    } else {
                       
                        if($item_card_Data['retail_uom_id']==$uom_id and $item_card_Data['does_has_retailunit']==1){
                           
                            if( $sales_item_type == 1){
                               
                                echo json_decode( $item_card_Data['price_retail']);
                            }elseif( $sales_item_type == 2){
                               
                                echo json_decode( $item_card_Data['nos_gomla_price_retail']);
                            }else{
                                
                                echo json_decode( $item_card_Data['gomla_price_retail']);
                            }
                        }
                        
                    }






                }
            }
        }
    }

    public function add_sales_row(Request $request){

       
        $com_code = auth()->user()->com_code;

        if ($request->ajax()) {
             
            $received_data['store_id'] = $request->store_id;
            $received_data['sales_item_type'] = $request->sales_item_type;
            $received_data['item_code'] = $request->item_code;
            $received_data['uom_id'] = $request->uom_id;
            $received_data['inv_itemcard_batches_id'] = $request->inv_itemcard_batches_id;
            $received_data['item_quantity'] = $request->item_quantity;
            $received_data['price'] = $request->price;
            $received_data['is_normal_orOthers'] = $request->is_normal_orOthers;
            $received_data['total_cost'] = $request->total_cost;
            $received_data['store_name'] = $request->store_name;
            $received_data['sales_item_type_name'] = $request->sales_item_type_name;
            $received_data['uom_id_name'] = $request->uom_id_name;
            $received_data['item_code_name'] = $request->item_code_name;
            $received_data['is_normal_orOthers_name'] = $request->is_normal_orOthers_name;

            return view("admin.sales_invoices.add_sales_row", ['received_data' => $received_data]);
        }
    }
}
