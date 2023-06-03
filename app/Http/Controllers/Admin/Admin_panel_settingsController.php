<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin_setting;
use App\Models\Admin;
use App\Models\AccountModel;
use App\Http\Requests\Admin_panel_settings_Request;
use Illuminate\Http\Request;

class Admin_panel_settingsController extends Controller
{
    public function index(){
        $data = Admin_setting::where('com_code',auth()->user()->com_code)->first();

         

        if(!empty($data)){
            if($data['updated_by']>0 and $data['updated_by']!=null){
                $data['updated_by_admin']= Admin::where('id',$data['updated_by'])->value('name');
                $data['customer_parent_account_name']= AccountModel::where('account_number',$data['customer_parent_account_number'])->value('name');
                $data['suppliers_parent_account_name']= AccountModel::where('account_number',$data['suppliers_parent_account_number'])->value('name');

            }
        };

        return view('admin.admin_panel_settings.index', ['data'=>$data]);
    }

    public function edit(){
        $com_code = auth()->user()->com_code ;
        $data = Admin_setting::where('com_code',$com_code)->first();
        $parent_accounts = get_cols_where(new AccountModel(), array("account_number", "name"), array("is_parent" => 1, "com_code" => $com_code), 'id', 'ASC');
        return view('admin.admin_panel_settings.edit', ['data'=>$data, 'parent_accounts'=>$parent_accounts]);

    }

    public function update(Admin_panel_settings_Request $request){
        try {
            $admin_panel_setting = Admin_setting::where('com_code',auth()->user()->com_code)->first();
            $admin_panel_setting->system_name = $request->system_name;
            $admin_panel_setting->address = $request->address;
            $admin_panel_setting->phone = $request->phone;
            $admin_panel_setting->customer_parent_account_number = $request->customer_parent_account_number;
            $admin_panel_setting->suppliers_parent_account_number = $request->suppliers_parent_account_number;
            $admin_panel_setting->general_alert = $request->general_alert;
            $admin_panel_setting->updated_by = auth()->user()->name;
            $admin_panel_setting->updated_at =date("Y-m-d H:i:s");
            $oldphotoPath = $admin_panel_setting->photo;
            if($request->has('photo')){
                $request->validate([
                    'photo'=>'required|mimes:png,jpg,jpeg|max:2000',
                    
                ]);

               

                $the_file_path = uploadImage('admin/uploads', $request->photo);
                $admin_panel_setting->photo = $the_file_path;
                if (file_exists('admin/uploads/' . $oldphotoPath) and !empty($oldphotoPath)) {
                    unlink('admin/uploads/' . $oldphotoPath);
                }
            }

            $admin_panel_setting->save();
            return redirect()->route('admin.adminPanelSettings.index')->with(['success'=>'تم تحديث بيانات بنجاح']);

            

        } catch (\Exception $ex) {
            return redirect()->route('admin.adminPanelSettings.index')->with(['error'=>'عفوا حصل خطأ'.$ex->getMessage()]);
        }
    }
}
