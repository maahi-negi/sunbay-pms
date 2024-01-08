<style>
.abcRioButtonIcon {
	border-top-left-radius: 4px !important;
	border-bottom-left-radius: 4px !important;
}

.abcRioButtonBlue {
	border-radius: 5px !important;
}

button.close.text-right {
    position: absolute;
    right: 5px;
    top: 0px;
    z-index: 99999;
}
</style>
<meta name="google-signin-client_id" content="887903172218-lb8pn2mr1shkm5f51758aa0nj1iaiks3.apps.googleusercontent.com">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css" integrity="sha256-h20CPZ0QyXlBuAw7A+KluUYx/3pK+c7lYEpqLTlxjYQ=" crossorigin="anonymous" />
<!-- Begin Login Section Pop Up -->
<div class="login-section">
	<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false" style="z-index:1300;">
		<div class="modal-dialog modal-dialog-centered" role="document">

			<div class="modal-content">
                <button type="button" class="close text-right" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>

				<div class="modal-body p-3">

                    <h3 class="text-center mb-3">Customer Login/Register</h3>

					<ul class="nav nav-tabs" id="myTab" role="tablist">
						<li class="nav-item">
							<a class="nav-link authentication-modal-form active" id="home-tab" data-toggle="tab" data-target="#home" role="tab" aria-controls="home" aria-selected="true"> <i class="icon-login"></i> Sign In</a>
						</li>
						<li class="nav-item">
							<a class="nav-link authentication-modal-form" id="profile-tab" data-toggle="tab" data-target="#profile" role="tab" aria-controls="profile" aria-selected="false"> <i class="icon-note"></i> Sign Up </a>
						</li>
<!--						<li class="nav-item">
							<a class="nav-link authentication-modal-form" id="contact-tab" data-toggle="tab" data-target="#contact" role="tab" aria-controls="contact" aria-selected="false"> <i class="icon-lock"></i> Forgot Password </a>
						</li>-->
					</ul>
					<div class="tab-content" id="myTabContent">
						<div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
							<div id="loginbox" class="tab-box">
								<div class="panel panel-info">
									<div class="panel-body">
										<div style="display:none" id="login-alert" class="alert alert-danger col-sm-12"></div>
										<form action="javascript:void(0)" id="signInForm" method="post">
                                            <input type="hidden" name="user_role_id" value="0">
											<input type="hidden" id="btn_clicked" value="@auth{{'finish'}}@else{{'login'}}@endauth">
                                            @csrf
											<div id="lerr-msg"></div>
											<div class="form-group">
												<div class="input-group"> <span class="input-group-addon"><i class="icon-envelope"></i></span>
													<input id="lemail" type="email" class="form-control" name="username" placeholder="Your Email Address" required="" aria-invalid="true" style=""> </div>
											</div>
											<div class="form-group">
												<div class="input-group"> <span class="input-group-addon"><i class="icon-lock"></i></span>
													<input id="lpassword" type="password" class="form-control" name="password" placeholder="Your Password" required="" aria-invalid="true"> </div>
											</div>
											<div class="form-group text-center" id="logindiv">
												<button type="submit" class="btn btn-primary btn-lg btn-block login-button">Login</button>
											</div>
										</form>
									</div>
								</div>
							</div>
						</div>
						<div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
							<div id="loginbox" class="tab-box">
								<div class="panel panel-info">
									<div class="panel-body">
										<div style="display:none" id="login-alert" class="alert alert-danger col-sm-12"></div>
										<form action="javascript:void(0)" id="signupForm" method="post">
                                            <input type="hidden" name="user_role_id" value="0">
                                            @csrf
											<div id="rerr-msg"></div>
											<div class="form-group">
												<div class="input-group"> <span class="input-group-addon"><i class="icon-user"></i></span>
													<input type="text" class="form-control" id="nname" required="" placeholder="Enter Full Name"> </div>
											</div>
											<div class="form-group">
												<div class="input-group"> <span class="input-group-addon"><i class="icon-envelope"></i></span>
													<input type="email" class="form-control" id="nemail" required="" placeholder="Enter Email"> </div>
											</div>
											<div class="form-group">
												<div class="input-group"> <span class="input-group-addon"><i class="icon-screen-smartphone"></i></span>
													<input type="mobile" class="form-control" id="nmobile" required="" placeholder="Enter Contact Number"> </div>
											</div>
											<div class="form-group">
												<div class="input-group"> <span class="input-group-addon"><i class="icon-lock"></i></span>
													<input type="password" class="form-control" id="npassword" required="" placeholder="Enter Password"> </div>
											</div>
											<div class="form-group" id="regdiv">
												<button type="submit" class="btn btn-block btn-primary btn-lg login-button">Sign Up</button>
											</div>
										</form>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
