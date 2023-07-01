<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Delegate extends Model
{
    use HasFactory;
    protected $table = 'delegates';
    protected $fillable = [
        'delegate_code', 'name', 'account_number', 'start_balance', 'start_balance_status', 'current_blance', 'notes',
         'added_by', 'updated_by', 'created_at', 'updated_at', 'active', 'com_code', 'date', 'city_id', 'address', 
         'percent_type', 'percent_collect_commiission', 'percent_sales_commission_kataei', 'percent_sales_commission_nosjomla',
          'percent_sales_commission_jomla'
    ];
}
