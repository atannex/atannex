 <div class="widget">
     <h3 class="widget_title">{{ __("Recent Posts") }}</h3>
     <div class="recent-post-wrap">
         @forelse ($recentPosts as $post)
         <div class="recent-post">
             <div class="media-img">

                 @include('partials.image')
             </div>
             <div class="media-body">
                 <h4 class="post-title">

                     @include('partials.title')

                 </h4>
             </div>
         </div>
         @empty
         <p class="text-muted">{{ __("No recent posts available.") }}</p>
         @endforelse
     </div>
 </div>