<!--				<div style="margin-bottom: 0px;text-align: center;font-weight: 500;border-radius:0px;" class="alert alert-success" role="alert"> OAuth 2.0 Protected. </div>-->
			</div>
		</div>
	</div>

















    <div class="modal fade" id="builderLoginModel" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false" style="z-index:1300;">
        <div class="modal-dialog modal-dialog-centered" role="document">

            <div class="modal-content">
                <button type="button" class="close text-right" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>

                <div class="modal-body p-3">
                    <h3 class="text-center mb-4">Builder Login</h3>

                    <div style="display:none" id="login-alert" class="alert alert-danger col-sm-12"></div>
                    <form action="javascript:void(0)" id="signInAdminForm" method="post">
                        <input type="hidden" id="btn_clicked" value="@auth{{'finish'}}@else{{'login'}}@endauth">
                        <input type="hidden" name="user_role_id" value="1">
                        @csrf
                        <div id="lerr-msg"></div>
                        <div class="form-group">
                            <div class="input-group"> <span class="input-group-addon"><i class="icon-envelope"></i></span>
                                <input id="lemail" type="email" class="form-control" name="username" placeholder="Your Email Address" required="" aria-invalid="true" style=""> </div>
                        </div>
                        <div class="form-group">
                            <div class="input-group"> <span class="input-group-addon"><i class="icon-lock"></i></span>
                                <input id="lpassword" type="password" class="form-control" name="password" placeholder="Your Password" required="" aria-invalid="true"> </div>
                        </div>
                        <div class="form-group text-center" id="logindiv">
                            <button type="submit" class="btn btn-primary btn-lg btn-block login-button">Login</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Login Section Pop Up -->

<!--  <script>
  function statusChangeCallback(response) {  // Called with the results from FB.getLoginStatus().
    if (response.status === 'connected') {   // Logged into your webpage and Facebook.
      testAPI();
    } else {                                 // Not logged into your webpage or we are unable to tell.
    }
  }

  function checkLoginState() {               // Called when a person is finished with the Login Button.
    FB.getLoginStatus(function(response) {   // See the onlogin handler
      statusChangeCallback(response);
    });
  }

  window.fbAsyncInit = function() {
    FB.init({
      appId      : '1081823865520726',
      cookie     : true,                     // Enable cookies to allow the server to access the session.
      xfbml      : true,                     // Parse social plugins on this webpage.
      version    : 'v7.0'           // Use this Graph API version for this call.
    });

    FB.getLoginStatus(function(response) {   // Called after the JS SDK has been initialized.
      statusChangeCallback(response);        // Returns the login status.
    });
  };

  (function(d, s, id) {                      // Load the SDK asynchronously
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) return;
    js = d.createElement(s); js.id = id;
    js.src = "https://connect.facebook.net/en_US/sdk.js";
    fjs.parentNode.insertBefore(js, fjs);
  }(document, 'script', 'facebook-jssdk'));

  function testAPI() {                      // Testing Graph API after login.  See statusChangeCallback() for when this call is made.
    FB.api('/me', {fields: 'id,name,email' }, function(response) {
      if(sessionStorage.getItem("loggedIn") != 1){
       $.ajax({
            type:"post",
            url: "/api/fb-sign-in-data",
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            data:{"fb_id":response.id, "name":response.name},
           beforeSend  : function () {
                $("#preloader").show();
            },
            complete: function (response) {
                if(response.responseJSON.status == 'success') {
                    if($('#btn_clicked').val() == 'login'){
                    window.location.reload();
                  }else{
                    sessionStorage.setItem("loggedIn", "1");
                    sessionStorage.setItem("login", "fb");
                    final_finish();
                    //window.location.reload();
                  }
                  $("#preloader").hide();
                  $('#rerr-msg').html('').removeClass('alert alert-danger');
                    $('#regdiv').html('');
                } else {
                    $('#rerr-msg').html(response.responseJSON.message).addClass('alert alert-danger');
                    $('#regdiv').html('<button type="submit" class="btn btn-block btn-primary btn-lg login-button">Sign Up</button>');
                }
            },
             success: function (response) {
//                window.location.reload();
            }
        });
      }
    });
  }
