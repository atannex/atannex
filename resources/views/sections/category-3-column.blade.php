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

                      @php
                      $categoryName = strtolower($post->category->name);
                      @endphp

                      @if(in_array($categoryName, ['fons', 'fon']))
                      <p class="blog-text">
                          {!! Str::limit($post->description, 200) !!}
                      </p>
                      @endif

                      <div class="blog-meta">

                          @include('partials.author')

                          @include('partials.date')

                      </div>

                  </div>
              </div>
              @endforeach
          </div>

          <x-partials.pagination :paginator="$posts" />
      </div>
  </section>
