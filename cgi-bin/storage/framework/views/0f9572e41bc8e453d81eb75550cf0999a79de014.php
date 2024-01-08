<?php $__env->startSection('content'); ?>
<div class="content-header d-flex flex-wrap justify-content-between align-items-center bg-white" style="padding: 0.8rem 2rem 0.4rem;">
    <div class="content-header-left p-0">
        <h3 class="content-header-title m-0 mr-1">Designs</h3>
        <div class="row breadcrumbs-top">
            <div class="breadcrumb-wrapper col-12">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="<?php echo e(route('home')); ?>">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="<?php echo e(route('homeplans')); ?>">Homeplans</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="<?php echo e(route('home-design-types', ['elevation_id' => $elevation_id])); ?>"><?php echo e(ucwords($home_title)); ?></a>
                    </li>
                    <li class="breadcrumb-item">
                        <u class="text-capitalize"><?php echo e($design_type_title); ?></u>
                    </li>
                </ol>
            </div>
        </div>
    </div>
    <div class="content-header-right">
        <a href="<?php echo e(route('home-design-types', ['elevation_id' => $elevation_id])); ?>" class="btn btn-secondary square btn-min-width waves-effect waves-light box-shadow-2 px-2 standard-button">
            <i class="ft-arrow-left"></i>
            <span>Back</span>
        </a>
        <a href="javascript:;" onclick="designModal(false)" class="btn btn-secondary square btn-min-width waves-effect waves-light box-shadow-2 px-2 standard-button">
            <i class="ft-plus"></i>
            <span>Add New</span>
        </a>
    </div>
</div>
<div class="content-wrapper">
    <div class="row">
        <?php $__currentLoopData = $designs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $design): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="col-xl-3 col-lg-4 col-sm-6">
            <div class="card" id="card<?php echo e($design->id); ?>">
                <div class="card-header">
                    <h4 class="card-title text-capitalize">
                        <b><?php echo e($design->title); ?></b><br>
                        <small class="color-code"><?php echo e($design->product_id); ?></small>
                        <div class="heading-elements">
                            <?php if($design->status_id == 1): ?>
                                <span class="badge badge-success text-uppercase">active</span>
                            <?php elseif($design->status_id == 0): ?>
                                <span class="badge badge-danger text-uppercase">deactive</span>
                            <?php endif; ?>
                        </div>
                    </h4>
                </div>
                <div class="card-content">
                    <div class="d-flex justify-content-center" style="position:relative;">
                        <div class="design-color" style="background: rgb(<?php echo e($design->rgb_color); ?>)"></div>
                        <div class="default-badge">
                            <?php if($design->is_default == 1): ?>
                            <div style="position:absolute; bottom:0px; left:10px">
                                <span class="badge badge-info text-uppercase">default</span>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <div class="card-footer border-top-blue-grey border-top-lighten-5 text-muted">
                    <?php $rgb_color = explode(",",$design->rgb_color);?>
                    <span class="float-left">Updated On: <span><?php echo e(date('d-m-Y',strtotime($design->updated_at))); ?></span></span>
                    <span class="float-right">
                        <a href="javascript:;" onclick="updateDefault(<?php echo e($design->id); ?>)" data-toggle="tooltip" title="Make Default" class="text-dark mr-25"> <i class="ft-check-square"></i> </a>
                        <a href="javascript:;" onclick="designModal(true, <?php echo e($design->id); ?>, '<?php echo e($design->title); ?>','<?php echo e($design->product_id); ?>', <?php echo e($design->status_id); ?>,'<?php echo e($rgb_color[0]); ?>','<?php echo e($rgb_color[1]); ?>','<?php echo e($rgb_color[2]); ?>')" data-toggle="tooltip" title="Edit Design" class="text-dark mr-25 edit-button"> <i class="ft-edit"></i> </a>
                        <a href="javascript:;" onclick="deleteSwal(<?php echo e($design->id); ?>)" data-toggle="tooltip" title="Delete Design" class="text-dark mr-25"> <i class="ft-trash-2"></i> </a>
                    </span>
                </div>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</div>
