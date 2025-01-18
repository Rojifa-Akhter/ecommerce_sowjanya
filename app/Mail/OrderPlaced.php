<?php
namespace App\Mail;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderPlaced extends Mailable
{
    use Queueable, SerializesModels;

    public $order;
    public $product;
    public $address;
    public $orderDate;
    public $user_name;

    /**
     * Create a new message instance.
     */
    public function __construct(Order $order, Product $product, string $address, string $orderDate, $user_name)
    {
        $this->order     = $order;
        $this->product   = $product;
        $this->address   = $address;
        $this->orderDate = $orderDate;
        $this->user_name = $user_name;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your Stylish Receipt is Ready – It’s as Chic as You Are!',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'email.order', // The view you will use to design the email template
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
