<x-layouts.page :title="$author->user->name . ' - ' . __('Top Stories, Breaking News & Headlines') . ' | ' . config('app.name')">

    <x-partials.breadcrumb />

    <section class="space space-extra-bottom">
        <div class="container">
            <div class="row">
                <div class="col-xl-8">
                    <div>
                        @foreach($posts as $post)
                        <div class="mb-4 border-blog">
                            <div class="blog-style4">
                                <div class="blog-img w-270">
                                    <img src="{{ asset('storage/' . $post->image) }}" class="img-fluid h-200" alt="{{ config('app.name') }}">
                                </div>
                                <div class="blog-content">
                                    <a href="{{ $post->category->slug_path }}" class="category" data-theme-color="{{ \App\Models\Others\Color::randomHex() }}">
                                        {{ $post->category->name }}
                                    </a>
                                    <h3 class="box-title-22">
                                        <a class="hover-line" href="#">
                                            {{ $post->title }}
                                        </a>
                                    </h3>
                                    <div class="blog-meta">
                                        <a href="#">
                                            <i class="far fa-user"></i>
                                            {{ __("By - ") . $post->author->user->name }}
                                        </a>
                                        <a href="#">
                                            <i class="fal fa-calendar-days"></i>
                                            {{ $post->published_at->format('d M, Y') }}
                                        </a>
                                    </div>
                                    <a href="#" class="th-btn style2">
                                        {{ __(" Read More ") }}
                                        <i class="fas fa-arrow-up-right ms-2"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>


                    <x-partials.pagination :paginator="$posts" />

                </div>

                <div class="col-xl-4 sidebar-wrap">
                    <div class="mb-0 sidebar-area">
                        <div class="widget">
                            <div class="author-details">
                                <div class="author-img">
                                    <img src="{{ asset('storage/'. $post->author->user->image) }}" alt="Image">
                                </div>
                                <div class="author-content">
                                    <h3 class="box-title-24">
                                        {{ $post->author->user->name }}
                                    </h3>
                                    <div class="info-wrap">
                                        <span class="info">
                                            {{ $post->author->user->getRoleNames()->first() }}
                                        </span>
                                        @if($post->author->user->posts_count)
                                        <span class="info">
                                            <strong>{{ __("Post: ") }}</strong>
                                            {{ $post->author->user->posts_count }}
                                        </span>
                                        @endif
                                    </div>
                                    <p class="author-bio">{!! $post->author->user->bio !!}</p>

                                    @if($post->author->user->email)
                                    <div class="info-wrap top-border">
                                        <span class="info">
                                            <strong>{{ __("Email :") }}</strong>
                                        </span>
                                        <span class="info">
                                            <a href="mailto:{{ $post->author->user->email }}">{{ Str::limit($post->author->user->email, 25, '...') }}
                                            </a>
                                        </span>
                                    </div>
                                    @endif
                                    @if($post->author->user->tell)
                                    <div class="info-wrap">
                                        <span class="info">
                                            <strong>{{ __("Phone :") }}</strong>
                                        </span>
                                        <span class="info">
                                            <a href="tel:{{ $post->author->user->tell }}">{{ $post->author->user->tell }}</a>
                                        </span>
                                    </div>
                                    @endif
                                    @if($user_medias)
                                    <h4 class="box-title-18">{{ __("Social Media") }}</h4>
                                    <div class="th-social">
                                        @foreach($user_medias as $media)
                                        <a href="{{ $media['url'] }}" target="_blank" rel="noopener" class="d-inline-flex align-items-center justify-content-center rounded-circle me-1" style="width: 2.5rem; height: 2.5rem; background-color: var(--bs-{{ $media['color'] }});">
                                            <i class="{{ $media['icon'] }} text-white"></i>
                                        </a>
                                        @endforeach
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-layouts.page>
