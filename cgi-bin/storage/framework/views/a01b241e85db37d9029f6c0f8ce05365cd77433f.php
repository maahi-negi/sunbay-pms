<?php $__env->startSection('content'); ?>
<div class="main-wrapper">
    <?php echo $__env->make('includes.sidemenu', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <div id="mainLoader">
        <div class="fs-loading-content center-middle">
            <div class="fs-loading-text text-center">
                <?php if(isset($homeData,$homeData->error)): ?>
                    <h6><?php echo e($homeData->error); ?> </h6><br>
                <?php endif; ?>
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
                        <div class="col-md-6 pr-1">
                            <div class="form-group">
                                <label class="text-uppercase">Email</label>
                                <input id="email" class="form-control border" type="email" required>
                            </div>
                        </div>

                        <div class="col-md-6 pl-1">
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
                <button type="button" id="submitButton" onclick="submitForm()" data-id="" class="btn btn-dark text-white m-0">
                    <span class="button-text"> Submit </span>
                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                    <span class="sr-only">Loading...</span>
                </button>
            </div>
        </div>

    </div>
</div>

<style>
    #submitButton{pointer-events:unset}
    #submitButton.disable{pointer-events:none}
    #submitButton .spinner-border{display:none}
    #submitButton .spinner-border.show-spinner{display:inline-block}
</style>
<script>
    const api_url = '<?php echo url("/api/submit-visualizer"); ?>';
    let home_id = "<?php echo e((isset($homeData->id))?$homeData->id:''); ?>";
    let elevationId = "<?php echo e((isset($homeData->elevation))?$homeData->elevation:''); ?>";

    function submitVisualizer(){
        const modal = $('#submit_visualizer');
        var form = document.getElementById('submitForm');
        modal.find('#submitButton').attr('onclick', 'submitForm()');
        form.reset();
        modal.modal('show');
    }

    function submitForm(){
        const modal = $('#submit_visualizer');
        const name = $('#name').val();
        const email = $('#email').val();
        const contactNo = $('#contactNo').val();
        const message = $('#message').val();

        // Validations
        if(name == ''){
            toastr.clear();
            toastr.error('Name field is required');
            return false;
        }

        if(email == ''){
            toastr.clear();
            toastr.error('Email field is required');
            return false;
        }
        var mailformat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,6})+$/;
        if(!email.match(mailformat)) {
            toastr.clear();
            toastr.error('Please enter valid email.');
            return false;
        }

        if(contactNo == ''){
            toastr.clear();
            toastr.error('Contact No field is required');
            return false;
        }
        var phoneFormat = /^\(?(\d{3})\)?[- ]?(\d{3})[- ]?(\d{4})$/;
        if(!contactNo.match(phoneFormat)) {
            toastr.clear();
            toastr.error('Please enter correct Phone Number.');
            return false;
        }

        var o = $(".design-container.color-active").find(".design");
        var options = Array();
        $.each(o, function(indx) {
            // var designCategoryId = $(this).attr("data-design-title");
            // var optionId = o[indx].dataset.value;
            var designCategoryId = $(this).attr("data-designCategoryId");
            var optionId = o[indx].dataset.id;
            options.push({designCategoryId:designCategoryId,optionId:optionId});
        });

        const formData = new FormData();
        formData.append('name', name);
        formData.append('email', email);
        formData.append('contactNo', contactNo);
        formData.append('message', message);
        formData.append('homeId', home_id);
        formData.append('elevationId', elevationId);
        formData.append('options', JSON.stringify(options));
        modal.find('#submitButton .button-text').addClass('hide-button-text');
        modal.find('#submitButton .spinner-border').addClass('show-spinner');
        $.ajax({
            type: 'post',
            url: api_url,
            headers: {'Authorization': "<?php echo e($auth_token); ?>"},
            data: formData,
            processData: false,
            contentType: false,
            success: function(response){
                modal.find('#submitButton').removeClass('disable');
                modal.find('#submitButton .button-text').removeClass('hide-button-text');
                modal.find('#submitButton .spinner-border').removeClass('show-spinner');
                if(response.error){
                    var error_fields = "";
                    $.each(response.error,function (er){
                        error_fields += (error_fields=="")?er:", "+er;
                    });
                    toastr.clear();
                    toastr.error(error_fields + " are required.");
                    return false;
                }
                if(response.status && response.status=="1"){
                    toastr.clear();
                    toastr.success("Your Visualizer Submission saved successfully.");
                    document.getElementById('submitForm').reset();
                    $('#submit_visualizer').modal('hide');
                }
            },
            error: function(xhr, status, error) {
                modal.find('#submitButton').removeClass('disable');
                modal.find('#submitButton .button-text').removeClass('hide-button-text');
                modal.find('#submitButton .spinner-border').removeClass('show-spinner');

                toastr.clear();
                toastr.error("Error code: "+ xhr.status+ " - "+ error);
                return false;
            }
        });
    }
</script>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\biorev\tkr-xhome360\cgi-bin\resources\views/exterior.blade.php ENDPATH**/ ?>