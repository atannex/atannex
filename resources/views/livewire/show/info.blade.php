<div class="flex-wrap gap-2 blog-info-wrap d-flex align-items-center gap-md-3">
    <div class="gap-2 d-flex engagement-group">
        <button type="button" wire:click="like" wire:target="like" wire:loading.attr="disabled" wire:loading.class="opacity-50" class="blog-info d-flex align-items-center gap-1
            {{ $liked ? 'fw-bold text-primary' : 'text-muted' }}" aria-pressed="{{ $liked ? 'true' : 'false' }}" aria-label="Like this story">
            <i class="fas fa-thumbs-up"></i>

            <span class="text-nowrap">
                {{ format_count(number_format($likeCount)) }}
            </span>
        </button>
        <button type="button" wire:click="dislike" wire:target="dislike" wire:loading.attr="disabled" wire:loading.class="opacity-50" class="blog-info d-flex align-items-center gap-1
            {{ $disliked ? 'fw-bold text-danger' : 'text-muted' }}" aria-pressed="{{ $disliked ? 'true' : 'false' }}" aria-label="Dislike this story">
            <i class="fas fa-thumbs-down"></i>

            <span class="text-nowrap">
                {{ format_count(number_format($dislikeCount)) }}
            </span>
        </button>
    </div>
    <div class="stats-group">
        <div class="gap-2 d-flex engagement-group">
            <span class="gap-1 blog-info d-flex align-items-center">
                <i class="fas fa-eye"></i>
                <span>
                    {{ format_count(number_format($totalViews)) }}
                </span>
            </span>
            <span class="gap-1 blog-info d-flex align-items-center">
                <i class="fas fa-share-nodes"></i>
                <span>
                    {{ format_count(number_format($totalShares)) }}
                </span>
            </span>
            <span class="gap-1 blog-info d-flex align-items-center">
                <i class="fas fa-comments"></i>
                <span>
                    {{ format_count(number_format($module->post->comments_count)) }}
                </span>
            </span>
        </div>
        <div class="gap-2 blog-info d-flex align-items-center">
            <div class="gap-1 d-flex align-items-center rating-stars">
                @for ($i = 1; $i <= 5; $i++) <i wire:key="star-{{ $post->id }}-{{ $i }}" wire:click="rate({{ $i }})" wire:loading.class="is-loading" wire:target="rate" class="fas fa-star rating-star
                        {{ $i <= ($myRating ?? 0) ? 'is-rated' : '' }}
                        {{ $myRating === $i ? 'is-selected' : '' }}" role="button" tabindex="0" aria-label="Rate {{ $i }} out of 5"></i>
                    @endfor
            </div>
            <span class="ms-2 small rating-meta">
                {{ number_format($averageRating, 1) }}
                @if ($ratingCount > 0)
                <span class="rating-count">
                    ({{ $ratingCount }})
                </span>
                @endif
            </span>

        </div>
    </div>


    <style>
        /* =====================================================
           Blog Info Wrapper
        ===================================================== */

        .blog-info-wrap {
            background: #161616;
            padding: 1rem;
            border-radius: 12px;
            max-width: 100%;
        }

        /* =====================================================
           Blog Info Card
        ===================================================== */

        .blog-info {
            background: #2b2c2c;
            border: 1px solid #0a0a0aad;
            border-radius: 8px;
            padding: 0.5rem 0.875rem;
            font-size: 0.875rem;
            font-weight: 500;
            color: #f1ebeb;
            transition: all 0.2s ease;
            white-space: nowrap;
        }

        .blog-info:hover {
            background: #303031;
            border-color: #3b3d3f;
            transform: translateY(-1px);
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
        }

        /* =====================================================
           Rating Stars
        ===================================================== */

        .rating-stars {
            --star-default: #5c5f63;
            --star-rated: #ffc107;
            --star-hover: #ffd54f;
        }

        .rating-stars .rating-star {
            font-size: 1rem;
            color: var(--star-default);
            cursor: pointer;
            transition: color 0.15s ease, transform 0.15s ease;
        }

        /* Stars at or below the user's rating */
        .rating-stars .rating-star.is-rated {
            color: var(--star-rated) !important;
        }

        /* The exact star the user selected gets a pop */
        .rating-stars .rating-star.is-selected {
            color: var(--star-hover) !important;
            transform: scale(1.2);
        }

        /* On hover: light up all stars, then dim those after the hovered one */
        .rating-stars:hover .rating-star {
            color: var(--star-rated);
        }

        .rating-stars .rating-star:hover~.rating-star {
            color: var(--star-default);
        }

        .rating-stars .rating-star:hover {
            color: var(--star-hover);
            transform: scale(1.2);
        }

        /* Loading: disable interaction while Livewire is processing */
        .rating-stars .rating-star.is-loading {
            opacity: 0.35;
            pointer-events: none;
            cursor: not-allowed;
        }

        .rating-meta {
            color: #9ca3af;
            font-size: 0.8125rem;
            user-select: none;
        }

        .rating-count {
            opacity: 0.7;
        }

        /* =====================================================
           Non-Rating Icon Styling
        ===================================================== */

        .blog-info i:not(.rating-star) {
            font-size: 0.875rem;
            opacity: 0.85;
        }

        .blog-info .fa-thumbs-up {
            color: #0d6efd;
        }

        .blog-info .fa-thumbs-down {
            color: #dc3545;
        }

        .blog-info .fa-eye {
            color: #6c757d;
        }

        .blog-info .fa-share-nodes {
            color: #0dcaf0;
        }

        .blog-info .fa-comments {
            color: #198754;
        }

        /* =====================================================
           Layout Groups
        ===================================================== */

        .engagement-group,
        .stats-group {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        .stats-group {
            margin-left: auto;
        }

        /* =====================================================
           Responsive
        ===================================================== */

        @media (max-width: 768px) {
            .blog-info-wrap {
                padding: 0.75rem;
            }

            .stats-group {
                margin-left: 0;
                width: 100%;
                justify-content: flex-start;
            }

            .blog-info {
                padding: 0.5rem 0.75rem;
                font-size: 0.8125rem;
            }
        }

        @media (max-width: 576px) {
            .blog-info-wrap {
                padding: 0.625rem;
            }

            .blog-info {
                padding: 0.4rem 0.625rem;
                font-size: 0.75rem;
                width: 100%;
                justify-content: center;
            }

            .engagement-group,
            .stats-group {
                width: 100%;
                gap: 0.375rem;
            }

            .engagement-group .blog-info {
                flex: 1;
            }
        }

    </style>
</div>
