<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\History;
use Illuminate\View\View;

class HistoryController extends Controller
{
    public function index(): View
    {
        $history = History::getItemInfo(1, $this->lang);

        return view('client.history.index', compact('history'));
    }
}
