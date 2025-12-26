@extends('emails.base')

@section('og:title', seo_title('Reset Your Password!'))

@section('base')

<tr>
    <td style="padding: 50px 50px 40px 50px; text-align: center; background-color: #ffffff;">
        <div style="display: inline-block; width: 80px; height: 80px; background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); border-radius: 20px; margin-bottom: 24px; position: relative; box-shadow: 0 10px 30px rgba(245, 158, 11, 0.3);">
            <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);">
                <div style="width: 40px; height: 40px; position: relative;">
                    <div style="width: 16px; height: 16px; background-color: #ffffff; border-radius: 50%; position: absolute; top: 8px; left: 12px;"></div>
                    <div style="width: 20px; height: 12px; background-color: #ffffff; position: absolute; bottom: 8px; left: 10px; border-radius: 2px;"></div>
                    <div style="width: 6px; height: 6px; background-color: #f59e0b; position: absolute; bottom: 11px; left: 13px;"></div>
                    <div style="width: 6px; height: 6px; background-color: #f59e0b; position: absolute; bottom: 11px; right: 13px;"></div>
                </div>
            </div>
        </div>

        <h1 style="margin: 0; font-family: 'Fraunces', Georgia, serif; color: #0f172a; font-size: 34px; font-weight: 700; letter-spacing: -0.5px; line-height: 1.2;">
            {{ __("Reset Your Password") }}
        </h1>
        <p style="margin: 12px 0 0 0; color: #64748b; font-size: 16px; font-weight: 500;">
            {{ __("Secure your account with a new password") }}
        </p>
    </td>
</tr>

<tr>
    <td style="padding: 0 50px 40px 50px;">
        <table role="presentation" style="width: 100%; background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%); border-radius: 16px; border: 2px solid #fbbf24; overflow: hidden;">
            <tr>
                <td style="padding: 30px; text-align: center;">
                    <div style="display: inline-block; width: 56px; height: 56px; background-color: #f59e0b; border-radius: 50%; margin-bottom: 16px; position: relative; box-shadow: 0 8px 20px rgba(245, 158, 11, 0.3);">
                        <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); color: #ffffff; font-size: 32px; font-weight: 700; line-height: 1;">!</div>
                    </div>

                    <h2 style="margin: 0 0 8px 0; color: #78350f; font-size: 20px; font-weight: 700; font-family: 'DM Sans', sans-serif;">
                        {{ __("Password Reset Requested") }}
                    </h2>
                    <p style="margin: 0; color: #92400e; font-size: 15px; line-height: 1.6;">
                        {{ __("We received a request to reset your password. Click below to proceed.") }}
                    </p>
                </td>
            </tr>
        </table>
    </td>
</tr>

