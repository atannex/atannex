<div class="th-menu-wrapper">
    <div class="text-center th-menu-area">
        <button class="th-menu-toggle">
            <i class="fal fa-times"></i>
        </button>
        <div class="mobile-logo">

            @include('partials.logo')

        </div>
        <div class="th-mobile-menu">

            @include('partials.menu')

        </div>
    </div>
</div>

<header class="th-header header-layout5 dark-theme">
    <div class="sticky-wrapper">
        <div class="container">
            <div class="row gx-0">
                <div class="col-lg-2 d-none d-lg-inline-block">
                    <div class="header-logo">

                        @include('partials.logo')

                    </div>
                </div>
                <div class="col-lg-10">
                    <div class="header-top">
                        <div class="row align-items-center">
                            <div class="col-xl-9">
                                <div class="news-area">
                                    <div class="title">
                                        {{ __("Breaking News :") }}
                                    </div>
                                    <div class="news-wrap">
                                        <div class="row slick-marquee">
                                            @foreach ($global['breaking'] as $post)
                                            <div class="col-auto">
                                                <a href="{{ route('posts.show', $post->slug_path) }}" class="breaking-news">
                                                    {{ $post->title }}
                                                </a>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 text-end d-none d-xl-block">

                                @include('partials.social-links')

                            </div>
                        </div>
                    </div>
                    <div class="menu-area">
                        <div class="row align-items-center justify-content-between">
                            <div class="col-auto d-none d-xl-block"></div>
                            <div class="col-auto d-lg-none d-block">
                                <div class="header-logo">

                                    @include('partials.logo')

                                </div>
                            </div>
                            <div class="col-auto">
                                <nav class="main-menu d-none d-lg-inline-block">

                                    @include('partials.menu')

                                </nav>
                            </div>
                            <div class="col-auto">
                                <div class="header-button">
                                    <button type="button" class="th-menu-toggle d-block d-lg-none">
                                        <i class="fas fa-bars"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
