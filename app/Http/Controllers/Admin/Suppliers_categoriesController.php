<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\SupplierCategories;
use App\Http\Requests\Suppliers_categoriesRequest;
use Illuminate\Http\Request;

class Suppliers_categoriesController extends Controller
{
    public function index()
    {
        $data = SupplierCategories::select()->orderby('id', 'DESC')->paginate(PAGINATEION_COUNT);
   
        if (!empty($data)) {
            foreach ($data as $info) {
                $info->added_by_admin = Admin::where('id', $info->added_by)->value('name');
                if ($info->updated_by > 0 and $info->updated_by != null) {
                    $info->updated_by_admin = Admin::where('id', $info->updated_by)->value('name');
                }
            }
        };

        return view('admin.suppliers_categories.index', ['data' => $data]);
    }

    public function create()
    {
        return view('admin.suppliers_categories.create');
    }

    public function store(Suppliers_categoriesRequest $request)
    {
        try {

            $com_code = auth()->user()->com_code;
            //check if not exsits
            $checkExists = SupplierCategories::where(['name' => $request->name, 'com_code' => $com_code])->first();
            if ($checkExists == null) {
                $data['name'] = $request->name;
                $data['active'] = $request->active;
                $data['created_at'] = date("Y-m-d H:i:s");
                $data['updated_at'] = null;
                $data['added_by'] = auth()->user()->name;
                $data['com_code'] = $com_code;
                $data['date'] = date("Y-m-d");
                SupplierCategories::create($data);
                return redirect()->route('admin.suppliers_categories.index')->with(['success' => 'لقد تم اضافة البيانات بنجاح']);
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
        $data = SupplierCategories::select()->find($id);
        return view('admin.suppliers_categories.edit', ['data'=>$data]); 
    }

    public function update(Suppliers_categoriesRequest $request, $id ){
        try {
            //code...
            
            $com_code=auth()->user()->com_code;
            $data=SupplierCategories::select()->find($id);

            if(empty($data)){
                return redirect()->route('admin.suppliers_categories.index')->with(['error'=>'غير قادر على الوصول للبيانات المطلوبة ']);
            };

            $checkExists=SupplierCategories::where(['name'=>$request->name,'com_code'=>$com_code])->where('id','!=',$id)->first();
            if($checkExists!= null){
                return redirect()->back()->with(['error'=>'عفوا اسم الخزنة مسجل من قبل '])->withInput();
                
            }

            

            $data_to_update['name'] = $request->name;
            $data_to_update['active'] = $request->active;
            $data_to_update['updated_by'] = auth()->user()->name;
            $data_to_update['updated_at'] = date("Y-m-d H:i:s" );

            SupplierCategories::where(['id'=>$id,'com_code'=>$com_code])->update($data_to_update);
            return redirect()->route('admin.suppliers_categories.index')->with(['success'=>'لقد تم تحديث بيانات بنجاح']);
            

        } catch (\Exception $ex) {
            return redirect()->back()->with(['error'=>'عفوا حصل خطأ'.$ex->getMessage()])->withInput();
        }
    }

    public function delete($id){
        try {
             $supplier_item = SupplierCategories::find($id);
             if(!empty($supplier_item)){
                 $flag = $supplier_item->delete();
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
