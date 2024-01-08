
<?php $__env->startSection('content'); ?>
<div class="content-header d-flex flex-wrap justify-content-between align-items-center bg-white"
    style="padding: 0.8rem 2rem 0.4rem;">
    <div class="content-header-left p-0">
        <h3 class="content-header-title m-0 mr-1">Dashboard</h3>
        <div class="row breadcrumbs-top">
            <div class="breadcrumb-wrapper pl-1">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        API Data
                    </li>
                </ol>
            </div>
        </div>
    </div>
    <div class="content-header-right">
        <a href="javascript:;" data-toggle="modal" data-target="#apiModal"
            class="btn btn-secondary btn-sm square btn-min-width waves-effect waves-light box-shadow-2 standard-button">
            <i class="ft-eye"></i> <span>View API</span>
        </a>
    </div>
</div>
<div class="content-overlay"></div>
<div class="content-wrapper">
    <div class="content-body">
        <?php if($data['status'] == 'failed'): ?>
        <div class="alert alert-danger">
            <p><?php echo e($data['message']); ?></p>
        </div>
        <?php else: ?>
        <div class="card bg-white">
            <?php // var_dump($data['result']->Groupings); ?>
            <div class="card-body p-1 api-op-card">
               <ul class="nav nav-tabs nav-justified">
                    <?php $__currentLoopData = $data['result']->Groupings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $gkey => $group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li class="nav-item">
                        <a class="nav-link <?php echo e(($gkey == 0)?'active':''); ?>" id="tablink-<?php echo e($gkey); ?>" data-toggle="tab" href="#tabcontent-<?php echo e($gkey); ?>" aria-controls="tabcontent-<?php echo e($gkey); ?>" aria-expanded="<?php echo e(($gkey == 0)?'true':'false'); ?>">
                            <?php echo e($group->Name); ?>

                        </a>
                    </li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul> 
                <div class="tab-content pb-1">
                    <?php $__currentLoopData = $data['result']->Groupings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $gkey => $group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                    <div role="tabpanel" class="tab-pane <?php echo e(($gkey == 0)?'active':''); ?>" id="tabcontent-<?php echo e($gkey); ?>" aria-labelledby="tablink-<?php echo e($gkey); ?>" aria-expanded="<?php echo e(($gkey == 0)?'true':'false'); ?>">
                        <?php if($group->Categories): ?>

                        <div id="aop<?php echo e($gkey); ?>" role="tablist" aria-multiselectable="true">
                            <div class="card accordion collapse-icon accordion-icon-rotate right">
                                <?php $__currentLoopData = $group->Categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ockey => $optioncat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                                <a id="optionlink<?php echo e($gkey); ?>-<?php echo e($ockey); ?>" class="card-header ap-op-link primary border-primary <?php echo e(($ockey == 0)?'':'collapsed'); ?>" data-toggle="collapse" href="#optiondata<?php echo e($gkey); ?>-<?php echo e($ockey); ?>" aria-expanded="<?php echo e(($ockey == 0)?'true':'false'); ?>" aria-controls="optiondata<?php echo e($gkey); ?>-<?php echo e($ockey); ?>">
                                    <div class="card-title lead"><b><?php echo e($ockey+1); ?>. </b> <?php echo e($optioncat->Name); ?>

                                        <small class="float-right text-dark">
                                            <b>(<?php echo e(count($optioncat->AvailableOptions)); ?> Options)</b>
                                        </small>
                                    </div>
                                </a>
                                <div id="optiondata<?php echo e($gkey); ?>-<?php echo e($ockey); ?>" role="tabpanel" data-parent="#optionlink<?php echo e($gkey); ?>-<?php echo e($ockey); ?>" aria-labelledby="optionlink<?php echo e($gkey); ?>-<?php echo e($ockey); ?>" class="ap-op-content border-primary collapse mb-1 <?php echo e(($ockey == 0)?'show':''); ?>">
                                    <div class="card-content">
                                        <div class="card-body">
                                            <?php if(count($optioncat->AvailableOptions) >= 1): ?>
                                                <ul class="list-group">
                                                    <?php $__currentLoopData = $optioncat->AvailableOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $okey => $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <li class="list-group-item">
                                                           <b> <?php echo e($okey+1); ?>. </b> <?php echo e($option->Name); ?>

                                                        </li>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </ul>
                                            <?php else: ?>
                                                <p class="alert text-danger">No Options available for this category</p>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>

                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </div>
         <?php endif; ?>
    </div>
    <div class="modal animated slideInDown text-left fade" id="apiModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header border-bottom">
                    <h4 class="modal-title">API Info</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i class="ft-x text-secondary"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <code>
                    https://app.threadkore.com/api/units/getdesignsheet?publicApiKey=3475f5e1-96b1-4389-a7c2-8b95d1a6c7c3&privateApiKey=4717ad97-e27e-4452-91d6-5efa7fbfce5b&unitId=2907435210cc74ab667f9a4c308f2e
                </code>
                </div>
            </div>
        </div>
    </div>
    <?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.inner', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/xhome/public_html/tkr.xhome360.com/cgi-bin/resources/views/admin/apidata.blade.php ENDPATH**/ ?>