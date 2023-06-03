<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Inv_itemCard;
use App\Models\Inv_itemcard_category;
use App\Models\Inv_ums;
use App\Models\Admin;
use App\Http\Requests\ItemcardRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class Inv_itemcardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $com_code = auth()->user()->com_code;
        $data = get_cols_where_p(new Inv_itemCard(), array('*'), array('com_code' => $com_code), 'id', "DESC", PAGINATEION_COUNT);

        if (!empty($data)) {
            foreach ($data as $info) {
                $info->added_by_admin = get_field_value(new Admin(), 'name', array('id' => $info->added_by));
                $info->inv_itemcard_categories_name = get_field_value(new Inv_itemcard_category(), 'name', array('id' => $info->inv_itemcard_categories_id));
                $info->parent_item_name = get_field_value(new Inv_itemCard(), 'name', array('id' => $info->parent_inv_itemcard_id));
                $info->uom_name = get_field_value(new Inv_ums(), 'name', array('id' => $info->uom_id));
                $info->retail_uom_name = get_field_value(new Inv_ums(), 'name', array('id' => $info->retail_uom_id));


                if ($info->updated_by > 0 and $info->updated_by != null) {
                    $info->updated_by_admin =  get_field_value(new Admin(), 'name', array('id' => $info->added_by));
                }
            }
        };

        $inv_itemcard_categories = get_cols_where(
            new Inv_itemcard_category(),
            array('id', 'name'),
            array('com_code' => $com_code, 'active' => 1),
            'id',
            'DESC'
        );

        return view('admin.inv_itemcard.index', ['data' => $data, 'inv_itemcard_categories' => $inv_itemcard_categories]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $com_code = auth()->user()->com_code;
        $inv_itemcard_categories = get_cols_where(
            new Inv_itemcard_category(),
            array('id', 'name'),
            array('com_code' => $com_code, 'active' => 1),
            'id',
            'DESC'
        );
        $Inv_ums_parent = get_cols_where(
            new Inv_ums(),
            array('id', 'name'),
            array('com_code' => $com_code, 'active' => 1, 'is_master' => 1),
            'id',
            'DESC'
        );
        $Inv_ums_child = get_cols_where(
            new Inv_ums(),
            array('id', 'name'),
            array('com_code' => $com_code, 'active' => 1, 'is_master' => 0),
            'id',
            'DESC'
        );
        $parent_inv_itemcard_id = get_cols_where(
            new Inv_itemCard(),
            array('id', 'name'),
            array('com_code' => $com_code, 'active' => 1),
            'id',
            'DESC'
        );


        return view(
            'admin.inv_itemcard.create',
            [
                'inv_itemcard_categories' => $inv_itemcard_categories, 'Inv_ums_parent' => $Inv_ums_parent,
                'Inv_ums_child' => $Inv_ums_child, 'parent_inv_itemcard_id' => $parent_inv_itemcard_id
            ]
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ItemcardRequest $request)
    {

        try {
            $com_code = auth()->user()->com_code;

            // set item code  for itemcard
            $row  = get_cols_where_row_orderby(new Inv_itemCard, array("item_code"), array("com_code" => $com_code), 'id', 'DESC');
            if (!empty($row)) {
                $data_insert['item_code'] = $row['item_code'] + 1;
            } else {
                $data_insert['item_code'] = 1;
            }

            //check if not exsits for barcode
            if ($request->barcode != "") {
                $checkExists_barcode = Inv_itemCard::where(['barcode' => $request->barcode, 'com_code' => $com_code])->first();
                if (!empty($checkExists_barcode)) {
                    return redirect()->back()->with(['error' => 'عغوا باركود الصنف مسجل من قبل'])->withInput();
                } else {
                    $data_insert['barcode'] = $request->barcode;
                }
            } else {
                $data_insert['barcode'] = 'item' . $data_insert['item_code'];
            };

            //check if not exsits for name
            $checkExists_name = Inv_itemCard::where(['name' => $request->name, 'com_code' => $com_code])->first();
            if (!empty($checkExists_name)) {
                return redirect()->back()->with(['error' => 'عغوا اسم الصنف مسجل من قبل'])->withInput();
            }


            $data_insert['name'] = $request->name;
            $data_insert['item_type'] = $request->item_type;
            $data_insert['inv_itemcard_categories_id'] = $request->inv_itemcard_categories_id;
            $data_insert['uom_id'] = $request->uom_id;
            $data_insert['price'] = $request->price;
            $data_insert['nos_gomla_price'] = $request->nos_gomla_price;
            $data_insert['gomla_price'] = $request->gomla_price;
            $data_insert['cost_price'] = $request->cost_price;
            $data_insert['retail_uom_id'] = $request->retail_uom_id;
            $data_insert['does_has_retailunit'] = $request->does_has_retailunit;
            $data_insert['item_card_data'] = $request->item_card_data;
            $data_insert['parent_inv_itemcard_id'] = $request->parent_inv_itemcard_id;
            if ($data_insert['parent_inv_itemcard_id'] == "") {
                $data_insert['parent_inv_itemcard_id'] = 0;
            }

            if ($data_insert['does_has_retailunit'] == 1) {
                $data_insert['retail_uom_quantityToParent'] = $request->retail_uom_quantityToParent;
                $data_insert['price_retail'] = $request->price_retail;
                $data_insert['nos_gomla_price_retail'] = $request->nos_gomla_price_retail;
                $data_insert['gomla_price_retail'] = $request->gomla_price_retail;
                $data_insert['cost_price_retail'] = $request->cost_price_retail;
            }



            if ($request->has('Item_image')) {
                $request->validate([
                    'Item_image' => 'required|mimes:png,jpg,jpeg|max:2000',

                ]);



                $the_file_path = uploadImage('admin/uploads', $request->Item_image);
                $data_insert['photo'] = $the_file_path;
            }
            $data_insert['has_fixed_price'] = $request->has_fixed_price;
            $data_insert['active'] = $request->active;
            $data_insert['created_at'] = date('Y:m:d H:i:s');
            $data_insert['date'] = date('Y:m:d ');
            $data_insert['updated_at'] = null;
            $data_insert['added_by'] = auth()->user()->name;
            $data_insert['com_code'] = $com_code;

            Inv_itemCard::create($data_insert);
            return redirect()->route('inv_itemcard.index')->with(['success' => 'لقد تم إضافة بيانات بنجاح']);
        } catch (\Exception $ex) {
            return redirect()->back()->with(['error' => 'عفوا حصل خطأ' . $ex->getMessage()])->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $data = get_cols_where_row(new Inv_itemCard(), array('*'), array('id' => $id));
        $com_code = auth()->user()->com_code;

        $data['added_by_admin'] = get_field_value(new Admin(), 'name', array('id' => $data['added_by']));
        $data['inv_itemcard_categories_name'] = get_field_value(new Inv_itemcard_category(), 'name', array('id' => $data['inv_itemcard_categories_id']));
        $data['parent_item_name'] = get_field_value(new Inv_itemCard(), 'name', array('id' => $data['parent_inv_itemcard_id']));
        $data['uom_name'] = get_field_value(new Inv_ums(), 'name', array('id' => $data['uom_id']));
        $data['retail_uom_name'] = get_field_value(new Inv_ums(), 'name', array('id' => $data['retail_uom_id']));


        if ($data['updated_by'] > 0 and $data['updated_by'] != null) {
            $data['updated_by_admin'] =  get_field_value(new Admin(), 'name', array('id' => $data['added_by']));
        }



        return view('admin.inv_itemcard.show', ['data' => $data]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $com_code = auth()->user()->com_code;
        $inv_itemcard_categories = get_cols_where(
            new Inv_itemcard_category(),
            array('id', 'name'),
            array('com_code' => $com_code, 'active' => 1),
            'id',
            'DESC'
        );
        $Inv_ums_parent = get_cols_where(
            new Inv_ums(),
            array('id', 'name'),
            array('com_code' => $com_code, 'active' => 1, 'is_master' => 1),
            'id',
            'DESC'
        );
        $Inv_ums_child = get_cols_where(
            new Inv_ums(),
            array('id', 'name'),
            array('com_code' => $com_code, 'active' => 1, 'is_master' => 0),
            'id',
            'DESC'
        );
        $parent_inv_itemcard_id = get_cols_where(
            new Inv_itemCard(),
            array('id', 'name'),
            array('com_code' => $com_code, 'active' => 1),
            'id',
            'DESC'
        );


        $data = Inv_itemCard::select()->find($id);
        return view(
            'admin.inv_itemcard.edit',
            [
                'data' => $data, 'inv_itemcard_categories' => $inv_itemcard_categories, 'Inv_ums_parent' => $Inv_ums_parent,
                'Inv_ums_child' => $Inv_ums_child, 'parent_inv_itemcard_id' => $parent_inv_itemcard_id
            ]
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ItemcardRequest $request, $id)
    {

        try {

            $com_code = auth()->user()->com_code;
            $data = Inv_itemCard::select()->find($id);

            if (empty($data)) {
                return redirect()->route('inv_itemcard.index')->with(['error' => 'غير قادر على الوصول للبيانات المطلوبة ']);
            };

            //check if not exsits for barcode
            if ($request->barcode != "") {
                $checkExists_name = Inv_itemCard::where(['barcode' => $request->barcode, 'com_code' => $com_code])->where("id", "!=", $id)->first();
                if (!empty($checkExists_barcode)) {
                    dd($request->barcode);
                    return redirect()->back()->with(['error' => 'عغوا باركود الصنف مسجل من قبل'])->withInput();
                } else {
                    $data_to_update['barcode'] = $request->barcode;
                }
            }



            //check if not exsits for name
            $checkExists_name = Inv_itemCard::where(['name' => $request->name, 'com_code' => $com_code])->where("id", "!=", $id)->first();
            if (!empty($checkExists_name)) {
                return redirect()->back()->with(['error' => 'عغوا اسم الصنف مسجل من قبل'])->withInput();
            }



            $data_to_update['name'] = $request->name;
            $data_to_update['item_type'] = $request->item_type;
            $data_to_update['inv_itemcard_categories_id'] = $request->inv_itemcard_categories_id;
            $data_to_update['uom_id'] = $request->uom_id;
            $data_to_update['price'] = $request->price;
            $data_to_update['nos_gomla_price'] = $request->nos_gomla_price;
            $data_to_update['gomla_price'] = $request->gomla_price;
            $data_to_update['cost_price'] = $request->cost_price;
            $data_to_update['retail_uom_id'] = $request->retail_uom_id;
            $data_to_update['does_has_retailunit'] = $request->does_has_retailunit;

            $data_to_update['parent_inv_itemcard_id'] = $request->parent_inv_itemcard_id;
            if ($data_to_update['parent_inv_itemcard_id'] == "") {
                $data_to_update['parent_inv_itemcard_id'] = 0;
            }

            if ($data_to_update['does_has_retailunit'] == 1) {
                $data_to_update['retail_uom_quantityToParent'] = $request->retail_uom_quantityToParent;
                $data_to_update['price_retail'] = $request->price_retail;
                $data_to_update['nos_gomla_price_retail'] = $request->nos_gomla_price_retail;
                $data_to_update['gomla_price_retail'] = $request->gomla_price_retail;
                $data_to_update['cost_price_retail'] = $request->cost_price_retail;
            }



            if ($request->has('photo')) {
                $request->validate([
                    'photo' => 'required|mimes:png,jpg,jpeg|max:2000',
                ]);
                $oldphotoPath = $data['photo'];
                $the_file_path = uploadImage('admin/uploads', $request->photo);


                if (file_exists('admin/uploads/' . $oldphotoPath) and !empty($oldphotoPath)) {
                    unlink('admin/uploads/' . $oldphotoPath);
                }
                $data_to_update['photo'] = $the_file_path;
            }
            $data_to_update['has_fixed_price'] = $request->has_fixed_price;
            $data_to_update['active'] = $request->active;
            $data_to_update['date'] = date('Y:m:d ');
            $data_to_update['updated_at'] = date('Y:m:d H:i:s');
            $data_to_update['update_by'] = auth()->user()->name;
            $data_to_update['com_code'] = $com_code;
            update(new Inv_itemCard(), $data_to_update, array('id' => $id, 'com_code' => $com_code));


            return redirect()->route('inv_itemcard.index')->with(['success' => 'لقد تم تحديث بيانات بنجاح']);
        } catch (\Exception $ex) {
            return redirect()->back()->with(['error' => 'عفوا حصل خطأ' . $ex->getMessage()])->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
    public function delete($id)
    {

        try {
            $com_code = auth()->user()->com_code;
            $item_row = get_cols_where_row(new Inv_itemCard(), array("id"), array("id" => $id, 'com_code' => $com_code));
            if (!empty($item_row)) {
                $flag = delete(new Inv_itemCard(), array("id" => $id, 'com_code' => $com_code));
                if ($flag) {
                    return redirect()->back()
                        ->with(['success' => '   تم حذف البيانات بنجاح']);
                } else {
                    return redirect()->back()
                        ->with(['error' => 'عفوا حدث خطأ ما']);
                }
            } else {
                return redirect()->back()
                    ->with(['error' => 'عفوا غير قادر الي الوصول للبيانات المطلوبة']);
            }
        } catch (\Exception $ex) {
            return redirect()->back()
                ->with(['error' => 'عفوا حدث خطأ ما' . $ex->getMessage()]);
        }
    }

    public function ajax_search(Request $request)
    {



        if ($request->ajax()) {
            $search_by_text = $request->search_by_text;
            $item_type = $request->item_type;
            $inv_itemcard_categories_id = $request->inv_itemcard_categories_id;
            $searchbyradio = $request->searchbyradio;


            if ($search_by_text == "all") {

                $field1 = 'id';
                $operator1 = ">";
                $value1 = 0;
            } else {
                $field1 = 'name';
                $operator1 = "LIKE";
                $value1 = "%{$search_by_text}%";
            }


            if ($item_type == 'all') {
                $field1 = "id";
                $operator1 = ">";
                $value1 = 0;
            } else {
                $field1 = "item_type";
                $operator1 = "=";
                $value1 = $item_type;
            }
            if ($inv_itemcard_categories_id == 'all') {
                $field2 = "id";
                $operator2 = ">";
                $value2 = 0;
            } else {
                $field2 = "inv_itemcard_categories_id";
                $operator2 = "=";
                $value2 = $inv_itemcard_categories_id;
            }



            if ($search_by_text != "") {

                if ($searchbyradio == 'barcode') {

                    $field3 = 'barcode';
                    $operator3 = "=";
                    $value3 = $search_by_text;
                } elseif ($searchbyradio == 'item_code') {
                    $field3 = 'item_code';
                    $operator3 = "=";
                    $value3 = $search_by_text;
                } else {

                    $field3 = 'name';
                    $operator3 = "like";
                    $value3 = "%{$search_by_text}%";
                }
            } else {

                $field3 = 'id';
                $operator3 = ">";
                $value3 = 0;
            }






            $data = Inv_itemCard::where($field1, $operator1, $value1)->where($field2, $operator2, $value2)
                ->where($field3, $operator3, $value3)
                ->orderBy('id', 'DESC')->paginate(PAGINATEION_COUNT);





            if (!empty($data)) {
                foreach ($data as $info) {
                    $info->added_by_admin = get_field_value(new Admin(), 'name', array('id' => $info->added_by));
                    $info->inv_itemcard_categories_name = get_field_value(new Inv_itemcard_category(), 'name', array('id' => $info->inv_itemcard_categories_id));
                    $info->parent_item_name = get_field_value(new Inv_itemCard(), 'name', array('id' => $info->parent_inv_itemcard_id));
                    $info->uom_name = get_field_value(new Inv_ums(), 'name', array('id' => $info->uom_id));
                    $info->retail_uom_name = get_field_value(new Inv_ums(), 'name', array('id' => $info->retail_uom_id));


                    if ($info->updated_by > 0 and $info->updated_by != null) {
                        $info->updated_by_admin =  get_field_value(new Admin(), 'name', array('id' => $info->added_by));
                    }
                }
            };
            return view('admin.inv_itemcard.ajax_search', ['data' => $data]);
        }
    }
}
