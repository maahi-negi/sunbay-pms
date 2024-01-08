@extends('layouts.inner') 
@section('content')
<div class="content-header d-flex flex-wrap justify-content-between align-items-center bg-white">
    <div class="content-header-left p-1">
        <h3 class="content-header-title m-0 mr-1">Settings</h3>
    </div>
</div>
<div class="content-wrapper">
    <form class="col-sm-12" id="custom_fonts_1" method="POST" action="{{url('admin/settings/save')}}" enctype="multipart/form-data">
        @csrf
        <div class="text-right border-bottom pb-1">
            <button type="submit" class="btn btn-primary btn-glow">Save Settings</button>
        </div>
        <div class="card-body" id="new_custom_table">
            <div class="form-row">
            @foreach($setting as $key => $value) 
                @if($value->type =='file')
                <div class="form-group col-md-6" id="f_mar">
                    <div class="row">
                        <div class="col-xl-4">
                            <label for="{{ $value->name }}" class="font-weight-bold text-uppercase">{{ str_replace('_', ' ', $value->name) }}</label>

                            <label class="btn btn-default btn-file rounded-0">
                                <span class="btn btn-secondary btn-sm">Choose File</span>
                                <input type="file" id="{{ $value->name }}" name="{{ $value->name }}" style="display: none !important;" value="{{ $value->value }}" />
                            </label>
                        </div>
                        <div class="col-xl-8">
                            <span style="border:1px solid #ccc; padding:10pxborder: 1px solid #ccc; padding: 10px; height: 120px; display: inline-block; min-width: 120px;">
                                <img src="{{asset('uploads/'.$value->value)}}" style="max-width: 100%;" />
                            </span>
                        </div>
                    </div>
                </div>
                @elseif($value->type =='email')
                <div class="form-group col-md-6">
                    <label for="name" class="font-weight-bold text-uppercase">{{ str_replace('_', ' ', $value->name) }}</label>
                    <input class="form-control forms1" id="{{ $value->name }}" name="{{ $value->name }}" type="{{ $value->type }}" value="{{ $value->value }}" />
                </div>
                @elseif($value->type =='textarea')
                <div class="form-group col-md-6">
                    <label for="name" class="font-weight-bold text-uppercase">{{ str_replace('_', ' ', $value->name) }}</label>
                    <textarea class="form-control forms1" id="{{ $value->name }}" name="{{ $value->name }}">{{ $value->value }}</textarea>
                </div>
                @elseif($value->type =='readonly')
                <div class="form-group col-md-6">
                    <label for="name" class="font-weight-bold text-uppercase">{{ str_replace('_', ' ', $value->name) }}</label>
                    <input readonly class="form-control forms1" id="{{ $value->name }}" name="{{ $value->name }}" type="{{ $value->type }}" value="{{ $value->value }}" />
                </div>
                @elseif($value->type =='select')
                <div class="form-group col-md-6">
                    <label for="name" class="font-weight-bold text-uppercase">{{ str_replace('_', ' ', $value->name) }}</label>
                    <select class="form-control" id="{{ $value->name }}" name="{{ $value->name }}">
                        @if($value->options)
                            @php $options = explode(',', $value->options); @endphp
                            @foreach($options as $option)
                                @if($option == $value->value)
                                    <option selected="selected" value="{{ $option }}">{{ ucwords(str_replace('_', ' ', $option)) }}</option>
                                @else
                                    <option value="{{ $option }}">{{ ucwords(str_replace('_', ' ', $option)) }}</option>
                                @endif
                            @endforeach
                        @endif
                    </select>
                </div>
                @else
                <div class="form-group col-md-6">
                    <label for="name" class="font-weight-bold text-uppercase">{{ str_replace('_', ' ', $value->name) }}</label>
                    <input class="form-control forms1" id="{{ $value->name }}" name="{{ $value->name }}" type="{{ $value->type }}" value="{{ $value->value }}" />
                </div>
                @endif @endforeach
            </div>
        </div>
    </form>

</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<?php if(\Session::has('success')): ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js" crossorigin="anonymous"></script>
    <script type="text/javascript">
        toastr.success("<?= Session::get('success') ?>");
    </script>
<?php endif; ?>
<?php if(\Session::has('error')): ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js" crossorigin="anonymous"></script>
    <script type="text/javascript">
        toastr.success("<?= Session::get('error') ?>");
    </script>
<?php endif; ?>
@endsection