<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Suppliers_ordersRequest;
use App\Models\Admin;
use App\Models\Suppliers_orderModel;
use App\Models\Suppliers_with_orders_detailsModel;
use App\Models\SuppliersModel;
use App\Models\Inv_itemCard;
use App\Models\Inv_ums;
use App\Models\Store;
use App\Models\Admin_shifts;
use App\Models\Treasure;
use App\Models\Treasuries_transactionModel;


use Illuminate\Http\Request;

class Suppliers_orderControllers extends Controller
{
    public function index()
    {
        $com_code = auth()->user()->com_code;

        $data = get_cols_where_p(new Suppliers_orderModel(), array('*'), array("com_code" => $com_code,), 'id', "DESC", PAGINATEION_COUNT);

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

        return view('admin.suppliers_with_orders.index', ['data' => $data]);
    }

    public function create()
    {
        $com_code = auth()->user()->com_code;
        $suppliers = get_cols_where(new SuppliersModel(), array('supplier_code', 'name'), array('com_code' => $com_code, 'active' => 1), 'id', 'DESC');
        $stores = get_cols_where(new Store(), array('id', 'name'), array('com_code' => $com_code, 'active' => 1), 'id', 'DESC');
        return view('admin.suppliers_with_orders.create', ['suppliers' => $suppliers, 'stores' => $stores]);
    }
    public function store(Suppliers_ordersRequest $request)
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
            $row  = get_cols_where_row_orderby(new Suppliers_orderModel, array("auto_serial"), array("com_code" => $com_code), 'id', 'DESC');
            if (!empty($row)) {
                $data_insert['auto_serial'] = $row['auto_serial'] + 1;
            } else {
                $data_insert['auto_serial'] = 1;
            }


            $data_insert['order_date'] = $request->order_date;
            $data_insert['order_type'] = 1;
            $data_insert['Doc_No'] = $request->Doc_No;
            $data_insert['supplier_code'] = $request->supplier_code;
            $data_insert['pill_type'] = $request->pill_type;
            $data_insert['store_id'] = $request->store_id;
            $data_insert['account_number'] =  $supplierData['account_number'];




            $data_insert['created_at'] = date('Y:m:d H:i:s');
            $data_insert['date'] = date('Y:m:d ');
            $data_insert['updated_by'] = null;
            $data_insert['added_by'] = auth()->user()->name;
            $data_insert['com_code'] = $com_code;

