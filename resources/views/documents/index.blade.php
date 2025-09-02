<x-layouts.guest :title="seo_title($type)">

    <x-partials.breadcrumb />

    @if($isValidDocumentType)
    <section class="space-top space-extra-bottom">
        <div class="container">
            <div class="row">
                <div class="col-xxl-9 col-lg-8">
                    <div class="mb-30">
                        @forelse($documents as $post)
                        <div class="border-blog2">
                            <div class="blog-style4">
                                <div class="blog-content">
                                    <h3 class="box-title-30">
                                        <a class="hover-line" href="{{ route('document.show', ['type' => $type, 'slug' => $post->slug]) }}">
                                            {{ e($post->title) }}
                                        </a>
                                    </h3>
                                    <p class="blog-text">{!! $post->description !!}</p>
                                    <div class="blog-meta">
                                        @if($post->updated_at && $post->updated_at->diffInMinutes($post->published_at) >= 2)
                                        <a href="javascript:void(0)">
                                            <i class="far fa-user-edit"></i>
                                            {{ __('Updated by: ') . (isset($post->updated_by->name) ? e($post->updated_by->name) : e($post->author->name)) }}
                                        </a>
                                        <a href="{{ route('document.show', ['type' => $type, 'slug' => $post->slug]) }}">
                                            <i class="fal fa-calendar-edit"></i>
                                            {{ __('Updated: ') . $post->updated_at->format('d M, Y') }}
                                        </a>
                                        @else
                                        <a href="javascript:void(0)">
                                            <i class="far fa-user"></i>
                                            {{ __('By: ') . e($post->author->name) }}
                                        </a>
                                        <a href="{{ route('document.show', ['type' => $type, 'slug' => $post->slug]) }}">
                                            <i class="fal fa-calendar-days"></i>
                                            {{ __('Published: ') . $post->published_at->format('d M, Y') }}
                                        </a>
                                        @endif
                                    </div>
                                    <a href="{{ route('document.show', ['type' => $type, 'slug' => $post->slug]) }}" class="th-btn style2">
                                        {{ __("Read More") }} <i class="fas fa-arrow-up-right ms-2"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        @empty
                        <p>{{ __('No') }} {{ $type }} {{ __('found.') }}</p>
                        @endforelse
                    </div>
                </div>


                @include('documents.aside', ['documents' => $documents->reverse()->values()])
            </div>
        </div>
    </section>


    @elseif($isTestimonialType)
    <section class="space-top space-extra-bottom">
        <div class="container">
            <div class="row gy-30 mb-30">
                @forelse($documents as $post)
                <div class="col-xl-4 col-sm-6">
                    <div class="blog-style1">
                        @if($post->image)
                        <div class="blog-img">
                            <img src="{{ $post->image }}" alt="{{ e($post->title) }}">
                        </div>
                        @endif
                        <h3 class="box-title-24">
                            <a class="hover-line" href="javascript:void(0)">
                                {{ e($post->title) }}
                            </a>
                        </h3>
                        <p class="blog-text">{!! $post->description !!}</p>
                        <div class="blog-meta">
                            <a href="javascript:void(0)">
                                <i class="far fa-user"></i>
                                {{ __('By: ') . e($post->author->name) }}
                            </a>
                            <a href="javascript:void(0)">
                                <i class="fal fa-calendar-days"></i>
                                {{ __('Published: ') . $post->published_at->format('d M, Y') }}
                            </a>
                        </div>
                    </div>
                </div>
                @empty
                <p>{{ __('No testimonials found.') }}</p>
                @endforelse
            </div>
        </div>
    </section>
    @endif

</x-layouts.guest>
