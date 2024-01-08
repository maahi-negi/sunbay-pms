@extends('layouts.inner')
@section('content')
<div class="content-header d-flex flex-wrap justify-content-between align-items-center bg-white" style="padding: 0.8rem 2rem 0.4rem;">
    <div class="content-header-left p-0">
        <h3 class="content-header-title m-0 mr-1">Exterior</h3>
        <div class="row breadcrumbs-top">
            <div class="breadcrumb-wrapper pl-1">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{route('home')}}">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item"><a href="{{route('exterior')}}">Exterior</a>
                    </li>
                </ol>
            </div>
        </div>
    </div>
</div>
<div class="content-wrapper">
    <div class="row">
        @foreach($homes as $home)
        <div class="col-xl-4 col-lg-6 col-sm-12">
            <div class="card" id="card{{$home->id}}">
                <div class="card-header">
                    <h4 class="card-title text-capitalize"><b>{{$home->title}}</b>
                        <div class="heading-elements">
                            @if($home->status_id == 1)
                                <span class="badge badge-success text-uppercase">active</span>
                            @elseif($home->status_id == 0)
                                <span class="badge badge-danger text-uppercase">deactive</span>
                            @endif
                        </div>
                    </h4>
                </div>
                <div class="card-content">
                    <img class="img-fluid" src="{{asset('media/uploads/exterior/'.$home->base_image)}}">
                </div>
                <div class="card-footer border-top-blue-grey border-top-lighten-5 text-muted  p-25">
                    @if(count($home->extelevations) >= 1)
                        @foreach ($home->extelevations as $item)
                            <a href="{{ route('home-design-types', base64_encode($item->id)) }}" class="btn btn-outline-primary btn-block my-25">{{ $item->title }}</a>
                        @endforeach
                    @else
                        <p class="text-danger">No Elevation available for exterior design.</p>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection