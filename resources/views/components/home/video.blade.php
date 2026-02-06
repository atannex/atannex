<style>
    /* ============================================
   VIDEO PLAYER PROFESSIONAL STYLING
   ============================================ */

    /* Popup Hide Base */
    .mfp-hide {
        display: none !important;
    }

    /* Video Wrapper Container */
    .video-inline-wrapper {
        width: 100%;
        max-width: 1200px;
        margin: auto;
        display: block;
        padding: 20px;
        background: #000;
        border-radius: 16px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5);
        position: relative;
    }

    /* 🔒 Disable Right Click and Selection on Video */
    .video-inline-wrapper video {
        width: 100%;
        height: auto;
        display: block;
        border-radius: 12px;
        pointer-events: auto;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
        -webkit-user-drag: none;
        -moz-user-drag: none;
        -ms-user-drag: none;
        user-drag: none;
    }

    /* 🔒 Prevent Video Download Overlay Protection */
    .video-inline-wrapper::after {
        content: '';
        position: absolute;
        top: 20px;
        left: 20px;
        right: 20px;
        bottom: 20px;
        pointer-events: none;
        z-index: 1;
        border-radius: 12px;
    }

    .video-inline-wrapper .plyr {
        position: relative;
        z-index: 2;
    }

    /* Plyr Custom Styling */
    .video-inline-wrapper .plyr {
        border-radius: 12px;
        background: #000;
        overflow: hidden;
    }

    /* Plyr Video Background */
    .video-inline-wrapper .plyr--video {
        background: #000;
    }

    /* Large Center Play Button - FIXED (No shift on hover) */
    .video-inline-wrapper .plyr__control--overlaid {
        background: rgba(20, 20, 20, 0.95);
        color: #fff;
        padding: 24px;
        border-radius: 50%;
        transition: background 0.3s ease, box-shadow 0.3s ease;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
        transform-origin: center center;
    }

    .video-inline-wrapper .plyr__control--overlaid:hover {
        background: #fff;
        color: #000;
        box-shadow: 0 8px 40px rgba(0, 0, 0, 0.5);
        /* Removed transform: scale(1.15) to prevent shifting */
    }

    .video-inline-wrapper .plyr__control--overlaid svg {
        width: 32px;
        height: 32px;
        transition: none;
    }

    /* Plyr Controls Bar */
    .video-inline-wrapper .plyr__controls {
        background: linear-gradient(to top, rgba(0, 0, 0, 0.95), rgba(0, 0, 0, 0.7), transparent);
        padding: 25px 15px 15px;
        color: #fff;
    }

    .video-inline-wrapper .plyr__control {
        color: #fff;
        transition: all 0.2s ease;
    }

    .video-inline-wrapper .plyr__control:hover {
        background: rgba(255, 255, 255, 0.15);
    }

    .video-inline-wrapper .plyr__control.plyr__tab-focus {
        box-shadow: 0 0 0 3px rgba(255, 87, 51, 0.5);
    }

    /* 🔒 Hide Download Button (Multiple Selectors for Extra Security) */
    .video-inline-wrapper .plyr__controls button[data-plyr="download"],
    .video-inline-wrapper .plyr__controls [data-plyr="download"],
    .video-inline-wrapper button[data-plyr="download"],
    .video-inline-wrapper [data-plyr="download"],
    .plyr__control[data-plyr="download"] {
        display: none !important;
        visibility: hidden !important;
        opacity: 0 !important;
        pointer-events: none !important;
    }

    /* Progress Bar Styling */
    .video-inline-wrapper .plyr__progress input[type=range] {
        color: #ff5733;
    }

    .video-inline-wrapper .plyr__progress__buffer {
        background: rgba(255, 255, 255, 0.3);
    }

    .video-inline-wrapper .plyr__progress input[type=range]::-webkit-slider-thumb {
        background: #ff5733;
    }

    .video-inline-wrapper .plyr__progress input[type=range]::-moz-range-thumb {
        background: #ff5733;
    }

    /* Volume Control */
    .video-inline-wrapper .plyr__volume input[type=range] {
        color: #ff5733;
    }

    .video-inline-wrapper .plyr__volume input[type=range]::-webkit-slider-thumb {
        background: #ff5733;
    }

    .video-inline-wrapper .plyr__volume input[type=range]::-moz-range-thumb {
        background: #ff5733;
    }

    /* Time Display */
    .video-inline-wrapper .plyr__time {
        color: #fff;
        font-size: 14px;
        font-weight: 500;
        font-family: 'Poppins', sans-serif;
    }

    /* Progress Container */
    .video-inline-wrapper .plyr__progress__container {
        position: relative;
    }

    /* Poster/Thumbnail Image */
    .video-inline-wrapper .plyr__poster {
        background-size: cover;
        background-position: center;
        border-radius: 12px;
    }

    /* Loading Spinner */
    .video-inline-wrapper .plyr--loading .plyr__controls {
        display: none;
    }

    /* Buffering Indicator */
    .video-inline-wrapper .plyr__progress__buffer {
        transition: width 0.2s ease;
    }

    /* Settings Menu Styling */
    .video-inline-wrapper .plyr__menu {
        background: rgba(0, 0, 0, 0.95);
        border-radius: 8px;
        backdrop-filter: blur(10px);
    }

    .video-inline-wrapper .plyr__menu__container {
        background: transparent;
    }

    .video-inline-wrapper .plyr__menu__container [role=menu] {
        background: rgba(0, 0, 0, 0.9);
        border-radius: 8px;
    }

    .video-inline-wrapper .plyr__control[role=menuitemradio]:hover {
        background: rgba(255, 87, 51, 0.2);
    }

    /* Magnific Popup Background */
    .mfp-bg {
        background: #0b0b0b;
        opacity: 0.97;
        backdrop-filter: blur(5px);
    }

    /* Popup Content Container */
    .mfp-inline-holder .mfp-content {
        max-width: 1200px;
        margin: 0 auto;
    }

    /* Close Button Styling */
    .mfp-close {
        color: #fff;
        font-size: 44px;
        opacity: 0.8;
        transition: all 0.3s ease;
        background: rgba(255, 255, 255, 0.1);
        width: 44px;
        height: 44px;
        border-radius: 50%;
        line-height: 44px;
    }

    .mfp-close:hover {
        opacity: 1;
        transform: rotate(90deg);
        background: rgba(255, 87, 51, 0.8);
    }

    /* Custom Accent Color */
    .video-inline-wrapper .plyr--video .plyr__control.plyr__tab-focus,
    .video-inline-wrapper .plyr--video .plyr__control:hover,
    .video-inline-wrapper .plyr--video .plyr__control[aria-expanded=true] {
        background: rgba(255, 87, 51, 0.3);
    }

    .video-inline-wrapper .plyr__menu__container .plyr__control[role=menuitemradio][aria-checked=true]::before {
        background: #ff5733;
    }

    .video-inline-wrapper .plyr--full-ui input[type=range] {
        color: #ff5733;
    }

    /* Tooltip Styling */
    .video-inline-wrapper .plyr__tooltip {
        background: rgba(0, 0, 0, 0.9);
        color: #fff;
        border-radius: 4px;
        padding: 5px 10px;
        font-size: 13px;
        font-weight: 500;
    }

    /* Smooth Transitions */
    .video-inline-wrapper .plyr {
        transition: all 0.3s ease;
    }

    /* Popup Fade Animations */
    .mfp-fade.mfp-bg {
        opacity: 0;
        transition: all 0.4s ease-out;
    }

    .mfp-fade.mfp-bg.mfp-ready {
        opacity: 0.97;
    }

    .mfp-fade.mfp-bg.mfp-removing {
        opacity: 0;
    }

    .mfp-fade.mfp-wrap .mfp-content {
        opacity: 0;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        transform: scale(0.9) translateY(20px);
    }

    .mfp-fade.mfp-wrap.mfp-ready .mfp-content {
        opacity: 1;
        transform: scale(1) translateY(0);
    }

    .mfp-fade.mfp-wrap.mfp-removing .mfp-content {
        opacity: 0;
        transform: scale(0.9) translateY(20px);
    }

    /* ============================================
   🔒 SECURITY & DOWNLOAD PREVENTION
   ============================================ */

    /* Prevent text selection on entire player */
    .video-inline-wrapper,
    .video-inline-wrapper * {
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
    }

    /* Allow text selection only for time display */
    .video-inline-wrapper .plyr__time {
        -webkit-user-select: text;
        -moz-user-select: text;
        -ms-user-select: text;
        user-select: text;
    }

    /* Prevent drag on all elements */
    .video-inline-wrapper img,
    .video-inline-wrapper video {
        -webkit-user-drag: none;
        -moz-user-drag: none;
        -ms-user-drag: none;
        user-drag: none;
    }

    /* ============================================
   RESPONSIVE DESIGN
   ============================================ */

    /* Tablet Devices */
    @media (max-width: 768px) {
        .video-inline-wrapper {
            padding: 15px;
            border-radius: 12px;
        }

        .video-inline-wrapper .plyr__control--overlaid {
            padding: 18px;
        }

        .video-inline-wrapper .plyr__control--overlaid svg {
            width: 26px;
            height: 26px;
        }

        .video-inline-wrapper .plyr__controls {
            padding: 18px 10px 10px;
        }

        .mfp-close {
            font-size: 38px;
            width: 38px;
            height: 38px;
            line-height: 38px;
        }
    }

    /* Mobile Devices */
    @media (max-width: 576px) {
        .video-inline-wrapper {
            padding: 10px;
            border-radius: 8px;
        }

        .video-inline-wrapper .plyr {
            border-radius: 8px;
        }

        .mfp-close {
            font-size: 34px;
            width: 34px;
            height: 34px;
            line-height: 34px;
            right: 5px;
            top: 5px;
        }

        .video-inline-wrapper .plyr__control--overlaid {
            padding: 14px;
        }

        .video-inline-wrapper .plyr__control--overlaid svg {
            width: 22px;
            height: 22px;
        }

        .video-inline-wrapper .plyr__controls {
            padding: 12px 8px 8px;
        }

        .video-inline-wrapper .plyr__time {
            font-size: 12px;
        }
    }

    /* Extra Small Devices */
    @media (max-width: 375px) {
        .video-inline-wrapper {
            padding: 8px;
        }

        .mfp-close {
            font-size: 30px;
            width: 30px;
            height: 30px;
            line-height: 30px;
            right: 3px;
            top: 3px;
        }

        .video-inline-wrapper .plyr__control--overlaid {
            padding: 12px;
        }

        .video-inline-wrapper .plyr__control--overlaid svg {
            width: 20px;
            height: 20px;
        }
    }

    /* ============================================
   ACCESSIBILITY IMPROVEMENTS
   ============================================ */

    /* Focus visible for keyboard navigation */
    .video-inline-wrapper .plyr__control:focus-visible {
        outline: 2px solid #ff5733;
        outline-offset: 2px;
    }

    /* High contrast mode support */
    @media (prefers-contrast: high) {
        .video-inline-wrapper .plyr__controls {
            background: rgba(0, 0, 0, 1);
        }

        .video-inline-wrapper .plyr__control {
            border: 1px solid #222020;
        }
    }

    /* Reduced motion support */
    @media (prefers-reduced-motion: reduce) {

        .video-inline-wrapper .plyr,
        .video-inline-wrapper .plyr__control,
        .mfp-fade.mfp-wrap .mfp-content,
        .mfp-fade.mfp-bg {
            transition: none;
            animation: none;
        }
    }

