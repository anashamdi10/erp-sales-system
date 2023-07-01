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
use App\Models\Admin_shifts;
use App\Models\Treasure;
use App\Models\Treasuries_transactionModel;
use App\Models\Delegate;
use App\Models\Sales_invoices_details;
use Illuminate\Bus\Batch;
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

    public function load_model_offer_price(Request $request)
    {
        $com_code = auth()->user()->com_code;
        if ($request->ajax()) {
            $items_cards = get_cols_where(new Inv_itemCard(), array('item_code', 'name', 'item_type'), array('com_code' => $com_code, 'active' => 1), 'id', 'DESC');
            $stores = get_cols_where(new Store(), array('name', 'id'), array('com_code' => $com_code), 'id', 'DESC');
            $user_shifts = get_user_shift(new Admin_shifts(), new Treasure(), new Treasuries_transactionModel());
            return view('admin.sales_invoices.load_offer_price', ['items_cards' => $items_cards, 'stores' => $stores, 'user_shifts' => $user_shifts]);
        }
    }
    public function load_model_sales_invoice(Request $request)
    {
        $com_code = auth()->user()->com_code;
        if ($request->ajax()) {
            $delgates = get_cols_where(new Delegate(), array('delegate_code', 'name'), array('com_code' => $com_code, 'active' => 1), 'id', 'DESC');
            $customers = get_cols_where(new Customer(), array('customer_code', 'name'), array('com_code' => $com_code, 'active' => 1), 'id', 'DESC');
            $Sales_material_type = get_cols_where(new Sales_material_type(), array('id', 'name'), array('com_code' => $com_code, 'active' => 1), 'id', 'DESC');
            return view('admin.sales_invoices.load_model_sales_invoice', [ 'Sales_material_type'=>$Sales_material_type, 'delgates' => $delgates, 'customers' => $customers]);
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
            $item_card_Data = get_cols_where_row(
                new Inv_itemCard(),
                array(
                    "uom_id", "price", "nos_gomla_price", "gomla_price",
                    "price_retail", "nos_gomla_price_retail", "gomla_price_retail", "does_has_retailunit", "retail_uom_id"
                ),
                array("com_code" => $com_code, "item_code" => $request->item_code)
            );


            if (!empty($item_card_Data)) {

                $uom_id = $request->uom_id;

                $sales_item_type = $request->sales_item_type;
                $uom_Data = get_cols_where_row(new Inv_ums(), array("is_master"), array("com_code" => $com_code, "id" => $uom_id));


                if (!empty($uom_Data)) {

                    //لو صنف مخزني يبقي ههتم بالتواريخ
                    if ($uom_Data['is_master'] == 1) {

                        if ($item_card_Data['uom_id'] == $uom_id) {

                            if ($sales_item_type == 1) {

                                echo json_decode($item_card_Data['price']);
                            } elseif ($sales_item_type == 2) {

                                echo json_decode($item_card_Data['nos_gomla_price']);
                            } else {

                                echo json_decode($item_card_Data['gomla_price']);
                            }
                        }
                    } else {

                        if ($item_card_Data['retail_uom_id'] == $uom_id and $item_card_Data['does_has_retailunit'] == 1) {

                            if ($sales_item_type == 1) {

                                echo json_decode($item_card_Data['price_retail']);
                            } elseif ($sales_item_type == 2) {

                                echo json_decode($item_card_Data['nos_gomla_price_retail']);
                            } else {

                                echo json_decode($item_card_Data['gomla_price_retail']);
                            }
                        }
                    }
                }
            }
        }
    }

    public function add_sales_row(Request $request)
    {


        $com_code = auth()->user()->com_code;

        if ($request->ajax()) {


            $received_data['store_id'] = $request->store_id;
            $received_data['sales_item_type'] = $request->sales_item_type;
            $received_data['item_code'] = $request->item_code;
            $received_data['uom_id'] = $request->uom_id;
            $received_data['inv_itemcard_batches_id'] = $request->inv_itemcard_batches_id;
            $received_data['item_quantity'] = $request->item_quantity;
            $received_data['item_price'] = $request->price;
            $received_data['is_normal_orOther'] = $request->is_normal_orOthers;
            $received_data['item_total'] = $request->item_total;
            $received_data['store_name'] = $request->store_name;
            $received_data['uom_id_name'] = $request->uom_id_name;
            $received_data['item_code_name'] = $request->item_code_name;

            $received_data['sales_item_type_name'] = $request->sales_item_type_name;
            $received_data['is_normal_orOther_name'] = $request->is_normal_orOthers_name;
            $received_data['isparentuom'] = $request->isparentuom;

            return view("admin.sales_invoices.add_sales_row", ['received_data' => $received_data]);
        }
    }



    public function do_add_new_sales_invoice(Request $request){

      
        if ($request->ajax()) {
            $com_code = auth()->user()->com_code;



            // set item code  for itemcard
            $last_auto_serial_data  = get_cols_where_row_orderby(new Sales_invoices(), array("auto_serial"), array("com_code" => $com_code), 'id', 'DESC');
            if (!empty($last_auto_serial_data)) {
                $data_insert['auto_serial'] = $last_auto_serial_data['auto_serial'] + 1;
            } else {
                $data_insert['auto_serial'] = 1;
            }

            $data_insert['invoice_date'] = $request->invoice_date;
            $data_insert['is_has_customer'] = $request->is_has_customer;
            if($request->is_has_customer == 1){
                $data_insert['customer_code'] = $request->customer_code;
                
            }
            $data_insert['delgate_code'] = $request->delgate_code;
            $data_insert['sales_material_type'] = $request->sales_material_type;
            
            
            $data_insert['created_at'] = date('Y:m:d H:i:s');
            $data_insert['date'] = date('Y:m:d ');
            $data_insert['updated_by'] = null;
            $data_insert['added_by'] = auth()->user()->name;
            $data_insert['com_code'] =  $com_code ;
           
           $flag= insert(new  Sales_invoices(), $data_insert , false);
           if($flag){
            
            echo  $data_insert['auto_serial'] ;
           }


           

        }
    }

    function do_update_sales_invoice(Request $request){
        if ($request->ajax()) {
            $com_code = auth()->user()->com_code;
            $invoice_data = get_cols_where_row(new Sales_invoices(), array('*'), array('com_code'=>$com_code, 'auto_serial' =>$request->auto_serial));
            $items_cards = get_cols_where(new Inv_itemCard(), array('item_code', 'name', 'item_type'), array('com_code' => $com_code, 'active' => 1), 'id', 'DESC');
            $stores = get_cols_where(new Store(), array('name', 'id'), array('com_code' => $com_code), 'id', 'DESC');
            $user_shifts = get_user_shift(new Admin_shifts(), new Treasure(), new Treasuries_transactionModel());
            $delegates = get_cols_where(new Delegate(), array('delegate_code', 'name'), array('com_code' => $com_code, 'active' => 1), 'id', 'DESC');
            $customers = get_cols_where(new Customer(), array('customer_code', 'name'), array('com_code' => $com_code, 'active' => 1), 'id', 'DESC');
            $Sales_material_type = get_cols_where(new Sales_material_type(), array('id', 'name'), array('com_code' => $com_code, 'active' => 1), 'id', 'DESC');
            return view('admin.sales_invoices.load_model_update_sales_invoice', ['items_cards' => $items_cards, 'Sales_material_type'=>$Sales_material_type,
                'stores' => $stores, 'user_shifts' => $user_shifts, 'delegates' => $delegates, 'customers' => $customers, 'invoice_data'=>$invoice_data]);
        } 
    }

    function add_items_to_invoice(Request $request){
      
        if ($request->ajax()) {
            $com_code = auth()->user()->com_code;
           
            $invoice_data = get_cols_where_row(new Sales_invoices(), array('is_approved'), array('com_code'=>$com_code, 'auto_serial' =>$request->auto_serial));
            if(!empty($invoice_data)){
                if($invoice_data['is_approved'] == 0){
                    
                   $batch_data = get_cols_where_row(new Inv_itemcard_batches(),array('quantity'), array('com_code'=>$com_code,
                    'auto_serial'=>$request->inv_itemcard_batches_id, 'item_code'=>$request->item_code,'store_id'=>$request->store_id)); 
                
                    if(!empty($batch_data)){
                        
                        if($batch_data['quantity'] >= $request->item_quantity){
                            
                           
                            $data_insert_items['store_id'] = $request->store_id;
                            $data_insert_items['sales_invoices_auto_serial'] = $request->auto_serial;
                            $data_insert_items['sales_item_type'] = $request->sales_item_type;
                            $data_insert_items['item_code'] = $request->item_code;
                            $data_insert_items['uom_id'] = $request->uom_id;
                            $data_insert_items['batch_auto_serial'] = $request->inv_itemcard_batches_id;
                            $data_insert_items['quantity'] = $request->item_quantity;
                            $data_insert_items['unit_price'] = $request->price;
                            $data_insert_items['is_normal_orOthers'] = $request->is_normal_orOthers;
                            $data_insert_items['total_price'] = $request->item_total;
                            $data_insert_items['isparentuom'] = $request->isparentuom;
                            $data_insert_items['created_at'] = date('Y:m:d H:i:s');
                            $data_insert_items['invoice_date'] = date('Y:m:d ');
                            $data_insert_items['updated_by'] = null;
                            $data_insert_items['added_by'] = auth()->user()->name;
                            $data_insert_items['com_code'] =  $com_code ;
                           
                            $flag = insert(new Sales_invoices_details(), $data_insert_items);
                            if($flag){
                            

                               echo  json_encode("done");
                            }
                        }
                    }
            }
        } 
    }}

    

    
}
