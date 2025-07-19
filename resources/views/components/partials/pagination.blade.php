@if ($paginator->hasPages())
<div id="pagination" class="mt-40 text-center th-pagination">
    <ul>
        {{-- Previous --}}
        @if (!$paginator->onFirstPage())
        <li>
            <a href="{{ $paginator->previousPageUrl() }}#pagination">
                <i class="fas fa-arrow-left"></i>
            </a>
        </li>
        @endif

        {{-- First page --}}
        <li>
            <a href="{{ $paginator->url(1) }}#pagination" @class(['active'=> $paginator->currentPage() === 1])>
                01
            </a>
        </li>

        {{-- Ellipsis before current window --}}
        @if ($paginator->currentPage() > 4)
        <li><span class="ellipsis">...</span></li>
        @endif

        {{-- Pages around current page --}}
        @foreach (range(max(2, $paginator->currentPage() - 2), min($paginator->lastPage() - 1, $paginator->currentPage() + 2)) as $page)
        <li>
            <a href="{{ $paginator->url($page) }}#pagination" @class(['active'=> $page === $paginator->currentPage()])>
                {{ str_pad($page, 2, '0', STR_PAD_LEFT) }}
            </a>
        </li>
        @endforeach

        {{-- Ellipsis after current window --}}
        @if ($paginator->currentPage() < $paginator->lastPage() - 3)
            <li><span class="ellipsis">...</span></li>
            @endif

            {{-- Last page --}}
            @if ($paginator->lastPage() > 1)
            <li>
                <a href="{{ $paginator->url($paginator->lastPage()) }}#pagination" @class(['active'=> $paginator->currentPage() === $paginator->lastPage()])>
                    {{ str_pad($paginator->lastPage(), 2, '0', STR_PAD_LEFT) }}
                </a>
            </li>
            @endif

            {{-- Next --}}
            @if ($paginator->hasMorePages())
            <li>
                <a href="{{ $paginator->nextPageUrl() }}#pagination">
                    <i class="fas fa-arrow-right"></i>
                </a>
            </li>
            @endif
    </ul>
</div>

{{-- Smooth scroll script --}}
<script>
    window.onload = function() {
        const pagination = document.getElementById('pagination');
        if (pagination) {
            pagination.scrollIntoView({
                behavior: 'smooth'
            });
        }
    };

</script>
@endif
