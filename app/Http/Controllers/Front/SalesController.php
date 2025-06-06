<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Sale;
use Cache;
use Illuminate\Http\Request;

class SalesController extends Controller
{
    public function index(Request $request)
    {
        $sales = Cache::has('sales') ? Cache::get('sales')[$this->lang] : Sale::allItems($this->lang, $status_on = true);

        return view('client.sales.index', compact('sales'));
    }
}
