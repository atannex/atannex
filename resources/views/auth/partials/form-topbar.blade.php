 <div class="hidden form-topbar lg:flex">
     <div class="desktop-topbar-text" style="display:flex;align-items:center;gap:8px;">
         <span class="live-dot"></span>
         <span style="font-size:0.7rem;letter-spacing:0.15em;text-transform:uppercase;color:#838181;font-family:'DM Sans',sans-serif;">
             {{ config('app.name') }}
         </span>
     </div>

     @if (Route::has('home'))
     <span style="font-family:'DM Sans',sans-serif;font-size:0.72rem;color:#838181;">
         <a href="{{ route('home') }}" target="_blank" rel="noopener noreferrer">
             {{ __('Home') }}
         </a>
     </span>
     @endif
 </div>
