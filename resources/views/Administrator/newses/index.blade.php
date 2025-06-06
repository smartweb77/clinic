@extends('layouts.admin')
@section('content')
<div class="col-md-3">
    <a href=" {{ route('News') }}">
        <div class="card-counter success">
            <i class="fa fa-newspaper"></i>
            <span class="count-numbers">{{ DB::table('news')->count() }}</span>
            <span class="count-name">@lang('admin.routes.News')</span>
        </div>
    </a>
</div>
@endsection