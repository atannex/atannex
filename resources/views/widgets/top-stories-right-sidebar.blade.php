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
                @foreach ($tab['content'] as $post)
                <div class="col-xl-12 col-md-6 border-blog">
                    <div class="blog-style2">
                        <div class="blog-img">

                            @include('partials.image')

                        </div>
                        <div class="blog-content">

                            @include('partials.category')

                            <h3 class="box-title-18">

                                @include('partials.title')

                            </h3>
                            <div class="blog-meta">

                                @include('partials.date')

                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endforeach
    </div>
</div>
