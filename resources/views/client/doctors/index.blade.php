@extends('layouts.client')
@section('title')

    <title>@lang('menu.doctors')</title>
    <meta property="og:url"                content="{{ route('doctors') }}" />
    <meta property="og:type"               content="article" />
    <meta property="og:title"              content="@lang('menu.doctors')" />
    <meta property="og:description"        content="კლინიკა სტატუსი" />
    <meta property="og:image"              content="{{ $contact_info->logo }}" />

@endsection

@section('content')

    <section class="section-hero">
        <img src="/assets/img/main.png" alt="" class="section-hero-img" />
        <div class="section-hero-header d-flex-centered">
            <div class="section-hero-cont d-flex container">
                <a href="{{ route('index') }}"><img src="/assets/img/icons/home.svg" alt="home"></a>
                <img src="/assets/img/icons/arrow-right-red.svg" alt="rigth">
                <a href="#" class="active">@lang('menu.doctors')</a>
            </div>
        </div>
    </section>

    <main class="container">
        <section class="section-header d-flex-between">
            <h3>@lang('menu.doctors')</h3>
            <div class="search">
                <form class="search-cont" id="filter-doctors-form">
                    <button type="submit">
                        <img class="search-icon" src="/assets/img/icons/search-normal.svg" alt="Search">
                    </button>
                    <input type="search" placeholder="@lang('site.search')">
                </form>
            </div>
        </section>
        <div class="doctors-list section-grid">
            @forelse($doctors as $doctor)
            <a href="{{ route('doctor', $doctor->id) }}" class="doctor-card">
                <img src="{{ $doctor->image }}" alt="{{ $doctor->full_name  }}" />
                <h5>{{ $doctor->full_name  }}</h5>
                <span>{{ $doctor->specialty }}</span>
            </a>
            @empty
            @endforelse
        </div>
    </main>

@endsection

@section('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.2/jquery.min.js" integrity="sha512-tWHlutFnuG0C6nQRlpvrEhE4QpkG1nn2MOUMWmUeRePl4e3Aki0VB6W1v3oLjFtd0hVOtRQ9PHpSfN6u6/QXkQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script>
  const filterDoctors = "{{ route('doctorSearch') }}";
</script>

<script src="{{ asset('assets/js/search-filter.js') }}"></script>

@endsection
