<div class="share-links-wrap" aria-label="{{ __('Share Post') }}">
    <div class="share-links">
        <span class="share-links-title">{{ __("Share Post:") }}</span>
        <div class="flex gap-2 multi-social">
            @foreach ($shares as $share)
            <a href="{{ $share['url'] }}" target="_blank" rel="noopener noreferrer" style="color: {{ $share['color'] }};" title="Share on {{ $share['label'] }}" aria-label="Share on {{ $share['label'] }}" class="inline-flex items-center justify-center w-10 h-10 p-2 transition rounded social-link hover:opacity-80">
                <i class="{{ $share['icon'] }}"></i>
            </a>
            @endforeach
        </div>
    </div>
</div>
