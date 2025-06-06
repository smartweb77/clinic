<?php

namespace App\Http\Controllers\Admin;

use DB;
use App\Models\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminIndexController extends BaseController 
{
    public function index(Request $request) 
    {    
        return view('Administrator.index.index');
    }
}
