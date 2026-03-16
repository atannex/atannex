<link rel="stylesheet" href="https://cdn.plyr.io/3.6.8/plyr.css" />

{{-- Google Fonts --}}
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=DM+Mono:wght@400;500&family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet">

<style>
    #vp-root {
        --vp-bg: #0a0c10;
        --vp-surface: #10131a;
        --vp-border: rgba(255, 255, 255, .07);
        --vp-accent: #e8b84b;
        --vp-accent-dim: rgba(232, 184, 75, .18);
        --vp-text: #f0f2f7;
        --vp-muted: #6b7280;
        --vp-radius: 14px;

        --plyr-color-main: var(--vp-accent);
        --plyr-video-control-color: var(--vp-text);
        --plyr-menu-background: #1a1d26;
        --plyr-menu-color: var(--vp-text);
        --plyr-menu-border-color: var(--vp-border);
        --plyr-tooltip-background: #1a1d26;
        --plyr-tooltip-color: var(--vp-text);
        --plyr-range-fill-background: var(--vp-accent);
        --plyr-video-progress-buffered-background: rgba(255, 255, 255, .15);
        --plyr-range-thumb-background: var(--vp-accent);
        --plyr-range-thumb-shadow: 0 0 0 3px var(--vp-accent-dim);
        --plyr-control-radius: 6px;
        --plyr-control-spacing: 12px;
        --plyr-font-family: 'DM Sans', system-ui, sans-serif;
        --plyr-font-size-base: 13px;

        position: relative;
        margin-bottom: 40px;
        border-radius: var(--vp-radius);
        overflow: hidden;
        background: var(--vp-bg);
        box-shadow: 0 32px 80px rgba(0, 0, 0, .65),
            0 0 0 1px var(--vp-border),
            0 0 60px rgba(232, 184, 75, .12);
    }

    #vp-root::before,
    #vp-root::after {
        content: '';
        position: absolute;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(90deg, transparent 0%, var(--vp-accent) 30%, var(--vp-accent) 70%, transparent 100%);
        opacity: .55;
        z-index: 30;
        pointer-events: none;
    }

    #vp-root::before {
        top: 0;
    }

    #vp-root::after {
        bottom: 0;
    }

    #vp-root .vp-header {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 13px 18px;
        background: var(--vp-surface);
        border-bottom: 1px solid var(--vp-border);
        position: relative;
        z-index: 2;
    }

    #vp-root .vp-header__dots {
        display: flex;
        gap: 6px;
        flex-shrink: 0;
    }

    #vp-root .vp-header__dots span {
        display: block;
        width: 10px;
        height: 10px;
        border-radius: 50%;
    }

    #vp-root .vp-header__dots span:nth-child(1) {
        background: #ff5f57;
    }

    #vp-root .vp-header__dots span:nth-child(2) {
        background: #febc2e;
    }

    #vp-root .vp-header__dots span:nth-child(3) {
        background: #28c840;
    }

    #vp-root .vp-header__label {
        flex: 1;
        display: flex;
        align-items: center;
        gap: 7px;
        overflow: hidden;
        font-size: 12px;
        color: var(--vp-muted);
        font-family: 'DM Mono', monospace;
        letter-spacing: .04em;
    }

    #vp-root .vp-header__label svg {
        flex-shrink: 0;
        color: var(--vp-accent);
    }

    #vp-root .vp-header__filename {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        color: var(--vp-text);
    }

    #vp-root .vp-header__badge {
        flex-shrink: 0;
        padding: 2px 8px;
        border-radius: 20px;
        font-size: 10px;
        font-weight: 600;
        letter-spacing: .07em;
        text-transform: uppercase;
        background: var(--vp-accent-dim);
        color: var(--vp-accent);
        border: 1px solid rgba(232, 184, 75, .3);
    }

    #vp-root .vp-header__quality {
        flex-shrink: 0;
        font-size: 11px;
        font-family: 'DM Mono', monospace;
        color: var(--vp-muted);
    }

    #vp-root .vp-player-wrap {
        position: relative;
        background: #000;
    }

    #vp-root .vp-player-wrap::before {
        content: '';
        position: absolute;
        inset: -20px;
        pointer-events: none;
        z-index: 0;
        background: radial-gradient(ellipse at 50% 50%, rgba(232, 184, 75, .08) 0%, transparent 70%);
    }

    #vp-root .vp-player-wrap .plyr {
        position: relative;
        z-index: 1;
        border-radius: 0;
        --plyr-video-background: #000;
    }

    #vp-root .vp-player-wrap .plyr__time {
        font-family: 'DM Mono', monospace;
        font-size: 12px;
        letter-spacing: .04em;
    }

    #vp-root .vp-player-wrap .plyr__volume {
        max-width: 90px;
    }

    #vp-root .vp-player-wrap .plyr--full-ui input[type=range] {
        color: var(--vp-accent);
    }

    #vp-root .vp-player-wrap .plyr--video .plyr__controls {
        background: linear-gradient(to top, rgba(0, 0, 0, .92) 0%, rgba(0, 0, 0, .6) 60%, transparent 100%);
        padding: 20px 16px 14px;
        border-radius: 0;
    }

    #vp-root .vp-player-wrap .plyr--video .plyr__control:hover,
    #vp-root .vp-player-wrap .plyr--video .plyr__control[aria-expanded=true] {
        background: var(--vp-accent-dim);
        color: var(--vp-accent);
    }

    #vp-root .vp-player-wrap .plyr--video .plyr__control--overlaid {
        background: var(--vp-accent);
        box-shadow: 0 0 0 5px var(--vp-accent-dim), 0 8px 30px rgba(232, 184, 75, .35);
        width: 68px;
        height: 68px;
        transition: transform .2s ease, box-shadow .2s ease;
    }

    #vp-root .vp-player-wrap .plyr--video .plyr__control--overlaid:hover {
        background: #f0c75a;
        transform: scale(1.08);
        box-shadow: 0 0 0 8px var(--vp-accent-dim), 0 12px 40px rgba(232, 184, 75, .5);
    }

    #vp-root .vp-player-wrap .plyr__control--overlaid svg {
        width: 24px;
        height: 24px;
    }

    #vp-root .vp-loading {
        position: absolute;
        inset: 0;
        z-index: 20;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 16px;
        background: var(--vp-bg);
        transition: opacity .35s ease, visibility .35s ease;
    }

    #vp-root .vp-loading.hidden {
        opacity: 0;
        visibility: hidden;
        pointer-events: none;
    }

    #vp-root .vp-loading__spinner {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        border: 3px solid rgba(255, 255, 255, .12);
        border-top-color: var(--vp-accent);
        animation: vp-spin 0.9s linear infinite;
    }

    #vp-root .vp-loading__text {
        font-size: 13px;
        color: var(--vp-muted);
        font-family: 'DM Mono', monospace;
        letter-spacing: .06em;
    }

    @keyframes vp-spin {
        to {
            transform: rotate(360deg);
        }
    }

    #vp-root .vp-footer {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 6px 16px;
        padding: 10px 18px;
        background: var(--vp-surface);
        border-top: 1px solid var(--vp-border);
        position: relative;
        z-index: 2;
    }

    #vp-root .vp-footer__stat {
        display: flex;
        align-items: center;
        gap: 5px;
        font-size: 11.5px;
        color: var(--vp-muted);
        font-family: 'DM Mono', monospace;
        letter-spacing: .03em;
    }

    #vp-root .vp-footer__stat svg {
        opacity: .6;
    }

    #vp-root .vp-footer__divider {
        width: 1px;
        height: 14px;
        background: var(--vp-border);
    }

    #vp-root .vp-footer__spacer {
        flex: 1;
    }

    #vp-root .vp-footer__resolutions {
        display: flex;
        align-items: center;
        gap: 4px;
    }

    #vp-root .vp-res-btn {
        padding: 2px 7px;
        border-radius: 4px;
        font-size: 10.5px;
        font-weight: 600;
        font-family: 'DM Mono', monospace;
        border: 1px solid var(--vp-border);
        background: transparent;
        color: var(--vp-muted);
        cursor: pointer;
        transition: all .15s;
        outline: none;
        -webkit-appearance: none;
    }

    #vp-root .vp-res-btn:hover,
    #vp-root .vp-res-btn.active {
        border-color: var(--vp-accent);
        color: var(--vp-accent);
        background: var(--vp-accent-dim);
    }

    #vp-root .vp-res-btn:focus-visible {
        outline: 2px solid var(--vp-accent);
        outline-offset: 2px;
    }

    #vp-root .plyr--fullscreen-active,
    #vp-root .plyr:-webkit-full-screen {
        border-radius: 0;
    }

    @media (max-width: 575px) {

        #vp-root .vp-header__quality,
        #vp-root .vp-footer__divider,
        #vp-root .vp-footer__stat:not(:first-child) {
            display: none;
        }

        #vp-root .vp-footer {
            padding: 8px 12px;
        }

        #vp-root .vp-header {
            padding: 10px 12px;
        }

        #vp-root .vp-player-wrap .plyr--video .plyr__control--overlaid {
            width: 54px;
            height: 54px;
        }

        #vp-root .vp-player-wrap .plyr__volume {
            display: none;
        }
    }

    @media (max-width: 400px) {
        #vp-root .vp-header__dots {
            display: none;
        }

        #vp-root .vp-res-btn {
            padding: 2px 5px;
            font-size: 9.5px;
        }
    }

