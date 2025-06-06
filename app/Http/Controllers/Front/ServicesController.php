<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;

class ServicesController extends Controller
{
    public function index(){
        $services = Service::allItems($this->lang, $status_on = true);

        return view('client.services.index', compact('services'));
    }

    public function in($id){
        $service = Service::getItemInfo($id, $this->lang);

        return view('client.services.in', compact('service'));
    }

    public function search(Request $request){
        if($request->keyword){
            $services = Service::allItems($this->lang, $status_on = true, $main = false, $searh = $request->keyword);
            $response = [];
            if($services->count()){
                foreach($services as $service){
                    $response[] = [
                        'name' => $service->title,
                        'icon' => $service->icon,
                        'desc' => $service->short_description,
                        'url' => route('service', $service->id)
                    ];
                }
                return response([
                    'status' => 1,
                    'services' => json_encode($response, JSON_UNESCAPED_UNICODE)
                ]);
            }
        }
        return response([
            'status' => 0
        ]);
    }
}
