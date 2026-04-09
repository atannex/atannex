<div>
    <div class="mb-5 widget widget_search">

        <form class="search-form" role="search" aria-label="Blog search">

            <label for="search-input" class="sr-only">
                {{ __("Search blog posts") }}
            </label>

            <input id="search-input" type="text" wire:model.live="query" placeholder="Enter keyword" class="form-control" aria-describedby="search-button">

            <button id="search-button" type="button" disabled aria-disabled="true" class="ml-2">
                <i class="fas fa-search" aria-hidden="true"></i>
                <span class="sr-only">
                    {{ __('Search') }}
                </span>
            </button>

        </form>
    </div>

    @if (!empty($query))

    <div wire:loading class="px-3 py-2 italic text-gray-400">
        {{ __("Searching...") }}
    </div>

    <div class="widget" wire:loading.remove>
        @if($items->isEmpty())
        <p class="px-3 py-2 text-sm italic text-gray-600">
            {{ __("No posts found matching your search.") }}
        </p>
        @else
        <div class="space-y-4 recent-post-wrap">
            @foreach($items as $post)
            <article class="flex gap-3 recent-post">
                <div class="w-20 h-20 overflow-hidden rounded media-img">
                    <a href="{{ route('posts.show', ['slug' => $post->slug_path ]) }}">
                        <img src="{{ asset('storage/' . $post->image) }}" alt="{{ $post->title }}" class="object-cover w-full h-full">
                    </a>
                </div>

                <div class="media-body">
                    <h4 class="text-sm font-semibold leading-tight post-title">
                        <a href="{{ route('posts.show', ['slug' => $post->slug_path ]) }}" class="hover:underline text-light" aria-disabled="true">
                            {{ Str::limit($post->title, 40) }}
                        </a>
                    </h4>

                    @include('partials.date', ['post' => $post])

                </div>
            </article>
            @endforeach
        </div>
        @endif
    </div>
    <br>
    @endif
</div>