</style>

@if($videos->isNotEmpty())
<div class="mb-4 space dark-theme bg-title-dark">
    <div class="container">
        <h2 class="sec-title has-line">
            {{ __('Latest Videos') }}
        </h2>
        <div class="row">
            <div class="col-xl-4 col-lg-2">
                <div class="blog-tab" data-asnavfor=".blog-tab-slide">

                    @foreach ($videos as $video)
                    <div class="tab-btn {{ $loop->first ? 'active' : '' }}">
                        <div class="blog-style2">
                            <div class="blog-img img-100">
                                <img src="{{ asset('storage/' . ($video->image ?? $video->post->image)) }}" alt="{{ $video->title ?? $video->post->title }}">

                                <a href="#video-popup-{{ $video->id }}" class="play-btn popup-video" aria-label="Play video">
                                    <i class="fas fa-play"></i>
                                </a>
                            </div>

                            <div class="blog-content">
                                <a href="{{ route('page.index', ['slug' => $video->post->category->slug_path]) }}" class="category" data-theme-color="{{ \App\Models\Others\Color::randomHex() }}">
                                    {{ $video->post->category->name }}
                                </a>

                                <h3 class="box-title-20">
                                    {{ Str::limit($video->title ?? $video->post->title, 55) }}
                                </h3>
                                <div class="blog-meta">
                                    <a href="{{ route('page.index', $video->post->published_at->format('Y/m')) }}">
                                        <i class="fal fa-calendar-days"></i>
                                        {{ ($video->published_at ?? $video->post->published_at)->format('d M, Y') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach

                </div>
            </div>
            <div class="col-xl-8 col-lg-10">
                <div class="blog-tab-slide th-carousel" data-slide-show="1" data-arrows="true" data-dots="false">
                    @foreach ($videos as $video)
                    <div>
                        <div class="blog-style8">
                            <div class="blog-img">
                                <img src="{{ asset('storage/' . ($video->image ?? $video->post->image)) }}" alt="{{ $video->title ?? $video->post->title }}" class="img-fluid">

                                <a href="#video-popup-{{ $video->id }}" class="play-btn popup-video" aria-label="Play {{ $video->title ?? $video->post->title }}">
                                    <i class="fas fa-play"></i>
                                </a>
                            </div>
                            <h3 class="box-title-30">
                                <a href="{{ route('page.index', ['slug' => $video->post->slug_path]) }}" class="hover-line">
                                    {{ Str::limit($video->title ?? $video->post->title, 56) }}
                                </a>
                            </h3>

                            <div class="blog-meta">
                                <a data-theme-color="{{ \App\Models\Others\Color::randomHex() }}" href="{{ route('page.index', ['slug' => $video->post->category->slug_path]) }}" class="category">
                                    {{ $video->post->category->name }}
                                </a>

                                <a href="{{ route('page.index', ['slug' => $video->post->author->user->slug]) }}">
                                    <i class="far fa-user"></i>
                                    By - {{ $video->post->author->user->name }}
                                </a>

                                <a href="{{ route('page.index', $video->post->published_at->format('Y/m')) }}">
                                    <i class="fal fa-calendar-days"></i>
                                    {{ ($video->published_at ?? $video->post->published_at)->format('d M, Y') }}
                                </a>
                            </div>
                        </div>
                    </div>
                    @endforeach

                </div>
            </div>

        </div>
    </div>
</div>
@foreach ($videos as $video)
<div id="video-popup-{{ $video->id }}" class="mfp-hide">
    <div class="video-inline-wrapper" data-video-title="{{ $video->title ?? $video->post->title }}">
        <video class="plyr-video" playsinline controlsList="nodownload noremoteplayback" disablePictureInPicture disableRemotePlayback preload="metadata" poster="{{ asset('storage/' . ($video->image ?? $video->post->image)) }}" data-video-id="{{ $video->id }}" data-plyr-config='{"title": "{{ addslashes($video->title ?? $video->post->title) }}"}' oncontextmenu="return false;" ondragstart="return false;" onselectstart="return false;">
            <source src="{{ asset('storage/' . $video->video_url) }}" type="video/mp4" size="720">
            {{ __('Your browser does not support the video tag.') }}
        </video>
    </div>
</div>
@endforeach
@endif
