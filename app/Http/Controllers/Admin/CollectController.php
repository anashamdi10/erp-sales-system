<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Treasuries_transactionModel;
use App\Models\Admin_shifts;
use App\Models\Admin;
use App\Models\AccountModel;
use App\Models\account_typeModel;
use App\Models\Treasure;

use Illuminate\Http\Request;

class CollectController extends Controller
{
    public function index()
    {
        $com_code = auth()->user()->com_code;
        $id = auth()->user()->id;
        
        $data = get_cols_where_p(new Treasuries_transactionModel(), array('*'), array("com_code" => $com_code,), 'id', "DESC", PAGINATEION_COUNT);

        if (!empty($data)) {
            foreach ($data as $info) {
    
                $info->treasures_name = Treasure::where('id', $info->treasures_id)->value('name');
            }
        };

      // check_if_admim has shifts to collect 
        $check_exsits_shifts = get_cols_where_row( new Admin_shifts(),array('id'),array('com_code'=> $com_code,
                            'is_finished'=>0 , 'admin_id'=>auth()->user()->id ) );    
                            
                            
        if(!empty($check_exsits_shifts)){
            $check_exsits_shifts['treasure_name'] = Treasure::where('id', $check_exsits_shifts['treasures_id'])->value('name');
        };                   

        $accounts = get_cols_where(new AccountModel(), array("name", "account_number", "account_type"), array("com_code" => $com_code, "is_archived"=>0, "is_parent" => 0), 'id', 'DESC');
      
        foreach ($accounts as $info) {
    
            $info->account_type_name = account_typeModel::where('id', $info->account_type)->value('name');
           
        }
        
        

        return view('admin.treasures_tranaction.index', ['data' => $data, 'check_exsits_shifts' => $check_exsits_shifts ,'accounts' =>$accounts]);
    }
}
        