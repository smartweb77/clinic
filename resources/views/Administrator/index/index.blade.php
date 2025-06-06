@extends('layouts.admin')
@section('content')
<div class="x_panel">
    <div class="x_title">
        <h2>@lang('admin.modules')</h2>
        <ul class="nav navbar-right panel_toolbox">
            <li>
                <a class="collapse-link">
                    <i class="fa fa-chevron-up"></i>
                </a>
            </li>
            <li>
                <a class="close-link">
                    <i class="fas fa-times"></i>
                </a>
            </li>
        </ul>
        <div class="clearfix"></div>
    </div>
    <div class="x_content">
        <div class="col-md-3">
            <a href="{{ route('Sliders') }}">
                <div class="card-counter success">
                    <i class="fa fa-image"></i>
                    <span class="count-numbers">{{ DB::table('sliders')->count() }}</span>
                    <span class="count-name">@lang('admin.routes.Sliders')</span>
                </div>
            </a>
        </div>
        <div class="col-md-3">
            <a href="{{ route('Newses') }}">
                <div class="card-counter info">
                    <i class="fas fa-newspaper"></i>
                    <span class="count-numbers">{{ DB::table('news')->count() }}</span>
                    <span class="count-name">@lang('admin.routes.News')</span>
                </div>
            </a>
        </div>
        <!--<div class="col-md-3">-->
        <!--    <a href="{{ route('EditTextpages',1) }}">-->
        <!--        <div class="card-counter warning">-->
        <!--            <i class="fas fa-barcode"></i>-->
        <!--            <span class="count-numbers"></span>-->
        <!--            <span class="count-name">@lang('admin.menu.about')</span>-->
        <!--        </div>-->
        <!--    </a>-->
        <!--</div>-->
        <!--<div class="col-md-3">-->
        <!--    <a href="{{ route('EditTextpages',2) }}">-->
        <!--        <div class="card-counter primary">-->
        <!--            <i class="fas fa-clipboard-list"></i>-->
        <!--            <span class="count-numbers"></span>-->
        <!--            <span class="count-name">@lang('admin.menu.terms')</span>-->
        <!--        </div>-->
        <!--    </a>-->
        <!--</div>-->
    </div>
</div>
@if(Session::get('admin')->role === 1)
<div class="x_panel">
    <div class="x_title">
        <h2>@lang('admin.info_and_configuration')</h2>
        <ul class="nav navbar-right panel_toolbox">
            <li>
                <a class="collapse-link">
                    <i class="fa fa-chevron-up"></i>
                </a>
            </li>
            <li>
                <a class="close-link">
                    <i class="fas fa-times"></i>
                </a>
            </li>
        </ul>
        <div class="clearfix"></div>
    </div>
    <div class="x_content">
        <div class="col-md-3">
            <a href="{{ route('EditInformations') }}">
                <div class="card-counter success">
                    <i class="fas fa-info-circle"></i>
                    <span class="count-numbers"></span>
                    <span class="count-name">@lang('admin.routes.Informations')</span>
                </div>
            </a>
        </div>
        <div class="col-md-3">
            <a href="{{ route('Seos') }}">
                <div class="card-counter primary">
                    <i class="fas fa-chart-line"></i>
                    <span class="count-numbers"></span>
                    <span class="count-name">@lang('admin.routes.Seos')</span>
                </div>
            </a>
        </div>
        <div class="col-md-3">
            <a href="{{ route('Admins') }}">
                <div class="card-counter iasamani">
                    <i class="fas fa-users-cog"></i>
                    <span class="count-numbers">{{ DB::table('admins')->count() }}</span>
                    <span class="count-name">@lang('admin.routes.Admins')</span>
                </div>
            </a>
        </div>
        <div class="col-md-3">
            <a href="{{ route('Logs') }}">
                <div class="card-counter info">
                    <i class="fas fa-clipboard-list"></i>
                    <span class="count-numbers"></span>
                    <span class="count-name">@lang('admin.routes.Logs')</span>
                </div>
            </a>
        </div>
    </div>
</div>
@endif
@endsection
@push('js')
@endpush
