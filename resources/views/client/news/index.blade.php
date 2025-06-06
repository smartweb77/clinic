@extends('layouts.client')
@section('title')

    <title>@lang('menu.news')</title>
    <meta property="og:url"                content="{{ route('newses') }}" />
    <meta property="og:type"               content="article" />
    <meta property="og:title"              content="@lang('menu.news')" />
    <meta property="og:description"        content="კლინიკა სტატუსი" />
    <meta property="og:image"              content="{{ $contact_info->logo }}" />

@endsection

@section('content')

    <section class="section-hero">
        <!-- <img src="/assets/img/main.png" alt="" class="section-hero-img" /> -->
        <div class="section-hero-header d-flex-centered">
            <div class="section-hero-cont d-flex container">
                <a href="{{ route('index') }}"><img src="/assets/img/icons/home.svg" alt="home"></a>
                <img src="/assets/img/icons/arrow-right-red.svg" alt="right">
                <a href="#" class="active">@lang('menu.news')</a>
            </div>
        </div>
    </section>

   
        <div class="news-cont" id="news">
            <section class="section-header d-flex-between container">
                <h3>@lang('menu.news')</h3>
            </section>
            <div class="news-main-slider">
                <div class="swiper">
                    <div class="swiper-wrapper">
                        @forelse($sliders as $slider)
                        <div class="swiper-slide">
                            <a href="{{ route('news', $slider->id) }}">
                                <div class="bg" id="bgA" style="background-image: url({{ $slider->image }})"></div>
                            </a>
                            <div class="bg-cont d-flex">
                                <h4>{{ $slider->title }}</h4>
                                <p>
                                    {{ $slider->meta_description }}
                                </p>
                            </div>
                        </div>
                        @empty
                        @endforelse
                    </div>
                </div>
                <div class="swiper-button-prev"></div>
                <div class="swiper-button-next"></div>
            </div>
            <div class="news-grid container">
                @forelse($newses as $news)
                <div class="news-article">
                    <div class="news-article-cont d-flex">
                        <div class="news-article-cont-upper">
                            <img src="{{ $news->image }}" alt="{{ $news->title }}">
                            <span class="news-in-date">{{ date_format($news->created_at, 'd.m.Y') }}</span>
                            <h4>{{ $news->title }}</h4>
                            <p>
                                {{ $news->meta_description }}
                            </p>
                        </div>
                        <a href="{{ route('news', $news->id) }}">@lang('site.read_more')</a>
                    </div>
                </div>
                @empty
                @endforelse
            </div>
        </div>
  

@endsection

@section('script')



@endsection
