<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Alder & Rhodes</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
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
        .otp {
            font-size: 24px;
            font-weight: bold;
            color: #ff6f00;
            margin: 10px 0;
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
    </style>
</head>
<body>
    <div class="email-container">

        <div class="content">
            <p>Hey <strong>{{ $user_name }}</strong>,</p>
            <p>Welcome to the Alder & Rhodes fam – we’re thrilled you’ve joined us! 🥳</p>
            <p>Here’s your VIP access code (also known as your OTP):</p>
            <div class="otp">{{ $otp }}</div>
            <p>No pressure, but it’s valid for just 10 minutes – kind of like a mission impossible moment, minus the explosions.</p>
            <p>Quick! Enter it now and unlock your stylish new adventure.</p>
            <p>If you didn’t request this, don’t worry – no fancy fashion heists happening here. Just ignore this message, and we’ll stay friends.</p>
        </div>

        <!-- Footer Section -->
        <div class="footer">
            <p>Cheers,</p>
            <p>The Alder & Rhodes Team</p>
            <p><a href="#">Visit our website</a> | <a href="mailto:support@alderandrhodes.com">Contact Support</a></p>
        </div>
    </div>
</body>
</html>
