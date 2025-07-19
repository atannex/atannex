<div class="col-xl-4 mt-35 mt-xl-0">
    <div class="nav tab-menu indicator-active" role="tablist">
        @foreach ($widget->tabs as $index => $tab)
        <button class="tab-btn {{ $index === 0 ? 'active' : '' }}" id="sidebar-tabs-{{ $index }}-button" data-bs-toggle="tab" data-bs-target="#sidebar-tabs-{{ $index }}" type="button" role="tab" aria-controls="sidebar-tabs-{{ $index }}" aria-selected="{{ $index === 0 ? 'true' : 'false' }}">
            {{ $tab['title'] }}
        </button>
        @endforeach
    </div>

    <div class="tab-content">
        @foreach ($widget->tabs as $index => $tab)
        <div class="tab-pane fade {{ $index === 0 ? 'show active' : '' }}" id="sidebar-tabs-{{ $index }}" role="tabpanel" aria-labelledby="sidebar-tabs-{{ $index }}-button">
            <div class="row gy-4">
                @foreach ($tab['content'] as $post)
                <div class="col-xl-12 col-md-6 border-blog">
                    <article class="blog-style2">
                        <div class="blog-img">
                            <img class="img-fluid top-stories-right-sidebar" src="{{ asset('storage/' . $post->image) }}" alt="{{ $post->title }}">
                        </div>
                        <div class="blog-content">
                            <a href="#" class="category" data-theme-color="{{ \App\Models\Others\Color::randomHex() }}">
                                {{ $post->category->name }}
                            </a>
                            <h3 class="box-title-18">
                                <a href="#" class="hover-line">{{ Str::limit($post->title, 60) }}</a>
                            </h3>
                            <div class="blog-meta">
                                <a href="#"><i class="fal fa-calendar-days"></i> {{ $post->published_at->format('d M, Y') }}</a>
                            </div>
                        </div>
                    </article>
                </div>
                @endforeach
            </div>
        </div>
        @endforeach
    </div>
</div>
