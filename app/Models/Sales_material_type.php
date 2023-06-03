<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sales_material_type extends Model
{
    use HasFactory;
    protected $table = "sales_material_types";
    protected $fillable = [
        'id', 'name', 'created_at', 'updated_at', 'added_by',
         'updated_by', 'com_code', 'date', 'active'
    ];
}
