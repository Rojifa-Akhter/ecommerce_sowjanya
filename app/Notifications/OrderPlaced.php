<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class OrderPlaced extends Notification
{
    use Queueable;

    protected $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function via($notifiable)
    {
        return [ 'database']; // Can notify via email and database
    }

    public function toDatabase($notifiable)
    {
        return [
            'order_id' => $this->order->id,
            'amount' => $this->order->amount,
            'status' => $this->order->status,
        ];
    }
}
