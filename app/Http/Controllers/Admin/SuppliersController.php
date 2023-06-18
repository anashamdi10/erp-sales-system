<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\SuppliersModel;
use App\Models\AccountModel;
use App\Models\Admin_setting;
use App\Models\SupplierCategories;
use App\Http\Requests\SuppliersRequest;
use App\Http\Requests\SuppliersEditRequest;
use Illuminate\Http\Request;

class SuppliersController extends Controller
{
    public function index()
    {
        $com_code = auth()->user()->com_code;
        $data = get_cols_where_p(new SuppliersModel(), array('*'), array("com_code" => $com_code,), 'id', "DESC", PAGINATEION_COUNT);

        if (!empty($data)) {
            foreach ($data as $info) {
                $info->added_by_admin = Admin::where('id', $info->added_by)->value('name');
                if ($info->updated_by > 0 and $info->updated_by != null) {
                    $info->updated_by_admin = Admin::where('id', $info->updated_by)->value('name');
                }

                $info->categories_name = SupplierCategories::where('id', $info->suppliers_categories_id)->value('name');
            }
        };
        
        return view('admin.suppliers.index', ['data' => $data]);
    }

    public function create()
    {
        $com_code = auth()->user()->com_code;
        $suppliers_categories = get_cols_where_p(new SupplierCategories(), array('*'), array("com_code" => $com_code,), 'id', "DESC", PAGINATEION_COUNT);
        return view('admin.suppliers.create' , ['suppliers_categories'=> $suppliers_categories]);
    }

    public function store(SuppliersRequest $request)
    {
      
        try {

             
            $com_code = auth()->user()->com_code;
            //check if not exsits for name
            $checkExists_name = get_cols_where_row(new SuppliersModel(), array("id"), array('name' => $request->name, 'com_code' => $com_code));
            if (!empty($checkExists_name)) {
                return redirect()->back()
                    ->with(['error' => 'عفوا اسم المورد مسجل من قبل'])
                    ->withInput();
            }
            //set customer code
            $row = get_cols_where_row_orderby(new SuppliersModel(), array("supplier_code"), array("com_code" => $com_code), 'id', 'DESC');
            if (!empty($row)) {
                $data_insert['supplier_code'] = $row['supplier_code'] + 1;
            } else {
                $data_insert['supplier_code'] = 1;
            }

             //set account number
             $row = get_cols_where_row_orderby(new AccountModel(), array("account_number"), array("com_code" => $com_code), 'id', 'DESC');
             if (!empty($row)) {
                 $data_insert['account_number'] = $row['account_number'] + 1;
             } else {
                 $data_insert['account_number'] = 1;
             }



            $data_insert['name'] = $request->name;
            $data_insert['suppliers_categories_id'] = $request->suppliers_categories_id;
            $data_insert['address'] = $request->address;
          

            $data_insert['start_balance_status'] = $request->start_balance_status;
            if ($data_insert['start_balance_status'] == 1) {
                //credit
                $data_insert['start_balance'] = $request->start_balance * (-1);
            } elseif ($data_insert['start_balance_status'] == 2) {
                //debit
                $data_insert['start_balance'] = $request->start_balance;
                if ($data_insert['start_balance'] < 0) {
                    $data_insert['start_balance'] = $data_insert['start_balance'] * (-1);
                }
            } elseif ($data_insert['start_balance_status'] == 3) {
                //balanced
                $data_insert['start_balance'] = 0;
            } else {
                $data_insert['start_balance_status'] = 3;
                $data_insert['start_balance'] = 0;
            }

            
            $data_insert['notes'] = $request->notes;
            $data_insert['active'] = $request->active;
            $data_insert['added_by'] = auth()->user()->name;
            $data_insert['created_at'] = date("Y-m-d H:i:s");
            $data_insert['date'] = date("Y-m-d");
            $data_insert['com_code'] = $com_code;

            $flag = insert(new SuppliersModel(),$data_insert) ;
            if($flag){
                // insert into accounts 
                $data_insert_account['name'] = $request->name;
               
            

                $data_insert_account['start_balance_status'] = $request->start_balance_status;
                if ($data_insert_account['start_balance_status'] == 1) {
                    //credit
                    $data_insert_account['start_balance'] = $request->start_balance * (-1);
                } elseif ($data_insert_account['start_balance_status'] == 2) {
                    //debit
                    $data_insert_account['start_balance'] = $request->start_balance;
                    if ($data_insert_account['start_balance'] < 0) {
                        $data_insert_account['start_balance'] = $data_insert_account['start_balance'] * (-1);
                    }
                } elseif ($data_insert_account['start_balance_status'] == 3) {
                    //balanced
                    $data_insert_account['start_balance'] = 0;
                } else {
                    $data_insert_account['start_balance_status'] = 3;
                    $data_insert_account['start_balance'] = 0;
                }

                $suppliers_parent_account_number =get_field_value(new Admin_setting(),'suppliers_parent_account_number',array('com_code'=>$com_code));
                $data_insert_account['parent_account_number'] =$suppliers_parent_account_number;
                $data_insert_account['is_parent']=0;
                $data_insert_account['notes'] = $request->notes;
                $data_insert_account['account_number'] = $data_insert['account_number'];
                $data_insert_account['account_type'] = 2;
                $data_insert_account['is_archived'] = $request->active;
                $data_insert_account['added_by'] = auth()->user()->id;
                $data_insert_account['created_at'] = date("Y-m-d H:i:s");
                $data_insert_account['date'] = date("Y-m-d");
                $data_insert_account['com_code'] = $com_code;
                $data_insert_account['other_table_FK'] =  $data_insert['supplier_code'];
                insert(new AccountModel(),$data_insert_account) ;

            }
            return redirect()->route('admin.suppliers.index')->with(['success' => 'لقد تم اضافة البيانات بنجاح']);
        } catch (\Exception $ex) {
            return redirect()->back()
                ->with(['error' => 'عفوا حدث خطأ ما' . $ex->getMessage()])
                ->withInput();
        }
    }

