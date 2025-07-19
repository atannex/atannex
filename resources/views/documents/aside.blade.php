<div class="col-xxl-3 col-lg-4 sidebar-wrap">
    <aside class="sidebar-area">
        <div class="widget">
            <h3 class="widget_title">{{ __(" Other ") . $documents->first()->type }}</h3>
            <div class="recent-post-wrap">
                @foreach($documents as $index => $item)
                <div class="recent-post">
                    <div class="media-body">
                        <h3 class="post-title">
                            <a class="hover-line" href="{{ route('document.show', ['type' => $type, 'slug' => $item->slug]) }}">
                                {{ $index + 1 }}. {{ $item->title }}
                            </a>
                        </h3>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </aside>
</div>
