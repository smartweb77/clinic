<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminIndexController extends BaseController
{
    public function index(Request $request): View
    {
        return view('Administrator.index.index');
    }
}
