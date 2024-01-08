<!doctype html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="<?php echo e(asset('media/favicon.ico')); ?>" sizes="16x16">
    <meta name="site-url" content="<?php echo e(url('/')); ?>">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>ULE Visualizer</title>
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('fonts/material-icons/material-icons.css')); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('css/material-vendors.min.css')); ?>">
    <link href="<?php echo e(asset('css/admin-panel.css')); ?>" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" integrity="sha512-vKMx8UnXk60zUwyUnUPM3HbQo8QfmNx7+ltw8Pm5zLusl1XIfwcxo8DbWCqMGKaWeNxWA8yrx5v3SaVpMvR3CA==" crossorigin="anonymous" />
    <style>
        body .content .content-wrapper {
            height: calc(100vh - 175px);
            font-family: 'Montserrat', sans-serif;
            overflow-y: auto;
            overflow-x: hidden;
        }
        .custom-control-label{cursor: pointer;}
        .ap-op-link {padding:10px !important;}
        .ap-op-link.collapsed{margin-bottom: 10px; border-bottom: 1px solid #673ab7!important;}
        .ap-op-link .card-title{font-size: 0.95rem;}
        .ap-op-content{border-width: 0 1px 1px;}
        .croppreviewcss {
            overflow: hidden;
            width: 160px;
            height: 160px;
            margin: 10px;
            border: 1px solid #323232;
            box-shadow: 0 0 4px 0 rgb(95 95 95 / 14%), 0 3px 4px 0 rgb(95 95 95 / 12%), 0 1px 5px 0 rgb(95 95 95 / 20%);
        }
        #image{padding-top: 30px;}
    </style>
</head>
<body class="vertical-layout vertical-compact-menu material-vertical-layout material-layout 2-columns  fixed-navbar <?php echo e(in_array($menu, ['models', 'media', 'pages'])?'todo-application':''); ?>" data-open="click" data-menu="vertical-compact-menu" data-col="2-columns">
<?php echo $__env->make('elements.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php echo $__env->make('elements.menu', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<div class="app-content content" id="app">
    <?php echo $__env->yieldContent('content'); ?>
</div>
<?php echo $__env->make('elements.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js" integrity="sha384-+sLIOodYLS7CIrQpBjl+C7nPvqq+FbNUBDunl/OZv93DB7Ln/533i8e/mZXLi/P+" crossorigin="anonymous"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js" integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw==" crossorigin="anonymous"></script>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.6/cropper.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.6/cropper.js"></script>
<script>
    $(document).ready(function(){
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>
<?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH /home/xhome/public_html/ule-uat.xhome360.com/cgi-bin/resources/views/layouts/design-layout.blade.php ENDPATH**/ ?>