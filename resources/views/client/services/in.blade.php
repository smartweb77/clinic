@extends('layouts.client')
@section('title')

    <title>{{ $service->title }}</title>
    <meta property="og:url"                content="{{ route('service', $service->id) }}" />
    <meta property="og:type"               content="article" />
    <meta property="og:title"              content="{{ $service->title }}" />
    <meta property="og:description"        content="{{ $service->meta_description }}" />
    <meta property="og:image"              content="{{ $service->image }}" />

@endsection

@section('content')

    <section class="section-hero">
        <div class="section-hero-header d-flex-centered">
            <div class="section-hero-cont d-flex container">
                <a href="{{ route('index') }}"><img src="/assets/img/icons/home.svg" alt="home"></a>
                <img src="/assets/img/icons/arrow-right.svg" alt="right">
                <a href="{{ route('services') }}" class="">@lang('menu.services')</a>
                <img src="/assets/img/icons/arrow-right-red.svg" alt="">
                <a href="#" class="active">{{ $service->title }}</a>
            </div>
        </div>
    </section>

    <main class="lab">
        <div class="laboratory container d-flex">
            <div class="laboratory-header d-flex">
                <div class="card-icon d-flex-centered">
                    <img src="{{ $service->icon }}" alt="{{ $service->title }}">
                </div>
                <h3>{{ $service->title }}</h3>
            </div>
            <div class="laboratory-cont d-flex">
                <div class="laboratory-left">
                    <div class="lab-text d-flex">
                        {!! $service->description !!}
                    </div>
                </div>

                <div class="laboratory-right">
                  <img src="{{ $service->image }}" alt="service" />
                </div>
            </div>
        </div>
    </main>

@endsection

@section('script')



@endsection
