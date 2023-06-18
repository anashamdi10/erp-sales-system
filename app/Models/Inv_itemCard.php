<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inv_itemCard extends Model
{
    use HasFactory;
    protected $table = "inv_itemcard";
    protected $fillable = [
        'id','item_code', 'name', 'item_type', 'inv_itemcard_categories_id', 'parent_inv_itemcard_id',
         'does_has_retailunit', 'retail_uom_id', 'uom_id', 'retail_uom_quantityToParent',"added_by",
         'update_by', 'created_at', 'updated_at','active','date','com_code','barcode', 
         'price','nos_gomla_price','gomla_price','price_retail','nos_gomla_price_retail','gomla_price_retail','cost_price', 
         'cost_price_retail','has_fixed_price','quantity','quantity_retail','quantity_all_retail','photo','all_quantity' 
    ];
}
