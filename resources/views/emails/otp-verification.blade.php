<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kode Verifikasi Email</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 500px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .header {
            background-color: #cb2786;
            color: #ffffff;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .content {
            padding: 30px;
            text-align: center;
        }
        .otp-code {
            font-size: 36px;
            font-weight: bold;
            color: #cb2786;
            letter-spacing: 8px;
            margin: 20px 0;
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 8px;
            border: 2px dashed #cb2786;
        }
        .timer {
            color: #dc3545;
            font-size: 14px;
            margin-top: 15px;
        }
        .footer {
            background-color: #f8f9fa;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Verifikasi Email</h1>
        </div>
        <div class="content">
            <p>Terima kasih telah mendaftar di <strong>KAMCUP</strong>.</p>
            <p>Silakan gunakan kode verifikasi berikut:</p>
            
            <div class="otp-code">{{ $otp }}</div>
            
            <p class="timer">⏱️ Kode ini berlaku selama 60 detik</p>
            
            <p><small>Jika Anda tidak melakukan pendaftaran, abaikan email ini.</small></p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} KAMCUP. All rights reserved.</p>
        </div>
    </div>
</body>
</html>

