@foreach ($widget->tabs as $tab)
<div class="mb-4 col-xl-6 mb-xl-0">
    <div class="row gy-4">
        @if (!empty($tab['content']))

        @foreach ($tab['content'] as $post)
        <div class="dark-theme img-overlay2">

            @include('components.posts.post-card', ['post' => $post])
        </div>

        @endforeach
        @elseif (!empty($tab['entities']))

        @foreach ($tab['entities'] as $region)

        @foreach ($region['posts'] as $post)
        <div class="dark-theme img-overlay2">

            @include('components.posts.post-card', ['post' => $post])
        </div>

        @endforeach
        @endforeach
        @endif
    </div>
</div>
@endforeach
