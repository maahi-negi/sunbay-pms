<?php $__env->startSection('content'); ?>
<div class="content-header d-flex flex-wrap justify-content-between align-items-center bg-white" style="padding: 0.8rem 2rem 0.4rem;">
    <div class="content-header-left p-0">
        <h3 class="content-header-title m-0 mr-1">Homeplans</h3>
        <div class="row breadcrumbs-top">
            <div class="breadcrumb-wrapper pl-1">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo e(route('home')); ?>">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item">Homeplans
                    </li>
                </ol>
            </div>
        </div>
    </div>
    <div class="content-header-right">
        <a href="javascript:;" onclick="ColorLibrary(false)" class="btn btn-secondary square btn-min-width waves-effect waves-light box-shadow-2 standard-button">
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
                    <th>SW CODE</th>
                    <th>RGB</th>
                    <th>HEX CODE</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                <?php $__currentLoopData = $colors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $color): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr id="row_<?php echo e($color->id); ?>">
                    <td><span class="cursor-pointer d-block w-50 h-50 line-height-3" style="background-color: <?php echo e($color->hex); ?>; line-height: 50px;">&nbsp;</span></td>
                    <td><?php echo e($color->sw_code); ?></td>
                    <td><?php echo e($color->rgb); ?></td>
                    <td><?php echo e($color->hex); ?></td>
                    <td><?php if($color->status_id == 1): ?>
                            <span class="badge badge-success text-uppercase">active</span>
                        <?php elseif($color->status_id == 0): ?>
                            <span class="badge badge-danger text-uppercase">deactive</span>
                        <?php endif; ?>
                    </td>
                    <td>


                        <a href="javascript:;" onclick="ColorLibrary(true, '<?php echo e($color->sw_code); ?>', '<?php echo e($color->rgb); ?>', '<?php echo e($color->hex); ?>','<?php echo e($color->status_id); ?>', <?php echo e($color->id); ?>)" data-toggle="tooltip" title="Edit Homeplan" class="text-dark mr-25 edit-button"> <i class="ft-edit"></i> </a>
                        <a href="javascript:;" onclick="deleteSwal(<?php echo e($color->id); ?>)" data-toggle="tooltip" title="Delete Homeplan" class="text-dark mr-25"> <i class="ft-trash-2"></i> </a>
                    </td>
                </tr>

                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>


                </tbody>
                <tfoot>
                <tr>
                    <th>THUMB</th>
                    <th>SW CODE</th>
                    <th>RGB</th>
                    <th>HEX CODE</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
                </tfoot>
            </table>

        </div>
    </div>
