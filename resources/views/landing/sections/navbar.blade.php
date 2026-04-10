 <nav class="sticky top-0 z-50 border-b shadow-md bg-slate-950 border-slate-800">
     <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-12">
         <div class="flex items-center justify-between h-16">
             <a href="#hero" class="flex items-center gap-2.5 group">
                 <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-gradient-to-br from-sky-600 to-cyan-600 group-hover:shadow-lg group-hover:shadow-sky-600/40 smooth-transition">
                     <i class="text-lg font-bold text-white fas fa-newspaper"></i>
                 </div>
                 <div class="flex flex-col leading-4">
                     <span class="text-base font-bold text-white group-hover:text-sky-300 smooth-transition">{{ config('app.name') }}</span>
                     <span class="text-xs font-medium text-slate-400">{{ __('Lebialem News') }}</span>
                 </div>
             </a>

             <div class="items-center hidden gap-0.5 lg:flex">
                 <a href="#hero" class="px-4 py-2 text-sm font-medium rounded-md text-slate-300 hover:bg-slate-800/50 hover:text-sky-300 smooth-transition">
                     {{ __('Home') }}
                 </a>
                 <a href="#about" class="px-4 py-2 text-sm font-medium rounded-md text-slate-300 hover:bg-slate-800/50 hover:text-sky-300 smooth-transition">
                     {{ __('About') }}
                 </a>
                 <a href="#features" class="px-4 py-2 text-sm font-medium rounded-md text-slate-300 hover:bg-slate-800/50 hover:text-sky-300 smooth-transition">
                     {{ __('Features') }}
                 </a>
                 <a href="#categories" class="px-4 py-2 text-sm font-medium rounded-md text-slate-300 hover:bg-slate-800/50 hover:text-sky-300 smooth-transition">
                     {{ __('Coverage') }}
                 </a>
                 <a href="#reviews" class="px-4 py-2 text-sm font-medium rounded-md text-slate-300 hover:bg-slate-800/50 hover:text-sky-300 smooth-transition">
                     {{ __('Reviews') }}
                 </a>
                 <a href="#team" class="px-4 py-2 text-sm font-medium rounded-md text-slate-300 hover:bg-slate-800/50 hover:text-sky-300 smooth-transition">
                     {{ __('Team') }}
                 </a>
                 <div class="w-px h-5 mx-3 bg-slate-700"></div>
                 <a href="{{ route('login') }}" class="inline-flex items-center justify-center px-4 py-2 text-sm font-semibold border rounded-md text-slate-300 border-slate-700 hover:border-slate-600 hover:bg-slate-800/30 hover:text-sky-300 smooth-transition">
                     {{ __('Sign In') }}
                 </a>
                 <a href="/donate" class="inline-flex items-center justify-center px-5 py-2 ml-2 text-sm font-semibold text-white rounded-md bg-sky-600 hover:bg-sky-500 hover:shadow-lg hover:shadow-sky-600/30 smooth-transition">
                     {{ __("Donate") }}
                 </a>
             </div>
             <button class="flex items-center justify-center w-10 h-10 rounded-md lg:hidden hover:bg-slate-800/50 text-slate-400 hover:text-sky-300 smooth-transition" id="mobileMenuBtn">
                 <i class="text-lg fas fa-bars"></i>
             </button>
         </div>

         <div id="mobileMenu" class="hidden border-t lg:hidden border-slate-800 bg-slate-900/50 backdrop-blur-sm">
             <div class="px-4 py-3 space-y-1">
                 <a href="#hero" class="flex items-center px-4 py-2.5 text-sm font-medium text-slate-300 rounded-md hover:bg-slate-800/50 hover:text-sky-300 smooth-transition">
                     <i class="w-5 mr-3 text-center fas fa-home"></i> {{ __('Home') }}
                 </a>
                 <a href="#about" class="flex items-center px-4 py-2.5 text-sm font-medium text-slate-300 rounded-md hover:bg-slate-800/50 hover:text-sky-300 smooth-transition">
                     <i class="w-5 mr-3 text-center fas fa-circle-info"></i> {{ __('About') }}
                 </a>
                 <a href="#features" class="flex items-center px-4 py-2.5 text-sm font-medium text-slate-300 rounded-md hover:bg-slate-800/50 hover:text-sky-300 smooth-transition">
                     <i class="w-5 mr-3 text-center fas fa-star"></i> {{ __('Features') }}
                 </a>
                 <a href="#categories" class="flex items-center px-4 py-2.5 text-sm font-medium text-slate-300 rounded-md hover:bg-slate-800/50 hover:text-sky-300 smooth-transition">
                     <i class="w-5 mr-3 text-center fas fa-list"></i> {{ __('Coverage') }}
                 </a>
                 <a href="#reviews" class="flex items-center px-4 py-2.5 text-sm font-medium text-slate-300 rounded-md hover:bg-slate-800/50 hover:text-sky-300 smooth-transition">
                     <i class="w-5 mr-3 text-center fas fa-comments"></i> {{ __('Reviews') }}
                 </a>
                 <a href="#team" class="flex items-center px-4 py-2.5 text-sm font-medium text-slate-300 rounded-md hover:bg-slate-800/50 hover:text-sky-300 smooth-transition">
                     <i class="w-5 mr-3 text-center fas fa-users"></i> {{ __('Team') }}
                 </a>
                 <div class="pt-3 mt-3 space-y-2 border-t border-slate-800">
                     <a href="{{ route('login') }}" class="block w-full px-4 py-2.5 text-sm font-semibold text-slate-300 rounded-md border border-slate-700 hover:border-slate-600 hover:bg-slate-800/30 hover:text-sky-300 smooth-transition text-center">
                         {{ __('Sign In') }}
                     </a>
                     <a href="/donate" class="block w-full px-4 py-2.5 text-sm font-semibold text-white bg-sky-600 rounded-md hover:bg-sky-500 smooth-transition text-center">
                         {{ __('Donate') }}
                     </a>
                 </div>
             </div>
         </div>
     </div>
 </nav>
