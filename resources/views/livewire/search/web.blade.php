<div>
    <div class="popup-search-box" wire:ignore.self>

        <button type="button" class="searchClose" aria-label="Close Search">
            <i class="fal fa-times"></i>
        </button>

        <form wire:submit.prevent="query">
            <div class="d-flex">
                <input wire:model.live.debounce.300ms="query" type="text" placeholder="What are you looking for?" class="form-control">
                <button type="submit" wire:loading.attr="disabled" aria-label="Search">
                    <i class="fal fa-search"></i>
                </button>
            </div>

            <div class="pt-4 mb-5" style="min-height: 200px;">
                @if (!empty($query))
                <div wire:loading class="px-3 py-2 text-muted fst-italic">
                    {{ __(" Searching... ") }}
                </div>

                <div wire:loading.remove>
                    @if ($items->isEmpty())
                    <div class="px-3 py-2 text-muted fst-italic">
                        {{ __("No posts found matching your search.") }}
                    </div>
                    @else
                    <div class="d-flex justify-content-center">
                        <div>
                            @foreach($items as $post)
                            <div class="mb-4 d-flex align-items-start">
                                <a href="{{ route('page.index', ['slug' => $post->slug_path ]) }}" class="me-3" style="flex-shrink: 0;">
                                    <img src="{{ asset('storage/' . $post->image) }}" alt="{{ $post->title }}" class="rounded-circle" style="width: 45px; height: 45px; object-fit: cover;">
                                </a>
                                <div>
                                    <h6 class="mb-1" style="font-size: 0.95rem;">
                                        <a href="{{ route('page.index', ['slug' => $post->slug_path ]) }}" class="text-white text-decoration-none fw-semibold">
                                            {{ Str::limit($post->title, 90) }}
                                        </a>
                                    </h6>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
                @endif
            </div>
        </form>
    </div>
</div>
