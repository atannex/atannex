@if ($reviews->hasPages())
<div class="paginator-mob paginator-mob--comments">
    <span class="paginator-mob__pages">
        {{ $reviews->currentPage() }} .{{ __(' of ') }}. {{ $reviews->lastPage() }}
    </span>

    <ul class="paginator-mob__nav">

        <li>
            <a href="javascript:void(0)" wire:click.prevent="previousPage">
                <i class="ti ti-chevron-left"></i>
                <span>{{ __('Prev') }}</span>
            </a>
        </li>

        <li>
            <a href="javascript:void(0)" wire:click.prevent="nextPage">
                <span>{{ __("Next") }}</span>
                <i class="ti ti-chevron-right"></i>
            </a>
        </li>
    </ul>
</div>

<ul class="paginator paginator--comments">

    <li class="paginator__item paginator__item--prev">
        <a href="javascript:void(0)" wire:click.prevent="previousPage">
            <i class="ti ti-chevron-left"></i>
        </a>
    </li>

    @for ($i = 1; $i <= $reviews->lastPage(); $i++)
        @if ($i == 1 || $i == $reviews->lastPage() || ($i >= $reviews->currentPage() - 2 && $i <= $reviews->currentPage() + 2))
            <li class="paginator__item {{ $i == $reviews->currentPage() ? 'paginator__item--active' : '' }}">
                <a href="javascript:void(0)" wire:click.prevent="gotoPage({{ $i }})">
                    {{ $i }}
                </a>
            </li>
            @elseif ($i == 2 && $reviews->currentPage() - 2 > 2)
            <li class="paginator__item">
                <span>
                    {{ __("...") }}
                </span>
            </li>
            @elseif ($i == $reviews->lastPage() - 1 && $reviews->currentPage() + 2 < $reviews->lastPage() - 1)
                <li class="paginator__item">
                    <span>
                        {{ __("...") }}
                    </span>
                </li>
                @endif
                @endfor

                <li class="paginator__item paginator__item--next">
                    <a href="javascript:void(0)" wire:click.prevent="nextPage">
                        <i class="ti ti-chevron-right"></i>
                    </a>
                </li>
</ul>
@endif
