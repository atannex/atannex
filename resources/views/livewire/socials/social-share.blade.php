<div class="share-links">
    <span class="share-links-title">{{ __("Share Post: ") }}</span>
    <div class="multi-social">
        @foreach ($shares as $share)
        <button type="button" style="color: {{ $share['color'] }}" title="Share on {{ $share['label'] }}" class="social-link" wire:click="share('{{ $share['platform'] }}')">
            <i class="{{ $share['icon'] }}"></i>
        </button>
        @endforeach
    </div>
</div>
