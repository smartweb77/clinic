<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\News;
use App\Models\Service;
use App\Models\Slider;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function index(){
        $sliders = Slider::allItems($this->lang, $status_on = true);
        $services = Service::allItems($this->lang, $status_on = true, $main = true);
        $doctors = Doctor::allItems($this->lang, $status_on = true, $main = true);
        $newses = News::mainPage($this->lang);

        return view('client.index.index', compact('sliders', 'services', 'doctors', 'newses'));
    }
}
