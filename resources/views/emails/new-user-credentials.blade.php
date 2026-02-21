<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Your Account Credentials</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .header {
            background-color: #1a5f2a;
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
        }
        .credentials-box {
            background-color: #f8f9fa;
            border: 2px dashed #1a5f2a;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
            text-align: center;
        }
        .email-label {
            font-size: 12px;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .email-value {
            font-size: 18px;
            font-weight: bold;
            color: #1a5f2a;
            margin-bottom: 15px;
        }
        .password-label {
            font-size: 12px;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .password-value {
            font-size: 16px;
            font-weight: bold;
            color: #333;
            background-color: #e9ecef;
            padding: 10px 20px;
            border-radius: 4px;
            display: inline-block;
            margin-top: 10px;
            letter-spacing: 2px;
        }
        .btn {
            display: inline-block;
            background-color: #1a5f2a;
            color: #ffffff;
            text-decoration: none;
            padding: 15px 30px;
            border-radius: 5px;
            font-weight: bold;
            margin: 20px 0;
        }
        .btn:hover {
            background-color: #144620;
        }
        .footer {
            background-color: #f8f9fa;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #666;
        }
        .security-notice {
            background-color: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px;
            margin: 20px 0;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🏆 WebViper Academy</h1>
        </div>

        <div class="content">
            <h2>Welcome to WebViper Academy, {{ $user->name }}!</h2>

            <p>A <strong>{{ $userType }}</strong> account has been created for you by the administrator. Here are your login credentials:</p>

            <div class="credentials-box">
                <div class="email-label">Email Address</div>
                <div class="email-value">{{ $user->email }}</div>

                <div class="password-label">Temporary Password</div>
                <div class="password-value">{{ $temporaryPassword }}</div>
            </div>

            <div style="text-align: center;">
                <a href="{{ $resetUrl }}" class="btn">Set New Password</a>
            </div>

            <div class="security-notice">
                <strong>🔒 Security Notice:</strong><br>
                For your security, please click the button above to set a new password within 24 hours. Your temporary password will expire after this period.
            </div>

            <h3>Account Details:</h3>
            <ul>
                <li><strong>Account Type:</strong> {{ $userType }}</li>
                <li><strong>Role:</strong> {{ $roleName }}</li>
                <li><strong>Dashboard URL:</strong>
                    @if($userType === 'Staff')
                        /admin/dashboard
                    @elseif($userType === 'Partner')
                        /partner/dashboard
                    @else
                        /dashboard
                    @endif
                </li>
            </ul>

            <p>If you have any questions or need assistance, please contact the academy administration.</p>

            <p>Best regards,<br>
            <strong>WebViper Academy Administration</strong></p>
        </div>

        <div class="footer">
            <p>This is an automated message from WebViper Academy.</p>
            <p>© {{ date('Y') }} WebViper Academy. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
