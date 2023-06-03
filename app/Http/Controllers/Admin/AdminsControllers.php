<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Admins_treasures;
use App\Models\Treasure;
use Illuminate\Http\Request;

class AdminsControllers extends Controller
{
    public function index()
    {
        
        $com_code = auth()->user()->com_code;
        $data = get_cols_where_p(new Admin(),array('*'),array('com_code'=>$com_code),'id','DESC',PAGINATEION_COUNT);
        if (!empty($data)) {
            foreach ($data as $info) {
                $info->added_by_admin = Admin::where('id', $info->added_by)->value('name');
                if ($info->updated_by > 0 and $info->updated_by != null) {
                    $info->updated_by_admin = Admin::where('id', $info->updated_by)->value('name');
                }
            }
        };

        return view('admin.admins_accounts.index', ['data' => $data]);
    }


    public function details($id){
        try {
            
            $com_code = auth()->user()->com_code;
            $data = get_cols_where_row(new Admin(),array('*'),array('id'=>$id,'com_code'=>$com_code));
            
            if(empty($data)){
                return redirect()->route('admin.admins_accounts.index')->with(['error'=>'غير قادر على الوصول للبيانات المطلوبة ']);
            };

            $data['added_by_admin'] = Admin::where('id',$data['added_by'])->value('name');
            if($data['updated_by'] >0 and $data['updated_by']!=null){
                $data['aupdated_by_admin'] = Admin::where('id',$data['updated_by'])->value('name');

            }

            $treasures = get_cols_where(new Treasure(),array('*'),array('active'=>1, 'com_code'=>$com_code),'id','DESC');

            $treasuries_delivery = get_cols_where(new Admins_treasures(),array('*'),
                                        array('admin_id'=>$id,'com_code'=>$com_code),'id','DESC');   
                                        
                                      
            if(!empty($treasuries_delivery)){
                foreach( $treasuries_delivery as $info){                       
                    $info->name = Treasure::where('id',$info->treasures_id)->value('name');      
                }
           }

            
            return view('admin.admins_accounts.details',['data'=>$data , 'treasuries_delivery'=>$treasuries_delivery , 'treasures'=> $treasures]);


        } catch (\Exception $ex) {
            return redirect()->back()->with(['error'=>'عفوا حصل خطأ'.$ex->getMessage()])->withInput();
        }
    }

    public function store_treasures_to_admin(Request $request ,$id){
        try {

            
            
            $com_code = auth()->user()->com_code;
            $data = get_cols_where_row(new Admin(),array('*'),array('id'=>$id,'com_code'=>$com_code));
            
            if(empty($data)){
                return redirect()->route('admin.admins_accounts.index')->with(['error'=>'غير قادر على الوصول للبيانات المطلوبة ']);
            };

   
           
            // check if not exstise 
            $dataadmins_treasures_exsits  = get_cols_where_row(new Admins_treasures(),array('id'),
                                            array('admin_id'=>$id,'com_code'=>$com_code, 'treasures_id'=>$request->treasures_id));
            
            if(!empty($dataadmins_treasures_exsits)){
                return redirect()->route('admin.admins_accounts.details',$id)->with(['error'=>'غفوا هذه الخزنة فعلا  مضافه من قبل بهذا المستخدم']);
            };

            $data_insert['admin_id'] = $id;
            $data_insert['treasures_id'] = $request->treasures_id;
            $data_insert['active'] = 1;
            $data_insert['created_at'] = date("Y-m-d H:i:s");
    
            $data_insert['added_by'] = auth()->user()->name;
            $data_insert['com_code'] = $com_code;
            $data_insert['date'] = date("Y-m-d");

            $flag = insert(new Admins_treasures(),$data_insert);
            if($flag){
                return redirect()->route('admin.admins_accounts.details',$id)->with(['success' => 'لقد تم اضافة البيانات بنجاح']);
            }else{
                return redirect()->route('admin.admins_accounts.details',$id)->with(['error'=>'غفوا حدث خطأ ما ']);

            }

           
            
        } catch (\Exception $ex) {
            return redirect()->back()->with(['error'=>'عفوا حصل خطأ'.$ex->getMessage()])->withInput();
        }
    }

}
