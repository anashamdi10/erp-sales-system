<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Inv_itemCard;
use App\Models\Inv_itemcard_batches;
use App\Models\Inv_itemcard_category;
use App\Models\Inv_ums;
use App\Models\Store;
use Illuminate\Http\Request;

class ItemCardBalanceController extends Controller
{
    public function index()
    {
        $com_code = auth()->user()->com_code;
        $allitemscarddata = get_cols_where_p(new Inv_itemCard(), array('*'), array('com_code' => $com_code), 'all_quantity', "DESC", PAGINATEION_COUNT);

        if (!empty($allitemscarddata)) {
            foreach ($allitemscarddata as $info) {
                $info->inv_itemcard_categories_name = get_field_value(new Inv_itemcard_category(), 'name', array('id' => $info->inv_itemcard_categories_id));
                $info->uom_name = get_field_value(new Inv_ums(), 'name', array('com_code'=>$com_code,'id'=>$info->uom_id));
                if($info->does_has_retailunit == 1){
                    $info->retail_uom_name = get_field_value(new Inv_ums(), 'name', array('id' => $info->retail_uom_id));
                    
                }

                $info->allitembatches = get_cols_where_order2(new Inv_itemcard_batches(),array('*'),
                    array('com_code'=>$com_code,'item_code'=>$info->item_code), 'store_id','ASC','quantity','DESC');
                
                    if(!empty($info->allitembatches)){
                        
                        foreach($info->allitembatches as $det){
                            
                            $det->store_name = get_field_value(new Store(),'name',array('com_code'=>$com_code,'id'=>$det->store_id) );
                            if ($info->does_has_retailunit == 1) {
                                $det->retail_quantity = $det->quantity * $info->retail_uom_quantityToParent;
                                $det->retail_price = $det->unit_cost_price / $info->retail_uom_quantityToParent ;
                            }    
                        }
                    }

                    
            }
        };


        $item_card_items_search = get_cols_where(new Inv_itemCard(), array('item_code' , 'name'), array('com_code'=>$com_code));

        $stores_search = get_cols_where(new Store(), array('id','name'), array('com_code'=>$com_code));

        return view('admin.itemCardBalance.index', ['allitemscarddata' => $allitemscarddata,'item_card_items_search'=> $item_card_items_search,
                'stores_search'=> $stores_search]);
    }

    public function ajax_search(Request $request)
    {
        
        $com_code = auth()->user()->com_code;
        if ($request->ajax()) {
            $store_id_search = $request->store_id_search;
            $batch_search = $request->batch_search;
            $item_code_search = $request->item_code_search;
            $TypeBatches = $request->TypeBatches;
            $BatchQuantity = $request->BatchQuantity;
            $BatchQuantityStatus = $request->BatchQuantityStatus;
            

            if ($item_code_search == "all") {
                $field1 = 'id';
                $operator1 = ">";
                $value1 = 0;
            } else {
                $field1 = 'item_code';
                $operator1 = "=";
                $value1 = $item_code_search;
            }

            if ($store_id_search == 'all') {
                $field2 = "id";
                $operator2 = ">";
                $value2 = 0;
            } else {
                $field2 = "store_id";
                $operator2 = "=";
                $value2 = $store_id_search;
            }
            if ($batch_search == 'all') {
                $field3 = "id";
                $operator3 = ">";
                $value3 = 0;
            } else {
                if($batch_search == 1){
                    $field3 = "quantity";
                    $operator3 = ">";
                    $value3 = 0;
                }else{
                    $field3 = "quantity";
                    $operator3 = "=";
                    $value3 = 0;
                }
            }

            if($TypeBatches == 'all'){
                $field4 = "id";
                $operator4 = ">";
                $value4 = 0;
            }else{
                $field4 = "item_type";
                $operator4 = "=";
                $value4 = $TypeBatches;
            }


            
            if($BatchQuantityStatus == 'all'){
                $field5 = "id";
                $operator5 = ">";
                $value5 = 0;
            }else {
                if($BatchQuantityStatus == 1){
                    $field5 = "quantity";
                    $operator5 = ">=";
                    $value5 = $BatchQuantity;
                }elseif($BatchQuantityStatus == 2){
                    $field5 = "quantity";
                    $operator5 = "<=";
                    $value5 = $BatchQuantity;
                }else{
                    $field5 = "quantity";
                    $operator5 = "=";
                    $value5 = $BatchQuantity;
                }
            }

            
            $allitemscarddata = Inv_itemCard::select('*')->where($field1, $operator1, $value1)->where($field4, $operator4, $value4)->orderby('all_quantity','DESC' )->paginate(PAGINATEION_COUNT);
            
            
            if (!empty($allitemscarddata)) {
                foreach ($allitemscarddata as $info) {
                    $info->inv_itemcard_categories_name = get_field_value(new Inv_itemcard_category(), 'name', array('id' => $info->inv_itemcard_categories_id));
                    $info->uom_name = get_field_value(new Inv_ums(), 'name', array('com_code' => $com_code, 'id' => $info->uom_id));
                    if ($info->does_has_retailunit == 1) {
                        $info->retail_uom_name = get_field_value(new Inv_ums(), 'name', array('id' => $info->retail_uom_id));
                    }
                    $info->allitembatches = Inv_itemcard_batches::select("*")->where('item_code', $info->item_code)->where($field2, $operator2, $value2)
                    ->where($field3, $operator3, $value3)->where($field5, $operator5, $value5)->orderBy('store_id', 'ASC')->orderBy('quantity', 'DESC')->get();
                    $info->researchQuantity = Inv_itemcard_batches::select("*")->where('item_code', $info->item_code)->where($field2, $operator2, $value2)
                    ->where($field3, $operator3, $value3)->where($field5, $operator5, $value5)->sum('quantity');

                    

                    if (!empty($info->allitembatches)) {
                        foreach ($info->allitembatches as $det) {
                            $det->store_name = get_field_value(new Store(), 'name', array('com_code' => $com_code, 'id' => $det->store_id));
                            if ($info->does_has_retailunit == 1) {
                                $det->retail_quantity = $det->quantity * $info->retail_uom_quantityToParent;
                                $det->retail_price = $det->unit_cost_price / $info->retail_uom_quantityToParent;
                            }
                        }
                    }
                }
            };
        }            
            return view('admin.itemCardBalance.ajax_search', ['allitemscarddata' => $allitemscarddata]);
        }
    }

