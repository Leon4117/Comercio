<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SupportController extends Controller
{
    /**
     * Redirect to chat with the administrator.
     */
    public function contactAdmin()
    {
        // Find the first admin user
        $admin = User::where('role', 'admin')->first();

        if (!$admin) {
            return redirect()->back()->with('error', 'No hay administradores disponibles para soporte en este momento.');
        }

        // Prevent admin from chatting with themselves via this route
        if (Auth::id() === $admin->id) {
            return redirect()->route('dashboard')->with('error', 'No puedes iniciar un chat de soporte contigo mismo.');
        }

        // Redirect to the chat start method in ChatController
        return redirect()->route('chat.start', $admin);
    }
}
