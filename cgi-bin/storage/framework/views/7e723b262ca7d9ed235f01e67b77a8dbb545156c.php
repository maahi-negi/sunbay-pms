
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
    </div>
    <div class="d-none" id="tempimagecontainer"></div>

    <!-- submit visualizer -->
    <div class="modal fade" id="submit_visualizer" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Visualizer Submission</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form id="submitForm">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="text-uppercase">Name</label>
                                    <input id="name" class="form-control border" type="text" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="text-uppercase">Email</label>
                                    <input id="email" class="form-control border" type="email" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="text-uppercase">Contact No</label>
                                    <input id="contactNo" class="form-control border" maxlength="10" type="text" required>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="text-uppercase">Message</label>
                                    <textarea id="message" class="form-control border" placeholder="Message"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="modal-footer">
                    <button type="button" id="submitButton" onclick="submitForm()" data-id="" class="btn sub-btn text-white m-0">
                        <span class="button-text"> Submit </span>
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        <span class="sr-only">Loading...</span>
                    </button>
                </div>
            </div>

        </div>
    </div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/xhome/public_html/tkr.xhome360.com/cgi-bin/resources/views/exterior.blade.php ENDPATH**/ ?>