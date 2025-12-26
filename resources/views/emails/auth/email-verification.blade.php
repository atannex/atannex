@extends('emails.base')

@section('og:title', seo_title('Verify Your Email Address!'))

@section('base')

<tr>
    <td style="padding: 50px 50px 40px 50px; text-align: center; background-color: #ffffff;">

        <div style="display: inline-block; width: 80px; height: 80px; background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); border-radius: 20px; margin-bottom: 24px; position: relative; box-shadow: 0 10px 30px rgba(99, 102, 241, 0.3);">
            <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);">

                <div style="width: 42px; height: 48px; position: relative;">

                    <div style="width: 0; height: 0; border-left: 21px solid transparent; border-right: 21px solid transparent; border-bottom: 40px solid #ffffff; position: relative;">
                    </div>

                    <div style="position: absolute; top: 18px; left: 12px; width: 8px; height: 14px; border: solid #6366f1; border-width: 0 3px 3px 0; transform: rotate(45deg);"></div>
                </div>
            </div>
        </div>

        <h1 style="margin: 0; font-family: 'Fraunces', Georgia, serif; color: #0f172a; font-size: 34px; font-weight: 700; letter-spacing: -0.5px; line-height: 1.2;">
            {{ __(' Verify Your Email Address') }}
        </h1>
        <p style="margin: 12px 0 0 0; color: #64748b; font-size: 16px; font-weight: 500;">
            {{ __("One quick step to secure your account") }}
        </p>
    </td>
</tr>

<tr>
    <td style="padding: 0 50px 40px 50px;">
        <table role="presentation" style="width: 100%; background: linear-gradient(135deg, #eef2ff 0%, #e0e7ff 100%); border-radius: 16px; border: 2px solid #c7d2fe; overflow: hidden;">
            <tr>
                <td style="padding: 30px; text-align: center;">

                    <div style="display: inline-block; width: 56px; height: 56px; background-color: #6366f1; border-radius: 50%; margin-bottom: 16px; position: relative; box-shadow: 0 8px 20px rgba(99, 102, 241, 0.3);">
                        <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); color: #ffffff; font-size: 28px; font-weight: 700;">✓</div>
                    </div>

                    <h2 style="margin: 0 0 8px 0; color: #3730a3; font-size: 20px; font-weight: 700; font-family: 'DM Sans', sans-serif;">
                        {{ __("Welcome! Let's Get Started") }}
                    </h2>
                    <p style="margin: 0; color: #4338ca; font-size: 15px; line-height: 1.6;">
                        {{ __("Thanks for signing up! Verify your email to activate your account.") }}
                    </p>
                </td>
            </tr>
        </table>
    </td>
</tr>

