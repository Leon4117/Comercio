<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $conversations = Conversation::where('user_one_id', $user->id)
            ->orWhere('user_two_id', $user->id)
            ->with(['userOne', 'userTwo', 'messages' => function($query) {
                $query->latest()->limit(1);
            }])
            ->get()
            ->sortByDesc(function($conversation) {
                return $conversation->messages->first() ? $conversation->messages->first()->created_at : $conversation->created_at;
            });

        return view('chat.index', compact('conversations'));
    }

    public function show(Conversation $conversation)
    {
        $user = Auth::user();

        // Verify participation
        if ($conversation->user_one_id !== $user->id && $conversation->user_two_id !== $user->id) {
            abort(403);
        }

        $conversation->load(['messages.sender', 'userOne', 'userTwo']);

        // Mark messages as read
        Message::where('conversation_id', $conversation->id)
            ->where('sender_id', '!=', $user->id)
            ->update(['is_read' => true]);

        return view('chat.show', compact('conversation'));
    }

    public function store(Request $request, Conversation $conversation)
    {
        $request->validate(['content' => 'required|string']);

        Message::create([
            'conversation_id' => $conversation->id,
            'sender_id' => Auth::id(),
            'content' => $request->content,
        ]);

        return redirect()->route('chat.show', $conversation);
    }

    public function start(User $recipient)
    {
        $user = Auth::user();

        // Check if conversation exists
        $conversation = Conversation::where(function($q) use ($user, $recipient) {
            $q->where('user_one_id', $user->id)->where('user_two_id', $recipient->id);
        })->orWhere(function($q) use ($user, $recipient) {
            $q->where('user_one_id', $recipient->id)->where('user_two_id', $user->id);
        })->first();

        if (!$conversation) {
            $conversation = Conversation::create([
                'user_one_id' => $user->id,
                'user_two_id' => $recipient->id,
            ]);
        }

        return redirect()->route('chat.show', $conversation);
    }
}