</script>-->
<script type="text/javascript">
    function finish(){
      $('#btn_clicked').val('finish');
      @auth
        final_finish();
      @else
      $('#exampleModal').modal('show');
      @endauth
    }
    var app_base_url="{{url('/')}}";
    // Sign up Form Action
    $('#signupForm').submit(function() {
        $.ajax({
            //url : app_base_url+'/api/user-register',
            url : '{{ route("register") }}',
            type : "POST",
            data : {'_token': "{{csrf_token()}}",'name':$('#nname').val(),'email':$('#nemail').val(),'mobile':$('#nmobile').val(),'password':$('#npassword').val()},
            beforeSend  : function () {
                $("#preloader").show();
            },
            complete: function (response) {
                if(response.responseJSON.status == 'success') {
                    if($('#btn_clicked').val() == 'login'){
                    window.location.reload();
                  }else{
                    final_finish();
                    //window.location.reload();
                  }
                  $("#preloader").hide();
                  $('#rerr-msg').html('').removeClass('alert alert-danger');
                    $('#regdiv').html('');
                } else {
                    $('#rerr-msg').html(response.responseJSON.message).addClass('alert alert-danger');
                    $('#regdiv').html('<button type="submit" class="btn btn-block btn-primary btn-lg login-button">Sign Up</button>');
                }
            },
            success: function (response) {
            }
        });
    });
    // Sign in Form Action

    $('#signInForm').submit(function() {
       // $('#logindiv').html('<img src="'+app_base_url+'/images/spinner.gif">');
        $.ajax({
            //url : app_base_url+'/api/user-login',
            url : '{{ route("login") }}',
            type : "POST",
           // data : {'_token': "{{csrf_token()}}",'email':$('#lemail').val(),'password':$('#lpassword').val()},
            data: $(this).serialize(),
            beforeSend  : function () {
                $("#preloader").show();
            },
            complete: function (response) {
                if(response.responseJSON.success == true) {
                  if($('#btn_clicked').val() == 'login'){
                   // window.location.reload();
                      window.location.href = "{{ route('homes.exterior') }}";
                  }else{
                    //final_finish();
                    //window.location.reload();
                      window.location.href = "{{ route('homes.exterior') }}";
                  }
                  $('#logindiv').html('Success');
                  $('#lerr-msg').html('').removeClass('alert alert-danger');
                    $('#logindiv').html('');
                } else {
                    $('#lerr-msg').html(response.responseJSON.message).addClass('alert alert-danger');
                    $('#logindiv').html('<button type="submit" class="btn btn-block btn-primary btn-lg btn-block login-button">Login</button>');
                }
            },
            success: function (response) {

            }
        });
    });

    $('#signInAdminForm').submit(function() {
       // $('#logindiv').html('<img src="'+app_base_url+'/images/spinner.gif">');
        $.ajax({
            //url : app_base_url+'/api/user-login',
            url : '{{ route("login") }}',
            type : "POST",
           // data : {'_token': "{{csrf_token()}}",'email':$('#lemail').val(),'password':$('#lpassword').val()},
            data: $(this).serialize(),
            beforeSend  : function () {
                $("#preloader").show();
            },
            complete: function (response) {
                if(response.responseJSON.success == true) {
                  if($('#btn_clicked').val() == 'login'){
                   // window.location.reload();
                      window.location.href = "{{ route('homes.exterior') }}";
                  }else{
                    //final_finish();
                    //window.location.reload();
                      window.location.href = "{{ route('homes.exterior') }}";
                  }
                  $('#logindiv').html('Success');
                  $('#lerr-msg').html('').removeClass('alert alert-danger');
                    $('#logindiv').html('');
                } else {
                    $('#lerr-msg').html(response.responseJSON.message).addClass('alert alert-danger');
                    $('#logindiv').html('<button type="submit" class="btn btn-block btn-primary btn-lg btn-block login-button">Login</button>');
                }
            },
            success: function (response) {

            }
        });
    });

    // Forgot password Form Actions - send code on email
    $('#forgotPasswordPopForm').submit(function() {
        $('#forgdiv').html('<img src="'+app_base_url+'/images/spinner.gif">');
        $.ajax({
            url : app_base_url+'/api/forgot-password',
            type : "POST",
            data : {'_token': "{{csrf_token()}}",'email':$('#femail').val()},
            beforeSend  : function () {
                //$("#preloader").show()
            },
            complete: function (response) {
                if(response.responseJSON.status == 'success') {
                    $('#ferr-msg').html(response.responseJSON.message).addClass('alert alert-success').removeClass('alert-danger');
                    $('#femail').attr('readonly', 'readonly');
                    $('#vcodeDiv').show();
                    $('#forgdiv').html('').hide();
                } else {
                    $('#ferr-msg').html(response.responseJSON.message).addClass('alert alert-danger').removeClass('alert-success');
                    $('#forgdiv').html('<button type="submit" class="btn btn-block btn-primary btn-lg login-button">Proceed</button>');
                }
            },
            success: function (response) {
            }
        });
    });

    // verify code sent on email

    $('#verifyCode').click(function() {
        $.ajax({
            url : app_base_url+'/api/verify-code',
            type : "POST",
            data : {'_token': "{{csrf_token()}}",'email':$('#femail').val(), 'vcode':$('#fvcode').val()},
            beforeSend  : function () {
                //$("#preloader").show();
            },
            complete: function (response) {
                if(response.responseJSON.status == 'success') {
                    $('#ferr-msg').html(response.responseJSON.message).addClass('alert alert-info').removeClass('alert-danger alert-success');
                    $('#femail').attr('readonly', 'readonly');
                    $('#vcodeDiv').hide();
                    $('.no-verify').html('').hide();
                    $('#npassDiv').show();
                } else {
                    $('#ferr-msg').html(response.responseJSON.message).addClass('alert alert-danger').removeClass('alert-success  alert-info');
                }
            },
            success: function (response) {
            }
        });

    });

    // update password

    $('#cpPassBtn').click(function() {

        $('cpPassBtnDiv').html('<img src="'+app_base_url+'/images/spinner.gif">');

        $.ajax({

            url : app_base_url+'/api/update-password',

            type : "POST",

            data : {'_token': "{{csrf_token()}}",'email':$('#femail').val(), 'vcode':$('#fvcode').val(), 'passcode': $('#fpassword').val()},



            beforeSend  : function () {
                //$("#preloader").show();
            },
            complete: function (response) {
                if(response.responseJSON.status == 'success') {
                    $(document).find('.manageToggle').each(function(){
                        let checked = $(this).find('input').is(':checked');
                        if(checked){
                            let featureid = $(this).attr('data-self');
                            let featureInput = '<input name="feature_id[]" type="hidden" value="'+featureid+'">';
                            $(document).find("input[name='home_id']").after(featureInput);
                        }
                    });

                    // $(document).find('#finishPage_form').submit();
                    $("#npassDiv").hide();
                    $("#vcodeDiv").hide();
                    $("#ediv").show();
                    $('#ferr-msg').html(response.responseJSON.message).addClass('alert alert-success').removeClass('alert-danger  alert-info');
                } else {
                    $('cpPassBtnDiv').html('<button type="button" id="cpPassBtn" class="btn btn-block btn-primary btn-lg login-button">Change Password</button>');
                    $('#ferr-msg').html(response.responseJSON.message).addClass('alert alert-danger').removeClass('alert-success  alert-info');
                }
            },
            success: function (response) {



            }

        });



    });



    // Show/hide password in forgot password box

    $('#showPass').mouseover(function() {

        $('#fpassword').attr('type', 'text');

    }).mouseout(function() {

        $('#fpassword').attr('type', 'password');

    });

    </script>

