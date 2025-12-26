<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>@yield('og:title', config('app.name') . ' - ' . config('app.title'))</title>

    <meta name="description" content="@yield('og:description', config('app.description'))">
    <meta name="keywords" content="{{ implode(', ', config('site.keywords')) }}">

    <link rel="canonical" href="{{ url()->current() }}">
    <meta property="og:url" content="{{ url()->current() }}">

    <meta name="robots" content="index, follow">

    @php
    $favicon = asset('storage/' . optional($global['favicon'])->image);
    @endphp

    <link rel="icon" href="{{ $favicon }}">
    <link rel="apple-touch-icon" href="{{ $favicon }}">
    <meta name="theme-color" content="#ffffff">

    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('favicon/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon/favicon-16x16.png') }}">
    <link rel="manifest" href="/favicon/site.webmanifest">

    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&family=Fraunces:wght@600;700&display=swap" rel="stylesheet">

</head>

<body style="margin: 0; padding: 0; background-color: #f8f9fa; font-family: 'DM Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;">

    <table role="presentation" style="width: 100%; border-collapse: collapse; background-color: #f8f9fa;">
        <tr>
            <td style="padding: 50px 20px;">

                <table role="presentation" style="max-width: 650px; margin: 0 auto; background-color: #ffffff; border-radius: 20px; overflow: hidden; box-shadow: 0 20px 60px rgba(0, 0, 0, 0.08);">

                    <tr>
                        <td style="position: relative; background: linear-gradient(135deg, #10b981 0%, #059669 50%, #047857 100%); padding: 0; height: 8px;">
                        </td>
                    </tr>

                    @yield('base')

                    @include('emails.social')

                    @include('emails.manage')

                    @include('emails.footer')

                </table>

            </td>
        </tr>
    </table>

</body>

</html>
