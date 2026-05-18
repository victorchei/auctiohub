<?php

namespace App\Notifications;

use App\Models\Lot;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OutbidNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Lot $lot, public float $newAmount) {}

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("Вашу ставку перебили: {$this->lot->title}")
            ->greeting("Привіт, {$notifiable->name}!")
            ->line("Хтось зробив вищу ставку на лот «{$this->lot->title}».")
            ->line("Нова ціна: ".number_format($this->newAmount, 2, ',', ' ').' ₴')
            ->action('Зробити нову ставку', route('lots.show', $this->lot))
            ->line('Поспішайте — аукціон може скоро завершитися.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'outbid',
            'lot_id' => $this->lot->id,
            'lot_title' => $this->lot->title,
            'new_amount' => $this->newAmount,
            'url' => route('lots.show', $this->lot),
        ];
    }
}
