<?php

namespace App\Notifications;

use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ProductAddedNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public $product;
    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }
// Database Notification
    public function toDatabase($notifiable)
    {
        return [
            'title' => $this->product->title,
            'description' => $this->product->description,
            'price' => $this->product->price,
        ];
    }
    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('New Product Added')
            ->line('A new product has been added to the store.')
            ->line('Product Title: ' . $this->product->title)
            ->line('Description: ' . $this->product->description)
            ->line('Price: $' . $this->product->price)
            // ->action('View Product', url('/products/' . $this->product->id))
            ;
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
