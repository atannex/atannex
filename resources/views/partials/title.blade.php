 @if($post->category)
 <a href="{{ route('page.index', ['slug' => $post->slug_path ]) }}" class="hover-line">
     {{ Str::limit($post->title, 60) }}
 </a>
 @else
 <span>{{ Str::limit($post->title, 60) }}</span>
 @endif
