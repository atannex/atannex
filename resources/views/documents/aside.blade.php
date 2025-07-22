<div class="col-xxl-3 col-lg-4 sidebar-wrap">
    <aside class="sidebar-area">
        <div class="widget">
            <h3 class="widget_title">{{ __('Other :type', ['type' => e($type ?? '')]) }}</h3>
            <div class="recent-post-wrap">
                @forelse($documents as $index => $item)
                    <div class="recent-post">
                        <div class="media-body">
                            <h3 class="post-title">
                                <a class="hover-line" href="{{ route('document.show', ['type' => $type, 'slug' => $item->slug]) }}">
                                    {{ $index + 1 }}. {{ e($item->title) }}
                                </a>
                            </h3>
                        </div>
                    </div>
                @empty
                    <p>{{ __('No related documents found.') }}</p>
                @endforelse
            </div>
        </div>
    </aside>
</div>
