<?php

namespace App\Http\Controllers\Front;

use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Http\Controllers\Controller;
use App\Models\Message;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index(): View
    {
        return view('client.contact.index');
    }

    public function send(Request $request): RedirectResponse
    {
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
            'date' => Carbon::now()->toDateTimeString(),
        ]);

        if ($store) {
            return redirect()->back()->with('status', 1);
        }
    }
}
