<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $subjectLine }}</title>
</head>
<body style="margin:0;padding:0;background:#f3f4f6;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Helvetica,Arial,sans-serif;color:#1f2937;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#f3f4f6;padding:24px 12px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width:600px;background:#ffffff;border-radius:12px;overflow:hidden;box-shadow:0 4px 12px rgba(0,0,0,0.06);">
                    <tr>
                        <td style="background:linear-gradient(135deg,#059669 0%,#0d9488 50%,#0891b2 100%);padding:24px;color:#ffffff;">
                            <div style="font-size:11px;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;opacity:0.85;">{{ config('notifications.school_name') }}</div>
                            <div style="font-size:20px;font-weight:800;margin-top:4px;">{{ $subjectLine }}</div>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:24px;">
                            <div style="white-space:pre-wrap;font-size:14px;line-height:1.65;color:#374151;">{!! e($bodyText) !!}</div>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:14px 24px;background:#f9fafb;border-top:1px solid #e5e7eb;font-size:12px;color:#6b7280;text-align:center;">
                            Email otomatis dari {{ config('notifications.school_name') }}.
                            @if(config('notifications.school_website'))
                                <br><a href="{{ config('notifications.school_website') }}" style="color:#059669;text-decoration:none;">{{ config('notifications.school_website') }}</a>
                            @endif
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
