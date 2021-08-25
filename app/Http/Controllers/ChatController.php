<?php

namespace App\Http\Controllers;

use App\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function index()
    {
        return response()->json(['messages' => Message::all()]);
    }

    public function store(Request $request)
    {
        $data = $request->all();

        try {
            $data['user_id'] = Auth::user()->id;

            $message = \App\Message::create($data);

            broadcast(new \App\Events\SendMessage($message));
        } catch (\Exception $e) {
            \Log::error(get_class($this)."->store() Error: ".$e->getMessage() ." - ". $e->getTraceAsString());
        }
    }
}
