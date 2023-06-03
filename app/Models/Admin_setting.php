<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admin_setting extends Model
{
    use HasFactory;
    protected $table = "admin_settings";
    protected $fillable = [
        'id', 'system_name', 'photo', 'active', 'general_alert','address', 'phone', 'added_by','suppliers_parent_account_number',
         'updated_by', 'com_code','created_at','updated_at', 'customer_parent_account_number'
    ];

}
