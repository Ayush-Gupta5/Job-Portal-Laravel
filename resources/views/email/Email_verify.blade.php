<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Email Verification</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f6f6f6;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .header {
            background-color: #4CAF50;
            color: #ffffff;
            text-align: center;
            padding: 20px;
        }
        .content {
            padding: 20px;
            text-align: left;
            color: #333333;
        }
        .button {
            display: inline-block;
            padding: 10px 20px;
            margin: 20px 0;
            background-color: #4CAF50;
            color: #ffffff;
            text-decoration: none;
            border-radius: 5px;
        }
        .footer {
            text-align: center;
            padding: 10px;
            font-size: 12px;
            color: #999999;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Verify Your Email </h1>
        </div>
        <div class="content">
            <p><b>Hi {{ $EmailData['user']->name }}</b></p>
            <p>Thank you for registering with <b>OurJobAdda</b>. Please click the button below to verify your email address and complete your registration:</p>
            <p><a href="{{ route('account.emailVerify',$EmailData['token']) }}" class="button">Verify Email</a></p>
            <p>If you did not create an account, no further action is required.</p>
            <p>Best regards,<br> OurJobAdda Team</p>
        </div>
        <div class="footer">
            <p>&copy; 2024 OurJobAdda. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
