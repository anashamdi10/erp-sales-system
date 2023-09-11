<?php

use App\Models\AccountModel;

use function PHPUnit\Framework\returnValue;

function uploadImage($folder, $image)
{
  $extension = strtolower($image->extension());
  $filename = time() . rand(100, 999) . '.' . $extension;
  $image->getClientOriginalName = $filename;
  $image->move($folder, $filename);


  return $filename;
}

/*get some cols by pagination table */
function get_cols_where_p($model, $columns_names = array(), $where = array(), $order_field,$order_type,$pagination_counter)
{
  $data = $model::select($columns_names)->where($where)->orderby($order_field, $order_type)->paginate($pagination_counter);
  return $data;
}
/*get some cols by pagination table 2*/
function get_cols_where2_p($model, $columns_names = array(), $where = array(),$where2field,$where2operator,$where2value, $order_field,$order_type,$pagination_counter)
{
  $data = $model::select($columns_names)->where($where)->where($where2field,$where2operator,$where2value)->
  orderby($order_field, $order_type)->paginate($pagination_counter);
  return $data;
}
/*get some cols  table */
function get_cols_where($model, $columns_names = array(), $where = array(), $order_field ='id',$order_type='DESC')
{
  $data = $model::select($columns_names)->where($where)->orderby($order_field, $order_type)->get();
  return $data;
}
/*get some cols  table order 2 */
function get_cols_where_order2($model, $columns_names = array(), $where = array(), $order_field ='id',$order_type='DESC', $order_field2 = 'id', $order_type2 = 'DESC')
{
  $data = $model::select($columns_names)->where($where)->orderby($order_field, $order_type)->orderby($order_field2, $order_type2)->get();
  return $data;
}
function get_cols($model, $columns_names = array(), $order_field,$order_type)
{
  $data = $model::select($columns_names)->orderby($order_field, $order_type)->get();
  return $data;
}

/*get some cols row table */
function get_cols_where_row($model, $columns_names = array(), $where = array())
{
  $data = $model::select($columns_names)->where($where)->first();
  return $data;
}
/*get some cols row table */
function get_cols_where2_row($model, $columns_names = array(), $where = array(),$where2 = "")
{
  $data = $model::select($columns_names)->where($where)->where($where2)->first();
  return $data;
}
/*get some cols row table  order by*/
function get_cols_where_row_orderby($model, $columns_names = array(), $where = array(),$order_field, $order_type)
{
  $data = $model::select($columns_names)->where($where)->orderby($order_field, $order_type)->first();
  return $data;
}

/*get some cols table */
function insert($model, $arrayToInsert=array(), $returnData = false)
{
  $flag = $model::create($arrayToInsert);
  if($returnData = true){

    $data = get_cols_where_row($model , array('*'), $arrayToInsert);
    return $data;
  }else{
    return $flag ;
  }
}
function get_field_value($model, $field_name , $where = array())
{
  $data = $model::where($where)->value($field_name);
  return $data;
}

function update($model,$data_to_update,$where = array()){
  $flag = $model::where($where)->update($data_to_update);
  return $flag ;
}
function delete($model=null,$where=array()){
  $flag= $model::where($where)->delete();
  return $flag;
}
function get_sum_where($model=null,$field_name,$where=array()){
  $sum=$model::where($where)->sum($field_name);
  return $sum;
}

