<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <title>X-Floor360 | X-Series</title>
    <link rel="apple-touch-icon" sizes="180x180" href="https://enterprise.xfloor360.com/images/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="https://enterprise.xfloor360.com/images/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="https://enterprise.xfloor360.com/images/favicon-16x16.png">
    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700&amp;display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
    <link rel="stylesheet" href="Xseries-new-ui/css/index.css">
    <style>
        .logo img {
            max-width: 420px;
        }
        @media (min-width: 1920px){
            .logo img {
                width: 550px;
            }
        }
        .bg-3 {
            background: url('https://enterprise.xfloor360.com/uploads/home_bg.jpg');
            background-attachment: fixed;
            background-clip: initial;
            background-color: rgba(0, 0, 0, 0);
            background-origin: initial;
            background-position: center center;
            background-repeat: no-repeat;
            background-size: cover;
            position: relative;
            width: 100%;
            height: calc(100vh - 0px);
            z-index: 0;
        }

        .bg-opacity-black-60:before {
            background: rgba(0, 0, 0, 0.6);
            content: "";
            height: 100%;
            left: 0;
            position: absolute;
            top: 0;
            width: 100%;
            z-index: -1;
        }

        .logo {
            text-align: center;
            margin-bottom: 20px;
        }

        @media (min-width: 1920px) {
            .logo img {
                width: 550px;
            }
        }
        .logo img {
            max-width: 420px;
        }

        .section-title {
            margin-bottom: 60px;
        }

        .section-title h2 {
            position: relative;
            text-align: center;
            color: #fff;
            font-size: 48px;
            letter-spacing: 5px;
            text-transform: uppercase;
            font-family: "poppins";
            top: 15px;
        }


        .find-home-box {
            width: 100%;
            padding: 45px 30px 20px;
            background: transparent;
        }

        .div1, .div2 {
            text-align: center;
        }

        .elev {
            position: absolute;
            width: 98%;
            top: 15%;
        }

        .div1 img {
            height: 77px;
            width: auto;
            margin-bottom: 10px;
        }
        .section-title2 {
            text-align: center;
        }

        .section-title2 h3 {
            font-family: "poppins";
            font-size: 33px;
            color: #fff;
            letter-spacing: 10px;
        }
        .section-title2 h2 {
            font-family: "poppins";
            font-size: 52px;
            color: #9fcc3a;
            letter-spacing: 10px;
        }
    </style>
</head>
<body class="page-homepage map-google" id="page-top" data-spy="scroll" data-target=".navigation" data-offset="90">
<div class="wrapper bg-3 bg-opacity-black-60 d-flex align-items-center justify-content-center">
    <div class="">
        <div class="logo">
            <a href="/"><img src="https://enterprise.xfloor360.com/uploads/biorev_logo.png" alt=""></a>
        </div>
        <div class="section-title text-white">
            <h2 class="h1">Be a part of something beautiful</h2>
        </div>
        <?php if(auth()->guard()->check()): ?>
            <a href="<?php echo e(route('homes.exterior')); ?>" style="background: #8cc442;color: #fff;padding: 18px 20px;font-size: 28px; text-align: center; display: inline-block">Already Login</a>
        <?php else: ?>
        <div class="find-home-box">
            <div class="find-homes">
                <div class="row">
                    <div class="col-sm-6 search_comm">
                        <span class="button-1 btn-hover-1">
                            <div class="div2">
                                <div class="elev">
                                    <div class="div1"><img src="https://enterprise.xfloor360.com/uploads/comm_icon.svg" alt=""></div>
                                    <div class="section-title2 text-white">

                                        <a href="#" class="btn login-btn" data-toggle="modal" data-target="#exampleModal" style="background: #8cc442;color: #fff;padding: 18px 20px;font-size: 28px;">Customer Login</a>

                                    </div>
                                </div>
                                <img class="ouline_box" src="https://enterprise.xfloor360.com/uploads/search_box.svg" alt="">
                            </div>
                        </span>
                    </div>
                    <div class="col-sm-6 search_elev">
                        <a class="button-1 btn-hover-1" href="/search-elevations">
                            <div class="div2">
                                <div class="elev">
                                    <div class="div1"><img src="https://enterprise.xfloor360.com/uploads/elev_icon.svg" alt=""></div>
                                    <div class="section-title2 text-white">
                                        <a href="#" class="btn login-btn" data-toggle="modal" data-target="#builderLoginModel" style="background: #8cc442;color: #fff;padding: 18px 20px;font-size: 28px;">Builder Login</a>

                                    </div>
                                </div>
                                <img class="ouline_box" src="https://enterprise.xfloor360.com/uploads/search_box.svg" alt="">
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdbootstrap/4.19.0/js/mdb.min.js"></script>
<?php echo $__env->make('elements.home-login', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
</body>
</html>
<?php /**PATH D:\xampp\htdocs\biorev\ule-2\cgi-bin\resources\views/home-page.blade.php ENDPATH**/ ?>