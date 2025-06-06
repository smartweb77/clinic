@extends('layouts.client')
@section('title')

    <title>{{ $contact_info->title }}</title>
    <meta property="og:url"                content="{{ route('services') }}" />
    <meta property="og:type"               content="article" />
    <meta property="og:title"              content="{{ $contact_info->title }}" />
    <meta property="og:description"        content="კლინიკა სტატუსი" />
    <meta property="og:image"              content="{{ $contact_info->logo }}" />

@endsection

@section('content')

    <section class="main__hero">
        <!-- <img src="/assets/img/main.png" alt="slider" /> -->
        <div class="hero-content">
            <!-- <div class="container"> -->
                <div class="swiper hero_swiper">
                    <div class="swiper-wrapper hero-wrapper">
                        @forelse($sliders as $slider)
                        <div class="swiper-slide hero_slide">
                            <img src="{{ $slider->image }}" alt="slider" />
                            <div class="main-page-slide-content container">
                              <h1>{{ $loop->iteration . '. ' . $slider->title }}</h1>
                              <p>
                                  {{ $slider->short_description }}
                              </p>
                              <a href="{{ $slider->url }}" class="btn btn-default-bg">@lang('site.read_more')</a>
                            </div>
                        </div>
                        @empty
                        @endforelse
                    </div>
                    <div class="main-slide-btns container">
                      <div class="swiper-button-next hero_slider-next"></div>
                      <div class="swiper-button-prev hero_slider-prev"></div>
                    </div>
                </div>
            <!-- </div> -->
        </div>
    </section>

    <section class="main_services">
        <div class="container">
            <div class="main_services-flex d-flex-between">
                <h2 class="section_title">@lang('menu.services')</h2>
                <a href="{{ route('services') }}" class="btn btn-default-bg">@lang('site.all_services')</a>
                <div class="services_list">
                    <div class="service-card service-card-main">
                        <img src="assets/img/news-in.png" alt="" />
                        <h4>@lang('site.premium_class_hospital')</h4>
                    </div>
                    @forelse($services as $service)
                    <a href="{{ route('service', $service->id) }}" class="service-card">
                        <div class="card-icon d-flex-centered">
                            <img src="{{ $service->icon }}" alt="REVM" />
                        </div>
                        <h5>{{ $service->title }}</h5>
                        <p>
                            {{ $service->short_description }}
                        </p>
                    </a>
                    @empty
                    @endforelse
                </div>
            </div>
        </div>
    </section>
    <section class="main_doctors">
        <div class="container">
            <div class="main_doctors-flex d-flex-between">
                <h2 class="section_title">@lang('menu.doctors')</h2>
                <a href="{{ route('doctors') }}" class="btn btn-default-bg">@lang('site.all_doctors')</a>
                <div class="swiper main_doctors-swiper">
                    <div class="swiper-wrapper main_doctors-wrapper">
                        @forelse($doctors as $doctor)
                        <a href="{{ route('doctor', $doctor->id) }}" class="swiper-slide doctor-card">
                            <img src="{{ $doctor->image }}" alt="{{ $doctor->full_name }}" />
                            <h5>{{ $doctor->full_name }}</h5>
                            <span>{{ $doctor->specialty }}</span>
                        </a>
                        @empty
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="main_about"></section>
    <section class="main_news">
        <div class="container">
            <h2 class="section_title">@lang('menu.news')</h2>
            <div class="news_list">
                @forelse($newses as $news)
                <div class="news-card d-flex-between">
                    <div class="news-desc">
                        <h4>
                            {{ $news->title }}
                        </h4>
                        <div class="news-desc-det">
                            {!! $news->meta_description !!}
                        </div>
                        <a href="{{ route('news', $news->id) }}" class="see-more">@lang('site.read_more')</a>
                    </div>
                    <div class="news-img">
                        <img src="{{ $news->image }}" alt="{{ $news->title }}" />
                    </div>
                </div>
                @empty
                @endforelse
            </div>
        </div>
    </section>

@endsection

@section('script')



@endsection