            Suppliers_orderModel::create($data_insert);
            return redirect()->route('admin.suppliers_orders.index')->with(['success' => 'لقد تم إضافة بيانات بنجاح']);
        } catch (\Exception $ex) {
            return redirect()->back()->with(['error' => 'عفوا حصل خطأ' . $ex->getMessage()])->withInput();
        }
    }

    public function show($id)
    {
        try {

            $com_code = auth()->user()->com_code;
            $data = get_cols_where_row(new Suppliers_orderModel(), array('*'), array('id' => $id, "com_code" => $com_code, 'order_type' => 1));
            $data['supplier_name'] = SuppliersModel::where('supplier_code', $data['supplier_code'])->value('name');
            $data['store_name'] = Store::where('id', $data['store_id'])->value('name');


            if (empty($data)) {
                return redirect()->route('admin.suppliers_orders.index')->with(['error' => 'غير قادر على الوصول للبيانات المطلوبة ']);
            };

            $data['added_by_admin'] = Admin::where('id', $data['added_by'])->value('name');
            if ($data['updated_by'] > 0 and $data['updated_by'] != null) {
                $data['aupdated_by_admin'] = Admin::where('id', $data['updated_by'])->value('name');
            }

            $details = get_cols_where(new Suppliers_with_orders_detailsModel(), array("*"), array('suppliers_with_orders_auto_serial' => $data['auto_serial'], 'order_type' => 1, 'com_code' => $com_code), 'id', 'DESC');

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
           


            return view('admin.suppliers_with_orders.show', ['data' => $data, 'details' => $details]);
        } catch (\Exception $ex) {
            return redirect()->back()->with(['error' => 'عفوا حصل خطأ' . $ex->getMessage()])->withInput();
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

        return view("admin.suppliers_with_orders.get_item_uoms", ['item_card_data' => $item_card_data]);
    }
    public function add_new_details(Request $request)
    {
        if ($request->ajax()) {
            $com_code = auth()->user()->com_code;


            $suppliers_with_ordersData = get_cols_where_row(
                new Suppliers_orderModel(),
                array('is_approved', 'order_date', 'tax_value', 'discount_value'),
                array('auto_serial' => $request->autoserailparent, 'com_code' => $com_code, 'order_type' => 1)
            );


            if (!empty($suppliers_with_ordersData)) {
                if ($suppliers_with_ordersData['is_approved'] == 0) {
                    $data_insert['suppliers_with_orders_auto_serial'] = $request->autoserailparent;
                    $data_insert['order_type'] = 1;
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
                            array('suppliers_with_orders_auto_serial' => $request->autoserailparent, "com_code" => $com_code, 'order_type' => 1)
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
            $data = get_cols_where_row(new Suppliers_orderModel(), array('is_approved', 'auto_serial'), array('auto_serial' => $auto_serial, "com_code" => $com_code, 'order_type' => 1));

            if (!empty($data)) {

                $details = get_cols_where(new Suppliers_with_orders_detailsModel(), array("*"), array('suppliers_with_orders_auto_serial' => $data['auto_serial'], 'order_type' => 1, 'com_code' => $com_code), 'id', 'DESC');

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



        return view("admin.suppliers_with_orders.reload_itemsdetails", ['data' => $data, 'details' => $details]);
    }
    public function reload_parent_pill(Request $request)
    {

        if ($request->ajax()) {

            $auto_serial = $request->autoserailparent;
            $com_code = auth()->user()->com_code;
            $data = get_cols_where_row(new Suppliers_orderModel(), array('*'), array('auto_serial' => $auto_serial, "com_code" => $com_code, 'order_type' => 1));
            $data['supplier_name'] = SuppliersModel::where('supplier_code', $data['supplier_code'])->value('name');
            $data['store_name'] = Store::where('id', $data['store_id'])->value('name');


            if (!empty($data)) {
                $data['added_by_admin'] = Admin::where('id', $data['added_by'])->value('name');
                if ($data['updated_by'] > 0 and $data['updated_by'] != null) {
                    $data['aupdated_by_admin'] = Admin::where('id', $data['updated_by'])->value('name');
                }



                return view("admin.suppliers_with_orders.reload_parent_pill", ['data' => $data]);
            };
        }
    }


    public function edit($id)
    {
        $com_code = auth()->user()->com_code;
        $data = get_cols_where_row(new Suppliers_orderModel(), array('*'), array('id' => $id, "com_code" => $com_code, 'order_type' => 1));

        if (empty($data)) {
            return redirect()->route('admin.suppliers_with_orders.index')->with(['error' => 'عفوا غير قادر على الوصول للبيانات المطلوبة !!']);
        };
        if ($data['is_approved'] == 1) {
            return redirect()->route('admin.suppliers_with_orders.index')->with(['error' => 'عفوا لا يمكن تحديث على فاتورة معتمده ومؤرشفة']);
        };

        $suppliers = get_cols_where(new SuppliersModel(), array('supplier_code', 'name'), array('com_code' => $com_code, 'active' => 1), 'id', 'DESC');
        $stores = get_cols_where(new Store(), array('id', 'name'), array('com_code' => $com_code, 'active' => 1), 'id', 'DESC');

        return view('admin.suppliers_with_orders.edit', ['data' => $data, 'suppliers' => $suppliers, 'stores' => $stores]);
    }

    public function update($id, Suppliers_ordersRequest $request)
    {
        try {

            $com_code = auth()->user()->com_code;
            $data = get_cols_where_row(new Suppliers_orderModel(), array('is_approved'), array('id' => $id, "com_code" => $com_code, 'order_type' => 1));

            if (empty($data)) {
                return redirect()->route('admin.suppliers_with_orders.index')->with(['error' => 'غير قادر على الوصول للبيانات المطلوبة ']);
            };

            $supplierData = get_cols_where_row(new SuppliersModel(), array('account_number'), array('supplier_code' => $request->supplier_code, "com_code" => $com_code));
            if (empty($supplierData)) {
                return redirect()->back()
                    ->with(['error' => 'عفوا غير قادر على الوصول الى بيانات المورد المحدد'])
                    ->withInput();
            }



            $data_to_update['order_date'] = $request->order_date;
            $data_to_update['order_type'] = 1;
            $data_to_update['Doc_No'] = $request->Doc_No;
            $data_to_update['supplier_code'] = $request->supplier_code;
            $data_to_update['pill_type'] = $request->pill_type;
            $data_to_update['store_id'] = $request->store_id;
            $data_to_update['account_number'] =  $supplierData['account_number'];

            $data_to_update['updated_by'] = auth()->user()->name;
            $data_to_update['updated_at'] = date("Y-m-d H:i:s");

            update(new Suppliers_orderModel(), $data_to_update, array('id' => $id, "com_code" => $com_code, 'order_type' => 1));
            return redirect()->route('admin.suppliers_orders.show', $id)->with(['success' => 'لقد تم تحديث بيانات بنجاح']);
        } catch (\Exception $ex) {
            return redirect()->back()->with(['error' => 'عفوا حصل خطأ' . $ex->getMessage()])->withInput();
        }
    }

    public function load_edit_item_details(Request $request)
    {

        if ($request->ajax()) {

            $auto_serial = $request->autoserailparent;

            $com_code = auth()->user()->com_code;
            $parent_pill_data = get_cols_where_row(new Suppliers_orderModel(), array('is_approved'), array('auto_serial' => $auto_serial, "com_code" => $com_code, 'order_type' => 1));



            if (!empty($parent_pill_data)) {

                if ($parent_pill_data['is_approved'] == 0) {
                    $items_cards = get_cols_where(new Inv_itemCard(), array("name", "item_code", "item_type"), array('active' => 1, 'com_code' => $com_code), 'id', 'DESC');
                    $item_data_details = get_cols_where_row(new Suppliers_with_orders_detailsModel(), array('*'), array('suppliers_with_orders_auto_serial' => $request->autoserailparent, "com_code" => $com_code, 'order_type' => 1, 'id' => $request->id));
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


                    return view("admin.suppliers_with_orders.load_edit_item_details", ['parent_pill_data' => $parent_pill_data, 'item_data_details' => $item_data_details, 'items_cards' => $items_cards , 'item_card_data'=>$item_card_data]);
                };
            }
        }
    }
    public function load_model_add_details(Request $request)
    {

        if ($request->ajax()) {

            $auto_serial = $request->autoserailparent;

            $com_code = auth()->user()->com_code;
            $parent_pill_data = get_cols_where_row(new Suppliers_orderModel(), array('is_approved'), array('auto_serial' => $auto_serial, "com_code" => $com_code, 'order_type' => 1));



            if (!empty($parent_pill_data)) {

                if ($parent_pill_data['is_approved'] == 0) {
                    $items_cards = get_cols_where(new Inv_itemCard(), array("name", "item_code", "item_type"), array('active' => 1, 'com_code' => $com_code), 'id', 'DESC');
                   
                    return view("admin.suppliers_with_orders.load_add_new_item_details", ['parent_pill_data' => $parent_pill_data,  'items_cards' => $items_cards ]);
                };
            }
        }
    }


    public function edit_item_details(Request $request)
    {
      

        if ($request->ajax()) {

            $auto_serial = $request->autoserailparent;

            $com_code = auth()->user()->com_code;
            $parent_pill_data = get_cols_where_row(new Suppliers_orderModel(), array('is_approved','order_date', 'tax_value','discount_value'), array('auto_serial' => $auto_serial, "com_code" => $com_code, 'order_type' => 1));



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

                    $flag = update(new Suppliers_with_orders_detailsModel(),$data_to_update,array('id'=>$request->id , 'com_code'=>$com_code, 
                                'order_type'=>1,'suppliers_with_orders_auto_serial'=>$request->autoserailparent));
                    if($flag){
                        $total_details_sum = get_sum_where(
                            new Suppliers_with_orders_detailsModel(),
                            'total_price',
                            array('suppliers_with_orders_auto_serial' => $request->autoserailparent, "com_code" => $com_code, 'order_type' => 1)
                        );

                        $dataUpdateParent['total_cost_items'] = $total_details_sum;
                        $dataUpdateParent['total_befor_discount'] = $total_details_sum + $parent_pill_data['tax_value'];
                        $dataUpdateParent['total_cost'] = $dataUpdateParent['total_befor_discount']  - $parent_pill_data['discount_value'];
                        $dataUpdateParent['updated_at'] = date('Y:m:d H:i:s');
                        $dataUpdateParent['updated_by'] = auth()->user()->name;
                        update(new Suppliers_orderModel(), $dataUpdateParent, array('auto_serial' => $request->autoserailparent, 'com_code' => $com_code, 'order_type' => 1));

                        echo json_encode("done");
                    }

                };
            }
        }
    }


    public function delete($id){
        try {



            $com_code = auth()->user()->com_code;
            $parent_pill_data = get_cols_where_row(new Suppliers_orderModel(), array('is_approved','auto_serial'), array('id' => $id, "com_code" => $com_code, 'order_type' => 1));



            if (empty($parent_pill_data)) {
                return redirect()->back()->with(['error'=>'عفوا حصل خطأ']);
            }

             
            if ($parent_pill_data['is_approved'] == 1) {
                return redirect()->back()->with(['error'=>'عفوا لا يمكن الحذف بتفاصيل فاتورة معتمده ومؤرشفة']);
            }

            $flag = delete(new Suppliers_orderModel(), array('id' => $id, "com_code" => $com_code, 'order_type' => 1));

            if($flag){
                delete(new Suppliers_with_orders_detailsModel(), array('suppliers_with_orders_auto_serial' => $parent_pill_data['auto_serial'], "com_code" => $com_code, 'order_type' => 1));
                return redirect()->route('admin.suppliers_orders.index')->with(['success' => 'لقد تم حذف  الفاتورة  بنجاح']);
            }
            
        } catch (\Exception $ex) {
         return redirect()->back()->with(['error'=>'عفوا حصل خطأ'.$ex->getMessage()])->withInput();
        }
    }

    
    public function delete_details($id,$parent_id){
        try {

            $com_code = auth()->user()->com_code;
            $parent_pill_data = get_cols_where_row(new Suppliers_orderModel(), array('is_approved','auto_serial'), array('id' => $parent_id, "com_code" => $com_code, 'order_type' => 1));



            if (empty($parent_pill_data)) {
                return redirect()->back()->with(['error'=>'عفوا حصل خطأ']);
            }

             
            if ($parent_pill_data['is_approved'] == 1) {
                return redirect()->back()->with(['error'=>'عفوا لا يمكن الحذف بتفاصيل فاتورة معتمده ومؤرشفة']);
            }
            $item_row = Suppliers_with_orders_detailsModel::find($id);
            if(!empty($item_row)){
                $flag = $item_row->delete();
                if($flag){
                    $total_details_sum = get_sum_where(
                        new Suppliers_with_orders_detailsModel(),
                        'total_price',
                        array('suppliers_with_orders_auto_serial' => $parent_pill_data['autoserailparent'], "com_code" => $com_code, 'order_type' => 1)
                    );
                    $dataUpdateParent['total_cost_items'] = $total_details_sum;
                    $dataUpdateParent['total_befor_discount'] = $total_details_sum + $parent_pill_data['tax_value'];
                    $dataUpdateParent['total_cost'] = $dataUpdateParent['total_befor_discount']  - $parent_pill_data['discount_value'];
                    $dataUpdateParent['updated_at'] = date('Y:m:d H:i:s');
                    $dataUpdateParent['updated_by'] = auth()->user()->name;
                    update(new Suppliers_orderModel(), $dataUpdateParent, array('id' => $parent_id, 'com_code' => $com_code, 'order_type' => 1));
                    return redirect()->back()->with(['success'=>' تم حذف بيانات بنجاح']);
                }else{
                    return redirect()->back()->with(['error'=>'عفوا حدث خطأ ما !!']);
                };
            }else{
                return redirect()->back()->with(['error'=>'عفوا غير قادر علي الوصول الي البيانات المطلوبة !!']);
            };
           



            
        } catch (\Exception $ex) {
         return redirect()->back()->with(['error'=>'عفوا حصل خطأ'.$ex->getMessage()])->withInput();
        }
    }


    public function load_model_approve_invoice(Request $request){
       
        if ($request->ajax()) {


            $auto_serial = $request->autoserailparent;
           
            $com_code = auth()->user()->com_code;
            $data = get_cols_where_row(new Suppliers_orderModel(), array('*'), array('auto_serial' => $auto_serial, "com_code" => 
                                        $com_code, 'order_type' => 1));
            
            $user_shifts = get_user_shift(new Admin_shifts(),new Treasure(),new Treasuries_transactionModel());
            
            
                                               
                
             
            return view("admin.suppliers_with_orders.load_model_approve_invoice" , ["data"=>$data , 'user_shifts'=> $user_shifts]);
            
            
        }
    }
    public function load_usershiftDiv(Request $request){
       
        if ($request->ajax()) {


           
           
           
            
            $user_shifts = get_user_shift(new Admin_shifts(),new Treasure(),new Treasuries_transactionModel());
            
            
                                               
                
             
            return view("admin.suppliers_with_orders.load_usershifts" , [ 'user_shifts'=> $user_shifts]);
            
            
        }
    }
 




}
