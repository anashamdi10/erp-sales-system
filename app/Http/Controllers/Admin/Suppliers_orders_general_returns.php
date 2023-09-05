<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Suppliers_orders_returnsRequest;
use App\Http\Requests\Return_Suppliers_ordersRequest;
use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\SuppliersModel;
use App\Models\Suppliers_with_orders_detailsModel;
use App\Models\Store;
use App\Models\Suppliers_orderModel;
use App\Models\Inv_itemCard;
use App\Models\Inv_ums;
use App\Models\Inv_itemcard_batches;
use App\Models\Admin_shifts;
use App\Models\Treasuries_transactionModel;
use App\Models\Treasure;
use App\Models\Inv_itemcard_movements;
use App\Models\AccountModel;


class Suppliers_orders_general_returns extends Controller
{
    public function index()
    {
        $com_code = auth()->user()->com_code;

        $data = get_cols_where_p(new Suppliers_orderModel(), array('*'), array("com_code" => $com_code, 'order_type' => 3), 'id', "DESC", PAGINATEION_COUNT);

        if (!empty($data)) {
            foreach ($data as $info) {
                $info->added_by_admin = Admin::where('id', $info->added_by)->value('name');
                $info->supplier_name = SuppliersModel::where('supplier_code', $info->supplier_code)->value('name');
                $info->store_name = Store::where('id', $data['store_id'])->value('name');

                if ($info->updated_by > 0 and $info->updated_by != null) {
                    $info->updated_by_admin = Admin::where('id', $info->updated_by)->value('name');
                }
            }
        };

        $suppliers = get_cols_where(new SuppliersModel(), array('supplier_code', 'name'), array('com_code' => $com_code, 'active' => 1), 'id', 'DESC');
        $stores = get_cols_where(new Store(), array('id', 'name'), array('com_code' => $com_code, 'active' => 1), 'id', 'DESC');


        return view('admin.suppliers_with_orders_general_returns.index', ['data' => $data, 'suppliers' => $suppliers, 'stores' => $stores]);
    }

    public function create()
    {
        $com_code = auth()->user()->com_code;
        $suppliers = get_cols_where(new SuppliersModel(), array('supplier_code', 'name'), array('com_code' => $com_code, 'active' => 1), 'id', 'DESC');
        $stores = get_cols_where(new Store(), array('id', 'name'), array('com_code' => $com_code, 'active' => 1), 'id', 'DESC');

        return view('admin.suppliers_with_orders_general_returns.create', ['suppliers' => $suppliers, 'stores' => $stores]);
    }

    public function store(Suppliers_orders_returnsRequest $request)
    {
        try {
            $com_code = auth()->user()->com_code;
            $supplierData = get_cols_where_row(new SuppliersModel(), array('account_number'), array('supplier_code' => $request->supplier_code, "com_code" => $com_code));
            if (empty($supplierData)) {
                return redirect()->back()
                    ->with(['error' => 'عفوا غير قادر على الوصول الى بيانات المورد المحدد'])
                    ->withInput();
            }



            // set item code  for itemcard
            $row  = get_cols_where_row_orderby(new Suppliers_orderModel, array("auto_serial"), array("com_code" => $com_code , 'order_type' => 3), 'id', 'DESC');
            if (!empty($row)) {
                $data_insert['auto_serial'] = $row['auto_serial'] + 1;
            } else {
                $data_insert['auto_serial'] = 1;
            }


            $data_insert['order_date'] = $request->order_date;
            $data_insert['order_type'] = 3;
            $data_insert['supplier_code'] = $request->supplier_code;
            $data_insert['pill_type'] = $request->pill_type;
            $data_insert['store_id'] = $request->store_id;
            $data_insert['account_number'] =  $supplierData['account_number'];


            $data_insert['created_at'] = date('Y:m:d H:i:s');
            $data_insert['updated_by'] = null;
            $data_insert['added_by'] = auth()->user()->name;
            $data_insert['com_code'] = $com_code;

            
            insert(new Suppliers_orderModel(),$data_insert);
            $id = get_field_value(new Suppliers_orderModel(), 'id',  array(
                'com_code' => $com_code,
                'auto_serial' => $data_insert['auto_serial'], 'order_type' => 3
            ));
            return redirect()->route('admin.suppliers_orders_general_return.show',$id)->with('success', 'لقد تم إضافة بيانات بنجاح');
        } catch (\Exception $ex) {
            return redirect()->back()->with(['error' => 'عفوا حصل خطأ في create' . $ex->getMessage()])->withInput();
        }
    }

    public function edit($id)
    {
        $com_code = auth()->user()->com_code;
        $data = get_cols_where_row(new Suppliers_orderModel(), array('*'), array('id' => $id, "com_code" => $com_code, 'order_type' => 3));

        if (empty($data)) {
            return redirect()->route('admin.suppliers_with_orders.index')->with(['error' => 'عفوا غير قادر على الوصول للبيانات المطلوبة !!']);
        };
        if ($data['is_approved'] == 1) {
            return redirect()->route('admin.suppliers_with_orders.index')->with(['error' => 'عفوا لا يمكن تحديث على فاتورة معتمده ومؤرشفة']);
        };

        $suppliers = get_cols_where(new SuppliersModel(), array('supplier_code', 'name'), array('com_code' => $com_code, 'active' => 1), 'id', 'DESC');
        $stores = get_cols_where(new Store(), array('id', 'name'), array('com_code' => $com_code, 'active' => 1), 'id', 'DESC');
        $git_item_counter_details = get_counter(new Suppliers_with_orders_detailsModel(), array('com_code'=>$com_code,
                                    'order_type' => 3, 'suppliers_with_orders_auto_serial'=>$data['auto_serial']));


        return view('admin.suppliers_with_orders_general_returns.edit', ['data' => $data, 'suppliers' => $suppliers, 
                    'stores' => $stores , 'git_item_counter_details' => $git_item_counter_details]);
    }

