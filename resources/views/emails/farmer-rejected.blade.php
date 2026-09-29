<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Account update</title>
</head>
<body style="margin:0;padding:0;background:#f6f3ea;font-family:Arial,Helvetica,sans-serif;color:#18231c;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#f6f3ea;padding:32px 12px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width:600px;background:#ffffff;border:1px solid #dfe5de;">

                    <tr>
                        <td style="background:#173923;padding:24px 32px;">
                            <span style="color:#ffffff;font-size:18px;font-weight:bold;letter-spacing:1px;">MarketLink</span>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:32px 32px 8px;">
                            <p style="margin:0 0 6px;font-size:12px;letter-spacing:1.5px;text-transform:uppercase;color:#b3261e;font-weight:bold;">Account not approved</p>
                            <h1 style="margin:0 0 16px;font-size:24px;line-height:1.3;color:#18231c;">Hi {{ $user->name }},</h1>
                            <p style="margin:0 0 14px;font-size:15px;line-height:1.7;">
                                Thank you for applying to sell on MarketLink. After reviewing your farmer account, our team could not approve it this time.
                            </p>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:0 32px 8px;">
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#fdecea;border:1px solid #f3c8c4;">
                                <tr>
                                    <td style="padding:16px 20px;font-size:14px;line-height:1.8;color:#18231c;">
                                        <strong style="color:#b3261e;">Comment from the admin</strong><br>
                                        {{ $reason }}
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <tr>
                        <td align="center" style="padding:24px 32px 12px;">
                            <a href="{{ $loginUrl }}" style="display:inline-block;background:#2f5d3a;color:#ffffff;text-decoration:none;font-weight:bold;font-size:14px;padding:14px 32px;">
                                Sign in to see details
                            </a>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:8px 32px 28px;">
                            <p style="margin:0;font-size:13px;line-height:1.6;color:#6f786f;">
                                This is an automated message from MarketLink. If you have questions, contact our support team.
                            </p>
                        </td>
                    </tr>

                    <tr>
                        <td style="background:#f6f3ea;padding:16px 32px;text-align:center;font-size:12px;color:#6f786f;border-top:1px solid #dfe5de;">
                            &copy; {{ date('Y') }} MarketLink
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
