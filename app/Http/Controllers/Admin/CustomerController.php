<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CustomerRequest;
use App\Http\Requests\EditCustomerRequest;
use App\Models\Admin;
use App\Models\Admin_setting;
use App\Models\AccountModel;
use App\Models\Customer;

use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        $com_code = auth()->user()->com_code;
        $data = get_cols_where_p(new Customer(), array('*'), array("com_code" => $com_code,), 'id', "DESC", PAGINATEION_COUNT);

        if (!empty($data)) {
            foreach ($data as $info) {
                $info->added_by_admin = Admin::where('id', $info->added_by)->value('name');
                if ($info->updated_by > 0 and $info->updated_by != null) {
                    $info->updated_by_admin = Admin::where('id', $info->updated_by)->value('name');
                }
            }
        };
        
        return view('admin.customers.index', ['data' => $data]);
    }

    public function create()
    {
        return view('admin.customers.create');
    }
    public function store(CustomerRequest $request)
    {
        try {

            $com_code = auth()->user()->com_code;
            //check if not exsits for name
            $checkExists_name = get_cols_where_row(new Customer(), array("id"), array('name' => $request->name, 'com_code' => $com_code));
            if (!empty($checkExists_name)) {
                return redirect()->back()
                    ->with(['error' => 'عفوا اسم العميل مسجل من قبل'])
                    ->withInput();
            }
            //set customer code
            $row = get_cols_where_row_orderby(new Customer(), array("customer_code"), array("com_code" => $com_code), 'id', 'DESC');
            if (!empty($row)) {
                $data_insert['customer_code'] = $row['customer_code'] + 1;
            } else {
                $data_insert['customer_code'] = 1;
            }

             //set account number
             $row = get_cols_where_row_orderby(new AccountModel(), array("account_number"), array("com_code" => $com_code), 'id', 'DESC');
             if (!empty($row)) {
                 $data_insert['account_number'] = $row['account_number'] + 1;
             } else {
                 $data_insert['account_number'] = 1;
             }



            $data_insert['name'] = $request->name;
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
            $data_insert['added_by'] = auth()->user()->id;
            $data_insert['created_at'] = date("Y-m-d H:i:s");
            $data_insert['date'] = date("Y-m-d");
            $data_insert['com_code'] = $com_code;

            $flag = insert(new Customer(),$data_insert) ;
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

                $customer_parent_account_number =get_field_value(new Admin_setting(),'customer_parent_account_number',array('com_code'=>$com_code));
                $data_insert_account['parent_account_number'] =$customer_parent_account_number;
                $data_insert_account['is_parent']=0;
                $data_insert_account['notes'] = $request->notes;
                $data_insert_account['account_number'] = $data_insert['account_number'];
                $data_insert_account['account_type'] = 3;
                $data_insert_account['is_archived'] = $request->active;
                $data_insert_account['added_by'] = auth()->user()->id;
                $data_insert_account['created_at'] = date("Y-m-d H:i:s");
                $data_insert_account['date'] = date("Y-m-d");
                $data_insert_account['com_code'] = $com_code;
                $data_insert_account['other_table_FK'] =  $data_insert['customer_code'];
                insert(new AccountModel(),$data_insert_account) ;

            }
            return redirect()->route('admin.customer.index')->with(['success' => 'لقد تم اضافة البيانات بنجاح']);
        } catch (\Exception $ex) {
            return redirect()->back()
                ->with(['error' => 'عفوا حدث خطأ ما' . $ex->getMessage()])
                ->withInput();
        }
    }

    public function edit($id)
    {
        $com_code = auth()->user()->com_code;
        $data = get_cols_where_row(new Customer(), array('*'), array('id' => $id, "com_code" => $com_code));
       

        return view('admin.customers.edit', ['data' => $data]);
    }

    public function update($id, EditCustomerRequest $request)
    {

        try {

            $com_code = auth()->user()->com_code;
            $data = get_cols_where_row(new Customer(), array('id','account_number','customer_code'), array('id' => $id, "com_code" => $com_code));

            if (empty($data)) {
                return redirect()->route('admin.customer.index')->with(['error' => 'غير قادر على الوصول للبيانات المطلوبة ']);
            };

            $checkExists = Customer::where(['name' => $request->name, 'com_code' => $com_code])->where('id', '!=', $id)->first();
            if ($checkExists != null) {
                return redirect()->back()->with(['error' => 'عفوا اسم العميل مسجل من قبل '])->withInput();
            }



            $data_to_update['name'] = $request->name;
            $data_to_update['address'] = $request->address;
            $data_to_update['notes'] = $request->notes;
            
            $data_to_update['active'] = $request->active;
            $data_to_update['updated_by'] = auth()->user()->name;
            $data_to_update['updated_at'] = date("Y-m-d H:i:s");

            $flag= update(new Customer(), $data_to_update, array('id' => $id, 'com_code' => $com_code));

            if($flag){
                $data_to_update_account['name'] = $request->name;
                $data_to_update_account['updated_by'] = auth()->user()->name;
                $data_to_update_account['updated_at'] = date("Y-m-d H:i:s");
                
                update(new AccountModel(), $data_to_update_account, array('account_number' => $data['account_number'],'other_table_FK'=>$data['customer_code'], 'com_code' => $com_code, 'account_type'=>3));
                
            }

            return redirect()->route('admin.customer.index')->with(['success' => 'لقد تم تحديث بيانات بنجاح']);
        } catch (\Exception $ex) {
            return redirect()->back()->with(['error' => 'عفوا حصل خطأ' . $ex->getMessage()])->withInput();
        }
    }
    public function delete($id)
    {
        try {

            $com_code = auth()->user()->com_code;
            $item_row = get_cols_where_row(new Customer(), array('id'), array('id' => $id, "com_code" => $com_code));
            if (!empty($item_row)) {
                $flag = delete(new Customer(), array('id' => $id, "com_code" => $com_code));
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
                    $field1 = "customer_code";
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
           
            $data = Customer::where($field1, $operator1, $value1)->where(['com_code'=>$com_code])->orderBy('id', 'DESC')->paginate(PAGINATEION_COUNT);
            if (!empty($data)) {
                foreach ($data as $info) {
                    $info->added_by_admin = Admin::where('id', $info->added_by)->value('name');
                    if ($info->updated_by > 0 and $info->updated_by != null) {
                        $info->updated_by_admin = Admin::where('id', $info->updated_by)->value('name');
                    }
                }
            };
            return view('admin.customers.ajax_search', ['data' => $data]);
        }
    }

}
