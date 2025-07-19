    <section class="th-blog-wrapper space-top space-extra-bottom">
      <div class="container">
          <div class="row">
              <div class="col-xxl-9 col-lg-8">
                  @foreach ($posts as $blog)
                  <div class="th-blog blog-single {{ $blog->image || $blog->carousel_images ? 'has-post-thumbnail' : '' }}">

                      @if ($blog->carousel_images)
                      <div class="blog-img">
                          <div class="th-carousel" data-overlay="black" data-opacity="4" data-arrows="true" data-slide-show="1" data-fade="true">
                              @foreach ($blog->carousel_images as $image)
                              <a href="#">
                                  <img src="{{ asset('storage/' . $image) }}" class="img-fluid-full" alt="{{ config('app.name') }}">
                              </a>
                              @endforeach
                          </div>
                          <a href="{{ route('atannex.page', $blog->category->slug_path) }}" class="category" data-theme-color="{{ \App\Models\Control\Color::randomHex() }}">
                              {{ $blog->category->name }}
                          </a>
                      </div>
                      @elseif ($blog->image)
                      <div class="blog-img" data-overlay="black" data-opacity="4">
                          <a href="#">
                              <img src="{{ asset('storage/' . $blog->image) }}" class="img-fluid-full" alt="{{ config('app.name') }}">
                          </a>
                          <a href="{{ route('atannex.page', $blog->category->slug_path) }}" class="category" data-theme-color="{{ \App\Models\Control\Color::randomHex() }}">
                              {{ $blog->category->name }}
                          </a>
                      </div>
                      @endif

                      <div class="blog-content">
                          <div class="blog-meta flex flex-wrap gap-3">
                              <a class="author" href="#">
                                  <i class="far fa-user"></i> {{ __("By - ") . $blog->author->name }}
                              </a>
                              <a href="#">
                                  <i class="fal fa-calendar-days"></i> {{ $blog->published_at->format('d F, Y') }}
                              </a>
                              <a href="#">
                                  <i class="far fa-comments"></i>
                                  {{ trans_choice(':count Comment|:count Comments', $blog->comment_count ?? 0, ['count' => $blog->comment_count ?? 0]) }}
                              </a>
                              <a href="#">
                                  <i class="far fa-thumbs-up"></i>
                                  {{ trans_choice(':count Like|:count Likes', $blog->like_count ?? 0, ['count' => $blog->like_count ?? 0]) }}
                              </a>
                              <a href="#">
                                  <i class="far fa-star"></i>
                                  {{ number_format($blog->average_rating ?? 0, 1) }} / 5
                              </a>
                          </div>

                          <h2 class="blog-title box-title-30">
                              <a href="#">{{ $blog->title }}</a>
                          </h2>

                          <p class="blog-text">
                              {!! Str::limit($blog->description, 200) !!}
                          </p>

                          <a href="#" class="th-btn style2">
                              {{ __("Read More") }}
                              <i class="fas fa-arrow-up-right ms-2"></i>
                          </a>
                      </div>
                  </div>
                  @endforeach

                  <x-partials.pagination :paginator="$posts" />
              </div>

              <div class="col-xxl-3 col-lg-4 sidebar-wrap">
                  @include('partials.aside')
              </div>
          </div>
      </div>
  </section>
