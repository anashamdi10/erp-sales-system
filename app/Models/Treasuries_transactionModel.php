<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Treasuries_transactionModel extends Model
{
    use HasFactory;
    protected $table = "treasuries_transaction";
    protected $fillable = [
        'treasures_id', 'mov_type','mov_date', 'the_foregin_key', 'account_number', 'is_account', 'is_approved',
        'admin_shifts_id', 'money_for_account', 'money', 'bayan', 'created_at', 'updated_at', 'updated_by',
        'com_code', 'added_by','shift_code','isal_number','auto_serial'
    ];

}
