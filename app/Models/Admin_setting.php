<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admin_setting extends Model
{
    use HasFactory;
    protected $table = "admin_settings";
    protected $fillable = [
        'id', 'system_name', 'photo', 'active', 'general_alert', 'address', 'phone', 'customer_parent_account_number', 
        'suppliers_parent_account_number', 'delegates_parent_account_number', 'employees_parent_account_number', 'added_by',
        'updated_by', 'com_code', 'created_at', 'updated_at' 
    ];

}
