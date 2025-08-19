<div class="blog-info-wrap">
    <div class="blog-metrics ms-sm-auto">
        <button wire:click="{{ $isLiked ? 'unlike' : 'like' }}" class="blog-info {{ $isLiked ? 'text-blue-500' : 'text-gray-500' }} hover:text-blue-700 transition-colors">
            {{ number_format($likesCount) }} <i class="fas fa-thumbs-up"></i>
        </button>

        <span class="text-gray-500 blog-info">
            {{ number_format($viewsCount) }} <i class="fas fa-eye"></i>
        </span>

        <!-- Shares -->
        <span class="blog-info">
            12k <i class="fas fa-share-nodes"></i>
        </span>

        <button wire:click="toggleRate" class="blog-info rating {{ $isRated ? 'text-yellow-500' : 'text-gray-500' }} hover:text-yellow-700 transition-colors">
            {{ number_format($ratingCount) }} ({{ number_format($averageRating, 1) }})
            @for ($i = 1; $i <= 5; $i++) <i class="{{ $isRated && $userRating >= $i ? 'fas fa-star' : 'far fa-star' }}"></i>
                @endfor
        </button>

    </div>
</div>
