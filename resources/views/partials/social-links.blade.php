<div class="social-links">
    <span class="social-title">{{ __("Follow Us :") }}</span>
    @if(!empty($global['global_icons']))
    @foreach($global['global_icons'] as $media)
    <a href="{{ $media['url'] }}" target="_blank" rel="noopener" title="{{ $media['label'] }}" class="d-inline-flex align-items-center justify-content-center rounded-circle social-icon" style="width:1.5rem; height:1.5rem; background-color: {{ $media['color'] }};">
        <i class="{{ $media['icon'] }} text-white"></i>
    </a>
    @endforeach
    @endif
</div>
