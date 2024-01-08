<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>Xhome 360</title>
    <link rel="icon" type="image/png" href="<?php echo e(asset('uploads/'.$all_configs['site_favicon'])); ?>" sizes="16x16">
    <!--    Material Icons-->
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
    <!--    mdb Bootstrap-->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdbootstrap/4.19.0/css/mdb.min.css" rel="stylesheet">
    <!--Select2 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" integrity="sha512-nMNlpuaDPrqlEls3IX/Q56H36qvBASwb3ipuo3MxeWbsQB1881ox0cRv7UPTgBlriqoynt35KjEwgGUeUXIPnw==" crossorigin="anonymous" />
    <!--Range Slider-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ion-rangeslider/2.3.1/css/ion.rangeSlider.min.css"/>
    <!-- Toastr -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
    <!-- Custom Css -->
    <link rel="stylesheet" href="<?php echo e(asset('css/style.css')); ?>?v=4">
    <style>
    body.model-open{overflow: hidden;}
    .preloader{position:fixed;top:0;left:0;width:100vw;height:100vh;z-index:1000;text-align:center;background:#fff;padding-top:calc(50vh - 25px);z-index:11111}
    .inner-breadcrumb{padding:8px 0;position:absolute;top:0;width:calc(100vw - 140px);z-index:1115}
    .inner-breadcrumb .d-flex .back-inner-btn{padding:7px 15px;font-size:12px;background:#199bfe;color:#fff;position:relative;line-height:2rem}
    .inner-breadcrumb .d-flex .back-inner-btn:after{content:'';position:absolute;top:0;right:-1.95rem;border-top:2rem solid #199bfe;border-right:2rem solid transparent;width:0}
    .inner-breadcrumb .d-flex ul{list-style:none;margin:0;padding:0;font-size:12px;text-align:right}
    .inner-breadcrumb .d-flex ul li{display:inline-block;padding:7px 15px;font-size:12px;background:#199bfe;color:#fff;position:relative;line-height:1.125rem;margin-right:2rem}
    .inner-breadcrumb .d-flex ul li:last-child{margin-right:0}
    .inner-breadcrumb .d-flex ul li a{color:#fff}
    .inner-breadcrumb .d-flex ul li:before{content:'';position:absolute;top:0;left:-1.95rem;border-bottom:2rem solid #199bfe;border-left:2rem solid transparent;width:0}
    .inner-breadcrumb .d-flex ul li:not(:last-child):after{content:'';position:absolute;top:0;right:-1.95rem;border-top:2rem solid #199bfe;border-right:2rem solid transparent;width:0}
    .sidemenu{z-index: 1112;}
    .header{z-index: 1113;}
    .sub-btn{margin: 0; background: #9fcc3a; border-radius: 50px; color: #323232 !important; width: auto; padding: 4px 5px; outline: 0; text-transform: uppercase; line-height: 16px; z-index: 1; width: 90px; font-size: 0.8rem; font-weight: 500; letter-spacing: 0.5px;-webkit-transition: all 500ms linear; -moz-transition: all 500ms linear; -ms-transition: all 500ms linear; -o-transition: all 500ms linear; transition: all 500ms linear; height: 35px;}
    .sub-btn.disable, .sub-btn:hover{width: 120px;}
    @media  screen and (max-width: 991px) {
        .header{width:60%;border-right:none}
        .header .logo,.header .logo a img{width:100px}
        .inner-breadcrumb{top:38px;width:calc(100vw)}
    }
    @media  screen and (orientation: landscape) and (max-width: 991px) and (min-width: 738px) {
        .mob-logo{display:none}
        .inner-breadcrumb{top:-8px;width:calc(100vw - 40px);left:40px}
    }
    @media  screen and (orientation: landscape) and (max-width: 825px) {
        .inner-breadcrumb .d-flex .back-inner-btn{display:none;display:none}
    }
    @media  screen and (max-width: 768px) and (min-width: 668px) {
        .inner-breadcrumb .d-flex .back-inner-btn{display:none}
    }
    @media  screen and (max-width: 370px) {
        .inner-breadcrumb .d-flex .back-inner-btn{display:none}
    }
    @media  screen and (max-width: 667px) {
        .inner-breadcrumb .d-flex ul li{display:block;margin-bottom:5px;margin-right:0}
        .inner-breadcrumb .d-flex ul li:not(:last-child):after{border:none;right:0}
    }
    @media  screen and (orientation: landscape) and (max-width: 737px) and (min-width: 597px) {
        .mob-logo{display:none}
        .inner-breadcrumb{top:-8px;width:calc(100vw - 40px);left:40px}
        .inner-breadcrumb .d-flex ul li{display:inline-block;padding:7px;font-size:10px}
        .inner-breadcrumb .d-flex ul li:before,.inner-breadcrumb .d-flex ul li:after{border-width:0}
    }
    @media  screen and (max-width: 597px) {
        .floor-img-holder.active{width: 100%;}
        .floor-img-holder.active img{height: calc(80vh); width: auto; margin: auto; top: 15vh;}
    }
    @media  screen and (max-width: 768px) and (orientation: landscape){
        .inner-breadcrumb{z-index:1115}
    }
    @media  screen and (max-width: 768px) and (orientation: portrait){
        .inner-breadcrumb{z-index:10}
    }
</style>
</head>
<?php /**PATH D:\xampp\htdocs\biorev\ule-2\cgi-bin\resources\views/includes/head.blade.php ENDPATH**/ ?>