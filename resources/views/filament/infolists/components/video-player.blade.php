@php
$videoUrl = $getVideoUrl();
$mimeType = $getVideoMimeType();
$width = $getWidth();
$height = $getHeight();
$maxHeight = $getMaxHeight();
$controls = $hasControls();
$autoplay = $isAutoplay();
$loop = $isLoop();
$muted = $isMuted();
$poster = $getPoster();
$preload = $getPreload();
$allowFullscreen = $isFullscreenAllowed();
$allowPip = $isPictureInPictureAllowed();
$videoAttributes = $getVideoAttributes();
$containerClass = $getContainerClass();
$playbackRates = $getPlaybackRates();
$defaultPlaybackRate = $getDefaultPlaybackRate();
$uniqueId = 'video-player-' . uniqid();
@endphp


<x-dynamic-component :component="$getEntryWrapperView()" :entry="$entry">
    @if ($videoUrl)
    <div class="video-player-container {{ $containerClass }}" x-data="videoPlayer({
                 id: '{{ $uniqueId }}',
                 autoplay: {{ $autoplay ? 'true' : 'false' }},
                 defaultPlaybackRate: {{ $defaultPlaybackRate }},
                 playbackRates: {{ json_encode($playbackRates) }}
             })" x-init="init()">

        <div class="relative overflow-hidden bg-gray-900 rounded-lg shadow-lg">
            <video id="{{ $uniqueId }}" x-ref="video" style="width: {{ $width }}; height: {{ $height }}; max-height: {{ $maxHeight }};" class="w-full h-auto" @if ($controls) controls @endif @if ($autoplay) autoplay @endif @if ($loop) loop @endif @if ($muted) muted @endif @if ($poster) poster="{{ $poster }}" @endif preload="{{ $preload }}" @if (!$allowPip) disablepictureinpicture @endif @if (!$allowFullscreen) controlslist="nofullscreen" @endif @foreach ($videoAttributes as $attr=> $value)
                {{ $attr }}="{{ $value }}"
                @endforeach
                @play="isPlaying = true"
                @pause="isPlaying = false"
                @ended="isPlaying = false"
                @timeupdate="updateProgress()"
                @loadedmetadata="onLoadedMetadata()"
                >
                <source src="{{ $videoUrl }}" type="{{ $mimeType }}">
                <p class="p-4 text-white">
                    {{ __(" Your browser doesn't support HTML5 video.") }}
                    <a href="{{ $videoUrl }}" download class="text-blue-400 underline">{{ __("Download") }}</a> {{ __("instead.") }}
                </p>
            </video>

            {{-- <div x-show="isLoading" class="absolute inset-0 flex items-center justify-center bg-gray-900/50" x-transition>
                <svg class="w-12 h-12 text-white animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </div> --}}
        </div>
    </div>

    @push('scripts')
    <script>
        function videoPlayer(config) {
            return {
                isPlaying: false
                , isLoading: true
                , isMuted: false
                , isFullscreen: false
                , pipSupported: false
                , currentTime: 0
                , duration: 0
                , progress: 0
                , volume: 1
                , playbackRate: config.defaultPlaybackRate || 1,

                init() {
                    const video = this.$refs.video;
                    this.pipSupported = document.pictureInPictureEnabled;
                    video.volume = this.volume;
                    video.playbackRate = this.playbackRate;

                    video.addEventListener('loadstart', () => this.isLoading = true);
                    video.addEventListener('canplay', () => this.isLoading = false);
                    video.addEventListener('waiting', () => this.isLoading = true);
                    video.addEventListener('playing', () => this.isLoading = false);

                    if (config.autoplay) {
                        video.play().catch(() => console.log('Autoplay prevented'));
                    }
                },

                togglePlay() {
                    const video = this.$refs.video;
                    video.paused ? video.play() : video.pause();
                },

                toggleMute() {
                    const video = this.$refs.video;
                    video.muted = !video.muted;
                    this.isMuted = video.muted;
                },

                setVolume(value) {
                    const video = this.$refs.video;
                    video.volume = value;
                    this.volume = value;
                    this.isMuted = value === 0;
                },

                setPlaybackRate(rate) {
                    const video = this.$refs.video;
                    video.playbackRate = rate;
                    this.playbackRate = rate;
                },

                toggleFullscreen() {
                    const container = this.$el;
                    !document.fullscreenElement ? container.requestFullscreen() : document.exitFullscreen();
                    this.isFullscreen = !this.isFullscreen;
                },

                async togglePip() {
                    const video = this.$refs.video;
                    try {
                        document.pictureInPictureElement ?
                            await document.exitPictureInPicture() :
                            await video.requestPictureInPicture();
                    } catch (e) {
                        console.error(e);
                    }
                },

                updateProgress() {
                    const video = this.$refs.video;
                    this.currentTime = video.currentTime;
                    this.progress = (video.currentTime / video.duration) * 100 || 0;
                },

                onLoadedMetadata() {
                    const video = this.$refs.video;
                    this.duration = video.duration;
                },

                seek(event) {
                    const video = this.$refs.video;
                    const rect = event.target.getBoundingClientRect();
                    const pos = (event.clientX - rect.left) / rect.width;
                    video.currentTime = pos * video.duration;
                },

                formatTime(seconds) {
                    if (!seconds || isNaN(seconds)) return '00:00';
                    const h = Math.floor(seconds / 3600);
                    const m = Math.floor((seconds % 3600) / 60);
                    const s = Math.floor(seconds % 60);
                    return h > 0 ?
                        `${h}:${m.toString().padStart(2, '0')}:${s.toString().padStart(2, '0')}` :
                        `${m}:${s.toString().padStart(2, '0')}`;
                }
            }
        }

    </script>
    @endpush

    @else

    <div class="flex items-center justify-center p-8 border-2 border-gray-300 border-dashed rounded-lg bg-gray-50 dark:bg-gray-900 dark:border-gray-700">
        <div class="text-center">
            <svg class="w-12 h-12 mx-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
            </svg>
            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">{{ __("No video available") }}</p>
        </div>
    </div>
    @endif
</x-dynamic-component>
