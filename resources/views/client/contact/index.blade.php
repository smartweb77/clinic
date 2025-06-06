@extends('layouts.client')
@section('title')

    <title>@lang('menu.contact')</title>
    <meta property="og:url"                content="{{ route('contact') }}" />
    <meta property="og:type"               content="article" />
    <meta property="og:title"              content="@lang('menu.contact')" />
    <meta property="og:description"        content="კლინიკა სტატუსი" />
    <meta property="og:image"              content="{{ $contact_info->logo }}" />

@endsection

@section('content')

    <section class="section-hero">
        <div class="section-hero-header d-flex-centered">
            <div class="section-hero-cont d-flex container">
                <a href="{{ route('index') }}"><img src="/assets/img/icons/home.svg" alt="home"></a>
                <img src="/assets/img/icons/arrow-right-red.svg" alt="right">
                <a href="#" class="active">@lang('menu.contact')</a>
            </div>
        </div>
    </section>

    <main class="container">
        <div class="contact-cont">
            <div class="contact-form-cont d-flex">
                <section class="section-header d-flex-between">
                    <h3>@lang('menu.contact')</h3>
                </section>
                <form id="contactForm" class="contact-box" action="{{ route('sendMessage') }}" method="post">
                    @csrf
                    <div class="contact-formfield">
                        <div class="contact-fields">
                            <h5>@lang('site.name')</h5>
                            <div class="name-field form-sizes">
                                <input id="userName" name="name" type="text" placeholder="@lang('site.name')" required
                                       maxlength="25">
                            </div>
                            <h5>@lang('site.email')</h5>
                            <div class="email-field form-sizes">
                                <input id="userEmail" type="email" name="email" placeholder="@lang('site.email')" required
                                       minlength="5" maxlength="64">
                            </div>
                            <h5>@lang('site.subject')</h5>
                            <div class="subject-field form-sizes">
                                <input id="subject" type="text" name="subject" placeholder="@lang('site.subject')" required
                                       maxlength="25">
                            </div>
                        </div>
                        <h5>@lang('site.message')</h5>
                        <div class="contact-message">
                            <div class="message-field">
                                <input id="userMessage" type="text" name="message" placeholder="@lang('site.message')" required
                                       minlength="5" maxlength="500">
                            </div>
                        </div>
                        <div class="contact-send">
                            <button id="contactSubmit" type="submit">@lang('site.send')</button>
                            <div class="contact-warning">
                                <span></span>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="map-cont">
                <div class="contact-us">
                    <div class="cnt">
                        <span>@lang('site.phone')</span>
                        <a href="tel:{{ $contact_info->phone }}">{{ $contact_info->phone }}</a>
                    </div>
                    <div class="cnt">
                        <span>@lang('site.email')</span>
                        <a href="mailto: {{ $contact_info->phone }}">{{ $contact_info->email }}</a>
                    </div>
                    <div class="cnt">
                        <span>@lang('site.address')</span>
                        <a href="https://goo.gl/maps/Dzu1RJ3g5DiA15yn8" target="_blank" rel="noopener noreferrer">ქ.
                            {{ $contact_info->address }}</a>
                    </div>
                </div>
                <div class="map">
                    <iframe
                        src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d743.885613750229!2d!3d41.7735217!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x40446d929f33c56b%3A0x5da3632e2b29183c!2sMikheil%20Chiaureli%20St%2C%20T&#39;bilisi!5e0!3m2!1sen!2sge!4v1668432212635!5m2!1sen!2sge"
                        width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
            </div>
        </div>
    </main>

@endsection

@section('script')

<script>
    const longitude = "{{ $contact_info->longitude }}";
    const latitude = "{{ $contact_info->latitude }}";
</script>

@endsection