<tr>
    <td style="padding: 0 50px 40px 50px;">
        <p style="margin: 0 0 20px 0; color: #334155; font-size: 17px; line-height: 1.7; font-weight: 400;">
            {{ __('Hi :name 👋', ['name' => $user->name]) }}
        </p>

        <p style="margin: 0 0 24px 0; color: #475569; font-size: 16px; line-height: 1.8;">
            {{ __("You recently requested to reset your password for your account. Click the button below to create a new password.") }}
        </p>

        <table role="presentation" style="width: 100%; background-color: #f8fafc; border-radius: 14px; border: 1.5px solid #e2e8f0; margin: 32px 0;">
            <tr>
                <td style="padding: 28px;">
                    <h3 style="margin: 0 0 18px 0; color: #0f172a; font-size: 18px; font-weight: 700; font-family: 'DM Sans', sans-serif;">
                        {{ __("🔑 Reset Request Details") }}
                    </h3>

                    <table role="presentation" style="width: 100%;">
                        <tr>
                            <td style="padding: 12px 0; border-bottom: 1px solid #e2e8f0;">
                                <table role="presentation" style="width: 100%;">
                                    <tr>
                                        <td style="width: 40px; vertical-align: top;">
                                            <div style="width: 36px; height: 36px; background-color: #e0e7ff; border-radius: 8px; text-align: center; line-height: 36px; font-size: 18px;">📧</div>
                                        </td>
                                        <td style="vertical-align: top; padding-left: 12px;">
                                            <span style="display: block; color: #64748b; font-size: 13px; font-weight: 500; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">{{ __("Email Address") }}</span>
                                            <span style="display: block; color: #1e293b; font-size: 16px; font-weight: 600;">{{ $user->email }}</span>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>

                        <tr>
                            <td style="padding: 12px 0; border-bottom: 1px solid #e2e8f0;">
                                <table role="presentation" style="width: 100%;">
                                    <tr>
                                        <td style="width: 40px; vertical-align: top;">
                                            <div style="width: 36px; height: 36px; background-color: #fef3c7; border-radius: 8px; text-align: center; line-height: 36px; font-size: 18px;">⏰</div>
                                        </td>
                                        <td style="vertical-align: top; padding-left: 12px;">
                                            <span style="display: block; color: #64748b; font-size: 13px; font-weight: 500; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">{{ __("Request Time") }}</span>
                                            <span style="display: block; color: #1e293b; font-size: 16px; font-weight: 600;">
                                                {{ $requestedAt->format('F d, Y \a\t h:i A') }}
                                            </span>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>

                        <tr>
                            <td style="padding: 12px 0; border-bottom: 1px solid #e2e8f0;">
                                <table role="presentation" style="width: 100%;">
                                    <tr>
                                        <td style="width: 40px; vertical-align: top;">
                                            <div style="width: 36px; height: 36px; background-color: #dbeafe; border-radius: 8px; text-align: center; line-height: 36px; font-size: 18px;">🌐</div>
                                        </td>
                                        <td style="vertical-align: top; padding-left: 12px;">
                                            <span style="display: block; color: #64748b; font-size: 13px; font-weight: 500; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">{{ __("IP Address") }}</span>
                                            <span style="display: block; color: #1e293b; font-size: 16px; font-weight: 600;">{{ $ipAddress ?? 'Unknown' }}</span>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>

                        <tr>
                            <td style="padding: 12px 0;">
                                <table role="presentation" style="width: 100%;">
                                    <tr>
                                        <td style="width: 40px; vertical-align: top;">
                                            <div style="width: 36px; height: 36px; background-color: #e0e7ff; border-radius: 8px; text-align: center; line-height: 36px; font-size: 18px;">💻</div>
                                        </td>
                                        <td style="vertical-align: top; padding-left: 12px;">
                                            <span style="display: block; color: #64748b; font-size: 13px; font-weight: 500; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">{{ __("Device") }}</span>
                                            <span style="display: block; color: #1e293b; font-size: 16px; font-weight: 600;">{{ $device ?? 'Unknown' }}</span>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <table role="presentation" style="width: 100%; margin: 36px 0;">
            <tr>
                <td style="text-align: center;">
                    <a href="{{ $resetUrl }}" style="display: inline-block; background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: #ffffff; text-decoration: none; padding: 18px 48px; border-radius: 12px; font-size: 17px; font-weight: 700; letter-spacing: 0.3px; box-shadow: 0 10px 30px rgba(245, 158, 11, 0.3); font-family: 'DM Sans', sans-serif;">
                        {{ __("🔑 Reset Password") }}
                    </a>
                </td>
            </tr>
        </table>

        <p style="margin: 32px 0 0 0; color: #64748b; font-size: 14px; line-height: 1.6; text-align: center;">
            {{ __("Or copy and paste this link into your browser:") }}<br>
            <a href="{{ $resetUrl }}" style="color: #f59e0b; text-decoration: none; font-weight: 500; word-break: break-all;">{{ $resetUrl }}</a>
        </p>
    </td>
</tr>

<tr>
    <td style="padding: 0 50px;">
        <div style="height: 1px; background: linear-gradient(90deg, transparent 0%, #e2e8f0 50%, transparent 100%);"></div>
    </td>
</tr>

<tr>
    <td style="padding: 40px 50px;">
        <table role="presentation" style="width: 100%; background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%); border-radius: 14px; border: 2px solid #fbbf24;">
            <tr>
                <td style="padding: 28px; text-align: center;">
                    <div style="display: inline-block; width: 48px; height: 48px; background-color: #f59e0b; border-radius: 50%; margin-bottom: 12px; position: relative;">
                        <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); color: #ffffff; font-size: 24px;">⏰</div>
                    </div>

                    <h3 style="margin: 0 0 8px 0; color: #78350f; font-size: 18px; font-weight: 700; font-family: 'DM Sans', sans-serif;">
                        {{ __("Link Expires in :minutes Minutes", ['minutes' => $expiresIn]) }}
                    </h3>
                    <p style="margin: 0; color: #92400e; font-size: 15px; line-height: 1.6;">
                        {{ __("For your security, this password reset link will expire in") }} <strong>{{ $expiresIn }} {{ __("minutes") }}</strong>{{ __(". Please reset your password soon.") }}
                    </p>
                </td>
            </tr>
        </table>
    </td>
</tr>

<tr>
    <td style="padding: 0 50px 40px 50px;">
        <table role="presentation" style="width: 100%; background-color: #fef2f2; border-left: 4px solid #ef4444; border-radius: 8px;">
            <tr>
                <td style="padding: 24px;">
                    <h4 style="margin: 0 0 12px 0; color: #7f1d1d; font-size: 17px; font-weight: 700; font-family: 'DM Sans', sans-serif;">
                        {{ __("⚠️ Didn't Request This?") }}
                    </h4>
                    <p style="margin: 0 0 16px 0; color: #991b1b; font-size: 15px; line-height: 1.7;">
                        {{ __("If you didn't request a password reset, please ignore this email. Your password will remain unchanged, and no action is needed on your part.") }}
                    </p>
                    <p style="margin: 0; color: #991b1b; font-size: 15px; line-height: 1.7;">
                        <strong>{{ __("Concerned about your account security?") }}</strong> {{ __("Contact us immediately at") }} <a href="mailto:security@yourcompany.com" style="color: #dc2626; font-weight: 600; text-decoration: underline;">security@yourcompany.com</a>
                    </p>
                </td>
            </tr>
        </table>
    </td>
</tr>
@endsection
