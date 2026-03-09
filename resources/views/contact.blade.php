@extends('components.layouts.guest')

@section('title', seo_title('Contact Atannex | Get in Touch'))

@section('guest')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;600&family=DM+Sans:wght@300;400;500;600&display=swap');

    :root {
        --form-bg: #0f1117;
        --card-bg: #16181f;
        --card-border: rgba(255, 255, 255, 0.07);
        --input-bg: #1c1f2a;
        --input-border: rgba(255, 255, 255, 0.1);
        --input-focus: #c9a96e;
        --accent: #c9a96e;
        --accent-soft: rgba(201, 169, 110, 0.12);
        --text-primary: #f0ede8;
        --text-muted: #7a7d8a;
        --text-label: #a0a3b1;
        --success: #4caf82;
        --error: #e07070;
        --radius: 14px;
        --transition: 0.22s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .contact-card-wrapper {
        font-family: 'DM Sans', sans-serif;
    }

    .quote-form-box {
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        border-radius: 24px;
        overflow: hidden;
        box-shadow:
            0 0 0 1px rgba(255, 255, 255, 0.03),
            0 32px 80px rgba(0, 0, 0, 0.5),
            0 8px 24px rgba(0, 0, 0, 0.3);
        position: relative;
    }

    /* Ambient top glow */
    .quote-form-box::before {
        content: '';
        position: absolute;
        top: -1px;
        left: 50%;
        transform: translateX(-50%);
        width: 60%;
        height: 1px;
        background: linear-gradient(90deg, transparent, var(--accent), transparent);
        opacity: 0.6;
    }

    /* Card Header */
    .quote-form-box .card-header {
        background: linear-gradient(160deg, #1e2130 0%, #151820 100%);
        border-bottom: 1px solid var(--card-border);
        padding: 2.5rem 2.5rem 2rem !important;
        position: relative;
        overflow: hidden;
    }

    .quote-form-box .card-header::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 80px;
        background: radial-gradient(ellipse at 50% 100%, rgba(201, 169, 110, 0.07) 0%, transparent 70%);
        pointer-events: none;
    }

    .quote-form-box .card-header img {
        filter: brightness(1.05);
        max-height: 48px;
        width: auto;
    }

    .quote-form-box .form-title {
        font-family: 'Playfair Display', serif;
        font-size: 1.75rem;
        font-weight: 600;
        color: var(--text-primary);
        letter-spacing: -0.02em;
        margin-top: 0.75rem !important;
    }

    .quote-form-box .form-description {
        font-size: 0.9rem;
        color: var(--text-muted) !important;
        font-weight: 300;
        line-height: 1.6;
        max-width: 340px;
        margin: 0 auto;
    }

    .security-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: rgba(76, 175, 130, 0.1);
        border: 1px solid rgba(76, 175, 130, 0.2);
        border-radius: 100px;
        padding: 5px 14px;
        font-size: 0.78rem;
        color: var(--success) !important;
        font-weight: 500;
        letter-spacing: 0.02em;
    }

    .security-badge i {
        font-size: 0.75rem;
    }

    /* Form Body */
    .contact-form {
        padding: 2rem 2.5rem 2.5rem;
    }

    .contact-form .row {
        gap: 0;
        row-gap: 0;
    }

    /* Form Groups */
    .contact-form .form-group {
        padding: 0 8px;
        margin-bottom: 1.1rem;
        position: relative;
    }

    /* Inputs & Selects */
    .contact-form .form-control,
    .contact-form .form-select {
        background: var(--input-bg);
        border: 1.5px solid var(--input-border);
        border-radius: var(--radius);
        color: var(--text-primary);
        font-family: 'DM Sans', sans-serif;
        font-size: 0.92rem;
        font-weight: 400;
        padding: 0.85rem 1.1rem;
        transition:
            border-color var(--transition),
            background var(--transition),
            box-shadow var(--transition);
        width: 100%;
        outline: none;
        -webkit-appearance: none;
    }

    .contact-form .form-control::placeholder {
        color: var(--text-muted);
        font-weight: 300;
    }

    .contact-form .form-control:focus,
    .contact-form .form-select:focus {
        border-color: var(--accent);
        background: #1f2232;
        box-shadow: 0 0 0 4px rgba(201, 169, 110, 0.1), 0 2px 8px rgba(0, 0, 0, 0.2);
        color: var(--text-primary);
    }

    /* Select styling */
    .contact-form .form-select {
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8' viewBox='0 0 12 8'%3E%3Cpath d='M1 1l5 5 5-5' stroke='%237a7d8a' stroke-width='1.5' fill='none' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 1rem center;
        padding-right: 2.5rem;
        cursor: pointer;
    }

    .contact-form .form-select option {
        background: #1c1f2a;
        color: var(--text-primary);
    }

    /* Textarea */
    .contact-form textarea.form-control {
        resize: none;
        min-height: 110px;
        line-height: 1.65;
    }

    /* Input label animation wrapper */
    .contact-form .form-group .field-wrapper {
        position: relative;
    }

    .contact-form .form-group .field-icon {
        position: absolute;
        right: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: var(--text-muted);
        font-size: 0.85rem;
        pointer-events: none;
        transition: color var(--transition);
    }

    .contact-form .form-group:focus-within .field-icon {
        color: var(--accent);
    }

    /* Error messages */
    .contact-form .text-danger {
        display: block;
        font-size: 0.78rem;
        color: var(--error) !important;
        margin-top: 5px;
        padding-left: 4px;
        font-weight: 400;
    }

    /* Submit Button */
    .contact-form .form-btn {
        padding: 0 8px;
        margin-top: 0.5rem;
    }

    .contact-form .th-btn {
        background: linear-gradient(135deg, #c9a96e 0%, #b8924f 100%);
        border: none;
        border-radius: var(--radius);
        color: #0f1117;
        font-family: 'DM Sans', sans-serif;
        font-size: 0.95rem;
        font-weight: 600;
        letter-spacing: 0.03em;
        padding: 1rem 2rem;
        cursor: pointer;
        transition:
            transform var(--transition),
            box-shadow var(--transition),
            filter var(--transition);
        position: relative;
        overflow: hidden;
    }

    .contact-form .th-btn::before {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.15) 0%, transparent 60%);
        opacity: 0;
        transition: opacity var(--transition);
    }

    .contact-form .th-btn:hover:not(:disabled) {
        transform: translateY(-2px);
        box-shadow: 0 8px 28px rgba(201, 169, 110, 0.35);
        filter: brightness(1.05);
    }

    .contact-form .th-btn:hover::before {
        opacity: 1;
    }

    .contact-form .th-btn:active:not(:disabled) {
        transform: translateY(0);
        box-shadow: 0 3px 10px rgba(201, 169, 110, 0.2);
    }

    .contact-form .th-btn:disabled {
        opacity: 0.65;
        cursor: not-allowed;
    }

    .contact-form .th-btn i {
        font-size: 0.85rem;
        transition: transform var(--transition);
    }

    .contact-form .th-btn:hover i {
        transform: translate(2px, -2px);
    }

    /* Loading spinner in button */
    .contact-form .th-btn [wire\:loading] {
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .contact-form .th-btn [wire\:loading]::before {
        content: '';
        width: 16px;
        height: 16px;
        border: 2px solid rgba(15, 17, 23, 0.3);
        border-top-color: #0f1117;
        border-radius: 50%;
        animation: spin 0.7s linear infinite;
        display: inline-block;
    }

    @keyframes spin {
        to {
            transform: rotate(360deg);
        }
    }

    /* Success Alert */
    .alert-success {
        background: rgba(76, 175, 130, 0.1);
        border: 1px solid rgba(76, 175, 130, 0.25);
        border-radius: var(--radius);
        color: #4caf82;
        font-size: 0.88rem;
        font-weight: 500;
        padding: 0.85rem 1.25rem;
        margin: 0 2.5rem 1.75rem;
        display: flex;
        align-items: center;
        gap: 8px;
        animation: slideUp 0.35s ease;
    }

    .alert-success::before {
        content: '✓';
        width: 20px;
        height: 20px;
        background: var(--success);
        color: #fff;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.7rem;
        flex-shrink: 0;
        font-weight: 700;
    }

    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(8px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Divider between header & form */
    .form-divider {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 1.75rem 2.5rem 0;
        margin-bottom: -0.5rem;
    }

    .form-divider span {
        font-size: 0.75rem;
        color: var(--text-muted);
        font-weight: 500;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        white-space: nowrap;
    }

    .form-divider hr {
        flex: 1;
        border: none;
        border-top: 1px solid var(--card-border);
        margin: 0;
    }

    /* Auth identity card */
    .auth-identity-card {
        background: linear-gradient(135deg, rgba(201, 169, 110, 0.06) 0%, rgba(201, 169, 110, 0.02) 100%);
        border: 1px solid rgba(201, 169, 110, 0.18);
        border-radius: var(--radius);
        padding: 1rem 1.25rem;
        margin: 0 8px 1.1rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        position: relative;
        overflow: hidden;
    }

    .auth-identity-card::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 3px;
        background: linear-gradient(180deg, var(--accent), transparent);
        border-radius: 0 2px 2px 0;
    }

    .auth-identity-avatar {
        width: 42px;
        height: 42px;
        background: var(--accent-soft);
        border: 1.5px solid rgba(201, 169, 110, 0.3);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--accent);
        font-size: 1rem;
        flex-shrink: 0;
    }

    .auth-identity-info {
        flex: 1;
        min-width: 0;
    }

    .auth-identity-name {
        font-size: 0.92rem;
        font-weight: 600;
        color: var(--text-primary);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        line-height: 1.3;
    }

    .auth-identity-meta {
        font-size: 0.78rem;
        color: var(--text-muted);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        margin-top: 2px;
    }

    .auth-identity-meta span {
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    .auth-identity-meta span+span::before {
        content: '·';
        margin: 0 4px;
        opacity: 0.4;
    }

    .auth-identity-badge {
        font-size: 0.7rem;
        font-weight: 600;
        letter-spacing: 0.06em;
        color: var(--accent);
        background: rgba(201, 169, 110, 0.1);
        border: 1px solid rgba(201, 169, 110, 0.2);
        border-radius: 100px;
        padding: 2px 9px;
        white-space: nowrap;
        flex-shrink: 0;
        text-transform: uppercase;
    }

    /* File Upload Drop Zone */
    .file-upload-zone {
        position: relative;
        border: 1.5px dashed var(--input-border);
        border-radius: var(--radius);
        background: var(--input-bg);
        padding: 1.5rem 1.25rem;
        text-align: center;
        cursor: pointer;
        transition:
            border-color var(--transition),
            background var(--transition),
            box-shadow var(--transition);
        overflow: hidden;
    }

    .file-upload-zone:hover,
    .file-upload-zone.drag-over {
        border-color: var(--accent);
        background: #1f2232;
        box-shadow: 0 0 0 4px rgba(201, 169, 110, 0.08);
    }

    .file-upload-zone input[type="file"] {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
        opacity: 0;
        cursor: pointer;
        z-index: 2;
    }

    .file-upload-icon-wrap {
        width: 44px;
        height: 44px;
        background: var(--accent-soft);
        border: 1.5px solid rgba(201, 169, 110, 0.25);
        border-radius: 12px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: var(--accent);
        font-size: 1.1rem;
        margin-bottom: 0.75rem;
        transition: transform var(--transition), background var(--transition);
    }

    .file-upload-zone:hover .file-upload-icon-wrap {
        transform: translateY(-2px);
        background: rgba(201, 169, 110, 0.18);
    }

    .file-upload-title {
        font-size: 0.88rem;
        font-weight: 600;
        color: var(--text-primary);
        line-height: 1.4;
    }

    .file-upload-title span {
        color: var(--accent);
        text-decoration: underline;
        text-underline-offset: 2px;
    }

    .file-upload-hint {
        font-size: 0.76rem;
        color: var(--text-muted);
        margin-top: 4px;
        font-weight: 300;
    }

    .file-upload-progress {
        margin-top: 0.75rem;
        background: rgba(201, 169, 110, 0.1);
        border-radius: 100px;
        height: 3px;
        overflow: hidden;
    }

    .file-upload-progress-bar {
        height: 100%;
        width: 60%;
        background: linear-gradient(90deg, var(--accent), #e8c98a);
        border-radius: 100px;
        animation: progressPulse 1.2s ease-in-out infinite alternate;
    }

    @keyframes progressPulse {
        from {
            width: 20%;
            opacity: 0.6;
        }

        to {
            width: 85%;
            opacity: 1;
        }
    }

    .file-uploading-label {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        font-size: 0.8rem;
        color: var(--accent);
        font-weight: 500;
        margin-top: 0.6rem;
    }

    .file-uploading-label::before {
        content: '';
        width: 12px;
        height: 12px;
        border: 1.5px solid rgba(201, 169, 110, 0.3);
        border-top-color: var(--accent);
        border-radius: 50%;
        animation: spin 0.7s linear infinite;
        display: inline-block;
        flex-shrink: 0;
    }

    .file-selected-chip {
        display: flex;
        align-items: center;
        gap: 10px;
        background: rgba(76, 175, 130, 0.08);
        border: 1px solid rgba(76, 175, 130, 0.22);
        border-radius: 10px;
        padding: 0.6rem 0.9rem;
        margin-top: 0.75rem;
        animation: slideUp 0.25s ease;
    }

    .file-selected-chip-icon {
        width: 28px;
        height: 28px;
        background: rgba(76, 175, 130, 0.12);
        border-radius: 7px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--success);
        font-size: 0.75rem;
        flex-shrink: 0;
    }

    .file-selected-chip-name {
        flex: 1;
        font-size: 0.82rem;
        color: var(--success);
        font-weight: 500;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        min-width: 0;
    }

    .file-selected-chip-check {
        color: var(--success);
        font-size: 0.78rem;
        flex-shrink: 0;
        opacity: 0.8;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .quote-form-box .card-header {
            padding: 2rem 1.5rem 1.5rem !important;
        }

        .contact-form {
            padding: 1.5rem 1.25rem 2rem;
        }

        .form-divider {
            padding: 1.5rem 1.25rem 0;
        }

        .alert-success {
            margin: 0 1.25rem 1.5rem;
        }

        .contact-form .form-group {
            padding: 0 4px;
        }

        .contact-form .form-btn {
            padding: 0 4px;
        }

        .auth-identity-card {
            margin: 0 4px 1.1rem;
        }

        .auth-identity-badge {
            display: none;
        }
    }

</style>
<div class="space2">
    <div class="container">
        <div class="row">
            <div class="col-xl-5">
                <div class="mb-40 text-center pe-xxl-4 me-xl-3 text-xl-start mb-lg-0">
                    <div class="mb-32 title-area">
                        <h2 class="sec-title2">
                            {{ __('Get in Touch') }}
                        </h2>
                        <p class="sec-text">
                            {{__('Reach out with questions, ideas, or collaborations.')}}
                        </p>
                    </div>

                    <div class="contact-feature-wrap">
                        @foreach($infos as $feature)
                        <div class="contact-feature">
                            <div class="box-icon">
                                <img src="{{ asset('storage/' . $feature['icon']) }}" alt="{{ $feature['title'] }} icon">
                            </div>
                            <div class="box-content">
                                <h3 class="box-title-22">
                                    {{ $feature['title'] }}
                                </h3>
                                <p class="box-text">
                                    @foreach($feature['items'] as $item)
                                    @switch($item['type'])
                                    @case('email')
                                    <a href="mailto:{{ $item['value'] }}">
                                        {{ $item['value'] }}
                                    </a>
                                    @break
                                    @case('phone')
                                    <a href="tel:{{ $item['value'] }}">
                                        {{ $item['label'] ?? $item['value'] }}
                                    </a>
                                    @break
                                    @default
                                    {{ $item['value'] }}
                                    @endswitch
                                    @if(!$loop->last)
                                    @endif
                                    @endforeach
                                </p>
                            </div>
                        </div>
                        @endforeach
                    </div>

                </div>
            </div>

            <livewire:forms.contact :subjects="$subjects" />

        </div>
    </div>
</div>
<iframe src="{{ $map }}" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>

@endsection
