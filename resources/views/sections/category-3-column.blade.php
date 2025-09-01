  <section class="space-top space-extra-bottom">
      <div class="container">
          <div class="row gy-30 mb-30">
              @foreach ($posts as $post)
              <div class="col-xl-4 col-sm-6">
                  <div class="blog-style1">
                      <div class="blog-img">

                          @include('partials.image',['class'=> 'category-3-column'])

                          @include('partials.category')

                      </div>

                      <h3 class="box-title-24">

                          @include('partials.title')

                      </h3>
                      <div class="blog-meta">

                          @include('partials.author')

                          <a href="{{ route('page.index', $post->published_at->format('Y/m'))}}">
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
