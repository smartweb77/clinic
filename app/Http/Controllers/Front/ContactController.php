<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Message;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ContactController extends Controller
{
    public function index(){
        return view('client.contact.index');
    }

    public function send(Request $request){
        $request->validate([
            'name' => 'required',
            'email' => 'required',
            'message' => 'required',
            'subject' => 'required',
        ]);

        $store = Message::create([
            'name' => $request->name,
            'email' => $request->email,
            'message' => $request->message,
            'subject' => $request->subject,
            'date' => Carbon::now()->toDateTimeString()
        ]);

        if($store){
            return redirect()->back()->with('status', 1);
        }
    }
}