function get_user_shift($Admin_shifts ,$Treasure,$Treasuries_transactionModel){
    $com_code = auth()->user()->com_code;
    $data = $Admin_shifts::select('shift_code', 'treasures_id')->where(['com_code'=> $com_code,
                            'is_finished'=>0 , 'admin_id'=>auth()->user()->id])->first();
    
    if(!empty($data)){
      $data['treasures_name'] = $Treasure::where(['com_code'=>$com_code,'id'=>$data['treasures_id']])->value('name');
      $data['current_blance'] = $Treasuries_transactionModel::where(['shift_code'=>$data['shift_code'],'com_code'=>$com_code])->sum('money');
      
    }                        
    return $data;
}
// function احتساب و تحديث رصيد الحساب المالي المورد 
function refresh_account_blance_suppliers($account_number= null , $AccountModel =null , $suppliersModel = null , $Treasuries_transactionModel,$suppliers_with_order , $returnFlage = false){
  $com_code = auth()->user()->com_code;
  // نجيب رصيد الافتتاحي للمورد 
  $AccountData =  $AccountModel::select('start_balance')->where(['com_code'=>$com_code,'account_number'=>$account_number ])->first();
  // صافي مجموع المشتريات والمبيعات للمورد

  $Net_in_supplier_order = $suppliers_with_order::where(['com_code'=>$com_code,'account_number'=>$account_number ])->sum('money_for_account');

  // صافي حركة النقدية بالخزنة على حساب المورد

  $Net_in_treasuries_transaction = $Treasuries_transactionModel::where(['com_code'=>$com_code,'account_number'=>$account_number ])->sum('money_for_account');


  // الرصيد النهائي للمورد 
  $the_final_balance = $AccountData['start_balance'] + $Net_in_supplier_order +  $Net_in_treasuries_transaction ; 

  $dataToUpdateAccount['current_blance'] =  $the_final_balance ;
            
              //  تحديث جدول الحسابات المالية 
    $AccountModel::where(['com_code'=>$com_code,'account_number'=>$account_number ])->update($dataToUpdateAccount);    
    
    $dataToUpdateSupplier['current_blance'] =  $the_final_balance ;
    $suppliersModel::where(['com_code'=>$com_code,'account_number'=>$account_number ])->update($dataToUpdateSupplier);       

  if($returnFlage){
    return $the_final_balance ;
  }

}
// function احتساب و تحديث رصيد الحساب المالي العميل 
function refresh_account_blance_customer($account_number= null , $AccountModel =null , $customerModel = null , $Treasuries_transactionModel,$SalesInvoicesModel , $returnFlage = false){
  $com_code = auth()->user()->com_code;
  // نجيب رصيد الافتتاحي للعميل 
  $AccountData =  $AccountModel::select('start_balance')->where(['com_code'=>$com_code,'account_number'=>$account_number ])->first();
  // صافي مجموع المبيعات المرتجعات للعميل

  $Net_sales_invoices_for_customer = $SalesInvoicesModel::where(['com_code'=>$com_code,'account_number'=>$account_number ])->sum('money_for_account');

  // صافي حركة النقدية بالخزنة على حساب العميل

  $Net_in_treasuries_transaction = $Treasuries_transactionModel::where(['com_code'=>$com_code,'account_number'=>$account_number ])->sum('money_for_account');


  // الرصيد النهائي للعميل 
  $the_final_balance = $AccountData['start_balance'] + $Net_sales_invoices_for_customer +  $Net_in_treasuries_transaction ; 

  $dataToUpdateAccount['current_blance'] =  $the_final_balance ;
              //  تحديث جدول الحسابات المالية 
  $AccountModel::where(['com_code'=>$com_code,'account_number'=>$account_number ])->update($dataToUpdateAccount);        
  $dataToUpdateSupplier['current_blance'] =  $the_final_balance ;
  $customerModel::where(['com_code'=>$com_code,'account_number'=>$account_number ])->update($dataToUpdateSupplier);       

  if($returnFlage){
    return $the_final_balance ;
  }

}
// function احتساب و تحديث رصيد الحساب المالي العميل 
function refresh_account_blance_general($account_number= null , $AccountModel =null  , $Treasuries_transactionModel , $returnFlage = false){
    $com_code = auth()->user()->com_code;
    // نجيب رصيد الافتتاحي للعميل 
    $AccountData =  $AccountModel::select('start_balance', 'account_type')->where(['com_code'=>$com_code,'account_number'=>$account_number ])->first();
    if($AccountData['account_type'] !=2 && $AccountData['account_type'] != 3 && $AccountData['account_type'] !=4 && $AccountData['account_type'] != 5 && $AccountData['account_type'] != 8){
    // صافي حركة النقدية بالخزنة على حساب العميل

      $Net_in_treasuries_transaction = $Treasuries_transactionModel::where(['com_code'=>$com_code,'account_number'=>$account_number ])->sum('money_for_account');


      // الرصيد النهائي للعميل 
      $the_final_balance = $AccountData['start_balance'] +  $Net_in_treasuries_transaction ; 

      $dataToUpdateAccount['current_blance'] =  $the_final_balance ;
                  //  تحديث جدول الحسابات المالية 
                  $dataToUpdateSupplier['current_blance'] =  $the_final_balance ;
                  $AccountModel::where(['com_code'=>$com_code,'account_number'=>$account_number ])->update($dataToUpdateAccount);        
      if($returnFlage){
        return $the_final_balance ;
      }
    }
}

function DoUpdateItemCard($item_card_model , $item_code, $Inv_itemcard_batches,$retail_uom_quantityToParent,$does_has_retailunit){
  
  $com_code = auth()->user()->com_code;
  
  $allQuantityBatches = get_sum_where( $Inv_itemcard_batches, 'quantity',array('com_code'=>$com_code ,
                                            'item_code' =>$item_code , 'is_send_to_archived'=>0) );
  $DataToUpdateQuantity['all_quantity'] = $allQuantityBatches;
  if($does_has_retailunit == 1){
      // all quantity is reatails كل الكمية بوجده التجزئة 
      // example 21 kilo
      
      $Quantity_All_retail = $allQuantityBatches * $retail_uom_quantityToParent;

      
      //  2kilo  21/10  => int 2 kilo 
      $parentQuantityUom = intval( $allQuantityBatches);
      $DataToUpdateQuantity['quantity'] =$parentQuantityUom; 
      
      
      // % models 21%10  1 
      
      
      $DataToUpdateQuantity['quantity_retail']  = fmod( $Quantity_All_retail,$retail_uom_quantityToParent);
      $DataToUpdateQuantity['quantity_all_retail'] =$Quantity_All_retail; 
      
  }else{
      $DataToUpdateQuantity['quantity'] = $allQuantityBatches ;
  }

  update($item_card_model, $DataToUpdateQuantity,array('com_code'=>$com_code , 'item_code' =>$item_code) ); 

}

function get_counter($model, $where = array())
{
  $counter = $model::where($where)->count();
  return $counter;
}