    public function update($id, Return_Suppliers_ordersRequest $request)
    {
        try {
            

            $com_code = auth()->user()->com_code;
            $data = get_cols_where_row(new Suppliers_orderModel(), array('is_approved'), array('id' => $id, "com_code" => $com_code, 'order_type' => 3));

            if (empty($data)) {
                return redirect()->route('admin.suppliers_orders_general_return.index')->with(['error' => 'غير قادر على الوصول للبيانات المطلوبة ']);
            };

            $supplierData = get_cols_where_row(new SuppliersModel(), array('account_number'), array('supplier_code' => $request->supplier_code, "com_code" => $com_code));
            if (empty($supplierData)) {
                return redirect()->back()
                    ->with(['error' => 'عفوا غير قادر على الوصول الى بيانات المورد المحدد'])
                    ->withInput();
            }


            $git_item_counter_details = get_counter(new Suppliers_with_orders_detailsModel(), array(
                'com_code' => $com_code,
                'order_type' => 3, 'suppliers_with_orders_auto_serial' => $data['auto_serial']
            ));

            if($git_item_counter_details == 0){
                if($request->has('store_id')){
                    if($request->store_id==''){
                        return redirect()->back()
                        ->with(['error' => 'عفوا يجب  اختيار مخزن الصرف للمرتجع'])
                        ->withInput();
                    }
                    $data_to_update['store_id'] = $request->store_id;
                }
            }

            $data_to_update['order_date'] = $request->order_date;
            $data_to_update['supplier_code'] = $request->supplier_code;
            $data_to_update['pill_type'] = $request->pill_type;
            $data_to_update['account_number'] =  $supplierData['account_number'];
            $data_to_update['updated_by'] = auth()->user()->name;
            $data_to_update['updated_at'] = date("Y-m-d H:i:s");

            update(new Suppliers_orderModel(), $data_to_update, array('id' => $id, "com_code" => $com_code, 'order_type' => 3));
            return redirect()->route('admin.suppliers_orders_general_return.index')->with(['success' => 'لقد تم تحديث بيانات بنجاح']);
        } catch (\Exception $ex) {
            return redirect()->back()->with(['error' => 'عفوا حصل خطأ' . $ex->getMessage()])->withInput();
        }
    }

