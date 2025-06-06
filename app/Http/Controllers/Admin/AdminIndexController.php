<?php

namespace App\Http\Controllers\Admin;

use Illuminate\View\View;
use Illuminate\Http\Request;

class AdminIndexController extends BaseController
{
    public function index(Request $request): View
    {
        return view('Administrator.index.index');
    }
}