<div class="modal fade text-left" id="addDesignModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document" style="max-width:43rem;">
        <div class="modal-content">
            <div class="modal-header border-bottom">
                <h3 class="modal-title"> Add New Design Group</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i class="ft-x text-secondary"></i>
                </button>
            </div>
            <form id="designForm">
                <div class="modal-body">
                    <div class="form-row mb-2">
                        <div class="col">
                            <label class="text-uppercase m-0">Title</label>
                            <input name="title" id="title" class="form-control border" type="text" placeholder="Enter title" required>
                        </div>
                        <div class="col">
                            <label class="text-uppercase">Color Code</label>
                            <input name="product_id" id="productId" class="form-control border" type="text" placeholder="Enter Color Code" required>
                        </div>
                    </div>
                    <div class="form-row mb-2">
                        <div class="col">
                            <label class="text-uppercase">RGB Color </label>
                            <div>
                                <input type="text" id="color_red" class="form-control border" style="width: 60px;float: left;" placeholder="R" required>
                                <input type="text" id="color_green" class="form-control border" style="width: 60px;float: left;" placeholder="G" required>
                                <input type="text" id="color_blue" class="form-control border" style="width: 60px;float: left;" placeholder="B" required>
                                <div class="clearfix"></div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="d-inline-block text-uppercase m-0">Activate </label>
                        <div class="d-inline-block custom-control custom-radio mr-1">
                            <input type="radio" name="status" class="custom-control-input" id="yes1" value="1">
                            <label class="custom-control-label" for="yes1">Yes</label>
                        </div>
                        <div class="d-inline-block custom-control custom-radio">
                            <input type="radio"name="status" class="custom-control-input" id="no1" value="0" checked>
                            <label class="custom-control-label" for="no1">No</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="submitButton" onclick="submitForm(true)" data-type-id="<?php echo e($design_type_id); ?>" data-group-id="<?php echo e($elevation_id); ?>" class="btn btn-dark text-white m-0">
                        <span class="button-text"> Save Changes </span>
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        <span class="sr-only">Loading...</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php $__env->startPush('scripts'); ?>
<style>
    select.form-control{height: 41px !important;}
    .design-color{
        width: 100%;
        height: 65px;
    }
    .card .card-header {
        padding: 1rem 1rem;
    }
