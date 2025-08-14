<li class="th-comment-item" x-data="{ openReplies: true }">
    <div class="th-post-comment">
        <div class="comment-avater">
            <img src="{{ asset('assets/img/user_comment_img.jpg') }}" alt="{{ $comment->user->name }}">
        </div>

        <div class="comment-content">
            <div class="row">
                <div class="col-md-8">
                    <span class="commented-on">
                        <i class="fas fa-calendar-alt me-1"></i>
                        {{ $comment->created_at->format('d M, Y') }}
                    </span>
                    <small class="text-muted ms-2">
                        ({{ $comment->created_at->diffForHumans() }})
                    </small>

                    <h3 class="mt-1 name">{{ $comment->user->name }}</h3>
                    <p class="mb-0 text">{{ $comment->comment }}</p>
                </div>

                <div class="col-6 col-md-4 text-end">
                    <div class="gap-2 d-flex justify-content-end">
                        {{-- Reply --}}
                        <button type="button" class="p-0 text-white bg-transparent btn"
                            wire:click="$dispatch('reply-to-comment', { commentId: {{ $comment->id }} })"
                            aria-label="{{ __('Reply') }}">
                            <i class="fas fa-reply"></i>
                        </button>

                        {{-- Edit --}}
                        @can('update', $comment)
                            <button type="button" class="p-0 bg-transparent btn text-warning"
                                wire:click="edit({{ $comment->id }})"
                                aria-label="{{ __('Edit') }}">
                                <i class="fas fa-edit"></i>
                            </button>
                        @endcan

                        {{-- Delete --}}
                        @can('delete', $comment)
                            <button type="button" class="p-0 bg-transparent btn text-danger"
                                wire:click="delete({{ $comment->id }})"
                                wire:loading.attr="disabled"
                                aria-label="{{ __('Delete') }}">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        @endcan

                        {{-- Toggle replies --}}
                        @if ($comment->allReplies->isNotEmpty())
                            <button type="button" class="p-0 text-white bg-transparent btn"
                                @click="openReplies = !openReplies"
                                aria-label="{{ __('Toggle Replies') }}">
                                <i :class="openReplies ? 'fas fa-chevron-up' : 'fas fa-chevron-down'"></i>
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Reply form --}}
    @if ($parentId === $comment->id)
        <div class="mt-4 mb-4 ms-5">
            <div class="p-4 rounded quote-form-box position-relative bg-gray">
                <button type="button" class="top-0 m-2 btn btn-sm btn-light position-absolute end-0"
                    wire:click="cancelReply"
                    aria-label="{{ __('Cancel reply') }}">
                    <i class="fas fa-times"></i>
                </button>

                <h6 class="mb-3 form-title">
                    {{ __('Replying To') }} <i class="text-success">{{ $comment->user->name }}</i>
                </h6>

                <form wire:submit.prevent="submit" class="contact-form" aria-label="{{ __('Reply Form') }}">
                    <div class="mb-3">
                        <textarea wire:model.defer="comment" class="form-control" rows="3" placeholder="{{ __('Type your message here...') }}"></textarea>
                        @error('comment')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-btn">
                        <button type="submit" class="th-btn" wire:loading.attr="disabled">
                            <span wire:loading.remove>{{ __('Post Reply') }}</span>
                            <span wire:loading>{{ __('Posting...') }}</span>
                            <i class="fas fa-arrow-up-right ms-2"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    {{-- Recursive replies --}}
    @if ($comment->allReplies->isNotEmpty())
        <ul class="mt-2 children ms-4" x-show="openReplies" x-transition>
            @foreach($comment->allReplies as $child)
                @include('livewire.forms._comment-item', ['comment' => $child])
            @endforeach
        </ul>
    @endif
</li>
