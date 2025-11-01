<div class="share-links-wrap" aria-label="{{ __('Share Post') }}">
    <div class="share-links">
        <span class="share-links-title">
            {{ __('Share Post:') }}
        </span>
        <div class="flex gap-2 multi-social">
            @foreach ($icons as $platform => $data)
            <a href="{{ route('share', ['platform' => $platform, 'post' => $module->post->slug]) }}" target="_blank" rel="noopener noreferrer" title="{{ __('Share on :platform', ['platform' => $data['label']]) }}" aria-label="{{ __('Share on :platform', ['platform' => $data['label']]) }}" class="inline-flex items-center justify-center w-10 h-10 p-2 transition rounded" style="--theme-color: {{ $data['color'] }}">
                <i class="{{ $data['icon'] }}" aria-hidden="true"></i>
            </a>
            @endforeach
        </div>
    </div>
</div>
