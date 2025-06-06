@extends('layouts.client')
@section('title')

    <title>{{ $doctor->full_name }}</title>
    <meta property="og:url"                content="{{ route('service', $doctor->id) }}" />
    <meta property="og:type"               content="article" />
    <meta property="og:title"              content="{{ $doctor->full_name }}" />
    <meta property="og:description"        content="{{ $doctor->specialty }}" />
    <meta property="og:image"              content="{{ $doctor->image }}" />

@endsection

@section('content')

    <section class="section-hero">
        <div class="section-hero-header d-flex-centered">
            <div class="section-hero-cont d-flex container">
                <a href="{{ route('index') }}"><img src="/assets/img/icons/home.svg" alt="home"></a>
                <img src="/assets/img/icons/arrow-right.svg" alt="rigth">
                <a href="{{ route('doctors') }}" class="">@lang('menu.doctors')</a>
                <img src="/assets/img/icons/arrow-right-red.svg" alt="rigth">
                <a href="#" class="active">{{ $doctor->full_name }}</a>
            </div>
        </div>
    </section>

    <main class="container">
        <div class="doctor-cont d-flex">
            <div class="doctor-pic">
                <img src="{{ $doctor->image }}" alt="{{ $doctor->full_name }}">
            </div>
            <div class="doctor-about d-flex">
                <div class="d-flex header">
                    <h3>{{ $doctor->full_name }}</h3>
                    <span>{{ $doctor->specialty }}</span>
                </div>
                <hr>
                <div class="d-flex contact">
                    <h6>@lang('menu.contact')</h6>
                    <a href="tel:{{ $doctor->phone }}">{{ $doctor->phone }}</a>
                    <a href="mailto: {{ $doctor->email }}">{{ $doctor->email }}</a>
                </div>
                <div class="doctor-abt d-flex">
                    <h4>@lang('site.education'):</h4>
                    <ul class="abt-ul">
                        <li class="abt">{!! $doctor->education !!}</li>
                    </ul>
                </div>
                <div class="doctor-abt d-flex">
                    <h4>@lang('site.experience'):</h4>
                    <ul class="abt-ul">
                        <li class="abt">{!! $doctor->experience !!}</li>
                    </ul>
                </div>
            </div>
        </div>
        </div>
    </main>

@endsection

@section('script')



@endsection
