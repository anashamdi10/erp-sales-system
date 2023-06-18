<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Suppliers_ordersRequest;
use App\Models\Admin;
use App\Models\AccountModel;
use App\Models\Suppliers_orderModel;
use App\Models\Suppliers_with_orders_detailsModel;
use App\Models\SuppliersModel;
use App\Models\Inv_itemCard;
use App\Models\Inv_ums;
use App\Models\Store;
use App\Models\Admin_shifts;
use App\Models\Treasure;
use App\Models\Treasuries_transactionModel;
use App\Models\Inv_itemcard_batches;
use App\Models\Inv_itemcard_movements;



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
        $suppliers = get_cols_where(new SuppliersModel(), array('supplier_code', 'name'), array('com_code' => $com_code, 'active' => 0), 'id', 'DESC');
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
            $id= get_field_value(new Suppliers_orderModel(), 'id',  array('com_code'=>$com_code, 
                                'auto_serial'=> $data_insert['auto_serial'] ,'order_type'=> 1 ));
            return redirect()->route('admin.suppliers_orders.show', $id)->with('success','لقد تم إضافة بيانات بنجاح');

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

    public function do_approve ($auto_serial , Request $request){
        $com_code = auth()->user()->com_code;
        
        // sheck if approve 
        $data = get_cols_where_row(new Suppliers_orderModel(),array('is_approved','store_id', 'total_cost_items','id','account_number' , 'supplier_code'),
                                         array('auto_serial'=>$auto_serial, 'order_type'=>1,'com_code'=>$com_code));
        $supplier_name = get_field_value(new SuppliersModel(),'name', array("supplier_code"=>$data['supplier_code']));
                                       
        if($data['is_approved']== 1){
            return redirect()->route('admin.suppliers_orders.show', $data['id'])->with('error','عفوا لا يمكن اعتماد الفاتورة معتمده من قبل');
        }

        $dataUpdateParent['tax_percent']= $request->tax_percent;
        $dataUpdateParent['tax_value']= $request->tax_value;
        $dataUpdateParent['total_befor_discount']= $request->total_befor_discount;
        $dataUpdateParent['discount_type']= $request->discount_type;
        $dataUpdateParent['discount_value']= $request->discount_value;
        $dataUpdateParent['discount_percent']= $request->discount_percent;
        $dataUpdateParent['total_cost']= $request->total_cost;
        $dataUpdateParent['pill_type']= $request->pill_type;
        $dataUpdateParent['what_paid']= $request->what_paid;
        $dataUpdateParent['what_remain']= $request->what_remain;
        $dataUpdateParent['auto_serial']= $auto_serial;
        $dataUpdateParent['is_approved']= 1;
        $dataUpdateParent['money_for_account']= $request->total_cost*(-1);
        $dataUpdateParent['updated_at']= date("Y-m-d H:i:s");
        $dataUpdateParent['updated_by']= auth()->user()->name;
        $dataUpdateParent['approved_by']= auth()->user()->name;

        
        // check if pill type is cash 
        if($request->pill_type == 1){
            if($request->what_paid !=  $request->total_cost ){
                return redirect()->route('admin.suppliers_orders.show', $data['id'])->with('error','عفوا يجب ان يكون المبلغ بالكامل مدفوع في حاله ان فاتورة كاش');
            }
        }

        // check if pill type is agrl 
        if($request->pill_type == 2){
            if($request->what_paid == $request->total_cost ){
                return redirect()->route('admin.suppliers_orders.show', $data['id'])->with('error','عفوا يجب ان يكون المبلغ بالكامل مدفوع  في حاله ان فاتورة اجل');
            }

            
            
        }
        
        if($request->what_paid >0 ){
            if($request->what_paid > $request->total_cost ){
                return redirect()->route('admin.suppliers_orders.show', $data['id'])->with('error','عفوا يجب ان يكون المبلغ  مدفوع اكبر من المبلغ الفاتورة ');
            }

            // check if the user has treasures id 0r money 
            $user_shift = get_user_shift(new Admin_shifts(),new Treasure(), new Treasuries_transactionModel());
            
            if(empty($user_shift)){
                return redirect()->route('admin.suppliers_orders.show', $data['id'])->with('error','عفوا لا تمتلك شيفت خزنة مفتوحة لكي تتمكن من اتمام عملية الصرف ');

            }
            
            
            if($user_shift['current_blance']  < $request->what_paid ){
                return redirect()->route('admin.suppliers_orders.show', $data['id'])->with('error','عفوا لا تمتلك رصيد كاف في الخزنة الصرف لكي تتمكن من انمام عملية الصرف   ');

            }
        }

        $flag = update(new Suppliers_orderModel(),$dataUpdateParent,array('auto_serial'=>$auto_serial, 'order_type'=>1,'com_code'=>$com_code));

        if($flag){


            // affect on supplier balance هتأثر في وصيد لبمورد 

            refresh_account_blance($data['account_number'],new AccountModel(),new SuppliersModel(),
                                    new Treasuries_transactionModel(), new Suppliers_orderModel(),false);
            // حركات مختلفة 
            if($request->what_paid>0){

                $trussery_data = get_cols_where_row(new Treasure(),array('last_isal_exchange'),array('com_code'=>$com_code, 'id'=>$user_shift['treasures_id']));
                if(empty($trussery_data)){
                   
                    return redirect()->route('admin.suppliers_orders.show', $data['id'])->with('error',' عفوا غير قادر على الوصول الي  بيانات الخزنة المطلوبة   ');

                }
                
                $last_record_Treasuries_transaction =  get_cols_where_row_orderby(new Treasuries_transactionModel(),array('auto_serial'),array('com_code'=>$com_code), 'auto_serial',"DESC");
    
                if(!empty( $last_record_Treasuries_transaction)) {
                    $data_insert_Treasuries_transaction['auto_serial'] = $last_record_Treasuries_transaction['auto_serial']+1;
                }else{
                    $data_insert_Treasuries_transaction['auto_serial'] = 1;
                }             

                $data_insert_Treasuries_transaction['isal_number'] = $trussery_data['last_isal_exchange'] +1;
                $data_insert_Treasuries_transaction['mov_date'] = date("Y-m-d");
                $data_insert_Treasuries_transaction['account_number'] = $data['account_number'];
                $data_insert_Treasuries_transaction['mov_type'] = 9;
                $data_insert_Treasuries_transaction['treasures_id'] = $user_shift['treasures_id'];
                // debit مدين
                $data_insert_Treasuries_transaction['money'] = $request->what_paid * (-1);

                // creadit دائن
                $data_insert_Treasuries_transaction['money_for_account'] =  $request->what_paid * (1);

                $data_insert_Treasuries_transaction['the_foregin_key']= $data['auto_serial'];
                $data_insert_Treasuries_transaction['bayan'] = 'صرف نظير فاتورة مشتريات  رقم '.  $auto_serial;
                $data_insert_Treasuries_transaction['is_account'] = 1;
                $data_insert_Treasuries_transaction['is_approved'] = 1;
                $data_insert_Treasuries_transaction['shift_code'] =  $user_shift['shift_code'];
                $data_insert_Treasuries_transaction['com_code'] =  $com_code;
                $data_insert_Treasuries_transaction['created_at']= date("Y-m-d H:i:s");
                $data_insert_Treasuries_transaction['added_by']= auth()->user()->name;
               
                $flage = insert(new Treasuries_transactionModel, $data_insert_Treasuries_transaction);
                

                if($flage){
                    $data_to_update['last_isal_exchange'] = $data_insert_Treasuries_transaction['isal_number'];
                    update(new Treasure() ,  $data_to_update , array('id'=>$com_code, 'id'=>$user_shift['treasures_id']));       
                }else{
                    return redirect()->route('admin.suppliers_orders.show', $data['id'])->with('error','حدث خطأ ما ');
  
                }
            }
                
        }

        // store  move 
        $items = get_cols_where(new Suppliers_with_orders_detailsModel(), array("*"), array("suppliers_with_orders_auto_serial" => $auto_serial, "com_code" => $com_code, "order_type" => 1), "id", "ASC");
        if (!empty($items)) {
        foreach ($items as $info) {
            //get itemCard Data
            $itemCard_data = get_cols_where_row(new Inv_itemCard(), array("uom_id", "retail_uom_quantityToParent", "retail_uom_id",'cost_price_retail','does_has_retailunit' ), array("com_code" => $com_code, "item_code" => $info->item_code));       
                    
            
            
            if(!empty($itemCard_data)){    

                // get quantity before any action  in all stores 
                $quantityBeforeMove = get_sum_where(new Inv_itemcard_batches(), 'quantity',
                                array('item_code'=>$info->item_code,'com_code'=>$com_code));
                // نجيب كمية الصنف بمخزن الفاتورة الحالي قبل الحركة 
                $quantityBeforeMoveCurrentStore = get_sum_where(new Inv_itemcard_batches(), 'quantity',
                array('item_code'=>$info->item_code,'com_code'=>$com_code, 'store_id' => $data['store_id']));
                                
                $Main_uom_name =get_field_value(new Inv_ums(),'name', array('com_code'=>$com_code,'id'=>$itemCard_data['uom_id']));
               
                        // بندخل كميات للخزن بوحده قياس الاب اجباري 
                            // لو كان الوحجه اب if ...
                        if($info->isparentuom == 1){
                            $quantity = $info->dliverd_quantity ; 
                            $unit_price = $info->unit_price;
                           
                           
                        }else{
                            // لو كان الوحده ابن 
                            $quantity = ($info->dliverd_quantity /$itemCard_data['retail_uom_quantityToParent'] );
                            $unit_price = $info->unit_price * $itemCard_data['retail_uom_quantityToParent'];
                           
                           
                           
                        }
                            
                            // دخل بيانات جدول باتشات 

                        

                        // لو الصنف استهلاكي له تاريخ صلاحية وانتاج فيعمل تحلق بسعر الشراء مع التواريخ 
                        // لو الصنف غير استهلاكي يبقى يعمل تحقق فقط بسعر الشراء 

                        if($info->item_card_type == 2){
                            $datainsert_batch['store_id'] = $data['store_id']; 
                            $datainsert_batch['item_code'] = $info->item_code; 
                            $datainsert_batch['inv_uoms_id'] = $itemCard_data['uom_id']; 
                            $datainsert_batch['unit_cost_price'] = $unit_price; 
                            $datainsert_batch['expired_date'] = $info->expire_date; 
                            $datainsert_batch['production_date'] = $info->production_date; 
                        
                        }else{
                            $datainsert_batch['store_id'] = $data['store_id']; 
                            $datainsert_batch['item_code'] = $info->item_code; 
                            $datainsert_batch['inv_uoms_id'] = $itemCard_data['uom_id']; 
                            $datainsert_batch['unit_cost_price'] =$unit_price; 
                        }

                        $OldBatchesExsist = get_cols_where_row(new Inv_itemcard_batches(), array('id','quantity','unit_cost_price'),$datainsert_batch);

                        if(!empty($OldBatchesExsist)){
                            // تحديث باتش قديمة 

                            $dataupdate_batch['quantity'] = $OldBatchesExsist['quantity'] + $quantity;
                            
                            $dataupdate_batch['toatal_cost_price'] = $OldBatchesExsist['unit_cost_price'] * $dataupdate_batch['quantity'] ;

                            $dataupdate_batch['updated_at']= date("Y-m-d H:i:s");
                            $dataupdate_batch['updated_by']= auth()->user()->name;
                            
                            update(new Inv_itemcard_batches(), $dataupdate_batch,
                            array('id'=> $OldBatchesExsist['id'], 'com_code'=>$com_code) );

                        }else{
                        // ادخال باتش جديده

                            $datainsert_batch['quantity'] = $quantity; 

                            

                            $datainsert_batch['toatal_cost_price'] = $info->total_price;
                            $datainsert_batch['com_code'] = $com_code;
                            
                            $datainsert_batch['created_at']= date("Y-m-d H:i:s");
                            $datainsert_batch['added_by']= auth()->user()->name;
                            


                            $row  = get_cols_where_row_orderby(new Inv_itemcard_batches(), array("auto_serial",'id','quantity'), array("com_code" => $com_code), 'id', 'DESC');
                            if (!empty($row)) {
                                $datainsert_batch['auto_serial'] = $row['auto_serial'] + 1;
                            } else {
                                $datainsert_batch['auto_serial'] = 1;
                            }

                            insert(new Inv_itemcard_batches(), $datainsert_batch);
    
                        }

                        // كمية الصنف بكل المخازن بعد اتمام حركة بكل باتشات 
                        $quantityAfterMove = get_sum_where(new Inv_itemcard_batches() , 'quantity', 
                        array('com_code'=>$com_code , 'item_code' => $info->item_code));
                        // كمية الصنف بمخزن الفاتورة الشراء بعد اتمام حركة بكل باتشات 
                        $quantityAfterMoveCurrentStore = get_sum_where(new Inv_itemcard_batches() , 'quantity', 
                        array('com_code'=>$com_code , 'item_code' => $info->item_code, 'store_id'=>$data['store_id']));
                        


                        $itemMovementInsert['inv_itemcard_movements_categories']= 1;
                        $itemMovementInsert['item_code']= $info->item_code;
                        $itemMovementInsert['items_movements_types']=1;
                        // كود الفاتورة الاب
                        $itemMovementInsert['FK_table']=$auto_serial;

                        // كود الصف الابن بتفاصيل الفاتورة 
                        $itemMovementInsert['FK_table_details']=$info->id;
                        $itemMovementInsert['byan']='نظير مشتريات من المورد  ' .' ' . $supplier_name . 'رقم الفاتورة ' .$auto_serial ;
                        
                        // كمية الصنف بكل المخازن قبل الحركة 
                        $itemMovementInsert['quantity_befor_movement']='عدد ' . " " . ($quantityBeforeMove *1) . ' ' . $Main_uom_name ;
                        $itemMovementInsert['quantity_after_move']='عدد ' . " " . ($quantityAfterMove *1) . ' ' . $Main_uom_name ;
                        
                        // كمية الصنف  بالمخزن الحالي بعد  الحركة 
                        
                        // كمية الصنف  بالمخزن الحالي قبل  الحركة 
                        $itemMovementInsert['quantity_befor_move_store']='عدد ' . " " . ($quantityBeforeMoveCurrentStore *1) . ' ' . $Main_uom_name ;
                        
                        
                        
                        // كمية الصنف  بالمخزن الحالي بعد  الحركة 
                        $itemMovementInsert['quantity_after_move_store']='عدد ' . " " . ($quantityAfterMoveCurrentStore  *1) . ' ' . $Main_uom_name;

                        
                        
                        
                        
                        
                        $itemMovementInsert['store_id']= $data['store_id'];
                        
                        $itemMovementInsert['added_by']= auth()->user()->name;
                        $itemMovementInsert['created_at']= date("Y-m-d H:i:s");
                        $itemMovementInsert['date']= date("Y-m-d ");
                        $itemMovementInsert['com_code']= $com_code;
                        insert(new Inv_itemcard_movements(),$itemMovementInsert );
                        


                    }
                    // تحديث اخر سعر شراء للصنف 
                    if($info->isparentuom == 1){
                       $dataToUpdateitemCardCost ['cost_price'] = $info->unit_price;
                      
                       if($itemCard_data['does_has_retailunit'] == 1){
                           
                            $dataToUpdateitemCardCost['cost_price_retail'] =$info->unit_price/$itemCard_data['retail_uom_quantityToParent'];
                           
                       }
                        
                    }else{
                        // لو كان الوحده ابن 
                        $dataToUpdateitemCardCost ['cost_price'] = $info->unit_price * $itemCard_data['retail_uom_quantityToParent'];
                        $dataToUpdateitemCardCost ['cost_price_retail'] = $info->unit_price;
                    }

                    update(new Inv_itemCard(), $dataToUpdateitemCardCost ,array('com_code'=>$com_code , 'item_code' =>$info->item_code));

                    //up date item card  quantity mirror  تحديث المراه الرئيسية للصنف 
                    // نجيب كمية الصنف من جدول الباتشات 
                    DoUpdateItemCard(new Inv_itemCard(),$info->item_code,new Inv_itemcard_batches(),$itemCard_data['retail_uom_quantityToParent'],
                    $itemCard_data['does_has_retailunit']);

        }


        return redirect()->route('admin.suppliers_orders.show', $data['id'])->with('success','تم الاعتماد وترحيل الفاتورة بنجاح ');


        }

    }
 




}
