<?php $__env->startSection('content'); ?>
<div class="content-header d-flex flex-wrap justify-content-between align-items-center bg-white" style="padding: 0.8rem 2rem 0.4rem;">
    <div class="content-header-left p-0">
        <h3 class="content-header-title m-0 mr-1">Pattern Library</h3>
        <div class="row breadcrumbs-top">
            <div class="breadcrumb-wrapper pl-1">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo e(route('home')); ?>">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item">Pattern Library
                    </li>
                </ol>
            </div>
        </div>
    </div>
    <div class="content-header-right">
        <a href="javascript:;" onclick="patternLibrary(false)" class="btn btn-secondary square btn-min-width waves-effect waves-light box-shadow-2 standard-button">
            <i class="ft-plus"></i>
            <span>Add New</span>
        </a>
    </div>
</div>
<div class="content-wrapper">
    <div class="row">
        <div class="col-12">

            <table id="example" class="table table-striped table-bordered" style="width:100%">
                <thead>
                <tr>
                    <th>THUMB</th>
                    <th>Title</th>
                    <th>Product ID</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                <?php $__currentLoopData = $patterns; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pattern): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr id="row_<?php echo e($pattern->id); ?>">
                    <td><span class="cursor-pointer d-block w-50 h-50 line-height-3">&nbsp;</span></td>
                    <td><?php echo e($pattern->title); ?></td>
                    <td><?php echo e($pattern->product_id); ?></td>
                    <td><?php if($pattern->status_id == 1): ?>
                            <span class="badge badge-success text-uppercase">active</span>
                        <?php elseif($pattern->status_id == 0): ?>
                            <span class="badge badge-danger text-uppercase">deactive</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="javascript:;" onclick="patternLibrary(true, '<?php echo e($pattern->title); ?>', '<?php echo e($pattern->product_id); ?>', '<?php echo e($pattern->status_id); ?>', <?php echo e($pattern->id); ?>)" data-toggle="tooltip" title="Edit Pattern" class="text-dark mr-25 edit-button"> <i class="ft-edit"></i> </a>
                        <a href="javascript:;" onclick="deleteSwal(<?php echo e($pattern->id); ?>)" data-toggle="tooltip" title="Delete Pattern" class="text-dark mr-25"> <i class="ft-trash-2"></i> </a>
                    </td>
                </tr>

                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>


                </tbody>
                <tfoot>
                <tr>
                    <th>THUMB</th>
                    <th>Title</th>
                    <th>Product ID</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
                </tfoot>
            </table>

        </div>
    </div>
</div>
<div class="modal fade text-left" id="addPatternLibrary" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header border-bottom">
                <h3 class="modal-title"> Add Pattern</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i class="ft-x text-secondary"></i>
                </button>
            </div>
            <form id="patternForm">
                <div class="modal-body">
                    <div class="form-group ">
                        <label class="text-uppercase">Title</label>
                        <input id="title" class="form-control border" type="text" placeholder="Title" required>
                    </div>
                    <div class="form-group ">
                        <label class="text-uppercase">Product ID</label>
                        <input id="product_id" class="form-control border" type="text" placeholder="Product ID" required>
                    </div>
                    <div class="form-group ">
                        <label class="text-uppercase">Thumbnail</label>
                        <input id="thumbnail" class="form-control border" type="file" placeholder="Thumbnail" required>
                    </div>
                    <div class="form-group ">
                        <label class="text-uppercase">Additional Thumbs</label>
                        <input id="additional_thumbnail" class="form-control border" type="file" placeholder="Additional Thumbs" required>
                    </div>
                    <div class="form-group ">

                        <div class="mr-2">
                            <div class="form-group">
                                <label class="d-inline-block mr-1 text-uppercase m-0">Activate </label>
                                <br>
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
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="submitButton" onclick="submitForm(true)" data-id="" class="btn btn-dark text-white m-0"> <span class="button-text"> Save Changes </span>
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        <span class="sr-only">Loading...</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<style type="text/css">
    #rgb_code span {
        border: 1px solid #ccc;
        padding: 4px 8px;
        display: inline-block;
        margin: 5px;
    }


</style>


