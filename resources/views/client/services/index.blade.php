@extends('layouts.client')
@section('title')

    <title>@lang('menu.services')</title>
    <meta property="og:url"                content="{{ route('services') }}" />
    <meta property="og:type"               content="article" />
    <meta property="og:title"              content="@lang('menu.services')" />
    <meta property="og:description"        content="კლინიკა სტატუსი" />
    <meta property="og:image"              content="{{ $contact_info->logo }}" />

@endsection

@section('content')

    <section class="section-hero">
        <img src="/assets/img/main.png" alt="" class="section-hero-img" />
        <div class="section-hero-header d-flex-centered">
            <div class="section-hero-cont d-flex container">
                <a href="{{ route('index') }}"><img src="/assets/img/icons/home.svg" alt="home"></a>
                <img src="/assets/img/icons/arrow-right-red.svg" alt="right">
                <a href="#" class="active">@lang('menu.services')</a>
            </div>
        </div>
    </section>

    <main class="container">
        <section class="section-header d-flex-between">
            <h3>@lang('menu.services')</h3>
            <div class="search">
                <form class="search-cont" id="search-services-form">
                    <button type="submit">
                        <img class="search-icon" src="/assets/img/icons/search-normal.svg" alt="Search">
                    </button>
                    <input type="search" placeholder="@lang('site.search')">
                </form>
            </div>
        </section>
        <div class="services-list section-grid">
            @forelse($services as $service)
            <a href="{{ route('service', $service->id) }}" class="service-card">
                <div class="card-icon d-flex-centered">
                    <img src="{{ $service->icon }}" alt="{{ $service->title }}" />
                </div>
                <h5>{{ $service->title }}</h5>
                <p>
                    {{ $service->short_description }}
                </p>
            </a>
            @empty
            @endforelse
        </div>
    </main>

@endsection

@section('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.2/jquery.min.js" integrity="sha512-tWHlutFnuG0C6nQRlpvrEhE4QpkG1nn2MOUMWmUeRePl4e3Aki0VB6W1v3oLjFtd0hVOtRQ9PHpSfN6u6/QXkQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
    // სერვისების გაფილტვრა: მიაკითხავ ამ როუტს და გამოუშვებ keyword პარამეტრს (ძიების ველშიც რაც მოვა),
    // რისპონში მოვა სტატუსი, 1 ან 0, თუ 1 მოვა გამოიტან, თუ 0 ყველა სერვისს ამოშლი დომიდან
    //                         1 წამიანი დალოდება გააკეთე, keyupზე არ ქნა


    const filterServices = "{{ route('serviceSearch') }}";
</script>
<script src="{{ asset('assets/js/search-filter.js') }}"></script>


@endsection
