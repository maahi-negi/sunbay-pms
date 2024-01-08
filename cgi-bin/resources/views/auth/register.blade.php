@extends('layouts.app')

@section('content')
<div class="app-content content">
    <div class="content-header row">
    </div>
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-body">
            <section class="row flexbox-container">
                <div class="col-12 d-flex align-items-center justify-content-center">
                    <div class="col-lg-6 col-md-8 col-10 box-shadow-2 p-0">
                        <div class="card border-grey border-lighten-3 m-0">
                            <div class="card-header border-0">
                                <div class="card-title text-center">
                                    <div class="p-1"><img src="{{ asset('img/logo.png') }}" class="logo-img" alt="Biorev"></div>
                                </div>
                            </div>
                            <div class="card-content">
                                <register-component></register-component>
                            </div>
                            <div class="card-footer my-1">
                                <p class="text-center">Already have an account ? <a href="{{ route('login') }}" class="card-link">Login</a></p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

        </div>
    </div>
</div>
@endsection
