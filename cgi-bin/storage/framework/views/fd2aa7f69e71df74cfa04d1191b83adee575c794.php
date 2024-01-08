
<?php $__env->startSection('content'); ?>
<div class="content-header d-flex flex-wrap justify-content-between align-items-center bg-white" style="padding: 0.8rem 2rem 0.4rem;">
    <div class="content-header-left p-0">
        <h3 class="content-header-title m-0 mr-1">Exterior</h3>
        <div class="row breadcrumbs-top">
            <div class="breadcrumb-wrapper pl-1">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo e(route('home')); ?>">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item"><a href="<?php echo e(route('exterior')); ?>">Exterior</a>
                    </li>
                </ol>
            </div>
        </div>
    </div>
</div>
<div class="content-wrapper">
    <div class="row">
        <?php $__currentLoopData = $homes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $home): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="col-xl-4 col-lg-6 col-sm-12">
            <div class="card" id="card<?php echo e($home->id); ?>">
                <div class="card-header">
                    <h4 class="card-title text-capitalize"><b><?php echo e($home->title); ?></b>
                        <div class="heading-elements">
                            <?php if($home->status_id == 1): ?>
                                <span class="badge badge-success text-uppercase">active</span>
                            <?php elseif($home->status_id == 0): ?>
                                <span class="badge badge-danger text-uppercase">deactive</span>
                            <?php endif; ?>
                        </div>
                    </h4>
                </div>
                <div class="card-content">
                    <img class="img-fluid" src="<?php echo e(asset('media/uploads/exterior/'.$home->base_image)); ?>">
                </div>
                <div class="card-footer border-top-blue-grey border-top-lighten-5 text-muted  p-25">
                    <?php if(count($home->extelevations) >= 1): ?>
                        <?php $__currentLoopData = $home->extelevations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <a href="<?php echo e(route('home-design-types', base64_encode($item->id))); ?>" class="btn btn-outline-primary btn-block my-25"><?php echo e($item->title); ?></a>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php else: ?>
                        <p class="text-danger">No Elevation available for exterior design.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.inner', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/xhome/public_html/tkr.xhome360.com/cgi-bin/resources/views/admin/exterior.blade.php ENDPATH**/ ?>