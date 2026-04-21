 <header class="modal-navbar-header">
     <a href="{{ route('landing') }}" class="flex items-center gap-3 transition hover:opacity-80">
         <div class="flex items-center justify-center w-10 h-10 text-lg font-bold text-white rounded-lg bg-gradient-to-br">
             <img src="{{ asset('storage/' . $global['logo']?->image) }}" alt="{{ config('app.name') }}">
         </div>
     </a>
     <button class="modal-nav-trigger" id="modalNavTrigger" aria-label="Open navigation">
         <i class="fas fa-bars"></i>
     </button>
 </header>

 <div class="modal-nav-overlay" id="modalNavOverlay"></div>

 <nav class="modal-bottom-nav" id="modalBottomNav">
     <div class="modal-nav-header">
         <div class="modal-nav-title">
             <i class="fas fa-compass" style="margin-right: 0.5rem;"></i>
             {{ __("Navigation") }}
         </div>
         <button class="modal-close-btn" id="modalNavClose" aria-label="Close navigation">
             <i class="fas fa-times"></i>
         </button>
     </div>

     <div class="modal-nav-content">
         <div class="modal-nav-section">
             <div class="modal-section-title">
                 <i class="fas fa-star"></i>
                 {{ __('Main Navigation') }}
             </div>
             <ul class="modal-nav-items">
                 <li class="modal-nav-item">
                     <a href="{{ route('landing') }}">
                         <i class="modal-nav-item-icon fas fa-info-circle"></i>
                         <span>{{ __('Home') }}</span>
                     </a>
                 </li>
                 <li class="modal-nav-item">
                     <a href="#about">
                         <i class="modal-nav-item-icon fas fa-info-circle"></i>
                         <span>{{ __('About Us') }}</span>
                     </a>
                 </li>
                 <li class="modal-nav-item">
                     <a href="{{ route('testimonials') }}">
                         <i class="modal-nav-item-icon fas fa-newspaper"></i>
                         <span>{{ __('Testimonials') }}</span>
                     </a>
                 </li>
                 <li class="modal-nav-item">
                     <a href="#coverage">
                         <i class="modal-nav-item-icon fas fa-map"></i>
                         <span>{{ __('Faqs') }}</span>
                     </a>
                 </li>
                 <li class="modal-nav-item">
                     <a href="#contact">
                         <i class="modal-nav-item-icon fas fa-envelope"></i>
                         <span>{{ __('Contact Us') }}</span>
                     </a>
                 </li>
             </ul>
         </div>

         {{-- Content Section --}}
         {{-- <div class="modal-nav-section">
             <div class="modal-section-title">
                 <i class="fas fa-align-left"></i>
                 Content
             </div>
             <ul class="modal-nav-items">
                 <li class="modal-nav-item">
                     <a href="{{ route('home') }}">
         <i class="modal-nav-item-icon fas fa-fire"></i>
         <span>Featured Stories</span>
         </a>
         </li>
         <li class="modal-nav-item">
             <a href="#coverage">
                 <i class="modal-nav-item-icon fas fa-video"></i>
                 <span>Video Reports</span>
             </a>
         </li>
         <li class="modal-nav-item">
             <a href="#archive">
                 <i class="modal-nav-item-icon fas fa-history"></i>
                 <span>Archive</span>
             </a>
         </li>
         </ul>
     </div> --}}

     {{-- Community Section --}}
     {{-- <div class="modal-nav-section">
             <div class="modal-section-title">
                 <i class="fas fa-users"></i>
                 Community
             </div>
             <ul class="modal-nav-items">
                 <li class="modal-nav-item">
                     <a href="/diaspora">
                         <i class="modal-nav-item-icon fas fa-globe"></i>
                         <span>Diaspora Program</span>
                     </a>
                 </li>
                 <li class="modal-nav-item">
                     <a href="/advertise">
                         <i class="modal-nav-item-icon fas fa-bullhorn"></i>
                         <span>Advertise</span>
                     </a>
                 </li>
                 <li class="modal-nav-item">
                     <a href="/careers">
                         <i class="modal-nav-item-icon fas fa-briefcase"></i>
                         <span>Careers</span>
                     </a>
                 </li>
             </ul>
         </div> --}}

     {{-- CTA Section --}}
     <div class="modal-nav-cta-section">
         <a href="/subscribe" class="modal-btn-cta modal-btn-primary">
             <i class="fas fa-envelope"></i> {{ __('Subscribe') }}
         </a>
         <a href="/donate" class="modal-btn-cta modal-btn-secondary">
             <i class="fas fa-heart"></i> {{ _('Donate') }}
         </a>
     </div>

     {{-- Info Section - Three Column Layout --}}
     <div class="modal-nav-info-section modal-info-grid">
         <div class="modal-info-item">
             <div class="modal-info-icon">
                 <i class="fas fa-phone"></i>
             </div>
             <div class="modal-info-content">
                 <div class="modal-info-label">Call Us</div>
                 <div class="modal-info-value">+237 690 000 000</div>
             </div>
         </div>
         <div class="modal-info-item">
             <div class="modal-info-icon">
                 <i class="fas fa-envelope"></i>
             </div>
             <div class="modal-info-content">
                 <div class="modal-info-label">Email</div>
                 <div class="modal-info-value">hello@atannex.cm</div>
             </div>
         </div>
         <div class="modal-info-item">
             <div class="modal-info-icon">
                 <i class="fas fa-map-marker-alt"></i>
             </div>
             <div class="modal-info-content">
                 <div class="modal-info-label">Location</div>
                 <div class="modal-info-value">Fontem, Lebialem</div>
             </div>
         </div>
     </div>
     </div>
 </nav>
