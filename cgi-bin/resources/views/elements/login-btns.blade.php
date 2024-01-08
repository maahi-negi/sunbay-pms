<meta name="google-signin-client_id" content="887903172218-lb8pn2mr1shkm5f51758aa0nj1iaiks3.apps.googleusercontent.com">
<div class="row justify-content-between align-items-center h-100">
  <div class="logo">
    <div id="nav-icon" onclick="openNav()">
      <span></span>
      <span></span>
      <span></span>
      <span></span>
      <span></span>
      <span></span>
    </div>
<!--    <a href="/">
      <img src="{{ asset('images/logo2.png') }}" alt="">
      </a>-->
  </div>

  <div class="top-right links">
    <div class="login">
      @auth
        <div class="dropdown">
          <button class="btn btn-secondary2 dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="icon-people"></i> Account</button>
          <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
            <a class="dropdown-item" href=""><i class="icon-user"></i> Profile</a>
            <a class="dropdown-item" href="" onclick="signOut()"><i class="icon-logout"></i> Logout</a>
          </div>
        </div>
      @else
        <button type="button" class="btn btn-success login-btn" data-toggle="modal" data-target="#exampleModal">
          <svg xmlns="http://www.w3.org/2000/svg" width="20.572" height="18" viewBox="0 0 20.572 18">
              <g id="login" transform="translate(0 -0.008)">
                  <path id="Path_1" data-name="Path 1" d="M89.669,8.681a4.336,4.336,0,1,1,4.337-4.336A4.341,4.341,0,0,1,89.669,8.681Zm0-7.372A3.036,3.036,0,1,0,92.7,4.345a3.039,3.039,0,0,0-3.036-3.036Zm0,0" transform="translate(-81.932)" fill="#fff" />
                  <path id="Path_2" data-name="Path 2" d="M14.961,263.814H.65a.651.651,0,0,1-.65-.65v-3.036a4.124,4.124,0,0,1,4.12-4.12h7.372a4.124,4.124,0,0,1,4.12,4.12v3.036A.651.651,0,0,1,14.961,263.814Zm-13.66-1.3H14.31v-2.385a2.822,2.822,0,0,0-2.819-2.819H4.12A2.822,2.822,0,0,0,1.3,260.128Zm0,0" transform="translate(0 -245.806)" fill="#fff" />
                  <path id="Path_3" data-name="Path 3" d="M306.69,187.977h-7.372a.65.65,0,1,1,0-1.3h7.372a.65.65,0,1,1,0,1.3Zm0,0" transform="translate(-286.769 -179.175)" fill="#fff" />
                  <path id="Path_4" data-name="Path 4" d="M395.322,109.546a.651.651,0,0,1-.46-1.111l3.01-3.01-3.01-3.01a.651.651,0,0,1,.92-.92l3.469,3.469a.651.651,0,0,1,0,.92l-3.469,3.469a.647.647,0,0,1-.461.192Zm0,0" transform="translate(-378.87 -97.267)" fill="#fff" />
              </g>
          </svg> Login
        </button>
      @endauth
    </div>
  </div>
</div>
<!--
<script src="https://apis.google.com/js/platform.js?onload=onLoad" async defer></script>
<script>
  function onLoad() {
    gapi.load('auth2', function() {
      gapi.auth2.init();
    });
  }
  function signOut() {
    // Google SignOut
    if(sessionStorage.getItem('login') !='fb'){
    var auth2 = gapi.auth2.getAuthInstance();
    auth2.signOut().then(function () {
      console.log('User signed out.');
    });
  }
    // Fb SignOut
  window.fbAsyncInit = function() {
    FB.init({
      appId      : '1081823865520726',
      cookie     : true,                     // Enable cookies to allow the server to access the session.
      xfbml      : true,                     // Parse social plugins on this webpage.
      version    : 'v7.0'           // Use this Graph API version for this call.
    });

  };

  (function(d, s, id) {                      // Load the SDK asynchronously
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) return;
    js = d.createElement(s); js.id = id;
    js.src = "https://connect.facebook.net/en_US/sdk.js";
    fjs.parentNode.insertBefore(js, fjs);
  }(document, 'script', 'facebook-jssdk'));
      FB.logout(function(response) {
        // Person is now logged out
        console.log('Fb User signed out.');
        sessionStorage.removeItem("loggedIn");
        sessionStorage.removeItem("login");
      });
  }
  let openMenu = false;
  function openNav() {
      openMenu =! openMenu;
      if(openMenu){
        $(".xplat-section-right").addClass('sidebar-show');
        $(".xplat-tabs-box .nav-tabs .nav-item").addClass('tab-border-right');
        $("#nav-icon").addClass('open');
      }
      else{
        $(".xplat-section-right").removeClass('sidebar-show');
        $(".xplat-tabs-box .nav-tabs .nav-item").removeClass('tab-border-right');
        $("#nav-icon").removeClass('open');
      }
  }

</script>
-->
