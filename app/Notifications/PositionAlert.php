<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PositionAlert extends Notification
{
    use Queueable;

    protected $keyword;
    protected $newPosition;

    public function __construct($keyword, $newPosition)
    {
        $this->keyword = $keyword;
        $this->newPosition = $newPosition;
    }

    public function via($notifiable)
    {
        return ['mail'];  // You can also add other channels like 'database', etc.
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject("Significant Position Change for Keyword: {$this->keyword}")
                    ->line("The position for the keyword '{$this->keyword}' has changed significantly.")
                    ->line("New Position: {$this->newPosition}")
                    ->action('Check Results', url("https://www.google.com/search?q=" . urlencode($this->keyword)))
                    ->line('Thank you for using our application!');
    }

    public function toArray($notifiable)
    {
        return [
            'keyword' => $this->keyword,
            'position' => $this->newPosition,
        ];
    }
}
