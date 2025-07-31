 <footer class="footer-wrapper footer-layout1" data-bg-src="assets/img/bg/footer_bg_1.png">
     <div class="widget-area">
         <div class="container">
             <div class="row justify-content-between">
                 <div class="col-md-6 col-xl-3">
                     <div class="widget footer-widget">
                         <div class="th-widget-about">
                             <div class="about-logo">
                                 <a href="{{ route('home') }}">
                                     <img class="dark-img" src="{{ asset('storage/'. $global['logo']->image) }}" class="img-fluid" style="max-width: 98px; height: 98px; object-fit: cover;">
                                 </a>
                             </div>
                             <p class="about-text">
                                 {{-- {{ $company->description }} --}}
                             </p>
                             <div class="th-social style-black">
                                 @foreach ($global['global_icons'] as $media)
                                 <a href="{{ $media['url'] }}" target="_blank" rel="noopener" class="d-inline-flex align-items-center justify-content-center rounded-circle me-1" style="width: 2.5rem; height: 2.5rem; background-color: var(--bs-{{ $media['color'] }});">
                                     <i class="{{ $media['icon'] }} text-white"></i>
                                 </a>
                                 @endforeach
                             </div>
                         </div>
                     </div>
                 </div>

                 {{-- @include('layouts.categories') --}}

                 <div class="col-md-6 col-xl-auto">
                     <div class="widget footer-widget">

                          @include('partials.recent-posts')

                     </div>
                 </div>
                 <div class="col-md-6 col-xl-3">
                     <div class="widget widget_tag_cloud footer-widget">
                         <h3 class="widget_title">Popular Tags</h3>
                         <div class="tagcloud">
                             <a href="blog.html">Sports</a>
                             <a href="blog.html">Politics</a>
                             <a href="blog.html">Business</a>
                             <a href="blog.html">Music</a> <a href="blog.html">Food</a>
                             <a href="blog.html">Technology</a>
                             <a href="blog.html">Travels</a>
                             <a href="blog.html">Health</a>
                             <a href="blog.html">Fashions</a>
                             <a href="blog.html">Animal</a>
                             <a href="blog.html">Weather</a> <a href="blog.html">Movies</a>
                         </div>
                     </div>
                 </div>
             </div>
         </div>
     </div>
     <div class="copyright-wrap">
         <div class="container">
             <div class="row jusity-content-between align-items-center">
                 <div class="col-lg-5">
                     <p class="copyright-text">
                         {{ __("Copyright") }} &copy; {{ now()->year }}
                         <a href="{{ route('home') }}">{{ config('app.name') }}</a> .
                         {{ __(" All Rights Reserved. ") }}
                     </p>
                 </div>
                 <div class="col-lg-auto ms-auto d-none d-lg-block">
                     <div class="footer-links">
                         <ul>
                             <li><a href="{{ route('home') }}">{{ __("Home") }}</a></li>
                             <li>
                                 <a href="{{ route('about') }}">{{ __('About Us') }}</a>
                             </li>
                             <li>
                                 <a href="{{ route('document.index', ['type' => 'faq']) }}">
                                     {{ __('FAQs') }}
                                 </a>
                             </li>
                             <li>
                                 <a href="{{ route('contact') }}">{{ __('Contact Us') }}</a>
                             </li>
                         </ul>
                     </div>
                 </div>
             </div>
         </div>
     </div>
 </footer>
