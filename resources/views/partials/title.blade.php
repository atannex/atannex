 @if($post->category)
 <a href="{{ route('post.show', [
        'slug_path' => $post->category->slug_path, 'slug' => $post->slug ]) }}" class="hover-line">
     {{ Str::limit($post->title, 60) }}
 </a>
 @else
 <span>{{ Str::limit($post->title, 60) }}</span>
 @endif
