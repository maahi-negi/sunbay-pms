<!-- Header -->
<header class="header">
    <div id="nav-icon" onclick="openNav()">
        <span></span>
        <span></span>
        <span></span>
        <span></span>
        <span></span>
        <span></span>
    </div>
    <div class="logo">
        <a href="/">
            <img src="<?php echo e(asset('media/logo2.png')); ?>" class="d-logo" alt="Biorev">
            <img src="<?php echo e(asset("media/logo2.png")); ?>" class="mob-logo" alt="Biorev">
        </a>
    </div>
</header>
<!-- Header End -->
<!--Footer-->
<footer style="display: none">
    <p>
        &copy;  <?= date('Y') ?> Timbercraft Homes, All Rights Reserved.
        | <a href="javascript:0" id="info-btn">Disclaimer</a>
    </p>
    <p>Designed &amp; Developed By <a href="https://biorev.com" target="_blank">Biorev</a></p>
</footer>
<!--Footer End-->
<div class="disclaim bottom-fix" style="display: none;">
    <button class="tns-close-btn svg-btn" type="button">
        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x-circle"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>
    </button>
    <h6 class="m-1">Disclaimer</h6>
    <p class="m-0 mt-1 mx-2">We reserve the right to make changes or alterations with or without notice due to our continuing efforts to offer our customers the greatest range of selections and products. This design studio is intended as a guide and not as an actual representation of home features.</p>
</div>
<script>
    /*infoButton = document.querySelector('#info-btn');
    infoCloseButton = document.querySelector('button.tns-close-btn');
    // ***** Show Disclaimer
    infoButton.addEventListener('click', function() {
        $('.disclaim').addClass('bottom-fix');
    });
    // ***** Hide Disclaimer
    infoCloseButton.addEventListener('click', function() {
        $('.disclaim').removeClass('bottom-fix');
    });*/
</script>
<?php /**PATH D:\xampp\htdocs\biorev\tkr-xhome360\cgi-bin\resources\views/includes/header-footer.blade.php ENDPATH**/ ?>