<?php $__env->startPush('scripts'); ?>
<script>
    const path = '<?php echo e(asset("media/uploads/exterior")); ?>';
    const date = new Date();
    let view1BaseImage = null, isChange = null;

    function patternLibrary(...values) {
        console.log(...values)
        isChange = null;
        view1BaseImage = null;
        const modal = $('#addPatternLibrary');
        if(values[0] == true){
            modal.find('.modal-title').text('Edit Pattern')
            modal.find('.modal-footer button .button-text').text('Save Changes')
            const radioButtons = $('#patternForm input[name="status"]');
            modal.find('#title').val(values[1]);
            modal.find('#product_id').val(values[2]);



            $.each(radioButtons, function(){
                if($(this).val() == values[5]){
                    $(this).prop('checked', true);
                }
            });

            modal.find('#submitButton').attr('data-id', values[5]);
            modal.find('#submitButton').attr('onclick', 'submitForm(true)');
            modal.modal('show');
        } else{
            var form = document.getElementById('patternForm');
            modal.find('.modal-title').text('Add New Pattern')
            modal.find('.modal-footer button .button-text').text('Add New')
            modal.find('#submitButton').attr('data-id', '');
            modal.find('#submitButton').attr('onclick', 'submitForm(false)');
            form.reset();
            modal.modal('show');
        }
    }





    function submitForm(editable){
        const title = $('#title').val();
        const product_id = $('#product_id').val();
        const status = $('input[name="status"]:checked').val();
        // Validations
        if(title == ''){
            toastr.clear()
            toastr.error('Title field is required');
            return false;
        }/*if(product_id == ''){
            toastr.clear()
            toastr.error('Hex Color field is required');
            return false;
        }*/

        var thumbnailInput = document.getElementById('thumbnail');
        var additionalThumbnailInput = document.getElementById('additional_thumbnail');
        const formData = new FormData();

        formData.append('title', title);
        formData.append('product_id', product_id);
        formData.append('status', status);

        formData.append('thumbnail', thumbnailInput.files[0]);
        formData.append('additional_thumbs', additionalThumbnailInput.files[0]);



        $("#add Modal").find('#submitButton').addClass('disable');
        $("#addPatternLibrary").find('#submitButton .button-text').addClass('hide-button-text');
        $("#addPatternLibrary").find('#submitButton .spinner-border').addClass('show-spinner');

        if(editable == true){
            const patternId = $("#submitButton").attr('data-id');
            formData.append('pattern_id', patternId);
            $.ajax({
                type: 'post',
                url: '<?php echo e(url("/api/edit-patternlibrary")); ?>',
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                data: formData,
                processData: false,
                contentType: false,
                success: function(response){
                    const parent = $(`#card${response.id}`);
                    parent.find('.card-title b').text(response.sw_code);

                    if(response.status_id == 1){
                        parent.find('.heading-elements').html('<span class="badge badge-success text-uppercase">active</span>');
                    }
                    else if(response.status_id == 0){
                        parent.find('.heading-elements').html('<span class="badge badge-danger text-uppercase">deactive</span>');
                    }
                    parent.find('.edit-button').attr('onclick', `patternLibrary(true, '${response.sw_code}', '${response.status_id}', '${response.hex_code}', '${response.rgb_input}', ${response.id})`);
                    $('#addPatternLibrary').modal('hide');
                    $("#addPatternLibrary").find('#submitButton').removeClass('disable');
                    $("#addPatternLibrary").find('#submitButton .button-text').removeClass('hide-button-text');
                    $("#addPatternLibrary").find('#submitButton .spinner-border').removeClass('show-spinner');

                    location.reload();
                }
            });
        }
        else{
            $.ajax({
                type: 'post',
                url: '<?php echo e(url("/api/add-patternlibrary")); ?>',
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                data: formData,
                processData: false,
                contentType: false,
                success: function(response){
                    //location.reload();
                    $('#addPatternLibrary').modal('hide');
                    $("#addPatternLibrary").find('#submitButton').removeClass('disable');
                    $("#addPatternLibrary").find('#submitButton .button-text').removeClass('hide-button-text');
                    $("#addPatternLibrary").find('#submitButton .spinner-border').removeClass('show-spinner');
                }
            });
        }
    }


    function deleteSwal(patternId){
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
                deletePatternLib(patternId);
                Swal.fire(
                    'Deleted!',
                    'Color has been deleted.',
                    'success'
                )
            }
        })
    }

    function deletePatternLib(id){
        $.ajax({
            type: 'delete',
            url: '<?php echo e(url("/api/delete-patternlibrary")); ?>',
            data: {color_id: id },
            success: function(){
                $(`#row_${id}`).remove();
            }
        });
    }

</script>
    <link rel="stylesheet" href="//cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="//cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap4.min.js"></script>
<script>
    $(document).ready( function () {
        let table = new DataTable('#myTable');
    })
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.inner', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/xhome/public_html/ule-uat.xhome360.com/cgi-bin/resources/views/admin/patternlibrary.blade.php ENDPATH**/ ?>