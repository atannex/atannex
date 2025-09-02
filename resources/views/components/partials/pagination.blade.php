@if ($paginator->hasPages())
<div id="pagination" class="mt-40 text-center th-pagination">
    <ul>
        {{-- Previous Page Link --}}
        @if (!$paginator->onFirstPage())
        <li>
            <a href="{{ $paginator->previousPageUrl() }}#pagination" aria-label="Previous Page">
                <i class="fas fa-arrow-left"></i>
            </a>
        </li>
        @endif

        {{-- First Page Link --}}
        <li>
            <a href="{{ $paginator->url(1) }}#pagination" @class(['active'=> $paginator->currentPage() === 1])>
                01
            </a>
        </li>

        {{-- Ellipsis Before Current Window --}}
        @if ($paginator->currentPage() > 4)
        <li><span class="ellipsis">...</span></li>
        @endif

        {{-- Pages Around Current Page --}}
        @foreach (range(max(2, $paginator->currentPage() - 2), min($paginator->lastPage() - 1, $paginator->currentPage() + 2)) as $page)
        <li>
            <a href="{{ $paginator->url($page) }}#pagination" @class(['active'=> $page === $paginator->currentPage()])>
                {{ str_pad($page, 2, '0', STR_PAD_LEFT) }}
            </a>
        </li>
        @endforeach

        {{-- Ellipsis After Current Window --}}
        @if ($paginator->currentPage() < $paginator->lastPage() - 3)
            <li><span class="ellipsis">...</span></li>
            @endif

            {{-- Last Page Link --}}
            @if ($paginator->lastPage() > 1)
            <li>
                <a href="{{ $paginator->url($paginator->lastPage()) }}#pagination" @class(['active'=> $paginator->currentPage() === $paginator->lastPage()])>
                    {{ str_pad($paginator->lastPage(), 2, '0', STR_PAD_LEFT) }}
                </a>
            </li>
            @endif

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
            <li>
                <a href="{{ $paginator->nextPageUrl() }}#pagination" aria-label="Next Page">
                    <i class="fas fa-arrow-right"></i>
                </a>
            </li>
            @endif
    </ul>
</div>

{{-- Smooth Scroll Script --}}
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const pagination = document.getElementById('pagination');
        if (pagination) {
            pagination.scrollIntoView({
                behavior: 'smooth'
            });
        }
    });

</script>
@endif
