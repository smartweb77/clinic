@extends('layouts.client')
@section('title')

    <title>{{ $news->title }}</title>
    <meta property="og:url"                content="{{ route('news', $news->id) }}" />
    <meta property="og:type"               content="article" />
    <meta property="og:title"              content="{{ $news->title }}" />
    <meta property="og:description"        content="{{ $news->meta_description }}" />
    <meta property="og:image"              content="{{ $news->image }}" />

@endsection

@section('content')

    <section class="section-hero">
        <div class="section-hero-header d-flex-centered">
            <div class="section-hero-cont d-flex container">
                <a href="{{ route('index') }}"><img src="/assets/img/icons/home.svg" alt="home"></a>
                <img src="/assets/img/icons/arrow-right.svg" alt="right">
                <a href="{{ route('newses') }}" class="">@lang('menu.news')</a>
                <img src="/assets/img/icons/arrow-right-red.svg" alt="">
                <a href="#" class="active">{{ $news->title }}</a>
            </div>
        </div>
    </section>

    <main class="">
        <div class="news-in-hero" style="background-image: url({{ $news->image }});"></div>
        <div class="news-in-cont container">
            <span class="news-in-date">{{ date_format($news->created_at, 'd.m.Y') }}</span>
            <div class="news-in-bottom d-flex">
                <div class="news-in-left">
                    <h3>{{ $news->title }}</h3>
                    <div class="news-in-text d-flex">
                        {!! $news->description !!}
                    </div>
                </div>
                <div class="news-in-right">
                    <div class="swiper-head d-flex-between">
                        <h4>@lang('site.other_news')</h4>
                        <div class="swiper-pagination"></div>
                    </div>
                    <div class="swiper">
                        <div class="swiper-wrapper">
                            @forelse($other_newses as $item)
                            <div class="swiper-slide news-article-cont d-flex">
                                <div class="swiper-cont d-flex">
                                    <div class="news-article-cont-upper">
                                        <img src="{{ $item->image }}" alt="{{ $item->title }}">
                                        <span class="news-in-date">{{ date_format($item->created_at, 'd,m,Y') }}</span>
                                        <h4>{{ $item->title }}</h4>
                                        <p>
                                            {{ $item->meta_description }}
                                        </p>
                                    </div>
                                    <a href="{{ route('news', $item->id) }}">@lang('site.read_more')</a>
                                </div>
                            </div>
                            @empty
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

@endsection

@section('script')



@endsection
