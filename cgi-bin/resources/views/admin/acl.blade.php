@extends('layouts.admin')
@section('content')
    <!-- BEGIN: Content-->
<div class="container-fluid page-wrapper">
    <div class="row mb-2 justify-content-between mr-1 ml-1 align-items-center">
        <div>
            <h1 class="a_dash p-0 m-0 d-inline-block">Floors<small><span class="color-secondary">|</span></small></h1>
            <div class="row breadcrumbs-top pl-2 d-inline-block">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item" aria-current="page">
                        <a href="{{ route('new_floors') }}">{{$floor->home->title}}</a>
                    </li>
                    <li class="breadcrumb-item" aria-current="page">
                        <a href="{{url('admin/homes/features/'.base64_encode($floor->id))}}">{{$floor->title}}</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">ACL Settings</li>
                </ol>
            </div>
        </div>
        <div class="btn-group">
            <a href="{{url('admin/homes/features/'.base64_encode($floor->id))}}" class="add_button"><i class="fa fa-arrow-left position-relative"></i> Back</a>
        </div>
    </div>

  <!-- <nav aria-label="breadcrumb" id="g_r_bar">
   <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{ route('new_floors') }}"><i class="fas fa-fw fa-table"></i>Floors</a></li>
      <li class="breadcrumb-item" aria-current="page">{{$floor->home->title}}</li>
      <li class="breadcrumb-item" aria-current="page">{{$floor->title}}</li>
      <li class="breadcrumb-item active" aria-current="page">ACL Settings</li>
      <li><a href="{{url('admin/homes/features/'.base64_encode($floor->id))}}" class="add_button"><i class="fa fa-arrow-left"></i>Back</a></li>
   </ol>
  </nav> -->
      
      <!-- end add button area-->
      
      <div class="clearfix"></div>
      
      
      <!--data table start-->
      
      
                @if(count($errors) > 0)
                <div class="alert alert-danger" id="msg">
                 <ul>
                 @foreach($errors->all() as $error)
                  <li>{{$error}}</li>
                 @endforeach
                 </ul>
                </div>
                @endif
                @if(\Session::has('success'))
                  
                  <div class="alert alert-success alert-dismissible" style="margin-top:18px;">
                      <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
                      <strong>Success!</strong> {{ \Session::get('success') }}
                  </div>
               @endif

                <div class="card-body">
              <div class="table-responsive">
                <form method="POST" action="{{url('admin/features/save-acl')}}" accept-charset="UTF-8" id="acl_setting_form" novalidate="novalidate">
                @csrf
                <input name="floorid" type="hidden" value="{{$floor->id}}">

                <table class="table table-bordered aclTable" width="100%" cellspacing="0">
                   <thead>
                      <tr class="bg-dark text-white">
                         <th>Options</th>
                         <th>Conflicts</th>
                         <th>Dependency</th>
                         <th>Togetherness</th>
                         <th class="delete_acl_row">Delete</th>
                      </tr>
                   </thead>
                   <tbody>
                     @php $i=0; @endphp
                      @forelse($acl_settings as $acl)
                        @php $i++; @endphp
                        <tr class="tr_clone" id="tr_{{$i}}">
                          <td class="w-20" style="width: 20%;">
                            <input class="form-control main_option forms1" id="main_option1" name="feature_id[]" type="hidden" value="{{$acl['feature_id']}}">
                            <select class="form-control forms1 main_option" id="main_option{{$i}}" name="main_option[{{$i}}]">
                              <option value="0">Choose Option</option>
                              <?php foreach ($features as $ky => $opt): ?>
                                <option <?php if (in_array($ky, $acl_settings) && $ky != $acl['feature_id']) {?> disabled <?php }?> <?php if ($ky == $acl['feature_id']) {?> selected <?php }?> value="{{$ky}}">{{$opt}}</option>
                              <?php endforeach;?>
                            </select>
                          </td>

                          <td class="w-20">
                            {{Form::select('conflict['.$i.'][]',$features,json_decode($acl['conflicts']),['class'=>'select2 form-control  conflict js-example-basic-single','id'=>'conflict'.$idstr.$i, "multiple"=>"multiple"])}}
                          </td>
                          <td class="w-20">
                             {{Form::select('dependency['.$i.'][]',$features,json_decode($acl['dependency']),['class'=>'select2 form-control  dependency js-example-basic-single','id'=>'togetherness'.$idstr.$i, "multiple"=>"multiple"])}}
                          </td>
                          <td class="w-20">
                             {{Form::select('togetherness['.$i.'][]',$features,json_decode($acl['togetherness']),['class'=>'select2 form-control  togetherness js-example-basic-single','id'=>'dependency'.$idstr.$i, "multiple"=>"multiple"])}}
                          </td>
                          <td class="w-20 delete_acl_row">
                           <a href="#" id="{{base64_encode($acl['id'])}}" class="delete_record_btn" onclick="deleteData({{$acl['id']}})" data-toggle="modal" data-target="#modal-delete"><i class="la la-trash"></i> Delete</a>
                          </td>

                          <div class="modal fade" id="modal-delete" tabindex="-1" role="dialog" aria-hidden="true">
                            <div class="modal-dialog">
                                <form action="" id="deleteForm" method="get">
                                <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Confirm Action</h5>
                                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                    </button>
                                </div>
                                <div class="modal-body">Are you sure, you want to delete this record ?</div>
                                <div class="modal-footer ">
                                    <button type="button" class="btn btn-success" data-dismiss="modal">Cancel</button>
                                    <button type="submit" name="" class="btn btn-danger" data-dismiss="modal" onclick="formSubmit()">Yes, Delete</button>
                                </div>
                                </div>
                            </form>
                            </div>
                            </div>

                    <script type="text/javascript">
                       function deleteData(id)
                       {
                           var id = id;
                           var url = '{{ action("Admin\FeaturesController@deleteAclSettings", ":id") }}';
                           url = url.replace(':id', id);
                           $("#deleteForm").attr('action', url);
                       }
                  
                       function formSubmit()
                       {
                           $("#deleteForm").submit();
                       }
                  </script>

                        </tr>
                        @empty
                        @endforelse
                   </tbody>

                   
                </table>
                <div class="col-md-3 float-left">
                  <button type="button" data-floor-id="{{$floor->id}}" class="d-none d-sm-inline-block btn btn-info btn-min-width mb-1 waves-effect waves-light clonetrBtn"><span class="fa fa-plus">&nbsp;&nbsp;</span>Add Row</button>
                </div>
                <div class="col-md-3 float-right saveACLBtn">
                  <button id="save_acl_btn" type="button" class="float-right d-none d-sm-inline-block btn btn-success btn-min-width mb-1 waves-effect waves-light"><span class="fa fa-save">&nbsp;&nbsp;</span>Save</button>
                </div>
                <input id="acl_data_field" name="acl_data" type="hidden">
              </form>
             </div>
          </div>

   
      <!--data table end-->
      
          
           
    </div>
    <!-- END: Content-->
@endsection
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script>
      $(document).ready(function(){
          setTimeout(function() {
              $('#msg').fadeOut('fast');
          }, 3000);
      });
  </script>