<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Invoice from Alder & Rhodes</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
        }

        .email-container {
            max-width: 600px;
            margin: 20px auto;
            background: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .header {
            background-color: #ffce00;
            color: #333333;
            padding: 20px;
            text-align: center;
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
        }

        .content {
            padding: 20px;
            text-align: center;
        }

        .content p {
            margin: 0 0 15px;
            line-height: 1.6;
            color: #555555;
        }

        .order-details {
            margin: 20px 0;
            text-align: left;
            background: #f4f4f4;
            padding: 15px;
            border-radius: 8px;
        }

        .order-details p {
            margin: 5px 0;
            font-size: 14px;
            color: #333333;
        }

        .footer {
            background-color: #f9f9f9;
            padding: 10px 20px;
            text-align: center;
            font-size: 12px;
            color: #999999;
        }

        .footer a {
            color: #ff6f00;
            text-decoration: none;
        }

        .cta {
            margin-top: 20px;
            font-size: 14px;
            color: #333333;
        }
    </style>
</head>

<body>
    <div class="email-container">

        <div class="content">
            <p>Hey <strong>$user_name</strong>,</p>
            <p>
                Thank you for shopping with Alder & Rhodes – your wardrobe
                just leveled up! 🎉
            </p>
            <p>
                Here’s your official invoice (because even fashion needs
                paperwork):
            </p>

            <!-- Order Details -->
            <div class="order-details">
                <p><strong>Order Number:</strong> {{ $order->id }}</p>
                <p><strong>Product:</strong> {{ $product->title }}</p>
                <p><strong>Total Amount:</strong> £{{ number_format($order->amount, 2) }}</p>
                <p><strong>Shipping Address:</strong> {{ $address }}</p>
                <p><strong>Date of Purchase:</strong> {{ date('F j, Y') }}</p>
            </div>

            <p>
                You can find all the dazzling details in the attached
                invoice. (It’s not as stylish as our clothes, but we promise
                it gets the job done.)
            </p>

            <div class="cta">
                <p>
                    Got questions? Need to chat? Or just want to tell us how
                    much you love your new items?
                </p>
                <p>
                    We’re all ears at
                    <a href="mailto:support@alderandrhodes.com">[Support Email]</a>.
                </p>
            </div>
        </div>

        <!-- Footer Section -->
        <div class="footer">
            <p>
                Can’t wait to see you rocking your new look! Feel free to
                tag us on Instagram <strong>@AlderAndRhodes</strong> – we
                LOVE a good style moment.
            </p>
            <p>Stay classy,<br />The Alder & Rhodes Team</p>
            <p><a href="#">Visit our website</a></p>
        </div>
    </div>
</body>

</html>
