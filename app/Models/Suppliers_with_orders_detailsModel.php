<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Suppliers_with_orders_detailsModel extends Model
{
    use HasFactory;
    protected $table = "suppliers_with_orders_details";
    protected $fillable = ['id','suppliers_with_orders_auto_serial', 'order_type', 'com_code', 'dliverd_quantity',
                            'uom_id', 'isparentuom', 'unit_price', 'total_price', 'order_date', 'added_by',
                            'created_at','updated_by','updated_at', 'item_code', 'batch_auto_serial', 'production_date' ,
                            'expire_date', 'item_card_type' ];

}
