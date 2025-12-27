<div class="widget widget_categories">
    <h3 class="widget_title">
        {{ __('Categories') }}
    </h3>
    <ul>
        @foreach ($relatedCategories as $category)
        @php($data = displayData($category))
        <li>
            <a href="{{ route('page.index', $category->slug_path) }}" @if($data['bgSrc']) data-bg-src="{{ $data['bgSrc'] }}" @endif>
                {{ $data['label'] }}
            </a>
        </li>
        @endforeach
    </ul>
</div>
