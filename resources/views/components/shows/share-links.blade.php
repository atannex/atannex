
<div class="share-links">
    <span class="share-links-title">{{__("Share Post: ")}}</span>
    <div class="multi-social">
        @foreach ($shares as $share)
        <a href="{{ $share['share_url'] }}" target="_blank" style="color: {{ $share['color'] }}" title="Share on {{ $share['label'] }}" class="social-link">
        <i class="{{ $share['icon'] }}"></i>
        </a>
        @endforeach
</div>
</div>