    public function edit($id)
    {
        $com_code = auth()->user()->com_code;
        $suppliers_categories = get_cols_where_p(new SupplierCategories(), array('*'), array("com_code" => $com_code,), 'id', "DESC", PAGINATEION_COUNT);
        $data = get_cols_where_row(new SuppliersModel(), array('*'), array('id' => $id, "com_code" => $com_code));
       

        return view('admin.suppliers.edit', ['data' => $data , "suppliers_categories" => $suppliers_categories]);
    }

    public function update($id, SuppliersEditRequest $request)
    {
       

        try {

            $com_code = auth()->user()->com_code;
            $data = get_cols_where_row(new SuppliersModel(), array('id','account_number','supplier_code'), array('id' => $id, "com_code" => $com_code));

            if (empty($data)) {
                return redirect()->route('admin.suppliers.index')->with(['error' => 'غير قادر على الوصول للبيانات المطلوبة ']);
            };

            $checkExists = SuppliersModel::where(['name' => $request->name, 'com_code' => $com_code])->where('id', '!=', $id)->first();
            if ($checkExists != null) {
                return redirect()->back()->with(['error' => 'عفوا اسم العميل مسجل من قبل '])->withInput();
            }



            $data_to_update['name'] = $request->name;
            $data_to_update['suppliers_categories_id'] = $request->suppliers_categories_id;
            $data_to_update['address'] = $request->address;
            $data_to_update['notes'] = $request->notes;
            
            $data_to_update['active'] = $request->active;
            $data_to_update['updated_by'] = auth()->user()->name;
            $data_to_update['updated_at'] = date("Y-m-d H:i:s");

            $flag= update(new SuppliersModel(), $data_to_update, array('id' => $id, 'com_code' => $com_code));
           
            if($flag){
                $data_to_update_account['name'] = $request->name;
                $data_to_update_account['updated_by'] = auth()->user()->name;
                $data_to_update_account['updated_at'] = date("Y-m-d H:i:s");
                
                update(new AccountModel(), $data_to_update_account, array('account_number' => $data['account_number'],'other_table_FK'=>$data['supplier_code'], 'com_code' => $com_code, 'account_type'=>3));
                
            }

            return redirect()->route('admin.suppliers.index')->with(['success' => 'لقد تم تحديث بيانات بنجاح']);
        } catch (\Exception $ex) {
            return redirect()->back()->with(['error' => 'عفوا حصل خطأ' . $ex->getMessage()])->withInput();
        }
    }
    public function delete($id)
    {
        try {

            $com_code = auth()->user()->com_code;
            $item_row = get_cols_where_row(new SuppliersModel(), array('id'), array('id' => $id, "com_code" => $com_code));
            if (!empty($item_row)) {
                $flag = delete(new SuppliersModel(), array('id' => $id, "com_code" => $com_code));
                if ($flag) {
                    return redirect()->back()->with(['success' => ' تم حذف بيانات بنجاح']);
                } else {
                    return redirect()->back()->with(['error' => 'عفوا حدث خطأ ما !!']);
                };
            } else {
                return redirect()->back()->with(['error' => 'عفوا غير قادر علي الوصول الي البيانات المطلوبة !!']);
            };
        } catch (\Exception $ex) {
            return redirect()->back()->with(['error' => 'عفوا حصل خطأ' . $ex->getMessage()])->withInput();
        }
    }


    public function ajax_search(Request $request)
    {
        $com_code = auth()->user()->com_code;
        if ($request->ajax()) {
            $search_by_text = $request->search_by_text;
           
           
            $searchbyradio = $request->searchbyradio;

            if ($search_by_text != '') {
                if ($searchbyradio == 'account_number') {
                    $field1 = "account_number";
                    $operator1 = "=";
                    $value1 = $search_by_text;
                } elseif($searchbyradio == 'code'){
                    $field1 = "supplier_code";
                    $operator1 = "=";
                    $value1 = $search_by_text;
                }else {
                    $field1 = "name";
                    $operator1 = "like";
                    $value1 = "%{$search_by_text}%";
                }
            } else {
                //true 
                $field1 = "id";
                $operator1 = ">";
                $value1 = 0;
            }
           
            $data = SuppliersModel::where($field1, $operator1, $value1)->where(['com_code'=>$com_code])->orderBy('id', 'DESC')->paginate(PAGINATEION_COUNT);
            if (!empty($data)) {
                foreach ($data as $info) {
                    $info->added_by_admin = Admin::where('id', $info->added_by)->value('name');
                    if ($info->updated_by > 0 and $info->updated_by != null) {
                        $info->updated_by_admin = Admin::where('id', $info->updated_by)->value('name');
                    }

                    $info->categories_name = SupplierCategories::where('id', $info->suppliers_categories_id)->value('name');
                }
            };
            return view('admin.suppliers.ajax_search', ['data' => $data]);
        }
    }

}
