<?php

namespace App\Http\Controllers\Front;

use Illuminate\View\View;
use App\Http\Controllers\Controller;
use App\Models\News;

class NewsController extends Controller
{
    public function index(): View
    {
        $newses = News::allItems($this->lang, $status_on = true);
        $sliders = News::allItems($this->lang, $status_on = true, $slider = true);

        return view('client.news.index', compact('newses', 'sliders'));
    }

    public function in($id): View
    {
        $news = News::getItemInfo($id, $this->lang);
        $other_newses = News::otherNews($this->lang, $no_id_contains = $id);

        return view('client.news.in', compact('news', 'other_newses'));
    }
}
