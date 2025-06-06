<!DOCTYPE html>
<html lang="{{ App::getLocale() }}">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    @yield('title')
    <link rel="stylesheet" href="{{ asset('assets/css/swiper-bundle.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" />
    <link rel="icon" href="{{ $contact_info->favicon }}">
</head>

<body id="mainPage">
<header>
    <div class="header container">
        <div class="d-flex-between">
            <a href="{{ route('index') }}" class="logo">
                <img src="{{ $contact_info->logo }}" alt="{{ $contact_info->title }}" />
            </a>
            <nav class="navbar">
                <ul class="d-flex-centered">
                    <li class="navbar-li {{ url()->current() == route('index') ? 'active' : '' }}">
                        <a href="{{ route('index') }}">@lang('menu.index')</a>
                    </li>
                    <li class="navbar-li {{ str_contains(url()->current(), 'service')  ? 'active' : '' }}">
                        <a href="{{ route('services') }}">@lang('menu.services')</a>
                    </li>
                    <li class="navbar-li {{ str_contains(url()->current(), 'doctor')  ? 'active' : '' }}">
                        <a href="{{ route('doctors') }}">@lang('menu.doctors')</a>
                    </li>
                    <li class="navbar-li {{ str_contains(url()->current(), 'news')  ? 'active' : '' }}">
                        <a href="{{ route('newses') }}">@lang('menu.news')</a>
                    </li>
                    <li class="navbar-li {{ url()->current() == route('history') ? 'active' : '' }}">
                        <a href="{{ route('history') }}">@lang('menu.history')</a>
                    </li>
                    <li class="navbar-li {{ url()->current() == route('contact') ? 'active' : '' }}">
                        <a href="{{ route('contact') }}">@lang('menu.contact')</a>
                    </li>
                </ul>
            </nav>
            <div class="lang d-flex main-lang">
                @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                    @if( $localeCode == $lang)
                        <a rel="alternate" href="#" class="active lang-main">
                            {{ strtoupper(mb_substr($properties['name'], 0, 3)) }}
                        </a>

                        
                        <!-- <div class="lang-drp"> -->
                    @else
                        <a class="not-main"  href="{{ LaravelLocalization::getLocalizedURL($localeCode, null, [], true) }}"
                            {{ strtoupper(mb_substr($properties['name'], 0, 3)) }}
                        >
                            {{ strtoupper(mb_substr($properties['name'], 0, 3)) }}
                        </a>
                    @if($loop->last)
                        <!-- </div> -->
                    @endif
                    @endif
                @endforeach
            </div>
            <button class="hamburger">
                <img src="/assets/img/icons/menu.svg" alt="Menu" class="menu-open switch-btn">
                <img src="/assets/img/icons/x.svg" alt="Close" class="menu-close">
            </button>
            <div class="navbar-mobile">
                <div class="navbar-mobile-cont">
                    <div class="lang d-flex">
                        @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                            @if( $localeCode == $lang)
                                <a rel="alternate" href="#" class="active lang-main">
                                    {{ strtoupper(mb_substr($properties['name'], 0, 3)) }}
                                </a>
                                <!-- <div class="lang-drp"> -->
                            @else
                                <a class="not-main"  href="{{ LaravelLocalization::getLocalizedURL($localeCode, null, [], true) }}"
                                    {{ strtoupper(mb_substr($properties['name'], 0, 3)) }}
                                >
                                    {{ strtoupper(mb_substr($properties['name'], 0, 3)) }}
                                </a>
                            @if($loop->last)
                                <!-- </div> -->
                            @endif
                            @endif
                        @endforeach
                    </div>
                    <ul class="d-flex-centered">
                        <li class="navbar-li {{ url()->current() == route('index') ? 'active' : '' }}">
                            <a href="{{ route('index') }}">@lang('menu.index')</a>
                        </li>
                        <li class="navbar-li {{ str_contains(url()->current(), 'service')  ? 'active' : '' }}">
                            <a href="{{ route('services') }}">@lang('menu.services')</a>
                        </li>
                        <li class="navbar-li {{ str_contains(url()->current(), 'doctor')  ? 'active' : '' }}">
                            <a href="{{ route('doctors') }}">@lang('menu.doctors')</a>
                        </li>
                        <li class="navbar-li {{ str_contains(url()->current(), 'news')  ? 'active' : '' }}">
                            <a href="{{ route('newses') }}">@lang('menu.news')</a>
                        </li>
                        <li class="navbar-li {{ url()->current() == route('history') ? 'active' : '' }}">
                            <a href="{{ route('history') }}">@lang('menu.history')</a>
                        </li>
                        <li class="navbar-li {{ url()->current() == route('contact') ? 'active' : '' }}">
                            <a href="{{ route('contact') }}">@lang('menu.contact')</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</header>

<main class="main-container">
@yield('content')
</main>

<footer>
    <div class="container">
        <div class="footer_grid">
            <div class="footer-contact">
                <a href="{{ route('index') }}">
                    <img class="footer-logo" src="{{ $contact_info->logo }}" alt="{{ $contact_info->title }}" />
                </a>
                <ul>
                    <li>
                        <a href="#">{{ $contact_info->phone }}</a>
                    </li>
                    <li>
                        <a href="#">{{ $contact_info->email }}</a>
                    </li>
                </ul>
            </div>
            <div class="footer-nav">
                <ul class="d-flex-centered">
                    <li class="navbar-li {{ url()->current() == route('index') ? 'active' : '' }}">
                        <a href="{{ route('index') }}">@lang('menu.index')</a>
                    </li>
                    <li class="navbar-li {{ str_contains(url()->current(), 'service')  ? 'active' : '' }}">
                        <a href="{{ route('services') }}">@lang('menu.services')</a>
                    </li>
                    <li class="navbar-li {{ str_contains(url()->current(), 'doctor')  ? 'active' : '' }}">
                        <a href="{{ route('doctors') }}">@lang('menu.doctors')</a>
                    </li>
                    <li class="navbar-li {{ str_contains(url()->current(), 'news')  ? 'active' : '' }}">
                        <a href="{{ route('newses') }}">@lang('menu.news')</a>
                    </li>
                    <li class="navbar-li {{ url()->current() == route('history') ? 'active' : '' }}">
                        <a href="{{ route('history') }}">@lang('menu.history')</a>
                    </li>
                    <li class="navbar-li {{ url()->current() == route('contact') ? 'active' : '' }}">
                        <a href="{{ route('contact') }}">@lang('menu.contact')</a>
                    </li>
                </ul>
            </div>
            <div class="footer-socials">
                <ul class="d-flex align-items-end">
                    <li class="">
                        <a href="{{ $contact_info->facebook }}">Facebook</a>
                    </li>
                    <li class="">
                        <a href="{{ $contact_info->instagram }}">Instagram</a>
                    </li>
                    <li class="">
                        <a href="{{ $contact_info->twitter }}">Twitter</a>
                    </li>
                </ul>
            </div>
            <div class="footer-smartweb d-flex-centered">
                <span> Created by <a href="https://smartweb.ge/"><span class="smartweb">Smartweb</span></a> </span>
            </div>
        </div>
    </div>
</footer>
<script src="{{ asset('assets/js/swiper-bundle.min.js') }}"></script>
<script src="{{ asset('assets/js/main.js') }}"></script>
@yield('script')
</body>

</html>
