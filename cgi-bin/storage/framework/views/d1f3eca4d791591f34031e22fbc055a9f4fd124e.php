<?php $__env->startSection('content'); ?>
    <div class="main-wrapper">
        <?php echo $__env->make('includes.sidemenu', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <div id="mainLoader">
            <div class="fs-loading-content center-middle">
                <div class="fs-loading-text text-center">
                    <div class="spinner-border" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
        <canvas id="canvas"></canvas>
        <canvas id="tempcanvas"></canvas>
    </div>
    <div class="d-none" id="tempimagecontainer"></div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/xhome/public_html/ule.xhome360.com/cgi-bin/resources/views/exterior.blade.php ENDPATH**/ ?>