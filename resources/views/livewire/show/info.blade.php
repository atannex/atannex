<div class="blog-info-wrap">
    <div class="blog-metrics ms-sm-auto">

        <!-- Likes -->
        <button class="blog-info" wire:click="toggleLike">
            {{ $likesCount }}
            <i class="fas fa-thumbs-up" style="color: {{ $isLikedByUser ? 'blue' : 'gray' }}"></i>
        </button>

        <!-- Views -->
        <span class="blog-info">
            126k <i class="fas fa-eye"></i>
        </span>

        <!-- Shares -->
        <span class="blog-info">
            12k <i class="fas fa-share-nodes"></i>
        </span>

        <!-- Rating -->
        <span class="blog-info rating">
            4.5 <i class="fas fa-star"></i>
        </span>
    </div>
</div>
