<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\account_typeModel;
use App\Models\Admin;
use App\Http\Requests\Admin_panel_settings_Request;
use Illuminate\Http\Request;

class Account_typesController extends Controller
{
    public function index(){
        $data = get_cols(new account_typeModel(), array('*'), 'id', "ASC", PAGINATEION_COUNT);
       

        return view('admin.account_types.index', ['data'=>$data]);
    }

  
}
