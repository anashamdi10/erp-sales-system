<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountModel extends Model
{
    use HasFactory;
    protected $table = "accounts";
    protected $fillable = ['id','name','account_type','parent_account_number','account_number',
                'start_balance','current_blance','other_table_FK','notes','added_by','updated_by',
                'created_at', 'updated_at','is_archived','com_code','date', 'is_parent','start_balance_status' ];

}
