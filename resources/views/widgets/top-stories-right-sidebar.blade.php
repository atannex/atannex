<div class="col-xl-3 mt-35 mt-xl-0">
    <div class="nav tab-menu indicator-active" role="tablist">
        @foreach ($widget->tabs as $index => $tab)
        <button class="tab-btn {{ $index === 0 ? 'active' : '' }}" id="nav-{{ $index }}-tab" data-bs-toggle="tab" data-bs-target="#nav-{{ $index }}" type="button" role="tab" aria-controls="nav-{{ $index }}" aria-selected="{{ $index === 0 ? 'true' : 'false' }}">
            {{ $tab['title'] }}
        </button>
        @endforeach
    </div>

    <div class="tab-content">
        @foreach ($widget->tabs as $index => $tab)
        <div class="tab-pane fade {{ $index === 0 ? 'show active' : '' }}" id="nav-{{ $index }}" role="tabpanel" aria-labelledby="nav-{{ $index }}-tab">

            <div class="row gy-4">
                @forelse ($tab['content'] as $post)
                <div class="col-xl-12 col-md-6 border-blog">
                    <div class="blog-style2">
                        <div class="blog-img">
                            <a href="{{ route('page.index', ['slug' => $post->slug_path ]) }}">
                                <img src="{{ asset('storage/' . $post->image) }}" alt="{{ config('app.name') }}" class="img-fluid top-stories-right-sidebar">
                            </a>
                        </div>
                        <div class="blog-content">
                            @include('partials.category', ['post' => $post])

                            <h3 class="box-title-18">
                                @if($post->category)
                                <a href="{{ route('page.index', ['slug' => $post->slug_path ]) }}" class="hover-line">
                                    {{ Str::limit($post->title, 30) }}
                                </a>
                                @else
                                <span>{{ Str::limit($post->title, 30) }}</span>
                                @endif
                            </h3>

                            @include('partials.date', ['post' => $post])
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12">
                    <p class="text-muted">{{__("No posts available.")}}</p>
                </div>
                @endforelse
            </div>
        </div>
        @endforeach
    </div>
</div>
