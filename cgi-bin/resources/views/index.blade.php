<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>XDesign360 - Timbercraft Homes</title>
    <link rel="shortcut icon" href="https://timbercraft.xdesign360.com/uploads/1667561065.png" type="image/png" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <style>
        .w-full{width:100%}
        .h-screen{height:100vh}
        .primary-color{color:#76BD1D}
        .secondary-color{color:#77787b}
        .bg-shadow{background:rgba(0,0,0,0.5)}
        .primary-bg{background-color:rgba(118,189,29,1)}
        .secondary-bg{background-color:rgba(119,120,123,1)}
        .left{background:url({!! url("/images/left-bg2.jpg") !!});background-size:cover;left:0;position:absolute;width:33.33%;height:100vh;box-shadow:3px 0 6px -1px #ccc;z-index:15;overflow:hidden;background-position:center}
        .center{background:url({!! url("/images/floorplan.png") !!});background-size:cover;left:33.33%;position:absolute;width:33.33%;height:100vh;box-shadow:3px 0 6px -1px #ccc;z-index:10;overflow:hidden}
        .right{background:url({!! url("/images/right-bg2.jpg") !!});right:0;position:absolute;width:33.33%;height:100vh;background-size:cover;overflow:hidden;background-position:center}
        h2{font-weight:800;font-size:48px;-webkit-transition:all 300ms linear;-moz-transition:all 300ms linear;-ms-transition:all 300ms linear;-o-transition:all 300ms linear;transition:all 300ms linear;letter-spacing:.5px}
        .left:hover h2,.right:hover h2,.center:hover h2{transform:scale(1.1)}
        .logo-box{top:20px;z-index:11}
        .logo-box img{width:290px;border:5px solid #eee}
        .left h2 span, .center h2 span, .right h2 span{display: block;}

        @media screen and (max-width: 540px) {
            .left{width:100%;height:33vh}
            .center{width:100%;height:34vh;top:33vh;left: 0}
            .right{width:100%;height:33vh;top:67vh}
            .left h2, .center h2, .right h2{font-size:40px;top:50%;position:absolute;width:100%;letter-spacing:.75px}
            .left h2 span, .center h2 span, .right h2 span{display: inline;}
            .logo-box{top:10px;z-index:111}
            .logo-box img{width:150px;border:5px solid #eee}
        }
        @media screen and (min-width: 541px) and (max-width: 912px) {
            .left h2, .center h2, .right h2{ font-size: 25px;}
            .logo-box{top:10px;z-index:111}
            .logo-box img{width:165px;border:5px solid #eee}
        }
    </style>
</head>
<body>
    <div class="text-center position-absolute w-full logo-box">
        <img src="{{ asset('images/logo.jpg') }}" alt="Timbercraft" />
    </div>
    <div class="position-relative">
        <div class="left">
            <div class="d-table w-full h-screen">
                <div class="d-table-cell align-middle text-center bg-shadow">
                    <a class="text-decoration-none  overflow-hidden" href="{{ route('front.exterior') }}">
                        <h2 class="text-uppercase text-white">Exterior <span>Design</span></h2>
                    </a>
                </div>
            </div>
        </div>
        <div class="center">
            <div class="d-table w-full h-screen">
                <div class="d-table-cell align-middle text-center bg-shadow">
                    <a class="text-decoration-none  overflow-hidden" href="{{ route('front.floorplan') }}">
                        <h2 class="text-uppercase text-white">Floorplan <span>Design</span></h2>
                    </a>
                </div>
            </div>
        </div>
        <div class="right">
            <div class="d-table w-full h-screen">
                <div class="d-table-cell align-middle text-center bg-shadow">
                    <a class="text-decoration-none overflow-hidden" href="{{ route('front.interior') }}">
                        <h2 class="text-uppercase text-white">Interior <span>Design</span></h2>
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
