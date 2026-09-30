<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Notifications\DatabaseNotification;

class NotificacaoController extends Controller
{
    public function abrir(DatabaseNotification $notification): RedirectResponse
    {
        abort_unless($notification->notifiable_id === auth()->id()
            && $notification->notifiable_type === auth()->user()::class, 404);

        $notification->markAsRead();
        $url = $notification->data['url'] ?? route('dashboard');

        return redirect(str_starts_with($url, '/') ? $url : route('dashboard'));
    }

    public function marcarTodasComoLidas(): RedirectResponse
    {
        auth()->user()->unreadNotifications()->update(['read_at' => now()]);

        return back()->with('success', 'Notificações marcadas como lidas.');
    }
}
