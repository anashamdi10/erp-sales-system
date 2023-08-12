<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AccountModel;
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
use App\Models\Inv_itemcard_movements;
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

        $customers = get_cols_where(new Customer(), array('customer_code', 'name'), array('com_code' => $com_code, 'active' => 1), 'id', 'DESC');
        $delegates = get_cols_where(new Delegate(), array('delegate_code', 'name'), array('com_code' => $com_code, 'active' => 1), 'id', 'DESC');
        $Sales_material_type = get_cols_where(new Sales_material_type(), array('id', 'name'), array('com_code' => $com_code, 'active' => 1), 'id', 'DESC');



        return view('admin.sales_invoices.index', ['data' => $data,'customers'=> $customers, 'delegates'=> $delegates, 'Sales_material_type'=> $Sales_material_type]);
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
            return view('admin.sales_invoices.load_model_sales_invoice', ['Sales_material_type' => $Sales_material_type, 'delgates' => $delgates, 'customers' => $customers]);
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
                                echo json_decode($item_card_Data['price']);
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



    public function do_add_new_sales_invoice(Request $request)
    {


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
            if ($request->is_has_customer == 1) {
                $data_insert['customer_code'] = $request->customer_code;
            }


            $data_insert['delegate_code'] = $request->delgate_code;
            $data_insert['sales_material_type'] = $request->sales_material_type;
            $data_insert['pill_type'] = $request->pill_type;


            $data_insert['created_at'] = date('Y:m:d H:i:s');
            $data_insert['date'] = date('Y:m:d ');
            $data_insert['updated_by'] = null;
            $data_insert['added_by'] = auth()->user()->name;
            $data_insert['com_code'] =  $com_code;

            $flag = insert(new  Sales_invoices(), $data_insert, false);
            if ($flag) {

                echo  $data_insert['auto_serial'];
            }
        }
    }

    function do_update_sales_invoice(Request $request)
    {
        if ($request->ajax()) {
            $com_code = auth()->user()->com_code;
            $invoice_data = get_cols_where_row(new Sales_invoices(), array('*'), array('com_code' => $com_code, 'auto_serial' => $request->auto_serial));
            $items_cards = get_cols_where(new Inv_itemCard(), array('item_code', 'name', 'item_type'), array('com_code' => $com_code, 'active' => 1), 'id', 'DESC');
            $stores = get_cols_where(new Store(), array('name', 'id'), array('com_code' => $com_code), 'id', 'DESC');
            $user_shifts = get_user_shift(new Admin_shifts(), new Treasure(), new Treasuries_transactionModel());
            $delegates = get_cols_where(new Delegate(), array('delegate_code', 'name'), array('com_code' => $com_code, 'active' => 1), 'id', 'DESC');
            $customers = get_cols_where(new Customer(), array('customer_code', 'name'), array('com_code' => $com_code, 'active' => 1), 'id', 'DESC');
            $Sales_material_type = get_cols_where(new Sales_material_type(), array('id', 'name'), array('com_code' => $com_code, 'active' => 1), 'id', 'DESC');

            $items_sales_details = get_cols_where(
                new Sales_invoices_details(),
                array('*'),
                array('com_code' => $com_code, 'sales_invoices_auto_serial' => $request->auto_serial),
                'id',
                "ASC"
            );

            if (!empty($items_sales_details)) {
                foreach ($items_sales_details as $info) {
                    $info->store_name = get_field_value(new Store(), 'name', array('com_code' => $com_code, 'id' => $info->store_id));
                    $info->item_name = get_field_value(new Inv_itemCard(), 'name', array('com_code' => $com_code, 'item_code' => $info->item_code));
                    $info->uom_name  = get_field_value(new Inv_ums(), 'name', array('com_code' => $com_code, 'id' => $info->uom_id));
                }
            }
                
            return view('admin.sales_invoices.load_model_update_sales_invoice', [
                'items_cards' => $items_cards, 'Sales_material_type' => $Sales_material_type,
                'stores' => $stores, 'user_shifts' => $user_shifts, 'delegates' => $delegates, 'customers' => $customers,
                'invoice_data' => $invoice_data, 'items_sales_details' => $items_sales_details
            ]);
        }
    }

    function add_items_to_invoice(Request $request)
    {
        try {

            if ($request->ajax()) {
                $com_code = auth()->user()->com_code;

                $invoice_data = get_cols_where_row(new Sales_invoices(), array('is_approved', 'invoice_date', 'is_has_customer', 'customer_code'), array('com_code' => $com_code, 'auto_serial' => $request->auto_serial));
                if (!empty($invoice_data)) {

                    if ($invoice_data['is_approved'] == 0) {

                        $batch_data = get_cols_where_row(new Inv_itemcard_batches(), array('quantity', 'unit_cost_price', 'id'), array(
                            'com_code' => $com_code,
                            'auto_serial' => $request->inv_itemcard_batches_id, 'item_code' => $request->item_code, 'store_id' => $request->store_id
                        ));

                        if (!empty($batch_data)) {

                            if ($batch_data['quantity'] >= $request->item_quantity) {
                                $itemCard_data = get_cols_where_row(new Inv_itemCard(), array("uom_id", "retail_uom_quantityToParent", "retail_uom_id", 'cost_price_retail', 'does_has_retailunit'), array("com_code" => $com_code, "item_code" => $request->item_code));

                                if (!empty($itemCard_data)) {

                                    $Main_uom_name = get_field_value(new Inv_ums(), 'name', array('com_code' => $com_code, 'id' => $itemCard_data['uom_id']));
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
                                    $data_insert_items['invoice_date'] = $invoice_data['invoice_date'];
                                    $data_insert_items['date'] = date('Y:m:d ');
                                    $data_insert_items['updated_by'] = null;
                                    $data_insert_items['added_by'] = auth()->user()->name;
                                    $data_insert_items['com_code'] =  $com_code;

                                    $flag_data = insert(new Sales_invoices_details(), $data_insert_items, true);
                                    if (!empty($flag_data)) {
                                        // خصم الكمية من الباتشات 
                                        // كميه الصنف الموجوده في المخاون 

                                        $quantityBeforeMove = get_sum_where(
                                            new Inv_itemcard_batches(),
                                            'quantity',
                                            array('item_code' => $request->item_code, 'com_code' => $com_code)
                                        );


                                        // حنجيب كمية الصنف بالمخزن المحدد معه الحالي بعد  الحركة
                                        $quantityBeforeMoveCurrentStore = get_sum_where(
                                            new Inv_itemcard_batches(),
                                            'quantity',
                                            array('item_code' => $request->item_code, 'com_code' => $com_code, 'store_id' => $request->store_id)
                                        );







                                        $dataupdate_batch['quantity'] = $batch_data['quantity'] - $request->item_quantity;

                                        $dataupdate_batch['toatal_cost_price'] = $batch_data['unit_cost_price'] * $dataupdate_batch['quantity'];

                                        $dataupdate_batch['updated_at'] = date("Y-m-d H:i:s");
                                        $dataupdate_batch['updated_by'] = auth()->user()->name;
                                        $flag = update(
                                            new Inv_itemcard_batches(),
                                            $dataupdate_batch,
                                            array('id' => $batch_data['id'], 'com_code' => $com_code)
                                        );



                                        if ($flag) {
                                            $quantityAfterMove = get_sum_where(
                                                new Inv_itemcard_batches(),
                                                'quantity',
                                                array('com_code' => $com_code, 'item_code' => $request->item_code)
                                            );
                                            // كمية الصنف بمخزن الفاتورة الشراء بعد اتمام حركة بكل باتشات 
                                            $quantityAfterMoveCurrentStore = get_sum_where(
                                                new Inv_itemcard_batches(),
                                                'quantity',
                                                array('com_code' => $com_code, 'item_code' => $request->item_code, 'store_id' => $request->store_id)
                                            );

                                            // تأثير على كارت الصنف  
                                            $itemMovementInsert['inv_itemcard_movements_categories'] = 2;
                                            $itemMovementInsert['item_code'] = $request->item_code;
                                            $itemMovementInsert['items_movements_types'] = 4;
                                            // كود الفاتورة الاب
                                            $itemMovementInsert['FK_table'] = $request->auto_serial;

                                            // كود الصف الابن بتفاصيل الفاتورة 
                                            $itemMovementInsert['FK_table_details'] = $flag_data->id;
                                            if ($invoice_data['is_has_customer'] == 1) {
                                                $customer_name = get_field_value(new Customer(), 'name', array("customer_code" => $invoice_data['customer_code']));
                                            } else {
                                                $customer_name = 'عميل نقدي';
                                            }
                                            $itemMovementInsert['byan'] = 'نظير مبيعات للعميل   ' . ' ' . $customer_name . 'رقم الفاتورة ' . $request->auto_serial;

                                            // كمية الصنف بكل المخازن قبل الحركة 
                                            $itemMovementInsert['quantity_befor_movement'] = 'عدد ' . " " . ($quantityBeforeMove * 1) . ' ' . $Main_uom_name;
                                            $itemMovementInsert['quantity_after_move'] = 'عدد ' . " " . ($quantityAfterMove * 1) . ' ' . $Main_uom_name;

                                            // كمية الصنف  بالمخزن الحالي بعد  الحركة 

                                            // كمية الصنف  بالمخزن الحالي قبل  الحركة 
                                            $itemMovementInsert['quantity_befor_move_store'] = 'عدد ' . " " . ($quantityBeforeMoveCurrentStore * 1) . ' ' . $Main_uom_name;



                                            // كمية الصنف  بالمخزن الحالي بعد  الحركة 
                                            $itemMovementInsert['quantity_after_move_store'] = 'عدد ' . " " . ($quantityAfterMoveCurrentStore  * 1) . ' ' . $Main_uom_name;






                                            $itemMovementInsert['store_id'] = $request->store_id;

                                            $itemMovementInsert['added_by'] = auth()->user()->name;
                                            $itemMovementInsert['created_at'] = date("Y-m-d H:i:s");
                                            $itemMovementInsert['date'] = date("Y-m-d ");
                                            $itemMovementInsert['com_code'] = $com_code;
                                            insert(new Inv_itemcard_movements(), $itemMovementInsert);
                                            // نجيب كمية الصنف من جدول الباتشات 
                                            DoUpdateItemCard(
                                                new Inv_itemCard(),
                                                $request->item_code,
                                                new Inv_itemcard_batches(),
                                                $itemCard_data['retail_uom_quantityToParent'],
                                                $itemCard_data['does_has_retailunit']
                                            );

                                            echo  json_encode("done");
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        } catch (\Exception $ex) {
            echo  'there is error' . $ex->getMessage();
        }
    }
    // reload_items_in_invoice
    function add_new_item_sales_row(Request $request)
    {
        
        if ($request->ajax()) {

            $com_code = auth()->user()->com_code;

            $sales_items_sales = get_cols_where(
                new Sales_invoices_details(),
                array('*'),
                array('com_code' => $com_code, 'sales_invoices_auto_serial' => $request->auto_serial),
                'id',
                'ASC'
            );

            if (!empty($sales_items_sales)) {

                foreach ($sales_items_sales as $info) {
                    $info->store_name = get_field_value(new Store(), 'name', array('com_code' => $com_code, 'id' => $info->store_id));
                    $info->item_name = get_field_value(new Inv_itemCard(), 'name', array('com_code' => $com_code, 'item_code' => $info->item_code));
                    $info->uom_name  = get_field_value(new Inv_ums(), 'name', array('com_code' => $com_code, 'id' => $info->uom_id));
                }

                return view('admin.sales_invoices.reload_sales_row', ['sales_items_sales' => $sales_items_sales]);
            }
        }
    }
    // recalclate_parent_invoice 
    public function reload_invoice_details(Request $request)
    {

        if ($request->ajax()) {
            $com_code = auth()->user()->com_code;
            $invoiceData = get_cols_where_row(
                new Sales_invoices(),
                array('*'),
                array('com_code' => $com_code, 'auto_serial' => $request->auto_serial)
            );
            if (!empty($invoiceData)) {
                if ($invoiceData['is_approved'] == 0) {

                    $dataUpdateParent['invoice_date'] = $request->invoice_date;
                    $dataUpdateParent['is_has_customer'] = $request->is_has_customer;

                    if ($request->is_has_customer == 1) {
                        $dataUpdateParent['customer_code'] = $request->customer_code;
                    }

                    $dataUpdateParent['sales_material_type'] = $request->sales_material_type;
                    $dataUpdateParent['delegate_code'] = $request->delegate_code;
                    $dataUpdateParent['total_cost_items'] = $request->total_cost_items;
                    $dataUpdateParent['tax_percent'] = $request->tax_percent;
                    $dataUpdateParent['tax_value'] = $request->tax_value;
                    $dataUpdateParent['total_befor_discount'] = $request->total_befor_discount;
                    $dataUpdateParent['discount_type'] = $request->discount_type;
                    $dataUpdateParent['discount_value'] = $request->discount_value;
                    $dataUpdateParent['discount_percent'] = $request->discount_percent;
                    $dataUpdateParent['total_cost'] = $request->total_cost;
                    $dataUpdateParent['pill_type'] = $request->pill_type;
                    $dataUpdateParent['what_paid'] = $request->what_paid;
                    $dataUpdateParent['what_remain'] = $request->what_remain;
                    $dataUpdateParent['auto_serial'] = $request->auto_serial;
                    $dataUpdateParent['notes'] = $request->notes;
                    $dataUpdateParent['money_for_account'] = $request->total_cost * (-1);
                    $dataUpdateParent['updated_at'] = date("Y-m-d H:i:s");
                    $dataUpdateParent['updated_by'] = auth()->user()->name;
                    $dataUpdateParent['approved_by'] = auth()->user()->name;

                    $flag = update(new Sales_invoices(),  $dataUpdateParent, array('com_code' => $com_code, 'auto_serial' => $request->auto_serial));
                    if ($flag) {
                        echo  json_encode("done");
                    } else {
                        dd('fshal');
                    }
                }
            }
        }
    }

    public function delete_item_sales_details_row(Request $request)
    {

        if ($request->ajax()) {
            $com_code = auth()->user()->com_code;
            $invoiceData = get_cols_where_row(
                new Sales_invoices(),
                array('is_approved', 'is_has_customer', 'customer_code'),
                array('com_code' => $com_code, 'auto_serial' => $request->auto_serial)
            );
            if (!empty($invoiceData)) {
            
                if ($invoiceData['is_approved'] == 0) {
                    $sales_invoice_details = get_cols_where_row(
                        new Sales_invoices_details(),
                        array('sales_invoices_auto_serial', 'quantity', 'store_id', 'item_code' , 'batch_auto_serial'),
                        array('com_code' => $com_code, 'id' => $request->id)
                    );

                    if (!empty($sales_invoice_details)) {
                        
                        $batch_data = get_cols_where_row(
                            new Inv_itemcard_batches(),
                            array('quantity', 'unit_cost_price', 'id'),
                            array('com_code' => $com_code, 'auto_serial' => $sales_invoice_details['batch_auto_serial'])
                        );
                        
                        if (!empty($batch_data)) {
                        
                            $itemCard_data = get_cols_where_row(
                                new Inv_itemCard(),
                                array("uom_id", "retail_uom_quantityToParent", "retail_uom_id", 'cost_price_retail', 'does_has_retailunit'),
                                array("com_code" => $com_code, "item_code" =>  $sales_invoice_details['item_code'])
                            );

                            if (!empty($itemCard_data)) {
                            
                                $Main_uom_name = get_field_value(new Inv_ums(), 'name', array('com_code' => $com_code, 'id' => $itemCard_data['uom_id']));
                                $flag = delete(new Sales_invoices_details(), array('com_code' => $com_code, 'id' => $request->id));
                                if ($flag) {



                                    // رد الكمية الى  الباتشات 
                                    // كميه الصنف الموجوده في المخاون 

                                    $quantityBeforeMove = get_sum_where(
                                        new Inv_itemcard_batches(),
                                        'quantity',
                                        array('item_code' => $sales_invoice_details['item_code'], 'com_code' => $com_code)
                                    );


                                    // نجيب كمية الصنف بمخزن الفاتورة الحالي قبل الحركة 
                                    $quantityBeforeMoveCurrentStore = get_sum_where(
                                        new Inv_itemcard_batches(),
                                        'quantity',
                                        array('item_code' => $sales_invoice_details['item_code'], 'com_code' => $com_code, 'store_id' => $sales_invoice_details['store_id'])
                                    );






                                    // هنا هنرد الكمية الى باتش الصنف 
                                    // تحديث على الباتش القديمة
                                    $dataupdate_batch['quantity'] = $batch_data['quantity'] + $sales_invoice_details['quantity'];

                                    $dataupdate_batch['toatal_cost_price'] = $batch_data['unit_cost_price'] * $dataupdate_batch['quantity'];

                                    $dataupdate_batch['updated_at'] = date("Y-m-d H:i:s");
                                    $dataupdate_batch['updated_by'] = auth()->user()->name;
                                    $flag = update(
                                        new Inv_itemcard_batches(),
                                        $dataupdate_batch,
                                        array('id' => $batch_data['id'], 'com_code' => $com_code)
                                    );



                                    if ($flag) {
                                        $quantityAfterMove = get_sum_where(
                                            new Inv_itemcard_batches(),
                                            'quantity',
                                            array('com_code' => $com_code, 'item_code' => $sales_invoice_details['item_code'])
                                        );


                                        // حنجيب كمية الصنف بالمخزن المحدد معه الحالي بعد  الحركة  
                                        $quantityAfterMoveCurrentStore = get_sum_where(
                                            new Inv_itemcard_batches(),
                                            'quantity',
                                            array('com_code' => $com_code, 'item_code' => $sales_invoice_details['item_code'], 'store_id' => $sales_invoice_details['store_id'])
                                        );

                                        // تأثير على كارت الصنف  
                                        $itemMovementInsert['inv_itemcard_movements_categories'] = 2;
                                        $itemMovementInsert['item_code'] = $sales_invoice_details['item_code'];
                                        $itemMovementInsert['items_movements_types'] = 15;
                                        // كود الفاتورة الاب
                                        $itemMovementInsert['FK_table'] = $sales_invoice_details['sales_invoices_auto_serial'];

                                        // كود الصف الابن بتفاصيل الفاتورة 
                                        $itemMovementInsert['FK_table_details'] = $request->id;
                                        if ($invoiceData['is_has_customer'] == 1) {
                                            $customer_name = get_field_value(new Customer(), 'name', array("customer_code" => $invoiceData['customer_code']));
                                        } else {
                                            $customer_name = 'عميل نقدي';
                                        }
                                        $itemMovementInsert['byan'] = 'حذف الصنف من تقاصيل فاتورة مبيعات للعميل   ' . ' ' . $customer_name . 'رقم الفاتورة ' .  $sales_invoice_details['sales_invoices_auto_serial'];

                                        // كمية الصنف بكل المخازن قبل الحركة 
                                        $itemMovementInsert['quantity_befor_movement'] = 'عدد ' . " " . ($quantityBeforeMove * 1) . ' ' . $Main_uom_name;
                                        $itemMovementInsert['quantity_after_move'] = 'عدد ' . " " . ($quantityAfterMove * 1) . ' ' . $Main_uom_name;

                                        // كمية الصنف  بالمخزن الحالي بعد  الحركة 

                                        // كمية الصنف  بالمخزن الحالي قبل  الحركة 
                                        $itemMovementInsert['quantity_befor_move_store'] = 'عدد ' . " " . ($quantityBeforeMoveCurrentStore * 1) . ' ' . $Main_uom_name;



                                        // كمية الصنف  بالمخزن الحالي بعد  الحركة 
                                        $itemMovementInsert['quantity_after_move_store'] = 'عدد ' . " " . ($quantityAfterMoveCurrentStore  * 1) . ' ' . $Main_uom_name;






                                        $itemMovementInsert['store_id'] = $sales_invoice_details['store_id'];

                                        $itemMovementInsert['added_by'] = auth()->user()->name;
                                        $itemMovementInsert['created_at'] = date("Y-m-d H:i:s");
                                        $itemMovementInsert['date'] = date("Y-m-d ");
                                        $itemMovementInsert['com_code'] = $com_code;
                                        insert(new Inv_itemcard_movements(), $itemMovementInsert);
                                        // نجيب كمية الصنف من جدول الباتشات 
                                        DoUpdateItemCard(
                                            new Inv_itemCard(),
                                            $sales_invoice_details['item_code'],
                                            new Inv_itemcard_batches(),
                                            $itemCard_data['retail_uom_quantityToParent'],
                                            $itemCard_data['does_has_retailunit']
                                        );
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }



    public function do_close_and_approve(Request $request)
    {

        if ($request->ajax()) {
            $com_code = auth()->user()->com_code;
            $invoiceData = get_cols_where_row(
                new Sales_invoices(),
                array('is_approved', 'total_cost', 'customer_code', 'is_has_customer'),
                array('com_code' => $com_code, 'auto_serial' => $request->auto_serial)
            );
            if (!empty($invoiceData)) {
                if ($invoiceData['is_approved'] == 0) {
                    $dataUpdateParent['money_for_account'] = $invoiceData['total_cost'];
                    $dataUpdateParent['is_approved'] = 1;
                    $dataUpdateParent['updated_at'] = date("Y-m-d H:i:s");
                    $dataUpdateParent['updated_by'] = auth()->user()->name;
                    $dataUpdateParent['approved_by'] = auth()->user()->name;
                    $dataUpdateParent['what_paid'] = $request->what_paid;
                    $dataUpdateParent['what_remain'] = $request->what_remain;
                    if($invoiceData['is_has_customer'] == 1){
                        $customerData = get_cols_where_row(new Customer(), array('account_number'), array('com_code' => $com_code, 'customer_code' => $invoiceData['customer_code']));
                        $dataUpdateParent['account_number'] = $customerData['account_number'];
                    }
                    $flag = update(new Sales_invoices(), $dataUpdateParent ,array('com_code' => $com_code, 'auto_serial' => $request->auto_serial));
                    if($flag){
                        if ($request->what_paid > 0) {
                            // check if the user has treasures id 0r money 
                            $user_shift = get_user_shift(new Admin_shifts(), new Treasure(), new Treasuries_transactionModel());
                            
                            $trussery_data = get_cols_where_row(new Treasure(), array('last_isal_collect'), array('com_code' => $com_code, 'id' => $user_shift['treasures_id']));
                            

                            $last_record_Treasuries_transaction =  get_cols_where_row_orderby(new Treasuries_transactionModel(), array('auto_serial'), array('com_code' => $com_code), 'auto_serial', "DESC");

                            if (!empty($last_record_Treasuries_transaction)) {
                                $data_insert_Treasuries_transaction['auto_serial'] = $last_record_Treasuries_transaction['auto_serial'] + 1;
                            } else {
                                $data_insert_Treasuries_transaction['auto_serial'] = 1;
                                
                            }

                            $data_insert_Treasuries_transaction['isal_number'] = $trussery_data['last_isal_collect'] + 1;
                            $data_insert_Treasuries_transaction['mov_date'] = date("Y-m-d");
                            if ($invoiceData['is_has_customer'] == 1) {
                                $data_insert_Treasuries_transaction['account_number'] = $customerData['account_number'];
                                $data_insert_Treasuries_transaction['is_account'] = 1;
                            }
                            $data_insert_Treasuries_transaction['mov_type'] = 5;
                            $data_insert_Treasuries_transaction['treasures_id'] = $user_shift['treasures_id'];
                            //  creadit دائن
                            $data_insert_Treasuries_transaction['money'] = $request->what_paid ;

                            // debit  مدين
                            $data_insert_Treasuries_transaction['money_for_account'] =  $request->what_paid * (-1);

                            $data_insert_Treasuries_transaction['the_foregin_key'] = $request->auto_serial;
                            $data_insert_Treasuries_transaction['bayan'] = 'تحصيل  نظير فاتورة مبيعات  رقم ' .  $request->auto_serial;
                        
                            $data_insert_Treasuries_transaction['is_approved'] = 1;
                            $data_insert_Treasuries_transaction['shift_code'] =  $user_shift['shift_code'];
                            $data_insert_Treasuries_transaction['com_code'] =  $com_code;
                            $data_insert_Treasuries_transaction['created_at'] = date("Y-m-d H:i:s");
                            $data_insert_Treasuries_transaction['added_by'] = auth()->user()->name;

                            $flage = insert(new Treasuries_transactionModel, $data_insert_Treasuries_transaction);


                            if ($flage) {
                                $data_to_update['last_isal_exchange'] = $data_insert_Treasuries_transaction['isal_number'];
                                update(new Treasure(),  $data_to_update, array('id' => $com_code, 'id' => $user_shift['treasures_id']));
                                
                            }
                            
                        }


                        if ($invoiceData['is_has_customer'] == 1) {
                            // afect on customer financial account balance 
                            refresh_account_blance_customer($customerData['account_number'],new AccountModel(),new Customer(),
                                new Treasuries_transactionModel(),new Sales_invoices(),false);
                        }

                        echo  json_encode("done");
                    }
                }
            }
        }
    }


    public function load_usershiftDiv(Request $request)
    {

        if ($request->ajax()) {

            $user_shifts = get_user_shift(new Admin_shifts(), new Treasure(), new Treasuries_transactionModel());
            return view("admin.sales_invoices.load_usershifts", ['user_shifts' => $user_shifts]);
        }
    }

    public function delete_invoice($id)
    {
        try {



            $com_code = auth()->user()->com_code;
            $Sales_invoice_data= get_cols_where_row(new Sales_invoices(), array('is_approved', 'auto_serial'), array('id' => $id, "com_code" => $com_code));



            if (empty($Sales_invoice_data)) {
                return redirect()->back()->with(['error' => 'عفوا العنصر غير موجود ']);
            }


        

            $flag = delete(new Sales_invoices(), array('id' => $id, "com_code" => $com_code));

            if ($flag) {

                delete(new Sales_invoices_details(), array('sales_invoices_auto_serial' => $Sales_invoice_data['auto_serial'], "com_code" => $com_code));
                return redirect()->route('admin.SalesInvoices.index')->with(['success' => 'لقد تم حذف  الفاتورة  بنجاح']);
            }
        } catch (\Exception $ex) {
            return redirect()->back()->with(['error' => 'عفوا حصل خطأ' . $ex->getMessage()])->withInput();
        }
    }

    public function load_sales_invoice_details(Request $request){
        if ($request->ajax()) {
            $com_code = auth()->user()->com_code;
            $invoice_data = get_cols_where_row(new Sales_invoices(), array('*'), array('com_code' => $com_code, 'auto_serial' => $request->auto_serial));
            $items_cards = get_cols_where(new Inv_itemCard(), array('item_code', 'name', 'item_type'), array('com_code' => $com_code, 'active' => 1), 'id', 'DESC');
            $stores = get_cols_where(new Store(), array('name', 'id'), array('com_code' => $com_code), 'id', 'DESC');
            $user_shifts = get_user_shift(new Admin_shifts(), new Treasure(), new Treasuries_transactionModel());
            $delegates = get_cols_where(new Delegate(), array('delegate_code', 'name'), array('com_code' => $com_code, 'active' => 1), 'id', 'DESC');
            $customers = get_cols_where(new Customer(), array('customer_code', 'name'), array('com_code' => $com_code, 'active' => 1), 'id', 'DESC');
            $Sales_material_type = get_cols_where(new Sales_material_type(), array('id', 'name'), array('com_code' => $com_code, 'active' => 1), 'id', 'DESC');

            $items_sales_details = get_cols_where(
                new Sales_invoices_details(),
                array('*'),
                array('com_code' => $com_code, 'sales_invoices_auto_serial' => $request->auto_serial),
                'id',
                "ASC"
            );

            if (!empty($items_sales_details)) {
                foreach ($items_sales_details as $info) {
                    $info->store_name = get_field_value(new Store(), 'name', array('com_code' => $com_code, 'id' => $info->store_id));
                    $info->item_name = get_field_value(new Inv_itemCard(), 'name', array('com_code' => $com_code, 'item_code' => $info->item_code));
                    $info->uom_name  = get_field_value(new Inv_ums(), 'name', array('com_code' => $com_code, 'id' => $info->uom_id));
                }
            }

            return view('admin.sales_invoices.load_model_sales_invoicedetails', [
                'items_cards' => $items_cards, 'Sales_material_type' => $Sales_material_type,
                'stores' => $stores, 'user_shifts' => $user_shifts, 'delegates' => $delegates, 'customers' => $customers,
                'invoice_data' => $invoice_data, 'items_sales_details' => $items_sales_details
            ]);
        }
    }




    public function ajax_search(Request $request)
    {



        if ($request->ajax()) {

            $com_code = auth()->user()->com_code;

            $searchbyradio = $request->searchbyradio;
            $search_by_text = $request->search_by_text;
            $customer_code_search = $request->customer_code_search;
            $delegates_code_search = $request->delegates_code_search;
            $Sales_matrial_types_search = $request->Sales_matrial_types_search;
            $pill_type_search = $request->pill_type_search;
            $discount_type_search = $request->discount_type_search;
            $is_approved_search = $request->is_approved_search;
            $invoice_date_from = $request->invoice_date_from;
            $invoice_date_to = $request->invoice_date_to;
            
            
            
        
        


            if ($customer_code_search == 'all') {
                $field1 = "id";
                $operator1 = ">";
                $value1 = 0;
            } elseif($customer_code_search == null ) {
                $field1 = "customer_code";
                $operator1 = "=";
                $value1 = $customer_code_search;
            } else {
                $field1 = "customer_code";
                $operator1 = "=";
                $value1 = $customer_code_search;
            }

            if ($delegates_code_search == 'all') {
                $field2 = "id";
                $operator2 = ">";
                $value2 = 0;
            } else {
                $field2 = "delegate_code";
                $operator2 = "=";
                $value2 = $delegates_code_search;
            }

            if ($Sales_matrial_types_search == 'all') {
                $field3 = "id";
                $operator3 = ">";
                $value3 = 0;
            } else {
                $field3 = "sales_material_type";
                $operator3 = "=";
                $value3 = $Sales_matrial_types_search;
            }


            if ($pill_type_search == 'all') {
                $field4 = "id";
                $operator4 = ">";
                $value4 = 0;
            } else {
                $field4 = "pill_type";
                $operator4 = "=";
                $value4 = $pill_type_search;
            }


            if ($discount_type_search == 'all') {
                $field5 = "id";
                $operator5 = ">";
                $value5 = 0;
            } else {
                $field5 = "discount_type";
                $operator5 = "=";
                $value5 = $discount_type_search;
            }


            if ($is_approved_search == 'all') {
                $field6 = "id";
                $operator6 = ">";
                $value6 = 0;
            } else {
                $field6 = "approved_by";
                $operator6 = "=";
                $value6 = $is_approved_search;
            }






            if ($invoice_date_from == '') {
                $field7 = "id";
                $operator7 = ">";
                $value7 = 0;
            } else {
                $field7 = "invoice_date";
                $operator7 = ">=";
                $value7 = $invoice_date_from;
            }
            if ($invoice_date_to == '') {
                $field8 = "id";
                $operator8 = ">";
                $value8 = 0;
            } else {
                $field8 = "invoice_date";
                $operator8 = "<=";
                $value8 = $invoice_date_to;
            }

            if ($search_by_text != "") {

                if ($searchbyradio == 'auto_serial') {

                    $field9 = 'auto_serial';
                    $operator9 = "=";
                    $value9 =  $search_by_text;
                } else if ($searchbyradio == 'customer_code') {

                    $field9 = 'customer_code';
                    $operator9 = "=";
                    $value9 =  $search_by_text;
                } else if ($searchbyradio == 'account_number') {

                    $field9 = 'account_number';
                    $operator9 = "=";
                    $value9 =  $search_by_text;
                }
            } else {

                $field9 = 'id';
                $operator9 = ">";
                $value9 = 0;
            }
            $data = Sales_invoices::where($field1, $operator1, $value1)->where($field2, $operator2, $value2)
                ->where($field3, $operator3, $value3)->where($field4, $operator4, $value4)->where($field5, $operator5, $value5)
                ->where($field6, $operator6, $value6)->where($field7, $operator7, $value7)->where($field8, $operator8,$value8)
                ->where($field9, $operator9, $value9)->orderBy('id', 'DESC')->paginate(PAGINATEION_COUNT);

        


            if (!empty($data)) {
                foreach ($data as $info) {
                    $info->material_types_name = get_field_value(new Sales_material_type(), 'name', array('com_code' => $com_code, 'id' => $info->sales_material_type));

                    
                    if ($info->is_has_customer == 1) {
                        $info->customer_name = get_field_value(new Customer(), "name", array("com_code" => $com_code, "customer_code" => $info->customer_code));
                    } else {
                        $info->customer_name = "بدون عميل";
                    }
                }
            };
            return view('admin.suppliers_with_orders.ajax_search', ['data' => $data]);
        }
    }





}
