<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ config('app.name') }} - New Contact Message</title>
</head>

<body style="font-family: Arial, Helvetica, sans-serif; background-color:#f9fafb; padding:30px;">

    <table width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <td align="center">

                <table width="600" cellpadding="0" cellspacing="0" style="background:#ffffff; border-radius:6px; padding:30px; border:1px solid #e5e7eb;">

                    <tr>
                        <td>

                            <h2 style="margin-top:0; color:#111827;">
                                New Contact Message
                            </h2>

                            <p style="color:#6b7280; font-size:14px;">
                                A new contact form submission has been received.
                            </p>

                            <hr style="border:none; border-top:1px solid #e5e7eb; margin:20px 0;">

                            <table width="100%" cellpadding="6" cellspacing="0">

                                <tr>
                                    <td width="140"><strong>Name</strong></td>
                                    <td>{{ $contact->name }}</td>
                                </tr>

                                <tr>
                                    <td><strong>Email</strong></td>
                                    <td>
                                        <a href="mailto:{{ $contact->email }}">
                                            {{ $contact->email }}
                                        </a>
                                    </td>
                                </tr>

                                @if(!empty($contact->number))
                                <tr>
                                    <td><strong>Phone</strong></td>
                                    <td>{{ $contact->number }}</td>
                                </tr>
                                @endif

                                <tr>
                                    <td><strong>Subject</strong></td>
                                    <td>{{ $contact->subject }}</td>
                                </tr>

                            </table>

                            <hr style="border:none; border-top:1px solid #e5e7eb; margin:20px 0;">

                            <h4 style="margin-bottom:10px;">Message</h4>

                            <p style="line-height:1.6; color:#374151;">
                                {!! nl2br(e($contact->message)) !!}
                            </p>

                            <hr style="border:none; border-top:1px solid #e5e7eb; margin:30px 0;">

                            <p style="font-size:12px; color:#9ca3af;">
                                This message was sent via the contact form on {{ config('app.name') }}.
                            </p>

                        </td>
                    </tr>

                </table>

            </td>
        </tr>
    </table>

</body>
</html>
