@extends('components.layouts.guest')

@section('og:title', seo_title())

@section('guest')

<x-home.recent :byRecent="$byRecent" />

<x-home.region :byRegion="$byRegion" />

<x-home.rulers :byRuler="$byRuler" />

<x-home.news :byNews="$byNews" />


<x-home.video :byNews="$byNews" />

<section class="mt-4 space-bottom">
    <div class="container">
        <div class="row">

            <div class="col-xl-8">

                <x-home.environment :byEnvironment="$byEnvironment" />

                <x-home.community :byCommunity="$byCommunity" />

            </div>

            <x-home.history-sidebar :byHistory="$byHistory" />

        </div>
    </div>
</section>

@endsection
