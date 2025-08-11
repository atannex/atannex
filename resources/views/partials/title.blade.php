 @if($post->category)
 <a href="{{ route('post.show', [
        'year'     => $post->published_at->format('Y'),
        'month'    => $post->published_at->format('m'),
        'day'      => $post->published_at->format('d'),
        'category' => $post->category->slug,
        'slug'     => $post->slug
    ]) }}" class="hover-line">
     {{ Str::limit($post->title, 60) }}
 </a>
 @else
 <span>{{ Str::limit($post->title, 60) }}</span>
 @endif
