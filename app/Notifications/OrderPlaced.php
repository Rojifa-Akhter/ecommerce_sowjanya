<?php

namespace App\Notifications;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class OrderPlaced extends Notification
{
    use Queueable;

    protected $order;
    protected $product;
    protected $quantity;
    protected $address;
    protected $orderDate;

    public function __construct(Order $order, Product $product, $quantity, $address, $orderDate)
    {
        $this->order = $order;
        $this->product = $product;
        $this->quantity = $quantity;
        $this->address = $address;
        $this->orderDate = $orderDate;
    }

    public function via($notifiable)
    {
        return ['database']; // You can also send this via email if needed
    }

    public function toDatabase($notifiable)
    {
        return [
            'order_id' => $this->order->id,
            'product_title' => $this->product->title,
            'order_date' => $this->orderDate,
            'quantity' => $this->quantity,
            'address' => $this->address,
            'amount' => $this->order->amount,
            'status' => $this->order->status,
        ];
    }
}
