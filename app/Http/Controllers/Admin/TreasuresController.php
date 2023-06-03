<?php

namespace App\Http\Controllers\Admin;

use App\Models\Treasure;
use App\Models\Treasuries_delivery;
use App\Models\Admin;
use App\Http\Requests\TrasuresRequest;
use App\Http\Requests\Addtreasures_deliveryRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TreasuresController extends Controller
{
    public function index(){
       $data = Treasure::select()->orderby('id','DESC')->paginate(PAGINATEION_COUNT);
        if(!empty($data)){
            foreach($data as $info){
                $info->added_by_admin = Admin::where('id',$info->added_by)->value('name');
                if($info->updated_by >0 and $info->updated_by!=null){
                    $info->updated_by_admin = Admin::where('id',$info->updated_by)->value('name');

                }
            }
        };

       return view('admin.treasures.index',['data'=>$data]);
    }

    public function create(){
        return view('admin.treasures.create');
    }

    public function store(TrasuresRequest $request){
        try {
            $com_code=auth()->user()->com_code;
            
            $checkExists = Treasure::where(['name'=>$request->name,'com_code'=>$com_code])->first();

            if($checkExists== null){
                if($request->is_master ==1){
                    $checkExists_ismaster = Treasure::where(['is_master'=>1,'com_code'=>$com_code])->first();
                    if($checkExists_ismaster!= null){
                        return redirect()->back()
                        ->with(['error'=>'عفوا يوجد خزنة رئيسية بالفعل مسجلة من قبل لا يمكن ان يكون هناك اكتر من خزينة رئيسية !!'])
                        ->withInput();
                    }
                     
                }
                

                $data['name'] =$request->name;
                $data['is_master'] =$request->is_master;
                $data['last_isal_exchange'] =$request->last_isal_exchange;
                $data['last_isal_collect'] =$request->last_isal_collect;
                $data['active'] =$request->active; 
                $data['created_at'] =date('Y:m:d H:i:s'); 
                $data['added_by'] =auth()->user()->name;
                $data['date']=date("Y-m-d");
                $data['com_code']= $com_code; 

                Treasure::create($data);

                  return redirect()->route('admin.treasures.index')->with(['success'=>'لقد تم إضافة بيانات بنجاح']);

            }else{
                return redirect()->back()->with(['error'=>'عفوا اسم الخزنة مسجل من قبل '])->withInput();
            }
            

        } catch (\Exception $ex) {
            return redirect()->back()->with(['error'=>'عفوا حصل خطأ'.$ex->getMessage()])->withInput();
           
        }
    }

    public function edit($id){
        $data = Treasure::select()->find($id);
        return view('admin.treasures.edit', ['data'=>$data]); 
    }

    public function update($id, TrasuresRequest $request){
        try {
            //code...
            $com_code=auth()->user()->com_code;
            $data=Treasure::select()->find($id);

            if(empty($data)){
                return redirect()->route('admin.treasures.index')->with(['error'=>'غير قادر على الوصول للبيانات المطلوبة ']);
            };

            $checkExists=Treasure::where(['name'=>$request->name,'com_code'=>$com_code])->where('id','!=',$id)->first();
            if($checkExists!= null){
                return redirect()->back()->with(['error'=>'عفوا اسم الخزنة مسجل من قبل '])->withInput();
                
            }

            if($request->is_master ==1){
                $checkExists_ismaster = Treasure::where(['is_master'=> 1,'com_code'=>$com_code])->where('id','!=',$id)->first();
                if($checkExists_ismaster!= null){

                    return redirect()->back()
                    ->with(['error'=>'عفوا يوجد خزنة رئيسية بالفعل مسجلة من قبل لا يمكن ان يكون هناك اكتر من خزينة رئيسية !!'])
                    ->withInput();
                }    
            }

            $data_to_update['name'] = $request->name;
            $data_to_update['active'] = $request->active;
            $data_to_update['is_master'] = $request->is_master;
            $data_to_update['last_isal_exchange'] = $request->last_isal_exchange;
            $data_to_update['last_isal_collect'] = $request->last_isal_collect;
            $data_to_update['updated_by'] = auth()->user()->name;
            $data_to_update['updated_at'] = date("Y-m-d H:i:s" );

            Treasure::where(['id'=>$id,'com_code'=>$com_code])->update($data_to_update);
            return redirect()->route('admin.treasures.index')->with(['success'=>'لقد تم تحديث بيانات بنجاح']);
            

        } catch (\Exception $ex) {
            return redirect()->back()->with(['error'=>'عفوا حصل خطأ'.$ex->getMessage()])->withInput();
        }
    }

    public function ajax_search(Request $request){
        if($request->ajax()){
        $search_by_text=$request->search_by_text;
        $data=Treasure::where('name','LIKE',"%{$search_by_text}%")->orderBy('id','DESC')->paginate(PAGINATEION_COUNT);
        return view('admin.treasures.ajax_search',['data'=>$data]);
        }
    }

    public function details($id){
        try {
            
            
            $data=Treasure::select()->find($id);
            if(empty($data)){
                return redirect()->route('admin.treasures.index')->with(['error'=>'غير قادر على الوصول للبيانات المطلوبة ']);
            };

                $data['added_by_admin'] = Admin::where('id',$data['added_by'])->value('name');
                if($data['updated_by'] >0 and $data['updated_by']!=null){
                    $data['aupdated_by_admin'] = Admin::where('id',$data['updated_by'])->value('name');

                }

            $treasuries_delivery = Treasuries_delivery::select()->where(['treasures_id'=>$id])->orderby('id','DESC')->get();

            if(!empty($treasuries_delivery)){
                foreach( $treasuries_delivery as $info){
                   
                    $info->name = Treasure::where('id',$info->treasures_can_delivery_id)->value('name');      
                    $info->added_by_admin=Admin::where('id',$info->added_by)->value('name');    


                }
            }

            


            return view('admin.treasures.details',['data'=>$data, 'treasuries_delivery'=> $treasuries_delivery]);


        } catch (\Exception $ex) {
            return redirect()->back()->with(['error'=>'عفوا حصل خطأ'.$ex->getMessage()])->withInput();
        }
    }

    public function add_treasures_delivery($id){
        try {
            $com_code=auth()->user()->com_code;
            $data = Treasure::select('id', 'name')->find($id);
            if(empty($data)){
                return redirect()->route('admin.treasuries.index')->with(['error'=>'عفوا غير قادر علي الوصول الي البيانات المطلوبة !!']);
            };

            $treasures = Treasure::select('id', 'name')->where(['com_code'=>$com_code, 'active'=>1])->get();
            return view("admin.treasures.add_treasures_delivery",['data'=>$data,'treasures'=>$treasures]);
            
        } catch (\Exception $ex) {
            return redirect()->back()->with(['error'=>'عفوا حصل خطأ'.$ex->getMessage()])->withInput();
        }
    }

    public function store_treasures_delivery($id,Addtreasures_deliveryRequest $request){
        try {
            $com_code = auth()->user()->com_code;
            $data = Treasure::select('id', 'name')->find($id);

            if(empty($data)){
                return redirect()->route('admin.treasuries.index')->with(['error'=>'عفوا غير قادر علي الوصول الي البيانات المطلوبة !!']);
            }

            $checkExists =Treasuries_delivery::where(['treasures_id'=>$id, 
                            'treasures_can_delivery_id'=>$request->treasures_can_delivery_id,
                            'com_code'=>$com_code])->first();
            if($checkExists !=null){
                return redirect()->back()->with(['error'=>'عفوا هذي الخزانة مسجلة من قبل'])->withInput();
            }


            $data_insert['treasures_id']=$id; 
            $data_insert['treasures_can_delivery_id']=$request->treasures_can_delivery_id;
            $data_insert['created_at']= date("Y-m-d H:i:s");
            $data_insert['added_by']= auth()->user()->name;
            $data_insert['com_code']= $com_code;

            Treasuries_delivery::create($data_insert);
            return redirect()->route('admin.treasures.details',$id)->with(['success'=>'لقد تم اضاقة بيانات بنجاح']);



        } catch (\Exception $ex) {
            return redirect()->back()->with(['error'=>'عفوا حصل خطأ'.$ex->getMessage()])->withInput();
        }
    }

    public function delete_treasures_delivery($id){
       try {
            $treasures_delivery = Treasuries_delivery::find($id);
            if(!empty($treasures_delivery)){
                $flag = $treasures_delivery->delete();
                if($flag){
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
}
