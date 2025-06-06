@extends('layouts.client')
@section('title')

    <title>{{ $history->title }}</title>
    <meta property="og:url"                content="{{ route('history') }}" />
    <meta property="og:type"               content="article" />
    <meta property="og:title"              content="{{ $history->title }}" />
    <meta property="og:description"        content="კლინიკა სტატუსი" />
    <meta property="og:image"              content="{{ $contact_info->logo }}" />

@endsection

@section('content')

    <section class="section-hero">
        <div class="section-hero-header d-flex-centered">
            <div class="section-hero-cont d-flex container">
                <a href="{{ route('index') }}"><img src="/assets/img/icons/home.svg" alt="home"></a>
                <img src="/assets/img/icons/arrow-right-red.svg" alt="right">
                <a href="#" class="active">{{ $history->title }}</a>
            </div>
        </div>
    </section>

    <main class="container">
        <div class="history">
            <div class="history-cont d-flex">
                <section class="section-header history-header d-flex-between">
                    <h3>{{ $history->title }}</h3>
                </section>
                <div class="history-hero" style="background-image: url({{ $history->image }})"></div>
                <div class="history-text d-flex">
                    {!! $history->description !!}
                </div>
            </div>

        </div>
    </main>

@endsection

@section('script')



@endsection
