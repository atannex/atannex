@foreach ($widget->tabs as $tab)
<div class="col-xl-6">
    <div class="row gy-4">
        @if (!empty($tab['content']))

        @foreach ($tab['content'] as $post)
        <div class="col-xl-6 col-md-6 dark-theme img-overlay2">

            @include('components.post-card', ['post' => $post])
        </div>

        @endforeach
        @elseif (!empty($tab['entities']))

        @foreach ($tab['entities'] as $region)

        @foreach ($region['posts'] as $post)
        <div class="col-xl-6 col-md-6 dark-theme img-overlay2">

            @include('components.post-card', ['post' => $post])
        </div>

        @endforeach
        @endforeach
        @endif
    </div>
</div>
@endforeach
