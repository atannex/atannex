<div class="share-links-wrap">
    <div class="share-links">
        <span class="share-links-title">{{ __("Share Post:") }}</span>
        <div class="flex gap-2 multi-social">
            @foreach ($shares as $share)
            <a href="{{ $share['url'] }}" target="_blank" rel="noopener noreferrer" style="color: {{ $share['color'] }};" title="Share on {{ $share['label'] }}" class="inline-flex items-center justify-center p-2 rounded social-link">
                <i class="{{ $share['icon'] }}"></i>
            </a>
            @endforeach
        </div>
    </div>
</div>
