<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuppliersModel extends Model
{
    use HasFactory;
    protected $table = "suppliers";
    protected $fillable = ['id','supplier_code','name','account_number','suppliers_categories_id',
                'start_balance','start_balance_status','current_blance','notes','added_by','updated_by',
                'created_at', 'updated_at','active','com_code','date','city_id','address' ];
}
