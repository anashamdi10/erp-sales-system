<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inv_itemcard_batches extends Model
{
    use HasFactory;
    protected $table = "inv_itemcard_batches";
    protected $fillable = [
        'id','store_id', 'item_code', 'inv_uoms_id', 'unit_cost_price', 'quantity', 'toatal_cost_price', 
        'production_date', 'expired_date', 'com_code', 'auto_serial', 'added_by', 'created_at', 'is_send_to_archived',
        'updated_at', 'updated_by'
    ];
}
