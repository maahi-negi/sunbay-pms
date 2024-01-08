<?php $__env->startSection('content'); ?>
<div class="content-header d-flex flex-wrap justify-content-between align-items-center bg-white" style="padding: 0.8rem 2rem 0.4rem;">
    <div class="content-header-left p-0">
        <h3 class="content-header-title m-0 mr-1">Design Types</h3>
        <div class="row breadcrumbs-top">
            <div class="breadcrumb-wrapper col-12">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="<?php echo e(route('home')); ?>">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="<?php echo e(route('homeplans')); ?>">Homeplans</a>
                    </li>
                    <li class="breadcrumb-item text-capitalize">
                        <u class="ml-25"><?php echo e(ucwords($home_title)); ?></u>
                    </li>
                </ol>
            </div>
        </div>
    </div>
    <div class="content-header-right">
        <a href="<?php echo e(route('homeplans')); ?>" class="btn btn-secondary square btn-min-width waves-effect waves-light box-shadow-2 px-2 standard-button">
            <i class="ft-arrow-left"></i>
            <span>Back</span>
        </a>
        <a href="javascript:;" onclick="designTypeModal(false)" class="btn btn-secondary square btn-min-width waves-effect waves-light box-shadow-2 px-2 standard-button">
            <i class="ft-plus"></i>
            <span>Add New</span>
        </a>
    </div>
</div>
<div class="content-wrapper">
    <div class="row">
        <?php $__currentLoopData = $design_types; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $design_type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="col-xl-3 col-lg-4 col-sm-6">
                <div class="card" id="card<?php echo e($design_type->id); ?>">
                    <div class="card-header">
                        <h4 class="card-title text-capitalize"><b><?php echo e($design_type->title); ?></b>
                            <div class="heading-elements">
                                <?php if($design_type->status_id == 1): ?>
                                    <span class="badge badge-success text-uppercase">active</span>
                                <?php elseif($design_type->status_id == 0): ?>
                                    <span class="badge badge-danger text-uppercase">deactive</span>
                                <?php endif; ?>
                            </div>
                        </h4>
                    </div>
                    <div class="card-content bg-secondary py-3 text-center" style="height:160px">
                        <img class="img-fluid" src="<?php echo e(asset('media/thumbnails/'.$design_type->thumbnail)); ?>">
                    </div>
                    <div class="card-footer border-top-blue-grey border-top-lighten-5 text-muted">
                        <span class="float-left">Updated On: <?php echo e(date('d-m-Y',strtotime($design_type->updated_at))); ?></span>
                        <span class="float-right">
                            <a href="<?php echo e(route('home-designs', ['home_design_type_id' => base64_encode($design_type->id), 'elevation_id' => $elevation_id])); ?>" data-toggle="tooltip" title="View Designs" class="text-dark mr-25"> <i class="ft-eye"></i> </a>
                            <a href="javascript:;" onclick="designTypeModal(true, '<?php echo e($design_type->id); ?>', '<?php echo e($design_type->title); ?>', '<?php echo e($design_type->thumbnail); ?>', '<?php echo e($design_type->image_view1); ?>', '<?php echo e($design_type->priority); ?>','<?php echo e($design_type->status_id); ?>','<?php echo e($design_type->slug); ?>')" data-toggle="tooltip" title="Edit Design Type" class="text-dark mr-25 edit-button"> <i class="ft-edit"></i> </a>
                            <a href="javascript:;" onclick="deleteSwal(<?php echo e($design_type->id); ?>);" data-toggle="tooltip" title="Delete Design Type" class="text-dark mr-25"> <i class="ft-trash-2"></i> </a>
                        </span>
                    </div>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</div>

