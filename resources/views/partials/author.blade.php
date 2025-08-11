 <a href="{{ route('page.index', $post->author->user->slug) }}">
     <i class="far fa-user"></i>
     {{ __('By - ') . $post->author->user->name }}
 </a>
