<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Suppliers_orderModel extends Model
{   public $timestamps = false;
    use HasFactory;
    protected $table = "suppliers_with_orders";
    protected $fillable = [
        'id', 'order_type', 'auto_serial', 'Doc_No', 'order_date','discount_value','tax_percent','tax_value',
        'supplier_code', 'is_approved', 'com_code', 'notes','discount_type','discount_percent','total_befor_discount',
        'total_cost' , 'account_number' , 'money_for_account', 'pill_type', 'what_paid' , 'what_remain' , 'treasuries_tranaction_id',
        'supplieries_balance_befor' , 'supplieries_balance_after', 'added_by' , 'created_at' , 'updated_at','updated_by',
        'total_cost_items','store_id'
    ];
}