</style>

<div id="vp-root" role="region" aria-label="Video player">
    <div class="vp-header">
        <div class="vp-header__dots" aria-hidden="true">
            <span></span><span></span><span></span>
        </div>
        <div class="vp-header__label">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <rect x="2" y="2" width="20" height="20" rx="2.18" ry="2.18" />
                <line x1="7" y1="2" x2="7" y2="22" />
                <line x1="17" y1="2" x2="17" y2="22" />
                <line x1="2" y1="12" x2="22" y2="12" />
                <line x1="2" y1="7" x2="7" y2="7" />
                <line x1="17" y1="7" x2="22" y2="7" />
                <line x1="17" y1="17" x2="22" y2="17" />
                <line x1="2" y1="17" x2="7" y2="17" />
            </svg>
            <span class="vp-header__filename">{{ $module->post->title ?? 'media-stream' }}</span>
        </div>
        <span class="vp-header__badge">HD</span>
        <span class="vp-header__quality" id="vp-qual-label">1080p</span>
    </div>

    <div class="vp-player-wrap">
        <div class="hidden vp-loading" id="vp-loading" role="status" aria-live="polite" aria-label="Loading video">
            <div class="vp-loading__spinner" aria-hidden="true"></div>
            <span class="vp-loading__text" id="vp-loading-text">Initializing stream…</span>
        </div>

        <video id="vp-video" controls playsinline preload="metadata" poster="{{ asset('storage/'.$module->post->image) }}" aria-label="{{ $module->post->title ?? 'Video' }}">
            <!-- Replace these with your actual video sources -->
            <source src="https://cdn.plyr.io/static/demo/View_From_A_Blue_Moon_Trailer-576p.mp4" type="video/mp4" size="576">
            <source src="https://cdn.plyr.io/static/demo/View_From_A_Blue_Moon_Trailer-720p.mp4" type="video/mp4" size="720">
            <source src="https://cdn.plyr.io/static/demo/View_From_A_Blue_Moon_Trailer-1080p.mp4" type="video/mp4" size="1080">
            <track kind="captions" label="English" srclang="en" src="https://cdn.plyr.io/static/demo/View_From_A_Blue_Moon_Trailer-HD.en.vtt" default>
            <track kind="captions" label="Français" srclang="fr" src="https://cdn.plyr.io/static/demo/View_From_A_Blue_Moon_Trailer-HD.fr.vtt">
            Your browser does not support the video tag.
        </video>
    </div>

    <div class="vp-footer" aria-label="Video metadata">
        <span class="vp-footer__stat" aria-label="Duration">
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <circle cx="12" cy="12" r="10" />
                <polyline points="12 6 12 12 16 14" />
            </svg>
            <span id="vp-duration">—:——</span>
        </span>
        <div class="vp-footer__divider" aria-hidden="true"></div>
        <span class="vp-footer__stat" aria-label="Format">
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <polyline points="22 12 18 12 15 21 9 3 6 12 2 12" />
            </svg>
            MP4 / H.264
        </span>
        <div class="vp-footer__divider" aria-hidden="true"></div>
        <span class="vp-footer__stat" aria-label="Closed captions available">
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <rect x="2" y="7" width="20" height="14" rx="2" ry="2" />
                <path d="M16 7V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v2" />
            </svg>
            CC · EN / FR
        </span>
        <div class="vp-footer__spacer" aria-hidden="true"></div>
        <div class="vp-footer__resolutions" role="group" aria-label="Select quality">
            <button class="vp-res-btn" type="button" data-size="576">576p</button>
            <button class="vp-res-btn" type="button" data-size="720">720p</button>
            <button class="vp-res-btn active" type="button" data-size="1080">1080p</button>
        </div>
    </div>
