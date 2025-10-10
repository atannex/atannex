<section class="space">
    <div class="container">
        <div class="row">
            @foreach ($section->widgets as $widget)
            @include("widgets.{$widget->slug}", ['widget' => $widget])
            @endforeach
        </div>
    </div>
</section>
