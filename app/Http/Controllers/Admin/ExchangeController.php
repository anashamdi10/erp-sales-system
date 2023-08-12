<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Treasuries_transactionModel;
use App\Models\Treasure;
use App\Models\Mov_type;
use App\Models\Admin_shifts;
use App\Models\AccountModel;
use App\Models\Suppliers_orderModel;
use App\Http\Requests\Trasuries_transactionRequest;
use App\Models\Admin;
use App\Models\account_typeModel;
use App\Models\SuppliersModel;
use App\Models\Customer;
use App\Models\Sales_invoices;
use Illuminate\Console\View\Components\Alert;
use Illuminate\Http\Request;

class ExchangeController extends Controller
{
    function index (){
        $com_code = auth()->user()->com_code;
        $data = get_cols_where2_p(new Treasuries_transactionModel(), array('*'),array('com_code'=>$com_code),'money',
                                        '<','0','id','DESC',PAGINATEION_COUNT);
        foreach($data as $info){
            $info->treasures_name = Treasure::where("id",$info->treasures_id)->value('name');
            $info->mov_type_name  = Mov_type::where('id',$info->mov_type)->value('name');
            $info->account_name = AccountModel::where(['com_code' => $com_code, 'account_number' => $info->account_number])->value('name');
            $info->account_type = AccountModel::where(['com_code' => $com_code, 'account_number' => $info->account_number])->value('account_type');
            $info->account_type_name = account_typeModel::where(['id' => $info->account_type])->value('name');
        }

        $check_exsits_shifts = get_cols_where_row(new Admin_shifts(),array('treasures_id' , 'shift_code'),array('com_code'=> $com_code,
                                                    'is_finished'=>0 , 'admin_id'=>auth()->user()->id ));


        if(!empty($check_exsits_shifts)){
            $check_exsits_shifts['treasure_name'] =  Treasure::where('id',$check_exsits_shifts['treasures_id'])->value('name');
        }
        $check_exsits_shifts['treasures_balance_now'] = get_sum_where(new Treasuries_transactionModel (),'money' ,
                                                        array('com_code'=>$com_code ,'shift_code'=>$check_exsits_shifts['shift_code'] ) ) ;

        $accounts = get_cols_where(new AccountModel() , array("name", "account_number", "account_type"), 
                    array("com_code"=>$com_code,'active'=>1 , 'is_parent'=>0),'id','DESC'); 
        
        foreach($accounts as $info){
            $info->account_type_name = account_typeModel::where("id",$info->account_type)->value('name');
        }

        $mov_type = get_cols_where(new Mov_type(), array("name", "id"), array("active" => 1 , 'in_screen'=>1,
                    'is_private_internal'=>0), 'id', 'ASC');

        
        $treasures = get_cols_where(new Treasure(), array('id', 'name'), array("com_code" => $com_code), 'id', 'ASC');
        $users = get_cols_where(new Admin(), array('id', 'name'), array("com_code" => $com_code), 'id', 'ASC');
        
        return view('admin.exchange_transaction.index', ['data' => $data, 'check_exsits_shifts'=> $check_exsits_shifts , 
                    'accounts' => $accounts ,  'mov_type'=>$mov_type , 'treasures'=>$treasures, 'users'=> $users]);
        
    }