</div>

{{-- Plyr JS --}}
<script src="https://cdn.plyr.io/3.6.8/plyr.polyfilled.js"></script>

<script>
    (function() {
        'use strict';

        const ROOT = document.getElementById('vp-root');
        const video = document.getElementById('vp-video');
        const loading = document.getElementById('vp-loading');
        const loadingText = document.getElementById('vp-loading-text');
        const durationEl = document.getElementById('vp-duration');
        const qualLabel = document.getElementById('vp-qual-label');
        const resButtons = ROOT.querySelectorAll('.vp-res-btn');

        // ── Replace with your real video URLs ───────────────────────────────
        const SOURCES = {
            576: 'https://cdn.plyr.io/static/demo/View_From_A_Blue_Moon_Trailer-576p.mp4'
            , 720: 'https://cdn.plyr.io/static/demo/View_From_A_Blue_Moon_Trailer-720p.mp4'
            , 1080: 'https://cdn.plyr.io/static/demo/View_From_A_Blue_Moon_Trailer-1080p.mp4'
        };

        let isSwitching = false;
        let currentQuality = 1080;

        function fmtTime(seconds) {
            if (!Number.isFinite(seconds) || seconds < 0) return '—:——';
            const m = Math.floor(seconds / 60);
            const s = Math.floor(seconds % 60);
            return `${m.toString().padStart(2,'0')}:${s.toString().padStart(2,'0')}`;
        }

        function showLoading(message = 'Loading…') {
            if (loadingText) loadingText.textContent = message;
            loading.classList.remove('hidden');
        }

        function hideLoading() {
            loading.classList.add('hidden');
        }

        function syncQualityUI(quality) {
            currentQuality = quality;
            if (qualLabel) qualLabel.textContent = `${quality}p`;

            resButtons.forEach(btn => {
                const q = Number(btn.dataset.size);
                const isActive = q === quality;
                btn.classList.toggle('active', isActive);
                btn.setAttribute('aria-pressed', isActive ? 'true' : 'false');
            });
        }

        function switchQuality(newQuality, fromPlyr = false) {
            if (isSwitching || newQuality === currentQuality) return;
            isSwitching = true;

            const previousTime = video.currentTime;
            const wasPlaying = !video.paused && !video.ended;

            showLoading(`Switching to ${newQuality}p…`);

            video.src = SOURCES[newQuality];
            video.load();

            syncQualityUI(newQuality);

            const onCanPlay = () => {
                video.removeEventListener('canplay', onCanPlay);

                if (previousTime > 0 && previousTime < video.duration) {
                    video.currentTime = previousTime;
                }

                if (wasPlaying) {
                    video.play().catch(() => {});
                }

                durationEl.textContent = fmtTime(video.duration);
                hideLoading();
                isSwitching = false;
            };

            video.addEventListener('canplay', onCanPlay);

            // Safety fallback
            setTimeout(() => {
                if (isSwitching) {
                    isSwitching = false;
                    hideLoading();
                }
            }, 12000);

            if (!fromPlyr) {
                player.quality = newQuality;
            }
        }

        // ── Initialize Plyr ─────────────────────────────────────────────────
        const player = new Plyr(video, {
            controls: [
                'play-large', 'rewind', 'play', 'fast-forward', 'progress'
                , 'current-time', 'duration', 'mute', 'volume', 'captions'
                , 'settings', 'pip', 'airplay', 'fullscreen'
            ]
            , settings: ['captions', 'quality', 'speed', 'loop']
            , speed: {
                selected: 1
                , options: [0.5, 0.75, 1, 1.25, 1.5, 2]
            }
            , captions: {
                active: true
                , update: true
            }
            , quality: {
                default: 1080
                , options: [576, 720, 1080]
                , forced: true
                , onChange: quality => {
                    if (isSwitching) return;
                    switchQuality(quality, true);
                }
            }
            , keyboard: {
                focused: true
                , global: false
            }
            , tooltips: {
                controls: true
                , seek: true
            }
            , invertTime: false
            , ratio: '16:9'
            , blankVideo: 'https://cdn.plyr.io/static/blank.mp4'
        });

        // ── Initial load handling ───────────────────────────────────────────
        function attemptEarlyHide() {
            if (video.readyState >= 2) {
                hideLoading();
                durationEl.textContent = fmtTime(video.duration);
            }
        }

        video.addEventListener('loadedmetadata', () => {
            durationEl.textContent = fmtTime(video.duration);
            attemptEarlyHide();
        }, {
            once: true
        });

        video.addEventListener('canplay', attemptEarlyHide, {
            once: true
        });

        player.on('ready', attemptEarlyHide);
        player.on('canplay', hideLoading);
        player.on('playing', hideLoading);
        player.on('waiting', () => showLoading('Buffering…'));
        player.on('error', () => {
            showLoading('Error loading video – try another quality');
            isSwitching = false;
        });

        player.on('loadedmetadata', () => {
            durationEl.textContent = fmtTime(video.duration);
        });

        // ── Footer quality buttons ──────────────────────────────────────────
        resButtons.forEach(button => {
            button.addEventListener('click', () => {
                const requested = Number(button.dataset.size);
                switchQuality(requested, false);
            });
        });

        // Initial UI sync
        syncQualityUI(1080);

        // Ultimate fallback (very rare)
        setTimeout(hideLoading, 5000);

    })();

</script>
