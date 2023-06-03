<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admin_shifts extends Model
{
    use HasFactory;
    protected $table = 'admins_shifts';
    protected $fillable = [
        'id','admin_id', 'treasures_id', 'treasures_balnce_in_shift_start', 'start_date', 'end_date', 'is_finished', 
        'is_delivered_and_reviwed', 'delivered_to_admin_id', 'delivered_to_admin_shift_id', 'delivered_to_treasuries_id',
         'money_should_deviled', 'what_realy_delivered', 'money_state', 'money_state_value', 'receive_type', 'review_receive_date',
          'treasuries_transaction_id', 'added_by', 'created_at', 'notes', 'com_code' , 'updated_by' , 'updated_at','date'
    ];
}
