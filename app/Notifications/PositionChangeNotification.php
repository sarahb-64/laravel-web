<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PositionChangeNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $keyword;
    protected $previousPosition;
    protected $newPosition;

    public function __construct($keyword, $previousPosition, $newPosition)
    {
        $this->keyword = $keyword;
        $this->previousPosition = $previousPosition;
        $this->newPosition = $newPosition;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $change = $this->newPosition - $this->previousPosition;
        $direction = $change > 0 ? 'subió' : 'bajó';
        $absoluteChange = abs($change);

        return (new MailMessage)
            ->subject('Cambio en Posición de Palabra Clave')
            ->line('La posición de la palabra clave "' . $this->keyword . '" ha ' . $direction)
            ->line('Cambio: ' . $absoluteChange . ' posiciones')
            ->line('Posición anterior: ' . $this->previousPosition)
            ->line('Nueva posición: ' . $this->newPosition);
    }
}