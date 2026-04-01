<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use App\Models\Listing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    // Affiche la boîte de réception
    public function index()
    {
        // Récupère les conversations (regroupées par expéditeur)
        $messages = Message::where('receiver_id', Auth::id())
            ->with(['sender', 'listing'])
            ->latest()
            ->paginate(15);

        // Marque les messages non lus comme lus
        Message::where('receiver_id', Auth::id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return view('messages.index', compact('messages'));
    }

    // Conversation avec un utilisateur spécifique
    public function conversation(User $user)
    {
        $messages = Message::where(function ($q) use ($user) {
            $q->where('sender_id', Auth::id())->where('receiver_id', $user->id);
        })->orWhere(function ($q) use ($user) {
            $q->where('sender_id', $user->id)->where('receiver_id', Auth::id());
        })->with(['sender', 'listing'])->oldest()->get();

        // Marquer comme lus les messages de cet utilisateur
        Message::where('sender_id', $user->id)
            ->where('receiver_id', Auth::id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return view('messages.conversation', compact('messages', 'user'));
    }

    // Envoyer un message
    public function store(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'body'        => 'required|string|max:1000',
            'listing_id'  => 'nullable|exists:listings,id',
        ]);

        // On ne peut pas s'envoyer un message à soi-même
        if ($request->receiver_id == Auth::id()) {
            return back()->with('error', 'Vous ne pouvez pas vous envoyer un message.');
        }

        Message::create([
            'sender_id'   => Auth::id(),
            'receiver_id' => $request->receiver_id,
            'listing_id'  => $request->listing_id,
            'body'        => $request->body,
        ]);

        return back()->with('success', 'Message envoyé !');
    }

    // Nombre de messages non lus (pour la navbar)
    public static function unreadCount()
    {
        if (Auth::check()) {
            return Message::where('receiver_id', Auth::id())
                ->where('is_read', false)
                ->count();
        }
        return 0;
    }
}