    public function store (Trasuries_transactionRequest $request){

    
        $com_code = auth()->user()->com_code;
        $trussery_data = get_cols_where_row(new Treasure(),array('last_isal_exchange'),array('com_code'=>$com_code, 'id'=>$request->treasures_id));
        if(empty($trussery_data)){
            return redirect()->back()->with(['error'=>'عفوا بيانات الخزنة غير موجوده '])->withInput();
        }
        $check_exsits_shifts = get_cols_where_row(new Admin_shifts(),array('treasures_id' , 'shift_code'),array('com_code'=> $com_code,
                                                    'is_finished'=>0 , 'admin_id'=>auth()->user()->id ));
        if(empty( $check_exsits_shifts)){
            return redirect()->back()->with(['error'=> 'عفوا  بيانات الخزنة غير موجوده'  ])->withInput();
        }

        $last_record_Treasuries_transaction =  get_cols_where_row_orderby(new Treasuries_transactionModel(),array('auto_serial'),array('com_code'=>$com_code), 'auto_serial',"DESC");

        if(!empty( $last_record_Treasuries_transaction)) {
            $data_insert['auto_serial'] = $last_record_Treasuries_transaction['auto_serial']+1;
        }else{
            $data_insert['auto_serial'] = 1;
        }             

        $data_insert['mov_date'] = $request->mov_date;
        $data_insert['isal_number'] = $trussery_data['last_isal_exchange'] +1;
        $data_insert['account_number'] = $request->account_number;
        $data_insert['mov_type'] = $request->mov_type;
        $data_insert['treasures_id'] = $request->treasures_id;
        // debit مدين
        $data_insert['money'] = $request->money * (-1);

          // creadit دائن
        $data_insert['money_for_account'] = $request->money * (1);


        $data_insert['bayan'] = $request->bayan ;
        $data_insert['is_account'] = 1;
        $data_insert['is_approved'] = 1;
        $data_insert['shift_code'] =  $check_exsits_shifts['shift_code'];
        $data_insert['com_code'] =  $com_code;
        $data_insert['created_at']= date("Y-m-d H:i:s");
        $data_insert['added_by']= auth()->user()->name;
        
        $flage = insert(new Treasuries_transactionModel, $data_insert);
        
        if($flage){
            $data_to_update['last_isal_exchange'] = $data_insert['isal_number'];
            update(new Treasure() ,  $data_to_update , array('id'=>$com_code, 'id'=>$request->treasures_id));

            $account_type = AccountModel::where(["account_number"=>$request->account_number, 'com_code'=>$com_code ])->value('account_type');
            if($account_type == 2){     
                refresh_account_blance_suppliers($request->account_number,new AccountModel(),new SuppliersModel(),
                new Treasuries_transactionModel(), new Suppliers_orderModel(),false);
            } elseif ($account_type == 3) {
                refresh_account_blance_customer($request->account_number,new AccountModel(),new Customer(),
                    new Treasuries_transactionModel(),new Sales_invoices(),false);
            }
        
            return redirect()->route('admin.exchange_tranaction.index')->with(['success'=> 'تم تحصيل بنجاح']);
        }


    }


    public function show_current_balance_account(Request $request)
    {

        if ($request->ajax()) {

            $com_code = auth()->user()->com_code;
            $account_number = $request->account_number;
            $accountData =  AccountModel::select('account_type')->where(['com_code' => $com_code, 'account_number' => $account_number])->first();
            if ($accountData['account_type'] == 2) {
                $the_current_balance = refresh_account_blance_suppliers(
                    $account_number,
                    new AccountModel(),
                    new SuppliersModel(),
                    new Treasuries_transactionModel(),
                    new Suppliers_orderModel(),
                    true
                );
                return view('admin.collect_tranaction.load_current_balance', ['the_current_balance' => $the_current_balance]);
            } elseif ($accountData['account_type'] == 3) {

                $the_current_balance = refresh_account_blance_customer(
                    $account_number,
                    new AccountModel(),
                    new Customer(),
                    new Treasuries_transactionModel(),
                    new Sales_invoices(),
                    true
                );
                return view('admin.collect_tranaction.load_current_balance', ['the_current_balance' => $the_current_balance]);
            } else {
                $the_current_balance = refresh_account_blance_general(
                    $account_number,
                    new AccountModel(),
                    new Treasuries_transactionModel(),
                    true
                );
                return view('admin.collect_tranaction.load_current_balance', ['the_current_balance' => $the_current_balance]);
            }
        }
    }

