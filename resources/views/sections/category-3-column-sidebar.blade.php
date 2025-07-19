  <section class="space">
      <div class="container">
          <div class="row gy-30">
              @foreach ($posts as $post)
              <div class="col-xl-3 col-lg-4 col-sm-6">
                  <div class="blog-style1">

                      <div class="blog-img" @if($post->carousel_images) data-overlay="black" data-opacity="4" @endif>

                          {{-- Show carousel images if available --}}
                          @if ($post->carousel_images)
                          <div class="th-carousel" data-overlay="black" data-opacity="4" data-arrows="true" data-slide-show="1" data-fade="true">
                              @foreach ($post->carousel_images as $image)
                              <a href="#">
                                  <img src="{{ asset('storage/' . $image) }}" class="{{ $height }}" alt="{{ config('app.name') }}">
                              </a>
                              @endforeach
                          </div>
                          @else
                          {{-- Show single image if no carousel --}}
                          <img src="{{ asset('storage/' . $post->image) }}" class="{{ $height }}" alt="{{ config('app.name') }}">
                          @endif

                          {{-- Conditional label for BREAKING flag --}}
                          @if ($post->flag === \App\Enums\Flag::BREAKING)
                          <span class="breaking-label" style="color: red; font-weight: bold; margin-right: 8px;">
                              BREAKING
                          </span>
                          @endif

                          {{-- Category link, shown beside breaking label or alone --}}
                          <a data-theme-color="{{ \App\Models\Control\Color::randomHex() }}" href="{{ route('atannex.page', $post->category->slug_path) }}" class="category">
                              {{ $post->category->name }}
                          </a>

                      </div>

                      <h3 class="box-title-24">
                          <a class="hover-line" href="#">
                              {{ $post->title }}
                          </a>
                      </h3>
                      <div class="blog-meta">
                          <a href="{{ route('atannex.page', $post->author->slug) }}">
                              <i class="far fa-user"></i>
                              {{ __(" By - ") . $post->author->name }}
                          </a>

                          <a href="#">
                              <i class="fal fa-calendar-days"></i>
                              {{ $post->published_at->format('d M, Y') }}
                          </a>
                      </div>


                  </div>
              </div>
              @endforeach
          </div>

          <x-partials.pagination :paginator="$posts" />

      </div>
  </section>
