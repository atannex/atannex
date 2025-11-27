@extends('components.layouts.page')

@section('og:title', seo_title($author->user->name))

@section('page')

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

                                    @include('partials.image')

                                </div>
                                <div class="blog-content">

                                    @include('partials.category')

                                    <h3 class="box-title-22">

                                        @include('partials.title')

                                    </h3>
                                    <p class="blog-text">
                                        {!! Str::limit($post->description, 200) !!}
                                    </p>
                                    <div class="blog-meta">

                                        @include('partials.author')

                                        @include('partials.date')

                                    </div>
                                    <a href="{{ route('page.index', $post->slug_path)}}" class="th-btn style2">
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

                @php
                $user = $author->user;
                @endphp

                <div class="col-xl-4 sidebar-wrap">
                    <div class="mb-0 sidebar-area">
                        <div class="widget">
                            <div class="author-details">
                                <div class="author-img">
                                    <img src="{{ asset('storage/' . $user->image) }}" alt="Image">
                                </div>
                                <div class="author-content">
                                    <h3 class="box-title-24">
                                        {{ $user->name }}
                                    </h3>
                                    <div class="info-wrap">

                                        <span class="info">
                                            {{ $user->getRoleNames()->first() }}
                                        </span>

                                        @if($user->posts_count)
                                        <span class="info">
                                            <strong>
                                                {{ __("Post: ") }}
                                            </strong>
                                            {{ $user->posts_count }}
                                        </span>
                                        @endif

                                    </div>

                                    <p class="author-bio">
                                        {!! $user->bio !!}
                                    </p>

                                    @if($user->email)
                                    <div class="info-wrap top-border">
                                        <span class="info">
                                            <strong>
                                                {{ __("Email :") }}
                                            </strong>
                                        </span>
                                        <span class="info">
                                            <a href="mailto:{{ $user->email }}">
                                                {{ Str::limit($user->email, 25, '...') }}
                                            </a>
                                        </span>
                                    </div>
                                    @endif

                                    @if($user->tell)
                                    <div class="info-wrap">
                                        <span class="info">
                                            <strong>
                                                {{ __("Phone :") }}
                                            </strong>
                                        </span>
                                        <span class="info">
                                            <a href="tel:{{ $user->tell }}">
                                                {{ $user->tell }}
                                            </a>
                                        </span>
                                    </div>
                                    @endif

                                    @if($user_medias)
                                    <h4 class="box-title-18">{{ __("Social Media") }}</h4>
                                    <div class="th-social">
                                        @foreach($user_medias as $media)
                                        <a href="{{ $media['url'] }}" target="_blank" rel="noopener" class="d-inline-flex align-items-center justify-content-center rounded-circle me-1 social-icon" style="width: 2.5rem; height: 2.5rem; background-color: {{ $media['color'] }};">
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

@endsection
