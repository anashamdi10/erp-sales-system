<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Inv_ums;
use App\Models\Admin;
use App\Http\Requests\Inv_uomsRequest;
use App\Http\Requests\InvUomUpdateRequest;
use App\Models\Sales_invoices_details;
use App\Models\Suppliers_with_orders_detailsModel;
use Illuminate\Http\Request;

class Inv_ums_UomController extends Controller
{
    public function index()
    {
        $data = Inv_ums::select()->orderby('id', 'DESC')->paginate(PAGINATEION_COUNT);
        if (!empty($data)) {
            foreach ($data as $info) {
                $info->added_by_admin = Admin::where('id', $info->added_by)->value('name');
                if ($info->updated_by > 0 and $info->updated_by != null) {
                    $info->updated_by_admin = Admin::where('id', $info->updated_by)->value('name');
                }
            }
        };

        return view('admin.inv_uoms.index', ['data' => $data]);
    }

    public function create()
    {
        return view('admin.inv_uoms.create');
    }


    public function store(Inv_uomsRequest $request)
    {
        try {

            $com_code = auth()->user()->com_code;
            //check if not exsits
            $checkExists = Inv_ums::where(['name' => $request->name, 'com_code' => $com_code])->first();
            if ($checkExists == null) {
                $data['name'] = $request->name;
                $data['is_master'] = $request->is_master;
                $data['active'] = $request->active;
                $data['created_at'] = date("Y-m-d H:i:s");
                $data['updated_at'] = null;
                $data['added_by'] = auth()->user()->name;
                $data['com_code'] = $com_code;
                $data['date'] = date("Y-m-d");
                Inv_ums::create($data);
                return redirect()->route('admin.uoms.index')->with(['success' => 'لقد تم اضافة البيانات بنجاح']);
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

    public function edit($id)
    {
        $com_code = auth()->user()->com_code;
        $data = Inv_ums::select()->find($id);
        
        $suppliers_order_details = get_counter(new Suppliers_with_orders_detailsModel(), array('com_code'=>$com_code, 'uom_id'=>$data->id));
        $sales_invoice_details = get_counter(new Sales_invoices_details(), array('com_code'=>$com_code, 'uom_id'=>$data->id));

        $total_counter_used = $suppliers_order_details +  $sales_invoice_details ; 

        return view('admin.inv_uoms.edit', ['data' => $data , 'total_counter_used' => $total_counter_used]);
    }

    public function update($id, InvUomUpdateRequest $request)
    {
        try {

            $com_code = auth()->user()->com_code;
            $data = Inv_ums::select()->find($id);

            if (empty($data)) {
                return redirect()->route('admin.uoms.index')->with(['error' => 'غير قادر على الوصول للبيانات المطلوبة ']);
            };

            $checkExists = Inv_ums::where(['name' => $request->name, 'com_code' => $com_code])->where('id', '!=', $id)->first();
            if ($checkExists != null) {
                return redirect()->back()->with(['error' => 'عفوا اسم الخزنة مسجل من قبل '])->withInput();
            }


            if($request->has('is_master')){
                if($request->is_master == ""){
                    return redirect()->back()->with(['error' => 'عفوا من فضلك اختر نوع الوحدة   '])->withInput();
                }
                $suppliers_order_details = get_counter(new Suppliers_with_orders_detailsModel(), array('com_code' => $com_code, 'uom_id' => $data->id));
                $sales_invoice_details = get_counter(new Sales_invoices_details(), array('com_code' => $com_code, 'uom_id' => $data->id));
                $total_counter_used = $suppliers_order_details +  $sales_invoice_details;
                if ($total_counter_used == 0) {
                    $data_to_update['is_master'] = $request->is_master;
                }
            }

            $data_to_update['name'] = $request->name;
            
            $data_to_update['active'] = $request->active;
            $data_to_update['updated_by'] = auth()->user()->name;
            $data_to_update['updated_at'] = date("Y-m-d H:i:s");

            Inv_ums::where(['id' => $id, 'com_code' => $com_code])->update($data_to_update);
            return redirect()->route('admin.uoms.index')->with(['success' => 'لقد تم تحديث بيانات بنجاح']);
        } catch (\Exception $ex) {
            return redirect()->back()->with(['error' => 'عفوا حصل خطأ' . $ex->getMessage()])->withInput();
        }
    }

    public function delete($id)
    {
        
        try {
        
            $treasures_delivery = Inv_ums::find($id);
            
            if (!empty($treasures_delivery)) {
                $flag = $treasures_delivery->delete();
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
        if ($request->ajax()) {
            $search_by_text = $request->search_by_text;
            $is_master_search = $request->is_master_search;


            if($search_by_text ==""){

                $field1 = 'id';
                $operator1 = ">";
                $value1 = 0;

            
            }else{
                $field1 = 'name';
                $operator1 = "LIKE";
                $value1 = "%{$search_by_text}%";
            }
            if($is_master_search =="all"){

                $field2 = 'id';
                $operator2 = ">";
                $value2 = 0;

            
            }else{
                $field2 = 'is_master';
                $operator2 = "=";
                $value2 = $is_master_search;
            }
            $data = Inv_ums::where($field1, $operator1, $value1 )->where($field2, $operator2, $value2)->orderBy('id', 'DESC')->paginate(PAGINATEION_COUNT);
            if (!empty($data)) {
                foreach ($data as $info) {
                    $info->added_by_admin = Admin::where('id', $info->added_by)->value('name');
                    if ($info->updated_by > 0 and $info->updated_by != null) {
                        $info->updated_by_admin = Admin::where('id', $info->updated_by)->value('name');
                    }
                }
            };
            return view('admin.inv_uoms.ajax_search', ['data' => $data]);
        }
    }
}
