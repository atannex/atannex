@component('mail::message')

# {{ __('emails.welcome.greeting', ['name' => $user->name]) }}

{{ __('emails.welcome.body') }}

@component('mail::button', ['url' => $supportUrl, 'color' => 'primary'])
{{ __('emails.welcome.cta') }}
@endcomponent

{{ __('emails.welcome.footer', ['appName' => $appName]) }}

@component('mail::subcopy')
{{ __('emails.welcome.subcopy', ['supportUrl' => $supportUrl]) }}
@endcomponent

@endcomponent