</div>
<div class="modal fade text-left" id="addColorLibrary" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header border-bottom">
                <h3 class="modal-title"> Add New Color</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i class="ft-x text-secondary"></i>
                </button>
            </div>
            <form id="colorForm">
                <div class="modal-body">
                    <div class="form-group ">
                        <label class="text-uppercase">SW Code</label>
                        <input id="sw_code" class="form-control border" type="text" placeholder="SW Code" required>
                    </div>
                    <div class="form-group ">
                        <div class="mr-2">
                            <div class="form-group">
                                <label class="text-uppercase">Hex Color</label>
                                <span class="color-picker">
                                  <label for="colorPicker">
                                    <input type="color" value="" onchange="printColor(event)" id="colorPicker">
                                      <span id="color_hex"></span>
                                  </label>
                                </span>
                                <input type="hidden" id="rgb_input" name="rgb_input">
                            </div>
                            <div class="form-group">
                                <label class="text-uppercase">RGB Color</label>
                                <div id="rgb_code">
                                    <span class="r"></span>
                                    <span class="g"></span>
                                    <span class="b"></span>
                                </div>

                        </div>
                        </div>

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

    function ColorLibrary(...values) {
        console.log(...values)
        isChange = null;
        view1BaseImage = null;
        const modal = $('#addColorLibrary');
        if(values[0] == true){
            modal.find('.modal-title').text('Edit Color')
            modal.find('.modal-footer button .button-text').text('Save Changes')
            const radioButtons = $('#colorForm input[name="status"]');
            modal.find('#sw_code').val(values[1]);
            modal.find('#rgb_input').val(values[2]);
            modal.find('#colorPicker').val(values[3]);

            let rgbString = values[2];
            let numbersOnly = rgbString.slice(4, -1);
            let rgbArray = numbersOnly.split(',');
            let red = rgbArray[0];
            let green = rgbArray[1];
            let blue = rgbArray[2];

            //$("#rgb_code").;
            modal.find('#rgb_code .r').text(red);
            modal.find('#rgb_code .g').text(green);
            modal.find('#rgb_code .b').text(blue);


            $.each(radioButtons, function(){
                if($(this).val() == values[4]){
                    $(this).prop('checked', true);
                }
            });

            modal.find('#submitButton').attr('data-id', values[5]);
            modal.find('#submitButton').attr('onclick', 'submitForm(true)');
            modal.modal('show');
        } else{
            var form = document.getElementById('colorForm');
            modal.find('.modal-title').text('Add New Color')
            modal.find('.modal-footer button .button-text').text('Add New')
            modal.find('#submitButton').attr('data-id', '');
            modal.find('#submitButton').attr('onclick', 'submitForm(false)');
            form.reset();
            modal.modal('show');
        }
    }





    function submitForm(editable){
        const sw_code = $('#sw_code').val();
        const hex_code = $('#colorPicker').val();
        const rgb_input = $('#rgb_input').val();
        const status = $('input[name="status"]:checked').val();
        // Validations
        if(sw_code == ''){
            toastr.clear()
            toastr.error('SW Code field is required');
            return false;
        }if(hex_code == ''){
            toastr.clear()
            toastr.error('Hex Color field is required');
            return false;
        }


        const formData = new FormData();

        formData.append('sw_code', sw_code);
        formData.append('hex_code', hex_code);
        formData.append('rgb_input', rgb_input);
        formData.append('status', status);
        $("#add Modal").find('#submitButton').addClass('disable');
        $("#addColorLibrary").find('#submitButton .button-text').addClass('hide-button-text');
        $("#addColorLibrary").find('#submitButton .spinner-border').addClass('show-spinner');

        if(editable == true){
            const colorId = $("#submitButton").attr('data-id');
            formData.append('color_id', colorId);
            $.ajax({
                type: 'post',
                url: '<?php echo e(url("/api/edit-colorlibrary")); ?>',
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
                    parent.find('.edit-button').attr('onclick', `ColorLibrary(true, '${response.sw_code}', '${response.status_id}', '${response.hex_code}', '${response.rgb_input}', ${response.id})`);
                    $('#addColorLibrary').modal('hide');
                    $("#addColorLibrary").find('#submitButton').removeClass('disable');
                    $("#addColorLibrary").find('#submitButton .button-text').removeClass('hide-button-text');
                    $("#addColorLibrary").find('#submitButton .spinner-border').removeClass('show-spinner');

                    location.reload();
                }
            });
        }
        else{
            $.ajax({
                type: 'post',
                url: '<?php echo e(url("/api/add-colorlibrary")); ?>',
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                data: formData,
                processData: false,
                contentType: false,
                success: function(response){
                    location.reload();
                    $('#addColorLibrary').modal('hide');
                    $("#addColorLibrary").find('#submitButton').removeClass('disable');
                    $("#addColorLibrary").find('#submitButton .button-text').removeClass('hide-button-text');
                    $("#addColorLibrary").find('#submitButton .spinner-border').removeClass('show-spinner');
                }
            });
        }
    }


    function deleteSwal(colorId){
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
                deleteColorLib(colorId);
                Swal.fire(
                    'Deleted!',
                    'Color has been deleted.',
                    'success'
                )
            }
        })
    }

    function deleteColorLib(id){
        $.ajax({
            type: 'delete',
            url: '<?php echo e(url("/api/delete-colorlibrary")); ?>',
            data: {color_id: id },
            success: function(){
                $(`#row_${id}`).remove();
            }
        });
    }
    function printColor(ev) {
        const color = ev.target.value
        const r = parseInt(color.substr(1,2), 16)
        const g = parseInt(color.substr(3,2), 16)
        const b = parseInt(color.substr(5,2), 16)
        $("#color_hex").html(color);
        $('#rgb_input').val(`rgb(${r}, ${g}, ${b})`)

        $('#rgb_code .r').text(r)
        $('#rgb_code .g').text(g)
        $('#rgb_code .b').text(b)
        console.log(`red: ${r}, green: ${g}, blue: ${b}`)
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

<?php echo $__env->make('layouts.inner', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/xhome/public_html/ule-uat.xhome360.com/cgi-bin/resources/views/admin/colorlibrary.blade.php ENDPATH**/ ?>