<tr>
    <td style="padding: 0 50px 40px 50px;">

        <p style="margin: 0 0 20px 0; color: #334155; font-size: 17px; line-height: 1.7; font-weight: 400;">
            {{ __("Hi there") . $user->name }} 👋
        </p>

        <p style="margin: 0 0 24px 0; color: #475569; font-size: 16px; line-height: 1.8;">
            {{ __("Thank you for creating an account with us! To complete your registration and start using your account, please verify your email address by clicking the button below.") }}
        </p>

        <table role="presentation" style="width: 100%; background-color: #f8fafc; border-radius: 14px; border: 1.5px solid #e2e8f0; margin: 32px 0;">
            <tr>
                <td style="padding: 28px;">
                    <h3 style="margin: 0 0 18px 0; color: #0f172a; font-size: 18px; font-weight: 700; font-family: 'DM Sans', sans-serif;">
                        {{ __('👤 Account Information') }}
                    </h3>

                    <table role="presentation" style="width: 100%;">

                        <tr>
                            <td style="padding: 12px 0; border-bottom: 1px solid #e2e8f0;">
                                <table role="presentation" style="width: 100%;">
                                    <tr>
                                        <td style="width: 40px; vertical-align: top;">
                                            <div style="width: 36px; height: 36px; background-color: #dbeafe; border-radius: 8px; text-align: center; line-height: 36px; font-size: 18px;">{{ __("👤") }}</div>
                                        </td>
                                        <td style="vertical-align: top; padding-left: 12px;">
                                            <span style="display: block; color: #64748b; font-size: 13px; font-weight: 500; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">{{ __("Name") }}</span>
                                            <span style="display: block; color: #1e293b; font-size: 16px; font-weight: 600;">{{ $user->name }}</span>
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
                                            <div style="width: 36px; height: 36px; background-color: #e0e7ff; border-radius: 8px; text-align: center; line-height: 36px; font-size: 18px;">{{ __('📧') }}</div>
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
                                            <div style="width: 36px; height: 36px; background-color: #fef3c7; border-radius: 8px; text-align: center; line-height: 36px; font-size: 18px;">{{ __("📅") }}</div>
                                        </td>
                                        <td style="vertical-align: top; padding-left: 12px;">
                                            <span style="display: block; color: #64748b; font-size: 13px; font-weight: 500; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">{{ __("Registered On") }}</span>
                                            <span style="display: block; color: #1e293b; font-size: 16px; font-weight: 600;">{{ optional($user->created_at)->format('F d, Y') }}</span>
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
                                            <div style="width: 36px; height: 36px; background-color: #fef3c7; border-radius: 8px; text-align: center; line-height: 36px; font-size: 18px;">{{ __("⏳") }}</div>
                                        </td>
                                        <td style="vertical-align: top; padding-left: 12px;">
                                            <span style="display: block; color: #64748b; font-size: 13px; font-weight: 500; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">{{ __("Status") }}</span>
                                            <span style="display: inline-block; background-color: #fef3c7; color: #92400e; font-size: 13px; font-weight: 700; padding: 6px 12px; border-radius: 6px; margin-top: 4px;">{{ __("⏳ PENDING VERIFICATION") }}</span>
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
                    <a href="{{ $verificationUrl }}" style="display: inline-block; background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); color: #ffffff; text-decoration: none; padding: 18px 48px; border-radius: 12px; font-size: 17px; font-weight: 700; letter-spacing: 0.3px; box-shadow: 0 10px 30px rgba(99, 102, 241, 0.3); font-family: 'DM Sans', sans-serif;">
                        {{ __("✓ Verify Email Address") }}
                    </a>
                </td>
            </tr>
        </table>

        <p style="margin: 32px 0 0 0; color: #64748b; font-size: 14px; line-height: 1.6; text-align: center;">
            {{ __("Or copy and paste this link into your browser:") }}<br>
            <a href="{{ $verificationUrl }}" style="color: #6366f1; text-decoration: none; font-weight: 500; word-break: break-all;">
                <strong>
                    {{ $verificationUrl }}
                </strong>
            </a>
        </p>

    </td>
</tr>

<tr>
    <td style="padding: 0 50px;">
        <div style="height: 1px; background: linear-gradient(90deg, transparent 0%, #e2e8f0 50%, transparent 100%);"></div>
    </td>
</tr>

<tr>
    <td style="padding: 0 50px 40px 50px;">
        <table role="presentation" style="width: 100%; background-color: #fef2f2; border-left: 4px solid #ef4444; border-radius: 8px;">
            <tr>
                <td style="padding: 24px;">
                    <h4 style="margin: 0 0 12px 0; color: #7f1d1d; font-size: 17px; font-weight: 700; font-family: 'DM Sans', sans-serif;">
                        {{ __("⚠️ Didn't Create This Account?") }}
                    </h4>
                    <p style="margin: 0; color: #991b1b; font-size: 15px; line-height: 1.7;">
                        {{ __("If you didn't sign up for an account, please ignore this email. The account will not be activated without email verification, and you won't receive any further emails from us.") }}
                    </p>
                </td>
            </tr>
        </table>
    </td>
</tr>

<tr>
    <td style="padding: 0 50px 40px 50px;">
        <table role="presentation" style="width: 100%; background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%); border-radius: 14px; border: 2px solid #fbbf24;">
            <tr>
                <td style="padding: 28px; text-align: center;">
                    <div style="display: inline-block; width: 48px; height: 48px; background-color: #f59e0b; border-radius: 50%; margin-bottom: 12px; position: relative;">
                        <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); color: #ffffff; font-size: 24px;">⏰</div>
                    </div>

                    <h3 style="margin: 0 0 8px 0; color: #78350f; font-size: 18px; font-weight: 700; font-family: 'DM Sans', sans-serif;">
                        {{ __("⏰ Link Expiry Notice") }}
                    </h3>
                    <p style="margin: 0; color: #92400e; font-size: 15px; line-height: 1.6;">
                        {{ __("This verification link will expire in") }}
                        <strong>
                            {{ $expiresIn . __(' minutes ') }}
                        </strong>
                        {{ __(". Please verify your email as soon as possible.") }}
                    </p>
                </td>
            </tr>
        </table>
    </td>
</tr>
@endsection
