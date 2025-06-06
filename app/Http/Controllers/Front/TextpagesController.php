<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Textpage;

class TextpagesController extends Controller
{
    public function in($id)
    {
        $textpage = Textpage::getItemInfo($id, $this->lang);

        if (! $textpage) {
            return redirect()->back();
        }

        return view('client.textpages.in', compact('textpage'));
    }
}
