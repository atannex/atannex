@if ($paginator->hasPages())
<div id="pagination" class="mt-40 text-center th-pagination">
    <ul>
        @if (!$paginator->onFirstPage())
        <li>
            <a href="{{ $paginator->previousPageUrl() }}#pagination" aria-label="Previous Page">
                <i class="fas fa-arrow-left"></i>
            </a>
        </li>
        @endif

        <li>
            <a href="{{ $paginator->url(1) }}#pagination" @class(['active'=> $paginator->currentPage() === 1])>
                01
            </a>
        </li>

        @if ($paginator->currentPage() > 4)
        <li><span class="ellipsis">...</span></li>
        @endif

        @foreach (range(max(2, $paginator->currentPage() - 2), min($paginator->lastPage() - 1, $paginator->currentPage() + 2)) as $page)
        <li>
            <a href="{{ $paginator->url($page) }}#pagination" @class(['active'=> $page === $paginator->currentPage()])>
                {{ str_pad($page, 2, '0', STR_PAD_LEFT) }}
            </a>
        </li>
        @endforeach

        @if ($paginator->currentPage() < $paginator->lastPage() - 3)
            <li><span class="ellipsis">...</span></li>
            @endif

            @if ($paginator->lastPage() > 1)
            <li>
                <a href="{{ $paginator->url($paginator->lastPage()) }}#pagination" @class(['active'=> $paginator->currentPage() === $paginator->lastPage()])>
                    {{ str_pad($paginator->lastPage(), 2, '0', STR_PAD_LEFT) }}
                </a>
            </li>
            @endif

            @if ($paginator->hasMorePages())
            <li>
                <a href="{{ $paginator->nextPageUrl() }}#pagination" aria-label="Next Page">
                    <i class="fas fa-arrow-right"></i>
                </a>
            </li>
            @endif
    </ul>
</div>
@endif
