<?php

namespace App\Http\Controllers\Admin;

use Illuminate\View\View;

class LogsController extends BaseController
{
    public function index(): View
    {
        return view('Administrator.logs.index');
    }
}
