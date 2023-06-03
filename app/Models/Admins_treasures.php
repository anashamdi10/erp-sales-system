<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admins_treasures extends Model
{
    use HasFactory;
    protected $table = "admins_treasures";
    protected $fillable = ['id', 'admin_id', 'treasures_id', 'active', 'added_by', 'created_at','date',
                                'updated_at', 'updated_by', 'com_code'];
}
