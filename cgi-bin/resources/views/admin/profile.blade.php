@extends('layouts.inner')
@section('content')
<div class="todo-application">
    <div class="content-header row">
        <div class="content-header-light col-12">
            <div class="row align-items-center">
                <div class="content-header-left col-md-12 col-12 mb-2">
                    <h3 class="content-header-title">Profile</h3>
                    <div class="row breadcrumbs-top">
                        <div class="breadcrumb-wrapper col-12">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="/admin/dashboard">Home</a></li>
                                <li class="breadcrumb-item">Profile</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-body">
            <div class="row">
                <div class="col-md-6 col-12">
                    <form class="form" id="profileForm">
                        <div class="card m-0">
                            <div class="card-header border-bottom">
                                <h4 class="card-title text-uppercase">Edit Your Profile</h4>
                            </div>
                            <div class="card-content">
                                <div class="card-body p-2">
                                    <div class="media mb-2">
                                        <img src="{{asset('media/'.$admin->profile_image)}}" alt="users avatar" class="users-avatar-shadow rounded-circle mr-2" height="64" width="64">
                                        <div class="media-body">
                                            <h4 class="media-heading"></h4>
                                            <div class="col-12 px-0 d-flex">
                                                <input type="file" id="profileImageInput" class="d-none" onchange="readUrl(this)">
                                                <label href="javascript:void(0)" for="profileImageInput" class="btn btn-sm btn-dark mr-25 waves-effect waves-light">Change</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="name" class="text-uppercase mb-1">Your Name</label>
                                        <input type="text" id="name" class="form-control" placeholder="Your Name" value="{{$admin->name}}" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="mobile" class="text-uppercase mb-1">Contact</label>
                                        <input type="number" id="contact" class="form-control" placeholder="Your contact" value="{{$admin->mobile}}" required>
                                    </div>
                                    <div class="form-group d-flex flex-sm-row flex-column justify-content-end mt-1">
                                        <button type="submit" class="btn btn-dark glow mb-1 mb-sm-0 mr-0 mr-sm-1 waves-effect waves-light">Save changes</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-md-6 col-12">
                    <form class="form" id="updatePasswordForm">
                        <input type="hidden" name="id" value={{$admin->id}}>
                        <div class="card m-0">
                            <div class="card-header border-bottom">
                                <h4 class="card-title text-uppercase">Change Your Password</h4>
                            </div>
                            <div class="card-content">
                                <div class="card-body p-2">
                                    <div class="form-group">
                                        <label class="text-uppercase mb-1">Old Password</label>
                                        <input type="password" class="form-control" placeholder="Enter your old password" name="old_password" required>
                                    </div>
                                    <div class="form-group">
                                        <label class="text-uppercase mb-1">New Password</label>
                                        <input type="password" class="form-control" placeholder="Enter your password" name="new_password" required>
                                    </div>
                                    <div class="form-group">
                                        <label class="text-uppercase mb-1">Confirm New Password</label>
                                        <input type="password" class="form-control" placeholder="Re-enter your password" name="confirm_password" required>
                                    </div>
                                    <div class="form-group d-flex flex-sm-row flex-column justify-content-end mt-1">
                                        <button type="submit" class="btn btn-dark glow mb-1 mb-sm-0 mr-0 mr-sm-1 waves-effect waves-light">Update Password</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
let profileImage = null;
const id = {{$admin->id}}, path = '{{asset("media")}}';
$(document).ready(function () {
    $("#profileForm").submit(function( event ) {
        event.preventDefault();
        const name = $("#name").val();
        const contact = $("#contact").val();

        const formData = new FormData();
        formData.append('id', id);
        formData.append('name', name);
        formData.append('contact', contact);
        formData.append('profile_image', profileImage);

        $.ajax({
            type: 'post',
            url : '/api/edit-profile',
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            data: formData,
            processData: false,
            contentType: false,
            success: function(response){
                toastr.clear();
                toastr.success("Profile Updated Successfully");
                $('.avatar img').attr('src', `${path}/${response.profile_image}`);
            }
        });
    });

    $("#updatePasswordForm").submit(function(event) {
        event.preventDefault();
        $.ajax({
            type: 'post',
            url : '/api/update-password',
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            data: $(this).serialize(),
            success: function(response){
                toastr.clear();
                toastr.success("Password Updated Successfully");
                const form = document.getElementById('updatePasswordForm');
                form.reset();
            },
            error: function(error){
                toastr.error(error.responseJSON);
            }
        });
    });
});

function readUrl(input) {
    if (input.files && input.files[0]) 
    {  
        var reader = new FileReader();
        reader.onload = function (e) 
        {
            $(input).parents('.media').find('img').attr('src', e.target.result);
        };
        reader.readAsDataURL(input.files[0]);
        profileImage = input.files[0];
    }
}
</script>
@endpush