<!DOCTYPE html>
<html>
<?php echo $__env->make('includes.head', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<body>
    <?php echo $__env->make('includes.header-footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php echo $__env->yieldContent('content'); ?>
    <?php if(Route::currentRouteName() != 'front.floorplan'): ?>
        <script>
            const minPrice = 0,
                maxPrice = 10000;
            let sourcesView1 = [],
                sourcesView2 = [];
        </script>
    <?php endif; ?>
    <?php echo $__env->make('includes.scripts', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
   
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $("#mainLoader").show();

        let auth_token = "LcMDq4lDWA3Zl8hN45l4xFqMe5jJnLprr17iyLq9DNme!MOa2maMY6F7Yx8B7Ouz";
        let home_id = null, elevationId = null;
        $(document).ready(function (){
            var api_url = "https://tkr.xhome360.com/api/home";
            $.ajax({
                type: 'get',
                url: api_url,
                headers: {'token': `${auth_token}`},
                processData: false,
                contentType: false,
                success: function(response){
                    $(".main-wrapper").addClass("main-wrapper-sidebar");
                    $("#sideMenu").addClass("d-flex").show();
                    $("#elevation-title").html(`${response.title}`);
                    home_id = `${response.id}`;
                    elevationId = `${response.elevation}`;
                    sourcesView1 = {base_image_view1:response.image};
                    sourcesView2 = {base_image_view2:response.image};
                    var option_listing = $("#options-listing-data");
                    let ol = "", design_lists = "";
                    if ( typeof response.designCategories !== "undefined" && response.designCategories) {
                        $.each(response.designCategories, function (k,design_type){
                            var c_slug =  design_type.categoryName
                            c_slug = c_slug.replace(" ", "-").toLowerCase();
                            ol += `<div class="options-loop-box" id="options-icon-${design_type.categoryId}">
                                    <div class="design-icons-wrap image-icons-wrap">
                                        <div class="design-icons back-image" style="background: url(${design_type.thumbailImage}) no-repeat;background-position: center center;background-size: contain;"></div>
                                        <span class="text-white">
                                           <i data-target="#${c_slug}Data" class="check-design-option" data-feather="check-circle"></i>
                                        </span>
                                    </div>
                                    <p class="text-capitalize">${design_type.categoryName}</p>
                                </div>`;

                            design_lists += `<div id="${c_slug}Data" class="content-container custom-scroll scroll-width-thin">
                                <div class="designs-wrapper container">
                                    <div class="row">`;

                            if ( typeof design_type.options !== "undefined" && design_type.options) {
                                $.each(design_type.options, function (d, design){
                                    var is_cls = "", is_cls2 = "", span_cls = "", icls = "";
                                    if(design.isDefault =="1"){
                                        is_cls = "color-active";
                                        is_cls2 = "fade-image";
                                        span_cls = "show-buttons";
                                        icls = "button-active";
                                    }
                                    design_lists += `<div class="col-sm-4 col-6">
                                        <div class="design-container image-icons-wrap mb-1 ${is_cls}">
                                            <div data-dcid="${design_type.designCategoryId}" data-designCategoryId="${design_type.categoryId}" data-design-title="${design_type.categoryName}" data-value="${design.title}" data-id="${design.optionId}" data-pid="${design.product_id}" class="w-100 design back-image ${is_cls2}" style="background-image: url('${design.thumbnailImage}'); background-repeat:no-repeat; background-size: contain; background-position: center;"></div>
                                            <span class="text-white d-flex ${span_cls}">
                                                <i data-feather="check-circle" class="mr-1 check-color-option ${icls}" data-design-group-view1='' data-design-group-view2='' data-design-view2='' data-open-view='' data-open-view2=''
                                                   data-design-type='${c_slug}'
                                                   data-design-view1='${design.layerImage}'>
                                                </i>
                                            </span>
                                        </div>
                                        <p class="text-capitalize p-0 m-0" data-price="${design.price}">${design.title.replace(" ","<br>")}</p>
                                    </div>`;
                                });
                            }
                            design_lists += `</div></div></div>`;

                        });
                        option_listing.prepend(ol);
                        $("#filtersWrap").after(design_lists);
                    }
                    feather.replace();
                    $('<script/>',{type:'text/javascript', src:'<?php echo e(asset("js/main.js")); ?>'}).appendTo('head');
                },
                error: function(xhr, status, error) {
                    alert("Error code: "+ xhr.status+ " - "+ error)
                    return false;
                }
            });
        });

        function submitVisualizer(){
            Swal.fire({
              title: 'Are you sure?',
              text: "You want to submit your selections!",
              icon: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: 'Yes, Submit!',
              cancelButtonText: 'No'
            }).then((result) => {
                if (result.isConfirmed) {

                    var api_url = '/api/submit-visualizer'; 
                    var o = $(".design-container.color-active").find(".design");
                    var options = Array();
                    $.each(o, function(indx) {
                        var designCategoryId = $(this).attr("data-dcid");
                        var optionId = o[indx].dataset.pid;
                        options.push({designCategoryId:designCategoryId,designOptionId:optionId});
                    });

                    const formData = {
                        selections:options,
                    }
                    $.ajax({
                        type: 'POST',
                        url: api_url,
                        crossDomain: true,
                        dataType: 'json',
                        headers: {'token': `${auth_token}`},
                        data: formData,
                        success: function(response){
                            console.log(response);
                            Swal.fire({
                              text: "You selection has been submitted successfully!",
                              icon: 'success',
                              showCancelButton: true,
                              confirmButtonColor: '#3085d6',
                              cancelButtonColor: '#d33',
                              confirmButtonText: 'Reset Selection!',
                              cancelButtonText: 'Keep Selection!'
                            }).then((result) => {
                              if (result.isConfirmed) {
                                location.reload();
                              }
                            })
                        },
                        error: function(xhr, status, error) {
                            toastr.error("Submission failed. Please try again later");
                            return false;
                        }
                    });
                }
            })
        }

        function submitForm(){

            /*var api_url = '/api/submit-visualizer'; 
            

            var o = $(".design-container.color-active").find(".design");
            var options = Array();
            $.each(o, function(indx) {
                // var designCategoryId = $(this).attr("data-design-title");
                // var optionId = o[indx].dataset.value;
                var designCategoryId = $(this).attr("data-dcid");
                var optionId = o[indx].dataset.pid;
                options.push({designCategoryId:designCategoryId,designOptionId:optionId});
            });
            modal.find('#submitButton').addClass('disable').attr('disabled', 'disabled');
            modal.find('#submitButton .button-text').addClass('hide-button-text');
            modal.find('#submitButton .spinner-border').addClass('show-spinner');
            const formData = {
                selections:options,
            }

            //console.log(formData);
            //return false;
            $.ajax({
                type: 'POST',
                url: api_url,
                crossDomain: true,
                dataType: 'json',
                headers: {'token': `${auth_token}`},
                data: formData,
                success: function(response){
                    modal.find('#submitButton').removeClass('disable').removeAttr('disabled');
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
                    modal.find('#submitButton').removeClass('disable').removeAttr('disabled');
                    modal.find('#submitButton .button-text').removeClass('hide-button-text');
                    modal.find('#submitButton .spinner-border').removeClass('show-spinner');
                    toastr.clear();
                    toastr.error("Submission failed. Please try again later");
                    return false;
                }
            });*/
        }
    </script>
</body>
</html>
<?php /**PATH /home/xhome/public_html/tkr.xhome360.com/cgi-bin/resources/views/layouts/main.blade.php ENDPATH**/ ?>