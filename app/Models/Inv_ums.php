<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inv_ums extends Model
{
    use HasFactory;
    protected $table = "inv_uoms";
    protected $fillable = [
        'id', 'name', 'created_at', 'updated_at', 'added_by',
        'updated_by', 'com_code', 'date', 'active',"is_master",
    ];
}
