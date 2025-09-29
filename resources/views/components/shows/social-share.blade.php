<div class="share-links-wrap" aria-label="{{ __('Share Post') }}">
    <div class="share-links">
        <span class="share-links-title">
            {{ __('Share Post:') }}
        </span>
        <div class="flex gap-2 multi-social">
            @foreach ($shares as $platform)
            <a href="{{ $platform['url'] }}" target="_blank" rel="noopener noreferrer" style="color: {{ $platform['color'] }};" title="{{ __('Share on :platform', ['platform' => $platform['label']]) }}" aria-label="{{ __('Share on :platform', ['platform' => $platform['label']]) }}" class="inline-flex items-center justify-center w-10 h-10 p-2 transition rounded hover:opacity-80">
                <i class="{{ $platform['icon'] }}" aria-hidden="true"></i>
            </a>
            @endforeach
        </div>
    </div>
</div>
