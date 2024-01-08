@extends('layouts.main')
@section('content')
    <div class="main-wrapper">
        @include('includes.sidemenu')
        @if(Auth::check())
            @include('includes.admin-bar')
        @endif
        <div id="mainLoader">
            <div class="fs-loading-content center-middle">
                <div class="fs-loading-text text-center">
                    <div class="spinner-border" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
        <canvas id="canvas"></canvas>
        <canvas id="canvas3" class="d-none"></canvas>
        <canvas id="tempcanvas"></canvas>
{{--        @include('includes.google')--}}
    </div>
    <div class="d-none" id="tempimagecontainer"></div>

@endsection
