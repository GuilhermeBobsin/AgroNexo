<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class AtualizacaoOperacional extends Notification
{
    use Queueable;

    public function __construct(
        private readonly string $titulo,
        private readonly string $mensagem,
        private readonly string $url,
        private readonly string $icone = 'bell'
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'titulo' => $this->titulo,
            'mensagem' => $this->mensagem,
            'url' => $this->url,
            'icone' => $this->icone,
        ];
    }
}
