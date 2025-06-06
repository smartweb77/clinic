<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use Illuminate\Http\Request;

class DoctorsController extends Controller
{
    public function index(){
        $doctors = Doctor::allItems($this->lang, $status_on = true);

        return view('client.doctors.index', compact('doctors'));
    }

    public function in($id){
        $doctor = Doctor::getItemInfo($id, $this->lang);
        
        return view('client.doctors.in', compact('doctor'));
    }

    public function search(Request $request){
        if($request->keyword){
            $doctors = Doctor::allItems($this->lang, $status_on = true, $main = false, $searh = $request->keyword);
            $response = [];
            if($doctors->count()){
                foreach($doctors as $doctor){
                    $response[] = [
                        'name' => $doctor->full_name,
                        'image' => $doctor->image,
                        'specialty' => $doctor->specialty,
                        'url' => route('doctor', $doctor->id)
                    ];
                }
                return response([
                    'status' => 1,
                    'doctors' => json_encode($response, JSON_UNESCAPED_UNICODE)
                ]);
            }
        }
        return response([
           'status' => 0
        ]);
    }
}
