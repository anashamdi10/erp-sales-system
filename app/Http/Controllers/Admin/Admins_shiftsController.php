<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin_shiftsRequest;
use App\Models\Admin_shifts;
use App\Models\Admin;
use App\Models\Treasure;
use App\Models\Admins_treasures;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class Admins_shiftsController extends Controller
{
    public function index()
    {
        $com_code = auth()->user()->com_code;
        $data = get_cols_where_p(new Admin_shifts(), array('*'), array("com_code" => $com_code,), 'id', "DESC", PAGINATEION_COUNT);
        $check_is_exists = get_cols_where_row( new Admin_shifts(),array("id"),array('com_code'=> $com_code,
        'is_finished'=>0 , 'admin_id'=>auth()->user()->id ) );
        if (!empty($data)) {
            foreach ($data as $info) {
                $info->added_by_admin = Admin::where('id', $info->admin_id)->value('name');
                $info->treasure_name = Treasure::where('id', $info->treasures_id)->value('name');
              
            }
        };

        
        
        return view('admin.admins_shifts.index', ['data' => $data ,'check_is_exists'=>$check_is_exists]);
    }

    public function create()
    {
        
        $com_code = auth()->user()->com_code;

        $admin_treasures = get_cols_where(new Admins_treasures(),array('treasures_id'),
                                    array('com_code' => $com_code, 'active' => 1),'id','DESC'
        );


       
        if(!empty($admin_treasures)){
            foreach($admin_treasures as $info){
                
                $info->name = Treasure::where('id',$info->treasures_id)->value('name');
                
                $check_exsit_admin_shifts = get_cols_where_row(new Admin_shifts(), array('id'),
                                             array('treasures_id'=>$info->treasures_id,'com_code'=>$com_code, 'is_finished'=>0));


                                        
                if(!empty($check_exsit_admin_shifts)){
                    $info->avalible = false;
                }else{
                    $info->avalible = true;
                };     
                
                
            }
        }

        

        
       

        return view('admin.admins_shifts.create' , ['admin_treasures'=>$admin_treasures]);
    }

    public function store(Admin_shiftsRequest $request){
        try {
           
            $com_code = auth()->user()->com_code;
            $admin_id = auth()->user()->id;
           



            // check if the admin has shifts 
            $check_is_exists = get_cols_where_row( new Admin_shifts(),array("id"),array('com_code'=> $com_code,
                                'is_finished'=>0 , 'admin_id'=>$admin_id ) );

            if(!empty( $check_is_exists)){
              
                return redirect()->route('admin.admin_shift.index')->with(['error'=>' عفوا هناك شيفت مفتوح لديك بالفعل مستخدمه
                 حاليا لدي شفت اخر ولا يمكن استخدامها الا بعد انتهاء من الشيفت الاخر!!']);
            }

            
            // check if the treasur has in shift 
           

            $check_exsit_admin_shifts_treasueres = get_cols_where_row( new Admin_shifts(),array("id"), array('com_code'=>$com_code,
                                                    'is_finished'=> 0 , 'treasures_id'=>$request->treasures_id));

            
            if(!empty($check_exsit_admin_shifts_treasueres)){
               
                return redirect()->route('admin.admins_shifts.index')->with(['error'=>'عفوا الخزنه المختارة بالفعل مستخدمه حاليا لدي شيفت اخر 
                                                ولا يمكنك استخدامها الا بعد انتهاء شيفت الاخر']);
            }                                       


            $data_insert ['admin_id']= $admin_id;
            $data_insert['treasures_id'] =$request->treasures_id;
            $data_insert['start_date']= date("Y-m-d H:i:s");
            $data_insert['date']= date("Y-m-d");
            
            


            $data_insert['created_at']= date("Y-m-d H:i:s");
            $data_insert['added_by']= auth()->user()->name;
            $data_insert['com_code']= $com_code;

            $flag = insert(new Admin_shifts(),$data_insert);

            if($flag){
                
              
                return redirect()->route('admin.admin_shift.index')->with(['success'=>'لقد تم اضاق بيانات بنجاح']);
            }
                
            return redirect()->route('admin.admin_shift.index')->with(['error'=>' عفوا لقد حدث خطأ في الاضافة ']);
          



        } catch (\Exception $ex) {
            return redirect()->back()->with(['error'=>'عفوا حصل خطأ'.$ex->getMessage()])->withInput();
        }
    }
}
