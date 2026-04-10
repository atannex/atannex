 <section id="hero" class="relative px-4 pt-16 pb-20 overflow-hidden sm:px-6 lg:px-8">
     <div class="blob blob-1"></div>
     <div class="blob blob-2"></div>

     <div class="container relative z-10 mx-auto">
         <div class="max-w-3xl mx-auto text-center">
             <div class="slide-in-up">
                 <span class="inline-block px-3 py-1 mb-3 text-xs font-semibold tracking-wide uppercase border rounded-full bg-sky-500/20 border-sky-500/40 text-sky-300">
                     <i class="fas fa-map-marker-alt"></i> {{ __('Welcome To Lebialem') }}
                 </span>
             </div>

             <div class="slide-in-up" style="animation-delay: 0.1s;">
                 <h1 class="mb-4 text-3xl font-black leading-tight sm:text-4xl md:text-5xl">
                     {{ __('Breaking News') }} <span class="gradient-text gradient-animate">{{ __('Lebialem Division') }}</span>
                 </h1>
             </div>

             <div class="slide-in-up" style="animation-delay: 0.2s;">
                 <p class="max-w-2xl mx-auto mb-6 text-sm font-light leading-relaxed sm:text-base text-slate-300">
                     {{__('Your trusted source for breaking news, in-depth analysis, and community stories from the Lebialem Division, Southwest Region of Cameroon. Covering local, regional, and international perspectives from Fontem, Alou, Wabane and beyond.')}}
                 </p>
             </div>

             <div class="flex flex-col justify-center gap-2 mb-10 sm:flex-row slide-in-up" style="animation-delay: 0.3s;">
                 <a href="{{ route('home') }}" class="px-6 py-2.5 bg-gradient-to-r from-sky-500 to-cyan-500 text-white font-bold rounded-lg hover:shadow-lg hover:shadow-sky-500/40 smooth-transition transform hover:-translate-y-0.5 flex items-center justify-center gap-2 text-xs uppercase tracking-wider group">
                     <i class="text-xs fas fa-play group-hover:rotate-90 smooth-transition"></i> {{ __('Start') }}
                 </a>
                 <a href="#categories" class="px-6 py-2.5 border border-sky-400/50 text-sky-400 font-bold rounded-lg hover:bg-sky-400/10 smooth-transition transform hover:-translate-y-0.5 flex items-center justify-center gap-2 text-xs uppercase tracking-wider group">
                     <i class="fas fa-arrow-right group-hover:translate-x-0.5 smooth-transition text-xs"></i> {{ __('Explore') }}
                 </a>
             </div>

             <div class="grid max-w-xl grid-cols-3 gap-3 mx-auto slide-in-up" style="animation-delay: 0.4s;">
                 <div class="p-3 border rounded-lg stat-card border-sky-500/30 hover:border-sky-500/60 smooth-transition group">
                     <div class="mb-1 text-2xl font-bold gradient-text group-hover:scale-110 smooth-transition">1K+</div>
                     <div class="text-xs font-semibold text-slate-400">{{ __('Monthly Readers') }}</div>
                 </div>
                 <div class="p-3 border rounded-lg stat-card border-cyan-500/30 hover:border-cyan-500/60 smooth-transition group">
                     <div class="mb-1 text-2xl font-bold gradient-text group-hover:scale-110 smooth-transition">50+</div>
                     <div class="text-xs font-semibold text-slate-400">{{ __('Stories Published') }}</div>
                 </div>
                 <div class="p-3 border rounded-lg stat-card border-sky-500/30 hover:border-sky-500/60 smooth-transition group">
                     <div class="mb-1 text-2xl font-bold gradient-text group-hover:scale-110 smooth-transition">24/7</div>
                     <div class="text-xs font-semibold text-slate-400">{{ __('Live Coverage') }}</div>
                 </div>
             </div>
         </div>
     </div>
 </section>