<div class="modal fade text-left" id="addDesignTypeModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header border-bottom">
                <h3 class="modal-title"> Add New Design Type</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i class="ft-x text-secondary"></i>
                </button>
            </div>
            <form id="designTypeForm">
                <div class="modal-body">
                    <div class="form-group">
                        <label class="text-uppercase">Title</label>
                        <input name="title" id="title" class="form-control border" type="text" placeholder="Enter title" required>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-12 col-12">
                                <div class="form-group d-flex flex-wrap justify-content-start">
                                    <div class="mr-2">
                                        <label for="image" class="text-uppercase mb-0">Thumbnail/Icon</label>
                                        <figure class="position-relative w-150 mb-0">
                                            <img src="<?php echo e(asset('media/placeholder.jpg')); ?>" class="img-thumbnail">
                                            <input type="file" id="thumbnailImage" class="d-none" onchange="readUrl(this)">
                                            <label class="btn btn-sm btn-secondary in-block m-0" style="padding:0.59375rem 1rem" for="thumbnailImage"> <i class="ft-image"></i> Choose Image</label>
                                        </figure>
                                    </div>
                                    <div class="mr-2">
                                        <label for="image" class="text-uppercase ml-0">Base Layer image</label>
                                        <figure class="position-relative w-150 mb-0">
                                            <img src="<?php echo e(asset('media/placeholder.jpg')); ?>" class="img-thumbnail">
                                            <input type="file" id="imageView1" class="d-none" onchange="readUrl(this, 'view1')">
                                            <label class="btn btn-sm btn-secondary in-block m-0" style="padding:0.59375rem 1rem" for="imageView1"> <i class="ft-image"></i> Choose Image</label>
                                        </figure>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group mt-2">
                                    <label class="d-inline-block mr-1 text-uppercase m-0">Activate </label><br>
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
                            <div class="col-sm-6 col-12">
                                <div class="form-group mt-2">
                                    <label class="d-inline-block mr-1 text-uppercase m-0">Priority </label>
                                    <input name="priority" id="priority" class="form-control border" type="number" min="1" placeholder="1" required>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="submitButton" onclick="submitForm(true)" data-id="" class="btn btn-dark text-white m-0" data-design-group-id="<?php echo e($elevation_id); ?>">
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
<script>
    const date = new Date();
    const path = '<?php echo e(asset("media/thumbnails")); ?>';
    const layer_path = '<?php echo e(asset("media/uploads/exterior")); ?>';
    var c = 0;
    var sc = 0;
    let thumbnailImage = null, view1Image = null, isChange = false;

    function designTypeModal(...values){
        thumbnailImage = null;
        isChange = false;
        const modal = $('#addDesignTypeModal');
        if(values[0] == true){
            modal.find('.modal-title').text('Edit Design Type')
            modal.find('.modal-footer button .button-text').text('Save Changes')
            const statusRadioButtons = $('#designTypeForm input[name="status"]');
            $.each(statusRadioButtons, function(){
                if($(this).val() == values[6]){
                    $(this).prop('checked', true);
                }
            });
            modal.find('#priority').val(values[5]);
            modal.find('#title').val(values[2]);
            modal.find('#thumbnailImage').prev().attr('src', `${path}/${values[3]}`);
            modal.find('#imageView1').prev().attr('src', `${layer_path}/${values[7]}_${values[1]}/${values[4]}`);

            modal.find('#submitButton').attr('data-id', values[1]);
            modal.find('#submitButton').attr('onclick', 'submitForm(true)');
            modal.modal('show');
        }
        else{
            var form = document.getElementById('designTypeForm');
            modal.find('.modal-title').text('Add New Design Type')
            modal.find('.modal-footer button .button-text').text('Add New')
            modal.find('.img-thumbnail').attr('src', '<?php echo e(asset("media/placeholder.jpg")); ?>')
            modal.find('#submitButton').attr('data-id', '');
            modal.find('#submitButton').attr('onclick', 'submitForm(false)');
            form.reset();
            $('.sets').html("");
            modal.modal('show');
        }
    }

    // Get FileType
    const fileType = (file) => {
        return file.type.split('/').pop().toLowerCase();
    }

    const imageValidation = () => {
        if(thumbnailImage == null){
            toastr.clear()
            toastr.error('Thumbnail/Icon is required');
            return false;
        }

        if (fileType(thumbnailImage) != "jpeg" && fileType(thumbnailImage) != "jpg" && fileType(thumbnailImage) != "png") {
            toastr.clear()
            toastr.error('Only jpeg, jpg, png formats are allowed for thumbnail/icon');
            return false;
        }
    }

    function submitForm(editable){
        const title = $('#title').val();
        const status = $('input[name="status"]:checked').val();
        const priority = $('#priority').val();
        var categories = [];
        var inputs = $(".new-sets");
        for(var i = 0; i < inputs.length; i++){
            var subCategories = [];
            var cate_name = $(inputs[i]).val();

            var cate_id = $(inputs[i]).attr("data-category");
            var subset = $(".new-subsets-"+ cate_id);
            for(var s = 0; s < subset.length; s++){
                subCategories.push($(subset[s]).val());
            }
            categories[i] = Array(cate_name,subCategories);
        }

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

        if(editable == true){
            if(isChange == true){
                if(imageValidation() == false){
                    return false;
                }
            }
        }
        else{
            if(imageValidation() == false){
                return false;
            }
        }

        const formData = new FormData();
        formData.append('title', title);
        formData.append('status', status);
        formData.append('priority', priority);
        formData.append('thumbnail_image', thumbnailImage);
        formData.append('view1_image', view1Image);

        $("#addDesignTypeModal").find('#submitButton').addClass('disable');
        $("#addDesignTypeModal").find('#submitButton .button-text').addClass('hide-button-text');
        $("#addDesignTypeModal").find('#submitButton .spinner-border').addClass('show-spinner');
        if(editable == true){
            const designTypeId = $("#submitButton").attr('data-id');
            formData.append('design_type_id', designTypeId);
            $.ajax({
                type: 'post',
                url: '<?php echo e(url("/api/edit-home-design-type")); ?>',
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                data: formData,
                processData: false,
                contentType: false,
                success: function(response){
                    console.log(response);
                    const parent = $(`#card${response.id}`);
                    parent.find('.card-title b').text(response.title);
                    parent.find('.card-content .img-fluid').attr('src',`${path}/${response.thumbnail}`);
                    if(response.status_id == 1){
                        parent.find('.heading-elements').html('<span class="badge badge-success text-uppercase">active</span>');
                    }
                    else if(response.status_id == 0){
                        parent.find('.heading-elements').html('<span class="badge badge-danger text-uppercase">deactive</span>');
                    }
                    parent.find('.edit-button').attr('onclick', `designTypeModal(true, '${response.id}', '${response.title}', '${response.thumbnail}','${response.image_view1}', '${response.priority}', ${response.status_id},'${response.slug}')`);
                    $('#addDesignTypeModal').modal('hide');
                    $("#addDesignTypeModal").find('#submitButton').removeClass('disable');
                    $("#addDesignTypeModal").find('#submitButton .button-text').removeClass('hide-button-text');
                    $("#addDesignTypeModal").find('#submitButton .spinner-border').removeClass('show-spinner');
                }
            });
        }
        else{
            const designGroupId = $("#submitButton").attr('data-design-group-id');
            formData.append('elevation_id', designGroupId);
            $.ajax({
                type: 'post',
                url: '<?php echo e(url("/api/add-home-design-type")); ?>',
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                data: formData,
                processData: false,
                contentType: false,
                success: function(response){
                    let card = null;
                    card = `<div class="col-xl-3 col-lg-4 col-sm-6">
                                <div class="card" id="card${response.id}">
                                    <div class="card-header">
                                        <h4 class="card-title text-capitalize"><b>${response.title}</b>
                                            <div class="heading-elements">
                                                ${(response.status_id == 1)?'<span class="badge badge-success text-uppercase">active</span>':'<span class="badge badge-danger text-uppercase">deactive</span>'}
                                            </div>
                                        </h4>
                                    </div>
                                    <div class="card-content bg-secondary py-3 text-center" style="height:160px">
                                        <img class="img-fluid" src="${path}/${response.thumbnail}">
                                    </div>
                                    <div class="card-footer border-top-blue-grey border-top-lighten-5 text-muted">
                                        <span class="float-left">Updated On: ${date.getDate(response.updated_at)}-${date.getMonth(response.updated_at)}-${date.getFullYear(response.updated_at)}</span>
                                        <span class="float-right">
                                            <a href="/admin/exterior/elevations/design-types/designs/${btoa(response.id)}/${btoa(response.elevation_id)}" data-toggle="tooltip" title="View Design Types" class="text-dark mr-25"> <i class="ft-eye"></i> </a>
                                            <a href="javascript:;" onclick="designTypeModal(true, '${response.id}', '${response.title}', '${response.thumbnail}','${response.image_view1}', '${response.priority}',${response.status_id},'${response.slug}')" data-toggle="tooltip" title="Edit Design Group" class="text-dark mr-25 edit-button"> <i class="ft-edit"></i> </a>
                                            <a href="javascript:;" onclick="deleteSwal(${response.id})" data-toggle="tooltip" title="Delete Design Group" class="text-dark mr-25"> <i class="ft-trash-2"></i> </a>
                                        </span>
                                    </div>
                                </div>
                            </div>`;
                    $('.content-wrapper .row').append(card);
                    $('#addDesignTypeModal').modal('hide');
                    $("#addDesignTypeModal").find('#submitButton').removeClass('disable');
                    $("#addDesignTypeModal").find('#submitButton .button-text').removeClass('hide-button-text');
                    $("#addDesignTypeModal").find('#submitButton .spinner-border').removeClass('show-spinner');
                }
            });
        }
    }

    function readUrl(input, element) {
        if (input.files && input.files[0])
        {
            var reader = new FileReader();
            reader.onload = function (e)
            {
                $(input).prev().attr('src', e.target.result);
            };
            reader.readAsDataURL(input.files[0]);
            switch(element){
                case 'thumbnail':
                    thumbnail = input.files[0];
                    isChange = 'thumbnail';
                    break;
                case 'view1':
                    view1Image = input.files[0];
                    isChange = 'view1';
                    break;
                case 'view2':
                    view2Image = input.files[0];
                    isChange = 'view2';
                    break;
                default:
                    break;
            }
        }
    }

    function deleteSwal(designTypeId){
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
                deleteDesignType(designTypeId);
                Swal.fire(
                'Deleted!',
                'Design Type has been deleted.',
                'success'
                )
            }
        })
    }

    function deleteDesignType(id){
        $.ajax({
            type: 'delete',
            url: '<?php echo e(url("/api/delete-home-design-type")); ?>',
            data: {design_type_id: id },
            success: function(){
                $('#card'+id).parent().remove();
            }
        });
    }

</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.inner', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\biorev\ule-xdesign\cgi-bin\resources\views/admin/home-design-types.blade.php ENDPATH**/ ?>