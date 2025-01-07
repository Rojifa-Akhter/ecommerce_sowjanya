<!DOCTYPE html>
<html>
<head>
    <title>Order Placed - Invoice</title>
</head>
<body>
    <h1>Thank you for your order!</h1>
    <p>Your order has been successfully placed. Below are the details of your order:</p>

    <h2>Order Details:</h2>
    <ul>
        <li><strong>Order ID:</strong> {{ $order->id }}</li>
        <li><strong>Product:</strong> {{ $product->title }}</li>
        <li><strong>Amount:</strong> ${{ number_format($order->amount, 2) }}</li>
        <li><strong>Status:</strong> {{ ucfirst($order->status) }}</li>
        <li><strong>Order Date:</strong> {{ $orderDate }}</li>
        <li><strong>Shipping Address:</strong> {{ $address }}</li>
    </ul>

    <p>Thank you for shopping with us!</p>
</body>
</html>
