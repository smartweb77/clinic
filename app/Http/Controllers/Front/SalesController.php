<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller as Controller;

use Cache;
use App\Models\Sale;

class SalesController extends Controller
{
    public function index(Request $request)
    {
        $sales = Cache::has('sales') ? Cache::get('sales')[$this->lang] : Sale::allItems($this->lang, $status_on = true);
        
        return view('client.sales.index',compact('sales'));
    }    
}
