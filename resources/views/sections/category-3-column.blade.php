  <section class="space-top space-extra-bottom">
      <div class="container">
          <div class="row gy-30 mb-30">
              @foreach ($posts as $post)
              <div class="col-xl-4 col-sm-6">
                  <div class="blog-style1">
                      <div class="blog-img">
                          <a href="#">
                              <img src="{{ asset('storage/' . $post->image) }}" alt="{{ config('app.name') }}">
                          </a>

                          <a data-theme-color="{{ \App\Models\Others\Color::randomHex() }}" href="{{ route('page.index', $post->category->slug_path) }}" class="category">
                              {{ $post->category->name }}
                          </a>

                      </div>

                      <h3 class="box-title-24">
                          <a class="hover-line" href="#">
                              {{ $post->title }}
                          </a>
                      </h3>
                      <div class="blog-meta">
                          <a href="#">
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
