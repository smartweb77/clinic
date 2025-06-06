<?php

namespace App\Http\Controllers\Admin;

use Illuminate\View\View;

class NewsesController extends BaseController
{
    public function index(): View
    {
        return view('Administrator.newses.index');
    }
}