<!--<script src="https://apis.google.com/js/platform.js?onload=renderButton" async defer></script>-->

<script>
    /*function onSignIn(googleUser) {
      var profile = googleUser.getBasicProfile();
        $.ajax({
            type:"post",
            url: "/api/google-sign-in-data",
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            data:{"g_id":profile.getId(), "name":profile.getName(), "email":profile.getEmail()},
            beforeSend  : function ()
            {
                $("#preloader").show();
            },
            complete: function (response)
            {
                if(response.responseJSON.status == 'success') {
                    if($('#btn_clicked').val() == 'login'){
                    window.location.reload();
                  }else{
                    final_finish();
                  }
                  $("#preloader").hide();
                  $('#rerr-msg').html('').removeClass('alert alert-danger');
                    $('#regdiv').html('');
                } else {
                    $('#rerr-msg').html(response.responseJSON.message).addClass('alert alert-danger');
                    $('#regdiv').html('<button type="submit" class="btn btn-block btn-primary btn-lg login-button">Sign Up</button>');
                }
            },
             success: function (response)
            {
//                window.location.reload();
            }
        });
    }*/

    /*function renderButton() {
      gapi.signin2.render('my-signin2', {
        'scope': 'profile email',
        'width': 240,
        'height': 40,
        'longtitle': true,
        'theme': 'dark',
      });
      gapi.load('auth2', function() {
        var auth2 = gapi.auth2.init();
        var element = document.getElementById('my-signin2');
        auth2.attachClickHandler(element, {}, onSignIn);
      });
    }*/
</script>


