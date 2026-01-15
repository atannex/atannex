@component('mail::message')
# 📬 New Contact Form Submission

This is an automated notification to inform you that a new contact request has been successfully submitted through the official website contact form.

Please review the details below and take the appropriate action in line with internal response procedures.

---

## 🧾 Submission Summary

**Reference ID:** {{ $contact->id }}
**Submitted On:** {{ $contact->created_at->format('l, F j, Y \a\t g:i A') }}

---

## 👤 Sender Information

**Full Name:** {{ $contact->name }}
**Email Address:** {{ $contact->email }}
**Phone Number:** {{ $contact->number ?: 'Not provided' }}
**Subject Category:** {{ $contact->subject }}

---

## 💬 Message Details

{{ $contact->message }}

---

## ⏱️ Response & Handling Guidelines

- Acknowledge receipt where applicable
- Respond within the standard service response window
- Escalate internally if the message indicates urgency or risk
- Ensure all follow-ups are documented for audit and reporting purposes

---

## 🔐 Confidentiality Notice

This email and its contents are intended solely for authorized personnel.
If you have received this message in error, please refrain from sharing or distributing its contents and notify the system administrator immediately.

---

@component('mail::panel')
This message has been sent to the admin team.
Multiple admins may receive this notification.
@endcomponent

**This message was generated automatically by the {{ config('app.name') }} platform.**
Please do not reply directly to this email unless explicitly instructed.

Kind regards,
**{{ config('app.name') }} **

@endcomponent
