<?php

namespace App\Notifications;

use App\Models\Lot;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AuctionWonNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Lot $lot) {}

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("🏆 Ви виграли аукціон: {$this->lot->title}")
            ->greeting("Вітаємо, {$notifiable->name}!")
            ->line("Ви виграли аукціон лоту «{$this->lot->title}».")
            ->line("Підсумкова ціна: ".number_format($this->lot->current_price, 2, ',', ' ').' ₴')
            ->action('Переглянути лот', route('lots.show', $this->lot))
            ->line('Зв\'яжіться з продавцем для оформлення угоди.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'auction_won',
            'lot_id' => $this->lot->id,
            'lot_title' => $this->lot->title,
            'final_price' => $this->lot->current_price,
            'url' => route('lots.show', $this->lot),
        ];
    }
}
