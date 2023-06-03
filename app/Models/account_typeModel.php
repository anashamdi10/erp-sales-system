<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class account_typeModel extends Model
{
    use HasFactory;
    protected $table = "account_types";
    protected $fillable = ['id','name','active','relatediternalaccounts' ];
}