    public function search(Request $request)
    {
        $com_code = auth()->user()->com_code;
        if ($request->ajax()) {
            $searchbyradio = $request->searchbyradio;
            $search_by_text = $request->search_by_text;
            $account_number_search = $request->account_number_search;
            $mov_type_search = $request->mov_type_search;
            $treasures_search = $request->treasures_search;
            $users_search = $request->users_search;
            $invoice_date_from_search = $request->invoice_date_from_search;
            $invoice_date_to_search = $request->invoice_date_to_search;

            if ($account_number_search == 'all') {
                $field1 = "id";
                $operator1 = ">";
                $value1 = 0;
            } else {
                $field1 = "account_number";
                $operator1 = "=";
                $value1 = $account_number_search;
            }




            if ($mov_type_search == 'all') {
                $field2 = "id";
                $operator2 = ">";
                $value2 = 0;
            } else {
                $field2 = "mov_type";
                $operator2 = "=";
                $value2 = $mov_type_search;
            }




            if ($treasures_search == 'all') {
                $field3 = "id";
                $operator3 = ">";
                $value3 = 0;
            } else {
                $field3 = "treasures_id";
                $operator3 = "=";
                $value3 = $treasures_search;
            }


            if ($users_search == 'all') {
                $field4 = "id";
                $operator4 = ">";
                $value4 = 0;
            } else {
                $field4 = "added_by";
                $operator4 = "=";
                $value4 = $users_search;
            }



            if ($invoice_date_from_search == '') {
                $field5 = "id";
                $operator5 = ">";
                $value5 = 0;
            } else {
                $field5 = "mov_date";
                $operator5 = ">=";
                $value5 = $invoice_date_from_search;
            }


            if ($invoice_date_to_search == '') {
                $field6 = "id";
                $operator6 = ">";
                $value6 = 0;
            } else {
                $field6 = "mov_date";
                $operator6 = "<=";
                $value6 = $invoice_date_to_search;
            }


            if ($search_by_text != "") {

                if ($searchbyradio == 'auto_serial') {

                    $field7 = 'auto_serial';
                    $operator7 = "=";
                    $value7 =  $search_by_text;
                } else if ($searchbyradio == 'isal_number') {

                    $field7 = 'isal_number';
                    $operator7 = "=";
                    $value7 =  $search_by_text;
                } else if ($searchbyradio == 'shift_code') {

                    $field7 = 'shift_code';
                    $operator7 = "=";
                    $value7 =  $search_by_text;
                } else if ($searchbyradio == 'account_number') {

                    $field7 = 'account_number';
                    $operator7 = "=";
                    $value7 =  $search_by_text;
                }
            } else {

                $field7 = 'id';
                $operator7 = ">";
                $value7 = 0;
            }
            $data = Treasuries_transactionModel::where($field1, $operator1, $value1)->where($field2, $operator2, $value2)
                ->where($field3, $operator3, $value3)->where($field4, $operator4, $value4)->where($field5, $operator5, $value5)
                ->where($field6, $operator6, $value6)->where($field7, $operator7, $value7)->where('money', '>', 0)
                ->where('com_code', '=', $com_code)->orderBy('id', 'DESC')->paginate(PAGINATEION_COUNT);

            if (!empty($data)) {
                foreach ($data as $info) {

                    $info->treasures_name = Treasure::where('id', $info->treasures_id)->value('name');
                    $info->mov_type_name = Mov_type::where('id', $info->mov_type)->value('name');
                    $info->account_name = AccountModel::where(['com_code' => $com_code, 'account_number' => $info->account_number])->value('name');
                    $info->account_type = AccountModel::where(['com_code' => $com_code, 'account_number' => $info->account_number])->value('account_type');
                    $info->account_type_name = account_typeModel::where(['id' => $info->account_type])->value('name');
                }


                $total_collect_search = Treasuries_transactionModel::where($field1, $operator1, $value1)->where($field2, $operator2, $value2)
                    ->where($field3, $operator3, $value3)->where($field4, $operator4, $value4)->where($field5, $operator5, $value5)
                    ->where($field6, $operator6, $value6)->where($field7, $operator7, $value7)->where('money', '>', 0)
                    ->where('com_code', '=', $com_code)->orderBy('id', 'DESC')->sum('money');

                return view('admin.exchange_transaction.ajax_search', ['data' => $data, 'total_collect_search' => $total_collect_search]);
            }
        }

}
}
