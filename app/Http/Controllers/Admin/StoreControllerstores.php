<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\Store;
use App\Http\Requests\StoreRequest;

class StoreControllerstores extends Controller
{
    public function index()
    {
        $data = Store::select()->orderby('id', 'DESC')->paginate(PAGINATEION_COUNT);
        if (!empty($data)) {
            foreach ($data as $info) {
                $info->added_by_admin = Admin::where('id', $info->added_by)->value('name');
                if ($info->updated_by > 0 and $info->updated_by != null) {
                    $info->updated_by_admin = Admin::where('id', $info->updated_by)->value('name');
                }
            }
        };

        return view('admin.stores.index', ['data' => $data]);
    }

    public function create()
    {
        return view('admin.stores.create');
    }


    public function store(StoreRequest $request)
    {
        try {

            $com_code = auth()->user()->com_code;
            //check if not exsits
            $checkExists = Store::where(['name' => $request->name, 'com_code' => $com_code])->first();
            if ($checkExists == null) {
                $data['name'] = $request->name;
                $data['active'] = $request->active;
                $data['created_at'] = date("Y-m-d H:i:s");
                $data['updated_at'] = null;
                $data['added_by'] = auth()->user()->name;
                $data['com_code'] = $com_code;
                $data['date'] = date("Y-m-d");
                Store::create($data);
                return redirect()->route('admin.stores.index')->with(['success' => 'لقد تم اضافة البيانات بنجاح']);
            } else {
                return redirect()->back()
                    ->with(['error' => 'عفوا اسم الفئة مسجل من قبل'])
                    ->withInput();
            }
        } catch (\Exception $ex) {
            return redirect()->back()
                ->with(['error' => 'عفوا حدث خطأ ما' . $ex->getMessage()])
                ->withInput();
        }
    }

    public function edit($id){
        $data = Store::select()->find($id);
        return view('admin.stores.edit', ['data'=>$data]); 
    }

    public function update( $id,StoreRequest $request ){
        try {
            
            $com_code=auth()->user()->com_code;
            $data=Store::select()->find($id);

            if(empty($data)){
                return redirect()->route('admin.treasures.index')->with(['error'=>'غير قادر على الوصول للبيانات المطلوبة ']);
            };

            $checkExists=Store::where(['name'=>$request->name,'com_code'=>$com_code])->where('id','!=',$id)->first();
            if($checkExists!= null){
                return redirect()->back()->with(['error'=>'عفوا اسم الخزنة مسجل من قبل '])->withInput();
                
            }

            

            $data_to_update['name'] = $request->name;
            $data_to_update['phones'] = $request->phones;
            $data_to_update['address'] = $request->address;
            $data_to_update['active'] = $request->active;
            $data_to_update['updated_by'] = auth()->user()->name;
            $data_to_update['updated_at'] = date("Y-m-d H:i:s" );

            Store::where(['id'=>$id,'com_code'=>$com_code])->update($data_to_update);
            return redirect()->route('admin.stores.index')->with(['success'=>'لقد تم تحديث بيانات بنجاح']);
            

        } catch (\Exception $ex) {
            return redirect()->back()->with(['error'=>'عفوا حصل خطأ'.$ex->getMessage()])->withInput();
        }
    }

    public function delete($id){
        try {
             $treasures_delivery = Store::find($id);
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
