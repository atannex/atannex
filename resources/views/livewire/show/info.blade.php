<div class="flex-wrap gap-2 blog-info-wrap d-flex align-items-center gap-md-3">

    @auth
    <div class="gap-2 engagement-group d-flex">
        <button class="blog-info d-flex align-items-center gap-1 {{ $post->isLikedBy(auth()->user()) ? 'fw-bold text-primary' : '' }}" wire:click="toggleLike" wire:loading.attr="disabled" type="button">
            <i class="fas fa-thumbs-up"></i>
            <span>{{ $likeCount ?? 0 }}</span>
        </button>

        <button class="blog-info d-flex align-items-center gap-1 {{ $post->isDislikedBy(auth()->user()) ? 'fw-bold text-danger' : '' }}" wire:click="toggleDislike" wire:loading.attr="disabled" type="button">
            <i class="fas fa-thumbs-down"></i>
            <span>{{ $dislikeCount ?? 0 }}</span>
        </button>
    </div>

    <div class="stats-group">
        <button class="gap-1 blog-info d-flex align-items-center">
            <i class="fas fa-eye"></i>
            <span>126k</span>
        </button>
        <button class="gap-1 blog-info d-flex align-items-center">
            <i class="fas fa-share-nodes"></i>
            <span>2k</span>
        </button>
        <span class="gap-1 blog-info d-flex align-items-center">
            <i class="fas fa-comments"></i>
            <span>1.2k</span>
        </span>
        <span class="gap-1 blog-info d-flex align-items-center">
    @for ($i = 1; $i <= 5; $i++)
        <i
            class="fas fa-star cursor-pointer {{ $i <= $rating ? 'text-yellow-500' : 'text-gray-300' }}"
            wire:click="$dispatch('post-rated', {{ $i }})">
        </i>
    @endfor
    <span class="ms-2">{{ $averageRating ?? 0 }}</span>
</span>


    </div>
    <style>
        .blog-info-wrap {
            background: rgb(22, 22, 22);
            padding: 1rem;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(20, 20, 20, 0);
            max-width: 100%;
        }

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
            cursor: pointer;
        }

        .blog-info:hover {
            background: #303031;
            border-color: #3b3d3f;
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .blog-info i {
            font-size: 0.875rem;
            opacity: 0.8;
        }

        /* Specific icon colors */
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

        .blog-info .fa-star {
            color: #ffc107;
        }

        /* Active states for buttons */
        button.blog-info:active {
            transform: translateY(0);
        }

        button.blog-info.active .fa-thumbs-up,
        button.blog-info.active .fa-thumbs-down {
            opacity: 1;
        }

        /* Engagement group styling */
        .engagement-group {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        .stats-group {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
            margin-left: auto;
        }

        /* Responsive breakpoints */
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
                flex: 1 1 auto;
                min-width: 0;
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

        @media (max-width: 768px) {
            .divider {
                display: none;
            }
        }

        /* Demo container */
        .demo-container {
            max-width: 900px;
            margin: 0 auto;
        }

        h1 {
            margin-bottom: 2rem;
            color: #212529;
        }

    </style>
    @endauth
</div>