    public function delete($id)
    {
        try {

            

            $com_code = auth()->user()->com_code;
            $parent_pill_data = get_cols_where_row(new Suppliers_orderModel(), array('is_approved', 'auto_serial', 'store_id', 'supplier_code'), array('id' => $id, "com_code" => $com_code, 'order_type' => 3));



            if (empty($parent_pill_data)) {
                return redirect()->back()->with(['error' => 'عفوا حصل خطأ في delete']);
            }


            if ($parent_pill_data['is_approved'] == 1) {
                return redirect()->back()->with(['error' => 'عفوا لا يمكن الحذف بتفاصيل فاتورة معتمده ومؤرشفة']);
            }


            $item_details = get_cols_where(new Suppliers_with_orders_detailsModel(),array('*'), array(
                'com_code' => $com_code,'order_type' => 3, 'suppliers_with_orders_auto_serial' => $parent_pill_data['auto_serial']),
                'id','ASC');


            




            $flag = delete(new Suppliers_orderModel(), array('id' => $id, "com_code" => $com_code, 'order_type' => 3));

            if ($flag) {

                if(!empty($item_details)){
                    foreach($item_details as $info){

                        $flagDelete = delete( new Suppliers_with_orders_detailsModel(), array(
                            'com_code' => $com_code, 'order_type' => 3, 'suppliers_with_orders_auto_serial' => $parent_pill_data['auto_serial'],'id'=>$info->id));
                        if($flagDelete){
                            $itemCard_data = get_cols_where_row(new Inv_itemCard(), array("uom_id", "retail_uom_quantityToParent", "retail_uom_id", 'cost_price_retail', 'does_has_retailunit', 'item_type'), array("com_code" => $com_code, "item_code" => $info['item_code']));
                            $batch_data = get_cols_where_row(new Inv_itemcard_batches(), array('quantity', 'unit_cost_price', 'id', 'expired_date', 'production_date'), array(
                                'com_code' => $com_code,
                                'auto_serial' => $info['batch_auto_serial'], 'item_code' => $info->item_code, 'store_id' => $parent_pill_data['store_id']

                            ));

                            if (!empty($itemCard_data) and !empty($batch_data)) {

                                // خصم الكمية من الباتشات 
                                // كميه الصنف الموجوده في المخاون 

                                $quantityBeforeMove = get_sum_where(
                                    new Inv_itemcard_batches(),
                                    'quantity',
                                    array('item_code' => $info['item_code'], 'com_code' => $com_code)
                                );


                                // حنجيب كمية الصنف بالمخزن المحدد معه الحالي بعد  الحركة
                                $quantityBeforeMoveCurrentStore = get_sum_where(
                                    new Inv_itemcard_batches(),
                                    'quantity',
                                    array('item_code' => $info['item_code'], 'com_code' => $com_code, 'store_id' => $parent_pill_data['store_id'])
                                );



 
                                // اذا كان الوحدة المرتجعة كانت اب او تجزئة 
                                if ($info['isparentuom'] == 1) {
                                    // اذا كانت اب نقص كمية من باتش 
                                    $dataupdate_batch['quantity'] = $batch_data['quantity'] + $info['dliverd_quantity'];
                                } else {
                                    //لو كان الوحدة تجزئة نحولها للاب ونقصها من باتش
                                    $item_quntity_per_parent_uom = $info['dliverd_quantity'] / $itemCard_data['retail_uom_quantityToParent'];
                                    $dataupdate_batch['quantity'] = $batch_data['quantity'] +  $item_quntity_per_parent_uom;
                                }


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
                                        array('com_code' => $com_code, 'item_code' => $itemCard_data['item_code'])
                                    );
                                    // كمية الصنف بمخزن الفاتورة الشراء بعد اتمام حركة بكل باتشات 
                                    $quantityAfterMoveCurrentStore = get_sum_where(
                                        new Inv_itemcard_batches(),
                                        'quantity',
                                        array('com_code' => $com_code, 'item_code' => $itemCard_data['item_code'], 'store_id' =>  $parent_pill_data['store_id'])
                                    );

                                    // تأثير على كارت الصنف  
                                    $itemMovementInsert['inv_itemcard_movements_categories'] = 1;
                                    $itemMovementInsert['item_code'] = $info['item_code'];
                                    $itemMovementInsert['items_movements_types'] = 3;
                                    // كود الفاتورة الاب
                                    $itemMovementInsert['FK_table'] = $parent_pill_data['auto_serial'];

                                    // كود الصف الابن بتفاصيل الفاتورة 
                                    $itemMovementInsert['FK_table_details'] = $info['id'];

                                    $Supplier_name = get_field_value(new SuppliersModel(), 'name', array("supplier_code" => $parent_pill_data['supplier_code']));
                                    $itemMovementInsert['byan'] = 'نظير حذف سطر صنف من مرتجع مشتريات عام الى المورد ' . ' ' . $Supplier_name . 'رقم الفاتورة ' . $parent_pill_data['auto_serial'];
                                    $Main_uom_name = get_field_value(new Inv_ums(), 'name', array('com_code' => $com_code, 'id' => $itemCard_data['uom_id']));

                                    // كمية الصنف بكل المخازن قبل الحركة 
                                    $itemMovementInsert['quantity_befor_movement'] = 'عدد ' . " " . ($quantityBeforeMove * 1) . ' ' . $Main_uom_name;
                                    $itemMovementInsert['quantity_after_move'] = 'عدد ' . " " . ($quantityAfterMove * 1) . ' ' . $Main_uom_name;

                                    // كمية الصنف  بالمخزن الحالي بعد  الحركة 

                                    // كمية الصنف  بالمخزن الحالي قبل  الحركة 
                                    $itemMovementInsert['quantity_befor_move_store'] = 'عدد ' . " " . ($quantityBeforeMoveCurrentStore * 1) . ' ' . $Main_uom_name;
                                    // كمية الصنف  بالمخزن الحالي بعد  الحركة 
                                    $itemMovementInsert['quantity_after_move_store'] = 'عدد ' . " " . ($quantityAfterMoveCurrentStore  * 1) . ' ' . $Main_uom_name;

                                    $itemMovementInsert['store_id'] = $parent_pill_data['store_id'];

                                    $itemMovementInsert['added_by'] = auth()->user()->name;
                                    $itemMovementInsert['created_at'] = date("Y-m-d H:i:s");
                                    $itemMovementInsert['date'] = date("Y-m-d ");
                                    $itemMovementInsert['com_code'] = $com_code;
                                    $flag = insert(new Inv_itemcard_movements(), $itemMovementInsert);
                                    if ($flag) {

                                        // نجيب كمية الصنف من جدول الباتشات 
                                        DoUpdateItemCard(
                                            new Inv_itemCard(),
                                            $info['item_code'],
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

            return redirect()->route('admin.suppliers_orders_general_return.index')->with(['success' => ' تم حذف بيانات بنجاح']);
        } catch (\Exception $ex) {
            return redirect()->back()->with(['error' => 'عفوا حصل خطأ' . $ex->getMessage()])->withInput();
}
}

    public function show($id)
    {
        try {

            $com_code = auth()->user()->com_code;
            $data = get_cols_where_row(new Suppliers_orderModel(), array('*'), array('id' => $id, "com_code" => $com_code, 'order_type' => 3));
            $data['supplier_name'] = SuppliersModel::where('supplier_code', $data['supplier_code'])->value('name');
            $data['store_name'] = Store::where('id', $data['store_id'])->value('name');


            if (empty($data)) {
                return redirect()->route('admin.suppliers_orders_general_return.index')->with(['error' => 'غير قادر على الوصول للبيانات المطلوبة ']);
            };

            $data['added_by_admin'] = Admin::where('id', $data['added_by'])->value('name');
            if ($data['updated_by'] > 0 and $data['updated_by'] != null) {
                $data['aupdated_by_admin'] = Admin::where('id', $data['updated_by'])->value('name');
            }

            $details = get_cols_where(new Suppliers_with_orders_detailsModel(), array("*"), array('suppliers_with_orders_auto_serial' => $data['auto_serial'], 'order_type' => 3, 'com_code' => $com_code), 'id', 'DESC');

            if (!empty($details)) {
                foreach ($details as $info) {

                    $info->item_card_name = Inv_itemCard::where('item_code', $info->item_code)->value('name');
                    $info->uom_name = \get_field_value(new Inv_ums(), 'name', array('id' => $info->uom_id));

                    $data['added_by_admin'] = Admin::where('id', $data['added_by'])->value('name');
                    if ($data['updated_by'] > 0 and $data['updated_by'] != null) {
                        $data['aupdated_by_admin'] = Admin::where('id', $data['updated_by'])->value('name');
                    }
                }
            }
            // if pill still open 



            return view('admin.suppliers_with_orders_general_returns.show', ['data' => $data, 'details' => $details]);
        } catch (\Exception $ex) {
            return redirect()->back()->with(['error' => ' عفوا حصل خطأ في show' . $ex->getMessage()])->withInput();
        }
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

        return view("admin.suppliers_with_orders_general_returns.get_item_uoms", ['item_card_data' => $item_card_data]);
    }

    public function add_new_details(Request $request)
    {
    
        if ($request->ajax()) {
            $com_code = auth()->user()->com_code;


            $suppliers_with_ordersData = get_cols_where_row(
                new Suppliers_orderModel(),
                array('is_approved', 'order_date', 'tax_value', 'discount_value'),
                array('auto_serial' => $request->autoserailparent, 'com_code' => $com_code, 'order_type' => 3)
            );
        

            if (!empty($suppliers_with_ordersData)) {
                if ($suppliers_with_ordersData['is_approved'] == 0) {
                    
                    $data_insert['suppliers_with_orders_auto_serial'] = $request->autoserailparent;
                    $data_insert['order_type'] = 3;
                    $data_insert['item_code'] = $request->item_code_add;
                    $data_insert['dliverd_quantity'] = $request->quantity_add;
                    $data_insert['unit_price'] = $request->price_add;
                    $data_insert['uom_id'] = $request->uom_id_Add;
                    $data_insert['total_price'] = $request->total_add;
                    $data_insert['isparentuom'] = $request->isparentuom;
                    $data_insert['order_date'] = $suppliers_with_ordersData['order_date'];
                    $data_insert['item_card_type'] = $request->type;


                    if ($request->type == 2) {
                        $data_insert['production_date'] = $request->production_date;
                        $data_insert['expire_date'] = $request->expire_date;
                    }

                    $data_insert['created_at'] = date('Y:m:d H:i:s');
                    $data_insert['added_by'] = auth()->user()->name;
                    $data_insert['com_code'] = $com_code;
                
                    $flage = insert(new Suppliers_with_orders_detailsModel(), $data_insert);
                
                    if ($flage) {
                        // update parent pill
                        $total_details_sum = get_sum_where(
                            new Suppliers_with_orders_detailsModel(),
                            'total_price',
                            array('suppliers_with_orders_auto_serial' => $request->autoserailparent, "com_code" => $com_code, 'order_type' => 3)
                        );
                        $dataUpdateParent['total_cost_items'] = $total_details_sum;
                    
                        $dataUpdateParent['total_befor_discount'] = $total_details_sum + $suppliers_with_ordersData['tax_value'];
                        $dataUpdateParent['total_cost'] = $dataUpdateParent['total_befor_discount']  - $suppliers_with_ordersData['discount_value'];
                        $dataUpdateParent['updated_at'] = date('Y:m:d H:i:s');
                        $dataUpdateParent['updated_by'] = auth()->user()->name;
                        update(new Suppliers_orderModel(), $dataUpdateParent, array('auto_serial' => $request->autoserailparent, 'com_code' => $com_code, 'order_type' => 1));

                        echo json_encode("done");
                    }
                }
            }
        }
    }

    public function reload_itemsdetails(Request $request)
    {

        if ($request->ajax()) {

            $com_code = auth()->user()->com_code;
            $auto_serial = $request->autoserailparent;
            $data = get_cols_where_row(new Suppliers_orderModel(), array('is_approved', 'auto_serial', 'id'), array('auto_serial' => $auto_serial, "com_code" => $com_code, 'order_type' => 3));

            if (!empty($data)) {

                $details = get_cols_where(new Suppliers_with_orders_detailsModel(), array("*"), array('suppliers_with_orders_auto_serial' => $data['auto_serial'], 'order_type' => 3, 'com_code' => $com_code), 'id', 'DESC');

                if (!empty($details)) {
                    foreach ($details as $info) {

                        $info->item_card_name = Inv_itemCard::where('item_code', $info->item_code)->value('name');
                        $info->uom_name = \get_field_value(new Inv_ums(), 'name', array('id' => $info->uom_id));

                        $data['added_by_admin'] = Admin::where('id', $data['added_by'])->value('name');
                        if ($data['updated_by'] > 0 and $data['updated_by'] != null) {
                            $data['aupdated_by_admin'] = Admin::where('id', $data['updated_by'])->value('name');
                        }
                    }
                }
            }
        }



        return view("admin.suppliers_with_orders_general_returns.reload_itemsdetails", ['data' => $data, 'details' => $details]);
    }
    public function reload_parent_pill(Request $request)
    {

        if ($request->ajax()) {

            $auto_serial = $request->autoserailparent;
            $com_code = auth()->user()->com_code;
            $data = get_cols_where_row(new Suppliers_orderModel(), array('*'), array('auto_serial' => $auto_serial, "com_code" => $com_code, 'order_type' => 3));
            $data['supplier_name'] = SuppliersModel::where('supplier_code', $data['supplier_code'])->value('name');
            $data['store_name'] = Store::where('id', $data['store_id'])->value('name');


            if (!empty($data)) {
                $data['added_by_admin'] = Admin::where('id', $data['added_by'])->value('name');
                if ($data['updated_by'] > 0 and $data['updated_by'] != null) {
                    $data['aupdated_by_admin'] = Admin::where('id', $data['updated_by'])->value('name');
                }



                return view("admin.suppliers_with_orders_general_returns.reload_parent_pill", ['data' => $data]);
            };
        }
    }


    public function load_edit_item_details(Request $request)
    {

        if ($request->ajax()) {

            $auto_serial = $request->autoserailparent;

            $com_code = auth()->user()->com_code;
            $parent_pill_data = get_cols_where_row(new Suppliers_orderModel(), array('is_approved'), array('auto_serial' => $auto_serial, "com_code" => $com_code, 'order_type' => 3));



            if (!empty($parent_pill_data)) {

                if ($parent_pill_data['is_approved'] == 0) {
                    $items_cards = get_cols_where(new Inv_itemCard(), array("name", "item_code", "item_type"), array('active' => 1, 'com_code' => $com_code), 'id', 'DESC');
                    $item_data_details = get_cols_where_row(new Suppliers_with_orders_detailsModel(), array('*'), array('suppliers_with_orders_auto_serial' => $request->autoserailparent, "com_code" => $com_code, 'order_type' => 3, 'id' => $request->id));
                    $item_card_data = get_cols_where_row(
                        new Inv_itemCard(),
                        array('does_has_retailunit', 'retail_uom_id', 'uom_id'),
                        array('item_code' =>  $item_data_details['item_code'], 'com_code' => $com_code)
                    );

                    if (!empty($item_card_data['does_has_retailunit'] == 1)) {
                        $item_card_data['parent_uom_name'] = \get_field_value(new Inv_ums(), 'name', array('id' => $item_card_data['uom_id']));
                        $item_card_data['retail_uom_name'] = \get_field_value(new Inv_ums(), 'name', array('id' => $item_card_data['retail_uom_id']));
                    } else {
                        $item_card_data['parent_uom_name'] = \get_field_value(new Inv_ums(), 'name', array('id' => $item_card_data['uom_id']));
                    }


                    return view("admin.suppliers_with_orders_general_returns.load_edit_item_details", ['parent_pill_data' => $parent_pill_data, 'item_data_details' => $item_data_details, 'items_cards' => $items_cards, 'item_card_data' => $item_card_data]);
                };
            }
        }
    }
    public function load_model_add_details(Request $request)
    {

        if ($request->ajax()) {

            $auto_serial = $request->autoserailparent;

            $com_code = auth()->user()->com_code;
            $parent_pill_data = get_cols_where_row(new Suppliers_orderModel(), array('is_approved','store_id'), array('auto_serial' => $auto_serial, "com_code" => $com_code, 'order_type' => 3));

            

            if (!empty($parent_pill_data)) {

                if ($parent_pill_data['is_approved'] == 0) {
                    $items_cards = get_cols_where(new Inv_itemCard(), array("name", "item_code", "item_type"), array('active' => 1, 'com_code' => $com_code), 'id', 'DESC');

                    $stors = get_cols_where(new Store(), array("name", "id"), array('active' => 1, 'com_code' => $com_code , 'id'=> $parent_pill_data['store_id']), 'id', 'DESC');
                    return view("admin.suppliers_with_orders_general_returns.load_add_new_item_details", ['parent_pill_data' => $parent_pill_data,  'items_cards' => $items_cards , 'stors'=> $stors]);
                };
            }
        }
    }


    public function edit_item_details(Request $request)
    {


        if ($request->ajax()) {

            $auto_serial = $request->autoserailparent;

            $com_code = auth()->user()->com_code;
            $parent_pill_data = get_cols_where_row(new Suppliers_orderModel(), array('is_approved', 'order_date', 'tax_value', 'discount_value'), array('auto_serial' => $auto_serial, "com_code" => $com_code, 'order_type' => 3));



            if (!empty($parent_pill_data)) {

                if ($parent_pill_data['is_approved'] == 0) {
                    $data_to_update['item_code'] = $request->item_code_edit;
                    $data_to_update['dliverd_quantity'] = $request->quantity_edit;
                    $data_to_update['unit_price'] = $request->price_edit;
                    $data_to_update['uom_id'] = $request->uom_id_edit;
                    $data_to_update['total_price'] = $request->total_edit;
                    $data_to_update['isparentuom'] = $request->isparentuom;
                    $data_to_update['order_date'] = $parent_pill_data['order_date'];
                    $data_to_update['item_card_type'] = $request->type_edit;


                    if ($request->type == 2) {
                        $data_to_update['production_date'] = $request->production_date_edit;
                        $data_to_update['expire_date'] = $request->expire_date_edit;
                    }

                    $data_to_update['updated_at'] = date('Y:m:d H:i:s');
                    $data_to_update['updated_by'] = auth()->user()->name;
                    $data_to_update['com_code'] = $com_code;

                    $flag = update(new Suppliers_with_orders_detailsModel(), $data_to_update, array(
                        'id' => $request->id, 'com_code' => $com_code,
                        'order_type' => 3, 'suppliers_with_orders_auto_serial' => $request->autoserailparent
                    ));
                    if ($flag) {
                        $total_details_sum = get_sum_where(
                            new Suppliers_with_orders_detailsModel(),
                            'total_price',
                            array('suppliers_with_orders_auto_serial' => $request->autoserailparent, "com_code" => $com_code, 'order_type' => 3)
                        );

                        $dataUpdateParent['total_cost_items'] = $total_details_sum;
                        $dataUpdateParent['total_befor_discount'] = $total_details_sum + $parent_pill_data['tax_value'];
                        $dataUpdateParent['total_cost'] = $dataUpdateParent['total_befor_discount']  - $parent_pill_data['discount_value'];
                        $dataUpdateParent['updated_at'] = date('Y:m:d H:i:s');
                        $dataUpdateParent['updated_by'] = auth()->user()->name;
                        update(new Suppliers_orderModel(), $dataUpdateParent, array('auto_serial' => $request->autoserailparent, 'com_code' => $com_code, 'order_type' => 3));

                        echo json_encode("done");
                    }
                };
            }
        }
    }



    public function delete_details($id, $parent_id)
    {
        try {
            
            $com_code = auth()->user()->com_code;
            $parent_pill_data = get_cols_where_row(new Suppliers_orderModel(), array('is_approved', 'auto_serial', 'store_id', 'supplier_code'), array('id' => $parent_id, "com_code" => $com_code, 'order_type' => 3));



            if (empty($parent_pill_data)) {
                return redirect()->back()->with(['error' => ' عفوا حصل خطأفي delete_details']);
            }


            if ($parent_pill_data['is_approved'] == 1) {
                return redirect()->back()->with(['error' => 'عفوا لا يمكن الحذف بتفاصيل فاتورة معتمده ومؤرشفة']);
            }
            $item_row = Suppliers_with_orders_detailsModel::find($id);
            
            
            if (!empty($item_row)) {
                $flag = $item_row->delete();
                if ($flag) {
                    $this->recalculate_parent_invoice($parent_pill_data['auto_serial']);
                    $itemCard_data = get_cols_where_row(new Inv_itemCard(), array("uom_id", "retail_uom_quantityToParent", "retail_uom_id", 'cost_price_retail', 'does_has_retailunit', 'item_type'), array("com_code" => $com_code, "item_code" => $item_row['item_code']));
                    $batch_data = get_cols_where_row(new Inv_itemcard_batches(), array('quantity', 'unit_cost_price', 'id', 'expired_date', 'production_date'), array(
                        'com_code' => $com_code,
                        'auto_serial' => $item_row['batch_auto_serial'], 'item_code' => $item_row['item_code'], 'store_id' => $parent_pill_data['store_id'] 
                    
                    ));
                
                    if (!empty($itemCard_data) and !empty($batch_data)) {

                        // خصم الكمية من الباتشات 
                        // كميه الصنف الموجوده في المخاون 
                        
                        $quantityBeforeMove = get_sum_where(
                            new Inv_itemcard_batches(),
                            'quantity',
                            array('item_code' => $item_row['item_code'], 'com_code' => $com_code)
                        );


                        // حنجيب كمية الصنف بالمخزن المحدد معه الحالي بعد  الحركة
                        $quantityBeforeMoveCurrentStore = get_sum_where(
                            new Inv_itemcard_batches(),
                            'quantity',
                            array('item_code' => $item_row['item_code'], 'com_code' => $com_code, 'store_id' => $parent_pill_data['store_id'])
                        );




                        // اذا كان الوحدة المرتجعة كانت اب او تجزئة 
                        if ($item_row['isparentuom'] == 1) {
                            // اذا كانت اب نقص كمية من باتش 
                            $dataupdate_batch['quantity'] = $batch_data['quantity'] - $item_row['dliverd_quantity'];
                        } else {
                            //لو كان الوحدة تجزئة نحولها للاب ونقصها من باتش
                            $item_quntity_per_parent_uom = $item_row['dliverd_quantity'] / $itemCard_data['retail_uom_quantityToParent'];
                            $dataupdate_batch['quantity'] = $batch_data['quantity'] -  $item_quntity_per_parent_uom;
                        }


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
                                array('com_code' => $com_code, 'item_code' => $itemCard_data['item_code'])
                            );
                            // كمية الصنف بمخزن الفاتورة الشراء بعد اتمام حركة بكل باتشات 
                            $quantityAfterMoveCurrentStore = get_sum_where(
                                new Inv_itemcard_batches(),
                                'quantity',
                                array('com_code' => $com_code, 'item_code' => $itemCard_data['item_code'], 'store_id' =>  $parent_pill_data['store_id'])
                            );

                            // تأثير على كارت الصنف  
                            $itemMovementInsert['inv_itemcard_movements_categories'] = 1;
                            $itemMovementInsert['item_code'] = $item_row['item_code'];
                            $itemMovementInsert['items_movements_types'] = 3;
                            // كود الفاتورة الاب
                            $itemMovementInsert['FK_table'] = $parent_pill_data['auto_serial'];

                            // كود الصف الابن بتفاصيل الفاتورة 
                            $itemMovementInsert['FK_table_details'] = $item_row['id'];
                            
                            $Supplier_name = get_field_value(new SuppliersModel(), 'name', array("supplier_code" => $parent_pill_data['supplier_code']));
                            $itemMovementInsert['byan'] = 'نظير حذف سطر صنف من مرتجع مشتريات عام الى المورد ' . ' ' . $Supplier_name . 'رقم الفاتورة ' . $parent_pill_data['auto_serial'];
                            $Main_uom_name = get_field_value(new Inv_ums(), 'name', array('com_code' => $com_code, 'id' => $itemCard_data['uom_id']));

                            // كمية الصنف بكل المخازن قبل الحركة 
                            $itemMovementInsert['quantity_befor_movement'] = 'عدد ' . " " . ($quantityBeforeMove * 1) . ' ' . $Main_uom_name;
                            $itemMovementInsert['quantity_after_move'] = 'عدد ' . " " . ($quantityAfterMove * 1) . ' ' . $Main_uom_name;

                            // كمية الصنف  بالمخزن الحالي بعد  الحركة 

                            // كمية الصنف  بالمخزن الحالي قبل  الحركة 
                            $itemMovementInsert['quantity_befor_move_store'] = 'عدد ' . " " . ($quantityBeforeMoveCurrentStore * 1) . ' ' . $Main_uom_name;
                            // كمية الصنف  بالمخزن الحالي بعد  الحركة 
                            $itemMovementInsert['quantity_after_move_store'] = 'عدد ' . " " . ($quantityAfterMoveCurrentStore  * 1) . ' ' . $Main_uom_name;

                            $itemMovementInsert['store_id'] = $parent_pill_data['store_id'];

                            $itemMovementInsert['added_by'] = auth()->user()->name;
                            $itemMovementInsert['created_at'] = date("Y-m-d H:i:s");
                            $itemMovementInsert['date'] = date("Y-m-d ");
                            $itemMovementInsert['com_code'] = $com_code;
                            $flag =insert(new Inv_itemcard_movements(), $itemMovementInsert);
                            if($flag){
                            
                            // نجيب كمية الصنف من جدول الباتشات 
                            DoUpdateItemCard(
                                new Inv_itemCard(),
                                $item_row['item_code'],
                                new Inv_itemcard_batches(),
                                $itemCard_data['retail_uom_quantityToParent'],
                                $itemCard_data['does_has_retailunit']
                            );
                            
                            return redirect()->back()->with(['success' => ' تم حذف بيانات بنجاح']);
                        }
                        } else {
                            return redirect()->back()->with(['error' => '111عفوا حدث خطأ ما !!']);
                        };
                    }

                    
                }
            } else {
                return redirect()->back()->with(['error' => 'عفوا غير قادر علي الوصول الي البيانات المطلوبة !!']);
            };
        } catch (\Exception $ex) {
            return redirect()->back()->with(['error' => 'عفوا حصل خطأ في try and catch in delete_details' . $ex->getMessage()])->withInput();
        }
    }


    public function load_model_approve_invoice(Request $request)
    {

        if ($request->ajax()) {


            $auto_serial = $request->autoserailparent;

            $com_code = auth()->user()->com_code;
            $data = get_cols_where_row(new Suppliers_orderModel(), array('*'), array(
                'auto_serial' => $auto_serial, "com_code" => $com_code,
                'order_type' => 3
            ));

            $user_shifts = get_user_shift(new Admin_shifts(), new Treasure(), new Treasuries_transactionModel());

            $get_counter_items = get_sum_where(
                new Suppliers_with_orders_detailsModel(),
                'id',
                array('suppliers_with_orders_auto_serial' => $auto_serial, "com_code" => $com_code, 'order_type' => 3)
            );

            

            return view("admin.suppliers_with_orders_general_returns.load_model_approve_invoice", [
                "data" => $data, 'user_shifts' => $user_shifts,
                'get_counter_items' => $get_counter_items
            ]);
        }
    }
    public function load_usershiftDiv(Request $request)
    {

        if ($request->ajax()) {

            $user_shifts = get_user_shift(new Admin_shifts(), new Treasure(), new Treasuries_transactionModel());
            return view("admin.suppliers_with_orders_general_returns.load_usershifts", ['user_shifts' => $user_shifts]);
        }
    }

    public function do_approve($auto_serial, Request $request)
    {
        
        $com_code = auth()->user()->com_code;

        // sheck if approve 
        $data = get_cols_where_row(
            new Suppliers_orderModel(),
            array('is_approved', 'store_id', 'total_cost_items', 'id', 'account_number', 'supplier_code'),
            array('auto_serial' => $auto_serial, 'order_type' => 3, 'com_code' => $com_code)
        );
        $supplier_name = get_field_value(new SuppliersModel(), 'name', array("supplier_code" => $data['supplier_code']));

        if ($data['is_approved'] == 1) {
            return redirect()->route('admin.suppliers_orders_general_return.show', $data['id'])->with('error', 'عفوا لا يمكن اعتماد الفاتورة معتمده من قبل');
        }

        $get_counter_items = get_sum_where(
            new Suppliers_with_orders_detailsModel(),
            'id',
            array('suppliers_with_orders_auto_serial' => $auto_serial, "com_code" => $com_code, 'order_type' => 3)
        );

        if ($get_counter_items == 0) {
            return redirect()->route('admin.suppliers_orders_general_return.show', $data['id'])->with('error', 'عفوا لا يمكن الاعتماد الفاتورة قبل اضافة الاصناف عليها !!');
        }


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
        $dataUpdateParent['auto_serial'] = $auto_serial;
        $dataUpdateParent['is_approved'] = 1;
        $dataUpdateParent['money_for_account'] = $request->total_cost ;
        $dataUpdateParent['updated_at'] = date("Y-m-d H:i:s");
        $dataUpdateParent['updated_by'] = auth()->user()->name;
        $dataUpdateParent['approved_by'] = auth()->user()->name;


        // check if pill type is cash 
        if ($request->pill_type == 1) {
            if ($request->what_paid !=  $request->total_cost) {
                return redirect()->route('admin.suppliers_orders_general_return.show', $data['id'])->with('error', 'عفوا يجب ان يكون المبلغ بالكامل مدفوع في حاله ان فاتورة كاش');
            }
        }

        // check if pill type is agrl 
        if ($request->pill_type == 2) {
            if ($request->what_paid == $request->total_cost) {
                return redirect()->route('admin.suppliers_orders_general_return.show', $data['id'])->with('error', 'عفوا يجب ان يكون المبلغ بالكامل مدفوع  في حاله ان فاتورة اجل');
            }
        }


        if ($request->what_paid > 0) {
            if ($request->what_paid > $request->total_cost) {
                return redirect()->route('admin.suppliers_orders_general_return.show', $data['id'])->with('error', 'عفوا يجب ان يكون المبلغ  مدفوع اكبر من المبلغ الفاتورة ');
            }

            // check if the user has treasures id or money 
            $user_shift = get_user_shift(new Admin_shifts(), new Treasure(), new Treasuries_transactionModel());

            if (empty($user_shift)) {
                return redirect()->route('admin.suppliers_orders_general_return.show', $data['id'])->with('error', 'عفوا لا تمتلك شيفت خزنة مفتوحة لكي تتمكن من اتمام عملية الصرف ');
            }
        }

        $flag = update(new Suppliers_orderModel(), $dataUpdateParent, array('auto_serial' => $auto_serial, 'order_type' => 3, 'com_code' => $com_code));

        if ($flag) {



            // حركات مختلفة 
            if ($request->what_paid > 0) {



                $trussery_data = get_cols_where_row(new Treasure(), array('last_isal_collect'), array('com_code' => $com_code, 'id' => $user_shift['treasures_id']));
                if (empty($trussery_data)) {

                    return redirect()->route('admin.suppliers_orders_general_return.show', $data['id'])->with('error', ' عفوا غير قادر على الوصول الي  بيانات الخزنة المطلوبة   ');
                }

                $last_record_Treasuries_transaction =  get_cols_where_row_orderby(new Treasuries_transactionModel(), array('auto_serial'), array('com_code' => $com_code), 'auto_serial', "DESC");

                if (!empty($last_record_Treasuries_transaction)) {
                    $data_insert_Treasuries_transaction['auto_serial'] = $last_record_Treasuries_transaction['auto_serial'] + 1;
                } else {
                    $data_insert_Treasuries_transaction['auto_serial'] = 1;
                }

                $data_insert_Treasuries_transaction['isal_number'] = $trussery_data['last_isal_collect'] + 1;
                $data_insert_Treasuries_transaction['mov_date'] = date("Y-m-d");
                $data_insert_Treasuries_transaction['account_number'] = $data['account_number'];
                $data_insert_Treasuries_transaction['mov_type'] = 10;
                $data_insert_Treasuries_transaction['treasures_id'] = $user_shift['treasures_id'];
                // debit مدين
                $data_insert_Treasuries_transaction['money'] = $request->what_paid ;

                // creadit دائن
                $data_insert_Treasuries_transaction['money_for_account'] =  $request->what_paid * (-1);

                $data_insert_Treasuries_transaction['the_foregin_key'] = $data['auto_serial'];
                $data_insert_Treasuries_transaction['bayan'] = 'تحصيل  نظير فاتورة مرتجع  مشتريات عام فاتورة  رقم ' .  $auto_serial;
                $data_insert_Treasuries_transaction['is_account'] = 1;
                $data_insert_Treasuries_transaction['is_approved'] = 1;
                $data_insert_Treasuries_transaction['shift_code'] =  $user_shift['shift_code'];
                $data_insert_Treasuries_transaction['com_code'] =  $com_code;
                $data_insert_Treasuries_transaction['created_at'] = date("Y-m-d H:i:s");
                $data_insert_Treasuries_transaction['added_by'] = auth()->user()->name;

                $flage = insert(new Treasuries_transactionModel, $data_insert_Treasuries_transaction);


                if ($flage) {
                    $data_to_update['last_isal_collect'] = $data_insert_Treasuries_transaction['isal_number'];
                    update(new Treasure(),  $data_to_update, array('id' => $com_code, 'id' => $user_shift['treasures_id']));
                } else {
                    return redirect()->route('admin.suppliers_orders_general_return.show', $data['id'])->with('error', 'حدث خطأ ما ');
                }
            }


            // affect on supplier balance هتأثر في وصيد لبمورد 
            if ($request->pill_type == 2) {
                refresh_account_blance_suppliers(
                    $data['account_number'],
                    new AccountModel(),
                    new SuppliersModel(),
                    new Treasuries_transactionModel(),
                    new Suppliers_orderModel(),
                    false
                );
            }
            return redirect()->route('admin.suppliers_orders_general_return.show', $data['id'])->with('success', 'تم الاعتماد وترحيل الفاتورة بنجاح ');
        }
    }

    public function ajax_search(Request $request)
    {



        if ($request->ajax()) {
            $search_by_text = $request->search_by_text;
            $supplier_code = $request->supplier_code;
            $store_id = $request->store_id;
            $searchbyradio = $request->searchbyradio;
            $to_order_date = $request->to_order_date;
            $from_order_date = $request->from_order_date;



            if ($supplier_code == 'all') {
                $field1 = "id";
                $operator1 = ">";
                $value1 = 0;
            } else {
                $field1 = "supplier_code";
                $operator1 = "=";
                $value1 = $supplier_code;
            }

            if ($store_id == 'all') {
                $field2 = "id";
                $operator2 = ">";
                $value2 = 0;
            } else {
                $field2 = "store_id";
                $operator2 = "=";
                $value2 = $store_id;
            }



            if ($from_order_date == '') {
                $field3 = "id";
                $operator3 = ">";
                $value3 = 0;
            } else {
                $field3 = "order_date";
                $operator3 = ">=";
                $value3 = $from_order_date;
            }
            if ($to_order_date == '') {
                $field4 = "id";
                $operator4 = ">";
                $value4 = 0;
            } else {
                $field4 = "order_date";
                $operator4 = "<=";
                $value4 = $to_order_date;
            }

            if ($search_by_text != "") {

                if ($searchbyradio == 'Doc_No') {

                    $field5 = 'Doc_No';
                    $operator5 = "=";
                    $value5 =  $search_by_text;
                } else {

                    $field5 = 'auto_serial';
                    $operator5 = "=";
                    $value5 =  $search_by_text;
                }
            } else {

                $field5 = 'id';
                $operator5 = ">";
                $value5 = 0;
            }






            $data = Suppliers_orderModel::where($field1, $operator1, $value1)->where($field2, $operator2, $value2)
                ->where($field3, $operator3, $value3)->where($field4, $operator4, $value4)->where($field5, $operator5, $value5)
                ->where('order_type', '=', 1)->orderBy('id', 'DESC')->paginate(PAGINATEION_COUNT);





            if (!empty($data)) {
                foreach ($data as $info) {
                    $info->added_by_admin = Admin::where('id', $info->added_by)->value('name');
                    $info->supplier_name = SuppliersModel::where('supplier_code', $info->supplier_code)->value('name');
                    $info->store_name = Store::where('id', $data['store_id'])->value('name');

                    if ($info->updated_by > 0 and $info->updated_by != null) {
                        $info->updated_by_admin = Admin::where('id', $info->updated_by)->value('name');
                    }
                }
            };
            return view('admin.suppliers_with_orders_general_returns.ajax_search', ['data' => $data]);
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

                    
                    return view("admin.suppliers_with_orders_general_returns.get_item_batches", ['item_card_Data' => $item_card_Data, 'requesed' => $requesed, 'uom_Data' => $uom_Data, 'inv_itemcard_batches' => $inv_itemcard_batches]);
                }
            }
        }
    }


    function add_return_items_from_invoice(Request $request)
    {
        try {

            if ($request->ajax()) {
                $com_code = auth()->user()->com_code;

                $invoice_data = get_cols_where_row(new Suppliers_orderModel(), array('is_approved', 'order_date', 'supplier_code'), array('com_code' => $com_code, 'auto_serial' => $request->auto_serial_parent ,'order_type'=>3));
                
                if (!empty($invoice_data)) {
                    
                    if ($invoice_data['is_approved'] == 0) {

                        $batch_data = get_cols_where_row(new Inv_itemcard_batches(), array('quantity', 'unit_cost_price', 'id', 'expired_date', 'production_date'), array(
                            'com_code' => $com_code,
                            'auto_serial' => $request->inv_itemcard_batches_id, 'item_code' => $request->item_code, 'store_id' => $request->store_id
                        ));
                        $itemCard_data = get_cols_where_row(new Inv_itemCard(), array("uom_id", "retail_uom_quantityToParent", "retail_uom_id", 'cost_price_retail', 'does_has_retailunit', 'item_type'), array("com_code" => $com_code, "item_code" => $request->item_code));
                        if (!empty($batch_data)) {
                            if($request->isparentuom == 0){
                                $quantity = $batch_data['quantity'] * $itemCard_data['retail_uom_quantityToParent'] ;
                            }else{
                                $quantity = $batch_data['quantity'];
                            }
                        
                            if ($quantity >= $request->item_quantity) {
                                

                                if (!empty($itemCard_data)) {
                                    
                                    $Main_uom_name = get_field_value(new Inv_ums(), 'name', array('com_code' => $com_code, 'id' => $itemCard_data['uom_id']));
                                    
                                    $data_insert_items['suppliers_with_orders_auto_serial'] = $request->auto_serial_parent;
                                    $data_insert_items['order_type'] = 3;
                                    $data_insert_items['item_code'] = $request->item_code;
                                    $data_insert_items['order_date'] = $invoice_data['order_date'];
                                    $data_insert_items['item_card_type'] = $itemCard_data['item_type'];
                                    $data_insert_items['uom_id'] = $request->uom_id;
                                    $data_insert_items['batch_auto_serial'] = $request->inv_itemcard_batches_id;
                                    $data_insert_items['dliverd_quantity'] = $request->item_quantity;
                                    $data_insert_items['unit_price'] = $request->price;
                                    $data_insert_items['total_price'] = $request->item_total;
                                    $data_insert_items['isparentuom'] = $request->isparentuom;
                                    $data_insert_items['production_date'] = $batch_data['production_date'];
                                    $data_insert_items['expire_date'] = $batch_data['expired_date'];


                                    $data_insert_items['created_at'] = date('Y:m:d H:i:s');
                                    $data_insert_items['updated_by'] = null;
                                    $data_insert_items['added_by'] = auth()->user()->name;
                                    $data_insert_items['com_code'] =  $com_code;
                                    
                                    $flag_data = insert(new Suppliers_with_orders_detailsModel(), $data_insert_items, true);
                                    
                                    
                                    if (!empty($flag_data)) {
                                        $this->recalculate_parent_invoice($request->auto_serial_parent);
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




                                        // اذا كان الوحدة المرتجعة كانت اب او تجزئة 
                                        if($request->isparentuom == 1){
                                            // اذا كانت اب نقص كمية من باتش 
                                            $dataupdate_batch['quantity'] = $batch_data['quantity'] - $request->item_quantity;
                                        }else{
                                            //لو كان الوحدة تجزئة نحولها للاب ونقصها من باتش
                                            $item_quntity_per_parent_uom = $request->item_quantity / $itemCard_data['retail_uom_quantityToParent'] ;
                                            $dataupdate_batch['quantity'] = $batch_data['quantity'] -  $item_quntity_per_parent_uom ;
                                        }

                                        
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
                                            $itemMovementInsert['inv_itemcard_movements_categories'] = 1;
                                            $itemMovementInsert['item_code'] = $request->item_code;
                                            $itemMovementInsert['items_movements_types'] = 3;
                                            // كود الفاتورة الاب
                                            $itemMovementInsert['FK_table'] = $request->auto_serial_parent;

                                            // كود الصف الابن بتفاصيل الفاتورة 
                                            $itemMovementInsert['FK_table_details'] = $flag_data->id;
                                            if ($invoice_data['is_has_customer'] == 1) {
                                                $Supplier_name = get_field_value(new SuppliersModel(), 'name', array("supplier_code" => $invoice_data['supplier_code']));
                                            } else {
                                                $Supplier_name = 'عميل نقدي';
                                            }
                                            $itemMovementInsert['byan'] = 'نظير مرتجع مشتريات عام الى المورد ' . ' ' . $Supplier_name . 'رقم الفاتورة ' . $request->auto_serial;

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

    function recalculate_parent_invoice($auto_serial){
        $com_code = auth()->user()->com_code;
        $invoice_data = get_cols_where_row(new Suppliers_orderModel(),array('*'),array('com_code'=>$com_code, 'auto_serial'=>$auto_serial,'order_type'=>3));
        if(!empty($invoice_data)){
            $dataUpdateParent['total_cost_items'] = get_sum_where(new Suppliers_with_orders_detailsModel(), 'total_price', array('com_code' => $com_code, 'suppliers_with_orders_auto_serial' => $auto_serial, 'order_type' => 3));
            $dataUpdateParent['total_cost'] = $dataUpdateParent['total_cost_items'];
            $dataUpdateParent['money_for_account'] = $dataUpdateParent['total_cost'];
            $dataUpdateParent['total_befor_discount'] = $dataUpdateParent['total_cost'];

            $dataUpdateParent['updated_at'] = date("Y-m-d H:i:s");
            $dataUpdateParent['updated_by'] = auth()->user()->name;

            update(new Suppliers_orderModel(),$dataUpdateParent,array('com_code' => $com_code, 'auto_serial' => $auto_serial, 'order_type' => 3));


        }
    }
}
