<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Sale;
use Cache;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SalesController extends Controller
{
    public function index(Request $request): View
    {
        $sales = Cache::has('sales') ? Cache::get('sales')[$this->lang] : Sale::allItems($this->lang, $status_on = true);

        return view('client.sales.index', compact('sales'));
    }
}
