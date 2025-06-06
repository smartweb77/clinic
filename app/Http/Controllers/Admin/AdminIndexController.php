<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

class AdminIndexController extends BaseController
{
    public function index(Request $request)
    {
        return view('Administrator.index.index');
    }
}
