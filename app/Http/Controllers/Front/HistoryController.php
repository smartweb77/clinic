<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\History;

class HistoryController extends Controller
{
    public function index()
    {
        $history = History::getItemInfo(1, $this->lang);

        return view('client.history.index', compact('history'));
    }
}
