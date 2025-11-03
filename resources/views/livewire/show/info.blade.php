<div>
    <div class="gap-3 blog-info-wrap d-flex align-items-center">

        <!-- Likes -->
        <button type="button" wire:click="{{ $isLiked ? 'unlike' : 'like' }}" wire:loading.attr="disabled" class="gap-1 blog-info like-btn ms-sm-auto d-flex align-items-center" aria-label="{{ $isLiked ? 'Unlike this post' : 'Like this post' }}" title="{{ $isLiked ? 'Unlike' : 'Like' }}">
            <span class="fw-medium">{{ number_format($likesCount) }}</span>
            <i class="fas fa-thumbs-up {{ $isLiked ? 'active-like' : '' }}"></i>
        </button>

        <!-- Views -->
        <div class="gap-1 blog-info d-flex align-items-center" aria-label="Views">
            <span class="fw-medium">{{ number_format($viewsCount) }}</span>
            <i class="fas fa-eye"></i>
        </div>

        <!-- Shares -->
        <button type="button" wire:click="share" wire:loading.attr="disabled" class="gap-1 blog-info share-btn d-flex align-items-center" aria-label="Share this article">
            <span class="fw-medium">{{ number_format($sharesCount) }}</span>
            <i class="fas fa-share-nodes"></i>
        </button>

        <!-- Rating -->
        <div class="gap-2 blog-info rating d-flex align-items-center" aria-label="Article rating">
            <div class="gap-1 d-flex">
                @for ($i = 1; $i <= 5; $i++) <button type="button" wire:click="rate({{ $i }})" wire:loading.attr="disabled" class="star-btn" aria-label="Rate {{ $i }} star{{ $i > 1 ? 's' : '' }}">
                    <i class="fas fa-star {{ $userRating >= $i ? 'active' : '' }}"></i>
                    </button>
                    @endfor
            </div>

            <span class="rating-summary small text-muted">
                {{ number_format($averageRating, 1) }}/5 ({{ number_format($ratingCount) }})
            </span>
        </div>

    </div>

    <style>
        .blog-info .fa {
            font-size: 1.1rem;
            transition: transform 0.2s;
        }

        .blog-info button {
            background: transparent;
            border: none;
            padding: 0;
            cursor: pointer;
        }

        .blog-info button:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .share-btn:hover i,
        .like-btn:hover i {
            transform: scale(1.1);
        }

        /* Rating Stars */
        .rating .fa-star {
            opacity: 0.35;
            transition: opacity 0.2s, transform 0.2s;
        }

        .rating .fa-star:hover,
        .rating .fa-star.active {
            opacity: 1;
            transform: scale(1.1);
            color: #ffc107;
        }

        /* Active Like Color */
        .active-like {
            color: #0d6efd;
        }

    </style>

    @script
    <script>
        $wire.on('shared', ({
            count
        }) => {
            Alpine.toast ? .(`Shared! Now ${count}`, {
                variant: 'success'
            });
        });

    </script>
    @endscript

</div>
