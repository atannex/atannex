<div class="widget widget_categories">
    <h3 class="widget_title">
        {{ __('Categories') }}
    </h3>
    <ul>
        @foreach ($relatedCategories as $category)
        @php($data = category_display_data($category))
        <li>
            <a href="{{ route('categories.show', $category->slug_path) }}" @if($data['bgSrc']) data-bg-src="{{ $data['bgSrc'] }}" @endif>
                {{ $data['label'] }}
            </a>
        </li>
        @endforeach
    </ul>
</div>
