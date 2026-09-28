<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Verify your email</title>
</head>
<body style="margin:0;padding:0;background:#f6f3ea;font-family:Arial,Helvetica,sans-serif;color:#18231c;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#f6f3ea;padding:32px 12px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width:520px;background:#ffffff;">
                    <tr>
                        <td style="background:#173923;padding:22px 32px;color:#ffffff;font-size:18px;font-weight:bold;letter-spacing:1px;">
                            MarketLink
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:32px;">
                            <p style="margin:0 0 12px;font-size:16px;">Hi {{ $user->name }},</p>
                            <p style="margin:0 0 24px;font-size:15px;line-height:1.6;color:#4a554c;">
                                Use the code below to verify your email address and finish creating your
                                {{ $user->role === 'farmer' ? 'farmer' : 'customer' }} account.
                            </p>

                            <div style="text-align:center;margin:0 0 24px;">
                                <span style="display:inline-block;padding:16px 28px;background:#eaf2eb;border:1px solid #dfe5de;font-size:34px;font-weight:bold;letter-spacing:10px;color:#173923;">
                                    {{ $code }}
                                </span>
                            </div>

                            <p style="margin:0 0 8px;font-size:14px;line-height:1.6;color:#4a554c;">
                                This code is valid for <strong>{{ $minutes }} minutes</strong>.
                            </p>
                            <p style="margin:0;font-size:13px;line-height:1.6;color:#6f786f;">
                                If you didn't create a MarketLink account, you can safely ignore this email.
                                Never share this code with anyone.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
