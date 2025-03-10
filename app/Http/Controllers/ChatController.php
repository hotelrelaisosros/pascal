<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\User;
use Exception;
use Illuminate\Mail\Events\MessageSent;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ChatController extends Controller
{
    public function index()
    {
        $users = User::whereNotIn('id', [Auth::user()->id])->get();
        return view('chats.index', ['users' => $users ?? []]);
    }

    public function show(Request $request)
    {
        // try {

        $query = DB::select("
        SELECT c.*, 
               (SELECT u.name FROM users u WHERE u.id = c.sender_id) AS sender_name, 
               (SELECT u.name FROM users u WHERE u.id = c.receiver_id) AS receiver_name
        FROM chats c
        WHERE (c.sender_id = ? AND c.receiver_id = ?) OR (c.sender_id = ? AND c.receiver_id = ?)
        ORDER BY c.created_at ASC;
    ", [
            $request->input('sender_id'),
            $request->input('receiver_id'),
            $request->input('receiver_id'),
            $request->input('sender_id')
        ]);
        // ORDER BY c.created_at ASC;
        // 
        // Get the raw SQL of the query


        // For debugging, you might also want to see the bindings
        // $bindings = $chats->getBindings(); // This gives you the bindings used in the query

        // dd($sql);
        return response()->json([
            "success" => "true",
            "status" => 200,
            "data" => $query
        ]);
        // }
        //  catch (Exception $e) {
        //     return response()->json([
        //         "success" => "false",
        //         "status" => 404,
        //         "data" => $e->getMessage()
        //     ]);
        // }
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'receiver_id' => 'required',
            'sender_id' => 'required',
            'message' => 'required|message'
        ]);

        try {
            $chat = Chat::create([
                'receiver_id' => $validated["receiver_id"],
                'sender_id' => $validated["sender_id"],
                'message' => $validated["message"]
            ]);
            // broadcast(new MessageSent($chat));
            return response()->json([
                "success" => "true",
                "status" => 200,
                "message" => "message sent successfully",
            ]);
        } catch (Exception $e) {
            return response()->json([
                "success" => "false",
                "status" => 404,
                "message" => $e->getMessage()
            ]);
        }
    }
}
