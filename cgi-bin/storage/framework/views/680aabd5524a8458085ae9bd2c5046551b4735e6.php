 
<?php $__env->startSection('content'); ?>
<div class="content-header d-flex flex-wrap justify-content-between align-items-center bg-white">
    <div class="content-header-left p-1">
        <h3 class="content-header-title m-0 mr-1">Settings</h3>
    </div>
</div>
<div class="content-wrapper">
    <form class="col-sm-12" id="custom_fonts_1" method="POST" action="<?php echo e(url('admin/settings/save')); ?>" enctype="multipart/form-data">
        <?php echo csrf_field(); ?>
        <div class="text-right border-bottom pb-1">
            <button type="submit" class="btn btn-primary btn-glow">Save Settings</button>
        </div>
        <div class="card-body" id="new_custom_table">
            <div class="form-row">
            <?php $__currentLoopData = $setting; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> 
                <?php if($value->type =='file'): ?>
                <div class="form-group col-md-6" id="f_mar">
                    <div class="row">
                        <div class="col-xl-4">
                            <label for="<?php echo e($value->name); ?>" class="font-weight-bold text-uppercase"><?php echo e(str_replace('_', ' ', $value->name)); ?></label>

                            <label class="btn btn-default btn-file rounded-0">
                                <span class="btn btn-secondary btn-sm">Choose File</span>
                                <input type="file" id="<?php echo e($value->name); ?>" name="<?php echo e($value->name); ?>" style="display: none !important;" value="<?php echo e($value->value); ?>" />
                            </label>
                        </div>
                        <div class="col-xl-8">
                            <span style="border:1px solid #ccc; padding:10pxborder: 1px solid #ccc; padding: 10px; height: 120px; display: inline-block; min-width: 120px;">
                                <img src="<?php echo e(asset('uploads/'.$value->value)); ?>" style="max-width: 100%;" />
                            </span>
                        </div>
                    </div>
                </div>
                <?php elseif($value->type =='email'): ?>
                <div class="form-group col-md-6">
                    <label for="name" class="font-weight-bold text-uppercase"><?php echo e(str_replace('_', ' ', $value->name)); ?></label>
                    <input class="form-control forms1" id="<?php echo e($value->name); ?>" name="<?php echo e($value->name); ?>" type="<?php echo e($value->type); ?>" value="<?php echo e($value->value); ?>" />
                </div>
                <?php elseif($value->type =='textarea'): ?>
                <div class="form-group col-md-6">
                    <label for="name" class="font-weight-bold text-uppercase"><?php echo e(str_replace('_', ' ', $value->name)); ?></label>
                    <textarea class="form-control forms1" id="<?php echo e($value->name); ?>" name="<?php echo e($value->name); ?>"><?php echo e($value->value); ?></textarea>
                </div>
                <?php elseif($value->type =='readonly'): ?>
                <div class="form-group col-md-6">
                    <label for="name" class="font-weight-bold text-uppercase"><?php echo e(str_replace('_', ' ', $value->name)); ?></label>
                    <input readonly class="form-control forms1" id="<?php echo e($value->name); ?>" name="<?php echo e($value->name); ?>" type="<?php echo e($value->type); ?>" value="<?php echo e($value->value); ?>" />
                </div>
                <?php elseif($value->type =='select'): ?>
                <div class="form-group col-md-6">
                    <label for="name" class="font-weight-bold text-uppercase"><?php echo e(str_replace('_', ' ', $value->name)); ?></label>
                    <select class="form-control" id="<?php echo e($value->name); ?>" name="<?php echo e($value->name); ?>">
                        <?php if($value->options): ?>
                            <?php $options = explode(',', $value->options); ?>
                            <?php $__currentLoopData = $options; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php if($option == $value->value): ?>
                                    <option selected="selected" value="<?php echo e($option); ?>"><?php echo e(ucwords(str_replace('_', ' ', $option))); ?></option>
                                <?php else: ?>
                                    <option value="<?php echo e($option); ?>"><?php echo e(ucwords(str_replace('_', ' ', $option))); ?></option>
                                <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endif; ?>
                    </select>
                </div>
                <?php else: ?>
                <div class="form-group col-md-6">
                    <label for="name" class="font-weight-bold text-uppercase"><?php echo e(str_replace('_', ' ', $value->name)); ?></label>
                    <input class="form-control forms1" id="<?php echo e($value->name); ?>" name="<?php echo e($value->name); ?>" type="<?php echo e($value->type); ?>" value="<?php echo e($value->value); ?>" />
                </div>
                <?php endif; ?> <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </form>

</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<?php if(\Session::has('success')): ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js" crossorigin="anonymous"></script>
    <script type="text/javascript">
        toastr.success("<?= Session::get('success') ?>");
    </script>
<?php endif; ?>
<?php if(\Session::has('error')): ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js" crossorigin="anonymous"></script>
    <script type="text/javascript">
        toastr.success("<?= Session::get('error') ?>");
    </script>
<?php endif; ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.inner', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/xhome/public_html/ule-uat.xhome360.com/cgi-bin/resources/views/admin/settings/index.blade.php ENDPATH**/ ?>