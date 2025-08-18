<x-layouts.page :title="seo_title($author->user->name)">

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
                                    <div class="blog-meta">

                                        @include('partials.author')

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

                @php
                $user = $post->author->user;
                @endphp

                <div class="col-xl-4 sidebar-wrap">
                    <div class="mb-0 sidebar-area">
                        <div class="widget">
                            <div class="author-details">
                                <div class="author-img">
                                    <img src="{{ asset('storage/' . $user->image) }}" alt="Image">
                                </div>
                                <div class="author-content">
                                    <h3 class="box-title-24">{{ $user->name }}</h3>
                                    <div class="info-wrap">
                                        <span class="info">{{ $user->getRoleNames()->first() }}</span>
                                        @if($user->posts_count)
                                        <span class="info">
                                            <strong>{{ __("Post: ") }}</strong>{{ $user->posts_count }}
                                        </span>
                                        @endif
                                    </div>
                                    <p class="author-bio">{!! $user->bio !!}</p>

                                    @php
                                    $contacts = [
                                    'email' => [
                                    'label' => __("Email :"),
                                    'value' => $user->email,
                                    'href' => $user->email ? 'mailto:' . $user->email : null,
                                    'display' => $user->email ? Str::limit($user->email, 25, '...') : null,
                                    'wrapper_class' => 'info-wrap top-border'
                                    ],
                                    'phone' => [
                                    'label' => __("Phone :"),
                                    'value' => $user->tell,
                                    'href' => $user->tell ? 'tel:' . $user->tell : null,
                                    'display' => $user->tell,
                                    'wrapper_class' => 'info-wrap'
                                    ],
                                    ];
                                    @endphp

                                    @foreach ($contacts as $contact)
                                    @if ($contact['value'])
                                    <div class="{{ $contact['wrapper_class'] }}">
                                        <span class="info"><strong>{{ $contact['label'] }}</strong></span>
                                        <span class="info">
                                            @if ($contact['href'])
                                            <a href="{{ $contact['href'] }}">{{ $contact['display'] }}</a>
                                            @else
                                            {{ $contact['display'] }}
                                            @endif
                                        </span>
                                    </div>
                                    @endif
                                    @endforeach

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
