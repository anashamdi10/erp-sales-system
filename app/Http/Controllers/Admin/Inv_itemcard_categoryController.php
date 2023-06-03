<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Inv_itemcard_category;
use App\Models\Admin;
use App\Http\Requests\Inv_itemcard_categoryRequest;
use Illuminate\Http\Request;

class Inv_itemcard_categoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Inv_itemcard_category::select()->orderby('id', 'DESC')->paginate(PAGINATEION_COUNT);
        if (!empty($data)) {
            foreach ($data as $info) {
                $info->added_by_admin = Admin::where('id', $info->added_by)->value('name');
                if ($info->updated_by > 0 and $info->updated_by != null) {
                    $info->updated_by_admin = Admin::where('id', $info->updated_by)->value('name');
                }
            }
        };

        return view('admin.inv_itemcard_categories.index', ['data' => $data]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.inv_itemcard_categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Inv_itemcard_categoryRequest $request)
    {
        try {

            $com_code = auth()->user()->com_code;
            //check if not exsits
            $checkExists = Inv_itemcard_category::where(['name' => $request->name, 'com_code' => $com_code])->first();
            if ($checkExists == null) {
                $data['name'] = $request->name;
                $data['active'] = $request->active;
                $data['created_at'] = date("Y-m-d H:i:s");
                $data['updated_at'] = null;
                $data['added_by'] = auth()->user()->name;
                $data['com_code'] = $com_code;
                $data['date'] = date("Y-m-d");
                Inv_itemcard_category::create($data);
                return redirect()->route('inv_itemcard_categories.index')->with(['success' => 'لقد تم اضافة البيانات بنجاح']);
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

    /**
     * Display the specified resource.
     */
    public function show( $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data = Inv_itemcard_category::select()->find($id);
        return view('admin.inv_itemcard_categories.edit', ['data' => $data]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Inv_itemcard_categoryRequest $request,$id)
    {
        try {

            $com_code = auth()->user()->com_code;
            $data = Inv_itemcard_category::select()->find($id);

            if (empty($data)) {
                return redirect()->route('inv_itemcard_categories.index')->with(['error' => 'غير قادر على الوصول للبيانات المطلوبة ']);
            };

            $checkExists = Inv_itemcard_category::where(['name' => $request->name, 'com_code' => $com_code])->where('id', '!=', $id)->first();
            if ($checkExists != null) {
                return redirect()->back()->with(['error' => 'عفوا اسم الخزنة مسجل من قبل '])->withInput();
            }



            $data_to_update['name'] = $request->name;
            $data_to_update['active'] = $request->active;
            $data_to_update['updated_by'] = auth()->user()->name;
            $data_to_update['updated_at'] = date("Y-m-d H:i:s");

            Inv_itemcard_category::where(['id' => $id, 'com_code' => $com_code])->update($data_to_update);
            return redirect()->route('inv_itemcard_categories.index')->with(['success' => 'لقد تم تحديث بيانات بنجاح']);
        } catch (\Exception $ex) {
            return redirect()->back()->with(['error' => 'عفوا حصل خطأ' . $ex->getMessage()])->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete( $id)
    {
        try {
            
            $treasures_delivery = Inv_itemcard_category::find($id);
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
}
