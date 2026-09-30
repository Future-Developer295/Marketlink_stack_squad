<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>New contact message</title>
</head>
<body style="margin:0;padding:0;background:#f6f3ea;font-family:Arial,Helvetica,sans-serif;color:#18231c;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#f6f3ea;padding:32px 12px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width:600px;background:#ffffff;border:1px solid #dfe5de;">

                    <tr>
                        <td style="background:#173923;padding:24px 32px;">
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td style="color:#ffffff;font-size:18px;font-weight:bold;letter-spacing:1px;">MarketLink</td>
                                    <td align="right" style="color:#9fb89f;font-size:12px;letter-spacing:1px;text-transform:uppercase;">Contact form</td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:32px 32px 8px;">
                            <p style="margin:0 0 6px;font-size:12px;letter-spacing:1.5px;text-transform:uppercase;color:#2f5d3a;font-weight:bold;">New message</p>
                            <h1 style="margin:0 0 8px;font-size:24px;line-height:1.3;color:#18231c;">{{ $contact->subject }}</h1>
                            <p style="margin:0;font-size:14px;color:#6f786f;">
                                {{ $contact->full_name }} sent you a message through the MarketLink contact page.
                            </p>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:20px 32px 8px;">
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#eaf2eb;border:1px solid #dfe5de;">
                                <tr>
                                    <td style="padding:14px 18px 6px;font-size:11px;letter-spacing:1px;text-transform:uppercase;color:#6f786f;">From</td>
                                </tr>
                                <tr>
                                    <td style="padding:0 18px 12px;font-size:15px;font-weight:bold;color:#173923;">{{ $contact->full_name }}</td>
                                </tr>
                                <tr>
                                    <td style="padding:0 18px;"><div style="border-top:1px solid #dfe5de;"></div></td>
                                </tr>
                                <tr>
                                    <td style="padding:12px 18px 6px;font-size:11px;letter-spacing:1px;text-transform:uppercase;color:#6f786f;">Email</td>
                                </tr>
                                <tr>
                                    <td style="padding:0 18px 12px;font-size:15px;">
                                        <a href="mailto:{{ $contact->email }}" style="color:#2f5d3a;text-decoration:none;font-weight:bold;">{{ $contact->email }}</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding:0 18px;"><div style="border-top:1px solid #dfe5de;"></div></td>
                                </tr>
                                <tr>
                                    <td style="padding:12px 18px 6px;font-size:11px;letter-spacing:1px;text-transform:uppercase;color:#6f786f;">Received</td>
                                </tr>
                                <tr>
                                    <td style="padding:0 18px 14px;font-size:15px;color:#18231c;">{{ $contact->created_at->format('d M Y, h:i A') }}</td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:20px 32px 8px;">
                            <p style="margin:0 0 10px;font-size:11px;letter-spacing:1px;text-transform:uppercase;color:#6f786f;font-weight:bold;">Message</p>
                            <div style="white-space:pre-wrap;background:#f6f3ea;padding:18px 20px;border-left:4px solid #2f5d3a;font-size:15px;line-height:1.7;color:#18231c;">{{ $contact->message }}</div>
                        </td>
                    </tr>

                    <tr>
                        <td align="center" style="padding:24px 32px 32px;">
                            <a href="mailto:{{ $contact->email }}?subject={{ rawurlencode('Re: '.$contact->subject) }}"
                               style="display:inline-block;background:#2f5d3a;color:#ffffff;text-decoration:none;font-weight:bold;font-size:14px;padding:14px 32px;">
                                Reply to {{ $contact->full_name }}
                            </a>
                            <p style="margin:14px 0 0;font-size:12px;color:#6f786f;">Or simply hit Reply &mdash; it goes straight to {{ $contact->email }}.</p>
                        </td>
                    </tr>

                    <tr>
                        <td style="background:#f6f3ea;padding:16px 32px;text-align:center;font-size:12px;color:#6f786f;border-top:1px solid #dfe5de;">
                            &copy; {{ date('Y') }} MarketLink &middot; Sent from the website contact form
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
