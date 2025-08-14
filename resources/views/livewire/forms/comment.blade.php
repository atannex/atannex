<div>
    <div class="th-comments-wrap">
        <h2 class="blog-inner-title h3">
            {{ __('Comments') }} ({{ $comments->total() }})
        </h2>

        <ul class="comment-list">
            @forelse($comments as $comment)
            @include('livewire.forms._comment-item', ['comment' => $comment])
            @empty
            <li>{{ __('No comments yet. Be the first to comment!') }}</li>
            @endforelse
        </ul>

        <div class="mt-3">
            <x-partials.pagination :paginator="$comments" />
        </div>
    </div>

    {{-- Only show form if not replying --}}
    @if (!$parentId)
    <div class="mt-4 th-comment-form">
        <div class="mb-3 form-title">
            <h3 class="blog-inner-title">{{ __('Leave a Comment') }}</h3>
            <p class="form-text">
                {{ __("Your email address will not be published. Required fields are marked *") }}
            </p>
        </div>

        @if (session()->has('message'))
        <div class="alert alert-success">
            {{ session('message') }}
        </div>
        @endif

        <div class="comment-form-container">
            <form wire:submit.prevent="submit" class="contact-form">
                <div class="mb-1">
                    <textarea wire:model.defer="comment" placeholder="{{ __('Write a Comment*') }}" class="auto-expand-textarea" style="
        height: auto;
        min-height: 1.5em;  /* approximate single line height */
        overflow:hidden;
        resize:none;
        border: none;
        border-bottom: 2px solid #ffffff;
        border-radius: 0;
        outline: none;
    "></textarea>

                    @error('comment')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-btn">
                    <button type="submit" class="th-btn" wire:loading.attr="disabled">
                        <span wire:loading.remove>{{ __('Comment') }}</span>
                        <span wire:loading>{{ __('Posting...') }}</span>
                        <i class="fas fa-arrow-up-right ms-2"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>
