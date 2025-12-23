@extends('components.layouts.guest')

@section('guest')

@php
$code = $code ?? 500;
$headline = $headline ?? __('Oops!');
$title = $title ?? __('Something went wrong');
$message = $message ?? __('An unexpected error occurred. Please try again.');
$imageLight = $imageLight ?? 'assets/img/theme-img/error.png';
$imageDark = $imageDark ?? 'assets/img/theme-img/error.png';

$buttonUrl = $buttonUrl ?? route('home');
$buttonText = $buttonText ?? __('Back To Home');
$buttonIcon = $buttonIcon ?? 'home';
@endphp

<div class="breadcumb-wrapper">
    <div class="container">
        <ul class="breadcumb-menu">
            <li>
                <a href="{{ route('home') }}">{{ __('Home') }}</a>
            </li>
            <li>{{ $code }} — {{ __('Error Page') }}</li>
        </ul>
    </div>
</div>

<section class="space2 error-page">
    <div class="container text-center">

        <div class="error-img" aria-hidden="true">
            <img class="light-img" src="{{ asset($imageLight) }}" alt="" loading="lazy">
            <img class="dark-img" src="{{ asset($imageDark) }}" alt="" loading="lazy">
        </div>

        <div class="error-content">
            <h2 class="error-title">
                <span class="text-theme">{{ $headline }}</span>
                {{ $title }}
            </h2>

            <p class="error-text">
                {{ $message }}
            </p>

            <a href="{{ $buttonUrl }}" class="th-btn">
                <i class="fal fa-{{ $buttonIcon }} me-2"></i>
                {{ $buttonText }}
            </a>
        </div>

    </div>
</section>
<style>
    /* Error page layout tuning */
    .error-page {
        padding-top: 2rem;
        /* reduces top whitespace */
    }

    /* Move image upward */
    .error-img {
        margin-top: -2.5rem;
        /* pulls image upward */
        margin-bottom: 1.5rem;
        /* tighter gap before text */
    }

    /* Image sizing (kept responsive) */
    .error-img img {
        max-width: 280px;
        width: 100%;
        height: auto;
        display: block;
        margin-inline: auto;
    }

    /* Responsive scaling */
    @media (min-width: 768px) {
        .error-img {
            margin-top: -3rem;
        }

        .error-img img {
            max-width: 360px;
        }
    }

    @media (min-width: 1200px) {
        .error-img {
            margin-top: -3.5rem;
        }

        .error-img img {
            max-width: 420px;
        }
    }

</style>
@endsection
