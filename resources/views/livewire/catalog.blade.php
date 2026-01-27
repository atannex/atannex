<div>
    <div class="filter filter--fixed">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="filter__content">

                        <button class="filter__menu" type="button">
                            <i class="ti ti-filter"></i>
                            {{ __('Filter') }}
                        </button>

                        <div class="filter__items">

                            <select class="filter__select" id="filter__category" name="category" wire:model.live="filters.category">
                                <option value="0">{{ __('All categories') }}</option>
                                @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>

                            <select class="filter__select" id="filter__region" name="region" wire:model.live="filters.region">
                                <option value="0">{{ __('All regions') }}</option>
                                @foreach ($regions as $region)
                                <option value="{{ $region->id }}">{{ $region->name }}</option>
                                @endforeach
                            </select>

                            <select class="filter__select" id="filter__author" name="author" wire:model.live="filters.author">
                                <option value="0">{{ __('All authors') }}</option>
                                @foreach ($authors as $author)
                                <option value="{{ $author->user->id }}">{{ $author->user->name }}</option>
                                @endforeach
                            </select>

                            <select class="filter__select" id="filter__duration" name="duration" wire:model.live="filters.duration">
                                <option value="">{{ __('Any duration') }}</option>
                                <option value="15">≤ 15 min</option>
                                <option value="30">≤ 30 min</option>
                                <option value="60">≤ 1 hour</option>
                                <option value="90">≤ 1.5 hours</option>
                                <option value="120">≤ 2 hours</option>
                                <option value="180">≤ 3 hours</option>
                            </select>

                            <select class="filter__select" id="filter__sort" name="sort" wire:model.live="filters.sort">
                                <option value="latest">{{ __('Newest first') }}</option>
                                <option value="oldest">{{ __('Oldest first') }}</option>
                                <option value="title">{{ __('Title A–Z') }}</option>
                            </select>

                        </div>

                        <button class="filter__btn" type="button" wire:click="applyFilters" wire:loading.attr="disabled">
                            <span wire:loading.remove>{{ __('Apply') }}</span>
                            <span wire:loading>{{ __('Applying…') }}</span>
                        </button>

                        <span class="filter__amount">
                            {{ __('Showing :perPage of :total', ['perPage' => $videos->count(), 'total' => $total]) }}
                        </span>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="section section--catalog">
        <div class="container">
            <div class="row g-3 g-xl-4">
                @forelse ($videos as $video)
                <div class="col-6 col-sm-4 col-lg-3 col-xl-2">
                    <div class="item">
                        <div class="item__cover">
                            <div class="d-flex justify-content-center">
                                <img src="{{ asset('storage/' . ltrim($video->image, '/')) }}" alt="{{ $video->title }}" class="img-fluid video-thumbnail" loading="lazy">
                            </div>
                            <a href="{{ route('video.show', $video->slug) }}" class="item__play">
                                <i class="ti ti-player-play-filled"></i>
                            </a>
                            @if($video->rating)
                            <span class="item__rate item__rate--green">{{ number_format($video->rating, 1) }}</span>
                            @endif
                            <button class="item__favorite" type="button">
                                <i class="ti ti-bookmark"></i>
                            </button>
                        </div>
                        <div class="item__content">
                            <h3 class="item__title">
                                <a href="{{ route('video.show', $video->slug) }}">{{ $video->title }}</a>
                            </h3>
                            <span class="item__category">
                                <a href="{{ route('page.index', $video->category->slug_path ?? $video->category->slug) }}">
                                    {{ $video->category->name }}
                                </a>
                            </span>
                        </div>
                    </div>
                </div>
                @empty
                <div class="py-5 text-center col-12">
                    <p class="text-muted">{{ __('No videos found matching your criteria.') }}</p>
                </div>
                @endforelse
            </div>

            <div class="mt-4 row">
                <div class="col-12">
                    {{ $videos->links() }}
                </div>
            </div>

        </div>
    </div>

</div>