</style>
<script>
    const date = new Date();
    //Price Formatter
    const formatter = new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD',
        minimumFractionDigits: 0,
        maximumFractionDigits: 0
    });

    function designModal(...values){
        var modal = $('#addDesignModal');
        if(values[0] == true){
            modal.find('.modal-title').text('Edit Design')
            modal.find('#submitButton .button-text').text('Save Changes')
            const statusRadioButtons = $('#designForm input[name="status"]');
            $.each(statusRadioButtons, function(){
                if($(this).val() == values[4]){
                    $(this).prop('checked', true);
                }
            });
            modal.find('#title').val(values[2]);
            modal.find('#productId').val(values[3]);
            modal.find('#color_red').val(values[5]);
            modal.find('#color_green').val(values[6]);
            modal.find('#color_blue').val(values[7]);

            modal.find('#submitButton').attr('data-id', values[1]);
            modal.find('#submitButton').attr('onclick', 'submitForm(true)');
            modal.modal('show');
        }
        else{
            var form = document.getElementById('designForm');
            modal.find('.modal-title').text('Add New Design')
            modal.find('#submitButton .button-text').text('Add New')
            modal.find('#submitButton').attr('data-id', '');
            modal.find('#submitButton').attr('onclick', 'submitForm(false)');
            form.reset();
            modal.modal('show');
        }
    }

    function submitForm(editable){
        const title = $('#title').val();
        const productId = $('#productId').val();
        const colorR = $('#color_red').val();
        const colorG = $('#color_green').val();
        const colorB = $('#color_blue').val();
        const status = $('input[name="status"]:checked').val();
        const designTypeId = $("#submitButton").attr('data-type-id');

        // Validations
        if(title == ''){
            toastr.clear()
            toastr.error('Title field is required');
            return false;
        }
        if(!(/^[A-Za-z0-9 ]+$/.test(title))){
            toastr.clear()
            toastr.error('Title field should only contain alphabets.');
            return false;
        }
        if(colorR=='' || colorG=='' || colorB==''){
            toastr.clear()
            toastr.error('RGB Color field is required');
            return false;
        }
        if(colorR > 244 || colorG > 244 || colorB > 244){
            toastr.clear()
            toastr.error('Invalid color');
            return false;
        }
        if(productId == ''){
            toastr.clear()
            toastr.error('Color Code field is required');
            return false;
        }

        const formData = new FormData();
        formData.append('title', title);
        formData.append('product_id', productId);
        formData.append('colorR', colorR);
        formData.append('colorG', colorG);
        formData.append('colorB', colorB);
        formData.append('status', status);
        formData.append('design_type_id', designTypeId);

        $("#addDesignModal").find('#submitButton').addClass('disable');
        $("#addDesignModal").find('#submitButton .button-text').addClass('hide-button-text');
        $("#addDesignModal").find('#submitButton .spinner-border').addClass('show-spinner');

        if(editable == true){
            const designId = $("#submitButton").attr('data-id');
            formData.append('design_id', designId);
            $.ajax({
                type: 'post',
                url: '<?php echo e(url("/api/edit-home-design")); ?>',
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                data: formData,
                processData: false,
                contentType: false,
                success: function(response){
                    const parent = $(`#card${response.id}`);
                    parent.find('.card-title b').text(response.title);
                    parent.find('.card-title small').text(response.product_id);
                    if(response.status_id == 1){
                        parent.find('.heading-elements').html('<span class="badge badge-success text-uppercase">active</span>');
                    }
                    else if(response.status_id == 0){
                        parent.find('.heading-elements').html('<span class="badge badge-danger text-uppercase">deactive</span>');
                    }
                    parent.find(".design-color").css("background",`rgb(${response.rgb_color})`)
                    parent.find('.card-footer span:first-child span').html(`${date.getDate(response.updated_at)}-${date.getMonth(response.updated_at)}-${date.getFullYear(response.updated_at)}`);
                    parent.find('.edit-button').attr('onclick', `designModal(true, ${response.id}, '${response.title}', '${response.product_id}', ${response.status_id}, '${colorR}', '${colorG}', '${colorB}')`);
                    $('#addDesignModal').modal('hide');
                    $("#addDesignModal").find('#submitButton').removeClass('disable');
                    $("#addDesignModal").find('#submitButton .button-text').removeClass('hide-button-text');
                    $("#addDesignModal").find('#submitButton .spinner-border').removeClass('show-spinner');
                }
            });
        }
        else{
            const designGroupId = $("#submitButton").attr('data-group-id');
            formData.append('elevation_id', designGroupId);
            $.ajax({
                type: 'post',
                url: '<?php echo e(url("/api/add-home-design")); ?>',
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                data: formData,
                processData: false,
                contentType: false,
                success: function(response){
                    let card = null;
                    card = `<div class="col-xl-3 col-lg-4 col-sm-6">
                                <div class="card" id="card${response.id}">
                                    <div class="card-header">
                                        <h4 class="card-title text-capitalize"><b>${response.title}</b><br>
                                            <small class="color-code">${response.product_id}</small>
                                            <div class="heading-elements">
                                                ${(response.status_id == 1)?'<span class="badge badge-success text-uppercase">active</span>':'<span class="badge badge-danger text-uppercase">deactive</span>'}
                                            </div>
                                        </h4>
                                    </div>
                                    <div class="card-content">
                                        <div class="d-flex justify-content-center" style="position:relative;">
                                            <div class="design-color" style="background: rgb(${response.rgb_color})"></div>
                                            <div class="default-badge"></div>
                                        </div>
                                    </div>
                                    <div class="card-footer border-top-blue-grey border-top-lighten-5 text-muted">
                                        <span class="float-left">Updated On: ${date.getDate(response.updated_at)}-${date.getMonth(response.updated_at)}-${date.getFullYear(response.updated_at)}</span>
                                        <span class="float-right">
                                            <a href="javascript:;" onclick="updateDefault(${response.id})" data-toggle="tooltip" title="Make Default" class="text-dark mr-25"> <i class="ft-check-square"></i> </a>
                                            <a href="javascript:;" onclick="designModal(true, ${response.id}, '${response.title}','${response.product_id}', ${response.status_id}, '${colorR}', '${colorG}', '${colorB}')" data-toggle="tooltip" title="Edit Design" class="text-dark mr-25 edit-button"> <i class="ft-edit"></i> </a>
                                            <a href="javascript:;" onclick="deleteSwal(${response.id})" data-toggle="tooltip" title="Delete Design" class="text-dark mr-25"> <i class="ft-trash-2"></i> </a>
                                        </span>
                                    </div>
                                </div>
                            </div>`;
                    $('.content-wrapper .row').append(card);
                    $('#addDesignModal').modal('hide');
                    $("#addDesignModal").find('#submitButton').removeClass('disable');
                    $("#addDesignModal").find('#submitButton .button-text').removeClass('hide-button-text');
                    $("#addDesignModal").find('#submitButton .spinner-border').removeClass('show-spinner');
                }
            });
        }
    }

    function deleteSwal(designId){
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                if(deleteDesign(designId) == true){
                    Swal.fire(
                    'Deleted!',
                    'Your file has been deleted.',
                    'success'
                    )
                }
            }
        })
    }

    function deleteDesign(id){
        let status = false;
        $.ajax({
            type: 'delete',
            url: '<?php echo e(url("/api/delete-home-design")); ?>',
            data: {design_id: id },
            success: function(){
                $(`#card${id}`).parent().remove();
                status = true;
            },
            error: function(error){
                status = false;
                toastr.error(error.responseJSON);
            }
        });
        return status;
    }

    function updateDefault(designId){
        $.ajax({
            type: 'put',
            url: '<?php echo e(url("/api/update-home-default")); ?>',
            data: {design_id: designId},
            success: function(){
                $(".default-badge").html('');
                $(`#card${designId}`).find('.default-badge').html(
                    `<div style="position:absolute; bottom:0px; left:10px">
                        <span class="badge badge-info text-uppercase">default</span>
                    </div>`);
            },
            error: function(error){
                toastr.error(error.responseJSON);
            }
        })
    }

</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.inner', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\biorev\ule-xdesign\cgi-bin\resources\views/admin/home-designs.blade.php ENDPATH**/ ?>