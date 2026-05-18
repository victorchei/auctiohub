<?php

namespace App\Notifications;

use App\Models\Lot;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AuctionEndedSellerNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Lot $lot) {}

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $mail = (new MailMessage)
            ->subject("Аукціон завершено: {$this->lot->title}")
            ->greeting("Привіт, {$notifiable->name}!")
            ->line("Ваш аукціон «{$this->lot->title}» завершився.");

        if ($this->lot->winner_id) {
            $mail->line("Переможець: {$this->lot->winner->name}")
                 ->line("Підсумкова ціна: ".number_format($this->lot->current_price, 2, ',', ' ').' ₴')
                 ->action('Переглянути лот', route('lots.show', $this->lot));
        } else {
            $mail->line('Ставок не було. Спробуйте знизити стартову ціну або змінити опис у наступний раз.');
        }

        return $mail;
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'auction_ended_seller',
            'lot_id' => $this->lot->id,
            'lot_title' => $this->lot->title,
            'winner_id' => $this->lot->winner_id,
            'final_price' => $this->lot->current_price,
        ];
    }
}
