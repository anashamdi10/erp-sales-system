<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Trasuries_transactionRequest;
use App\Models\Treasuries_transactionModel;
use App\Models\Admin_shifts;
use App\Models\Admin;
use App\Models\AccountModel;
use App\Models\account_typeModel;
use App\Models\Treasure;
use App\Models\Mov_type;
use App\Models\Customer;
use App\Models\Sales_invoices;

use App\Models\Suppliers_orderModel;
use App\Models\SuppliersModel;
use Illuminate\Http\Request;

class CollectController extends Controller
{
    public function index()
    {
        $com_code = auth()->user()->com_code;
        
        
        $data = get_cols_where2_p(new Treasuries_transactionModel(), array('*'), array("com_code" => $com_code),'money','>',0, 'id', "DESC", PAGINATEION_COUNT);

        if (!empty($data)) {
            foreach ($data as $info) {
    
                $info->treasures_name = Treasure::where('id', $info->treasures_id)->value('name');
                $info->mov_type_name = Mov_type::where('id', $info->mov_type)->value('name');
            }
        };

      // check_if_admim has shifts to collect 
        $check_exsits_shifts = get_cols_where_row( new Admin_shifts(),array('shift_code', 'treasures_id', ),array('com_code'=> $com_code,
                            'is_finished'=>0 , 'admin_id'=>auth()->user()->id ) );    
        
            
        if(!empty($check_exsits_shifts)){
            $check_exsits_shifts['treasure_name'] = Treasure::where('id', $check_exsits_shifts['treasures_id'])->value('name');
        };    
        
        
        $check_exsits_shifts['treasures_balance_now'] = get_sum_where(new Treasuries_transactionModel(),'money',
                                                array('com_code'=>$com_code,'shift_code'=>$check_exsits_shifts['shift_code']));




    
        $accounts = get_cols_where(new AccountModel(), array("name", "account_number", "account_type"), array("com_code" => $com_code, "is_archived"=>0, "is_parent" => 0), 'id', 'DESC');
        
        foreach ($accounts as $info) {
            
            $info->account_type_name = account_typeModel::where('id', $info->account_type)->value('name');
            
        }
        
        $mov_type = get_cols_where(new Mov_type(), array("name", "id"), array("active" => 1 , 'in_screen'=>2, 'is_private_internal'=>0), 'id', 'ASC');
        

        return view('admin.collect_tranaction.index', ['data' => $data, 'check_exsits_shifts' => $check_exsits_shifts ,'accounts' =>$accounts , 'mov_type'=>$mov_type]);
    }

    public function store( Trasuries_transactionRequest $request){
        
        try {
                $com_code = auth()->user()->com_code;
                // get last isal treasures
                $treasury_data = get_cols_where_row(new Treasure(), array('last_isal_collect'),array('com_code'=>$com_code , 'id'=>$request->treasures_id));
            
                // check if user has open shift or not 
                $check_exsits_shifts = get_cols_where_row( new Admin_shifts(),array('shift_code', 'treasures_id', ),array('com_code'=> $com_code,
                'is_finished'=>0 , 'admin_id'=>auth()->user()->id ) );  
                if(empty($check_exsits_shifts)){
                    return redirect()->back()->with(['error'=> 'عفوا  بيانات الخزنة غير موجوده'  ])->withInput();
                };

                $last_record_Treasuries_transaction = get_cols_where_row_orderby(new Treasuries_transactionModel(),array('auto_serial'),
                                                            array('com_code'=>$com_code),"auto_serial","DESC");

                if(!empty( $last_record_Treasuries_transaction)) {
                    $data_insert['auto_serial'] = $last_record_Treasuries_transaction['auto_serial']+1;
                }else{
                    $data_insert['auto_serial'] = 1;
                }                                           
            
                $data_insert['mov_date'] = $request->mov_date;
                $data_insert['isal_number'] = $treasury_data['last_isal_collect']+1;
                $data_insert['treasures_id'] = $request->treasures_id;
                $data_insert ['mov_type'] = $request->mov_type ;
                $data_insert ['account_number'] = $request->account_number ;
                $data_insert ['is_approved'] = 1 ;
                $data_insert ['is_account'] = 1 ;
                // creadit دائن 
                $data_insert['money'] = $request->money;

                // debit مدين 
                $data_insert['money_for_account'] = $request->money * (-1);
                $data_insert['bayan'] = $request->bayan;
                $data_insert['com_code'] = $com_code;
                $data_insert['created_at']= date("Y-m-d H:i:s");
                $data_insert['added_by']= auth()->user()->name;
                $data_insert['shift_code']=  $check_exsits_shifts['shift_code'];


                $flage = insert(new Treasuries_transactionModel(), $data_insert);
                if($flage){
                    
                    Treasure::where(['com_code'=>$com_code , 'id'=>$request->treasures_id])->update(["last_isal_collect"=>$data_insert['isal_number']]);
                $account_type = AccountModel::where(["account_number" => $request->account_number, 'com_code' => $com_code])->value('account_type');
                if ($account_type == 2) {
                    refresh_account_blance_suppliers($request->account_number,new AccountModel(),new SuppliersModel(),
                        new Treasuries_transactionModel(),new Suppliers_orderModel(),false);
                } elseif ($account_type == 3) {
                    refresh_account_blance_customer($request->account_number,new AccountModel(),new Customer(),
                        new Treasuries_transactionModel(),new Sales_invoices(),false);
                }                    
                    return redirect()->route('admin.collect_tranaction.index')->with(['success'=> 'تم تحصيل بنجاح']);
                }else{
                    return redirect()->back()->with(['error'=> 'عفوا حدث خطأ م من فضلك حاول مرة اخرى '  ])->withInput();

                }




                
                

        } catch (\Exception $e) {
            return redirect()->back()->with(['error'=> 'عفوا حدث خطأ ما ' . ' ' . $e->getMessage() ])->withInput();
        }
    
    }


}
        