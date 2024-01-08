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

        <div class="table-responsive bg-white fixed-header-table">
            <table class="table table-striped biorev-table">
                <thead>
                <tr class="text-uppercase">
                    <th>Title</th>
                    <th>Color Code</th>
                    <th class="wf-150">Color</th>
                    <th class="wf-150">Status</th>
                    <th class="wf-150">Is Default</th>
                    <th class="wf-150 text-center">Action</th>
                </tr>
                </thead>
                <tbody id="recordBody">
                <?php if(count($designs) >= 1): ?>
                    <?php $__currentLoopData = $designs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $design): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr id="row<?php echo e($design->id); ?>">
                            <td class="text-dark">
                                <?php echo e($design->title); ?>

                            </td>
                            <td class="text-dark"><?php echo e($design->product_id); ?></td>
                            <td>
                                <div class="design-color" style="background: rgb(<?php echo e($design->rgb_color); ?>);height: 55px;overflow: hidden;">
                                    <?php if($design->thumbnail): ?>
                                        <img class="img-fluid" src="<?php echo e(asset('media/uploads/exterior/'.$design_type_slug.'/'.$design->thumbnail)); ?>" style="width:100%;">
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td>
                                <?php if($design->status_id == 1): ?>
                                    <span class="badge badge-success text-uppercase">active</span>
                                <?php elseif($design->status_id == 0): ?>
                                    <span class="badge badge-danger text-uppercase">deactive</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="default-badge">
                                    <?php if($design->is_default == 1): ?>
                                        <span class="btn btn-info btn-sm text-uppercase">default</span>
                                    <?php else: ?>
                                        <a href="javascript:;" onclick="updateDefault(<?php echo e($design->id); ?>)" class="text-dark mr-25 btn btn-sm btn-warning">
                                            Set Default
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td class="text-center">
                                <?php $rgb_color = explode(",",$design->rgb_color);?>
                                <a href="javascript:;" onclick="designModal(true, <?php echo e($design->id); ?>, '<?php echo e($design->title); ?>','<?php echo e($design->product_id); ?>', <?php echo e($design->status_id); ?>,'<?php echo e(@$rgb_color[0]); ?>','<?php echo e(@$rgb_color[1]); ?>','<?php echo e(@$rgb_color[2]); ?>','<?php echo e($design->hex_color); ?>', '<?php echo e($design->thumbnail); ?>', '<?php echo e($design->design_type); ?>','<?php echo e($design->additional_thumbs); ?>','<?php echo e($design->layer); ?>','<?php echo e($design->category); ?>', '<?php echo e($design->sub_category); ?>')" data-toggle="tooltip" title="Edit Design" class="mr-25 table-icon-btn"> <i class="ft-edit"></i> </a>
                                <a href="javascript:;" onclick="deleteSwal(<?php echo e($design->id); ?>)" data-toggle="tooltip" title="Delete Design" class="table-icon-btn"> <i class="ft-trash-2"></i> </a>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php else: ?>
                    <tr class="nodata">
                        <td colspan="6">
                            <p class="text-danger p-0 m-0">
                                No items found in collection.
                            </p>
                        </td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="modal fade text-left" id="addDesignModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document" style="max-width:90vw;">
            <div class="modal-content">
                <div class="modal-header border-bottom">
                    <h3 class="modal-title"> Add New Design</h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i class="ft-x text-secondary"></i>
                    </button>
                </div>
                <form id="designForm">
                    <div class="modal-body">
                        <div class="form-group">
                            <div class="d-inline-block custom-control custom-radio mr-1">
                                <input type="radio" name="data_from" class="custom-control-input" id="df1" value="1">
                                <label class="custom-control-label" for="df1">Color from Library</label>
                            </div>
                            <div class="d-inline-block custom-control custom-radio">
                                <input type="radio" name="data_from" class="custom-control-input" id="df2" value="0">
                                <label class="custom-control-label" for="df2">Add New</label>
                            </div>

                            <div class="d-inline-block custom-control custom-radio">
                                <input type="radio" name="data_from" class="custom-control-input" id="df3" value="2">
                                <label class="custom-control-label" for="df3">Add New From Google</label>
                            </div>
                        </div>



                        <div class="box">

                            <div class="get-library" style="display: none;">
                                <select class="form-control selectpicker" id="select-color" data-live-search="true">
                                    <option data-tokens="">Select Color</option>
                                </select>
                                <hr>
                            </div>

                            <div class="design-data form-row mb-2">
                                <div class="col">
                                    <label class="text-uppercase m-0">Title</label>
                                    <input name="title" id="title" class="form-control border" type="text" placeholder="Enter title" required>
                                </div>
                                <div class="col">
                                    <label class="d-inline-block text-uppercase m-0">Color / Texture </label><br>
                                    <div class="d-inline-block custom-control custom-radio mr-1">
                                        <input type="radio" name="design_type" class="custom-control-input" id="dt-1" value="1" checked>
                                        <label class="custom-control-label" for="dt-1">Color</label>
                                    </div>
                                    <div class="d-inline-block custom-control custom-radio" style="padding: 10px 25px;">
                                        <input type="radio" name="design_type" class="custom-control-input" id="dt-2" value="2">
                                        <label class="custom-control-label" for="dt-2">Texture</label>
                                    </div>

                                    <?php if($design_type->layer_option): ?>
                                        <div class="d-inline-block custom-control custom-radio" style="padding: 10px 25px;">
                                            <input type="radio" name="design_type" class="custom-control-input" id="dt-3" value="3">
                                            <label class="custom-control-label" for="dt-3">Layer</label>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="row design-data">
                                <div class="col-sm-6 col-12">
                                    <div class="form-group form-row">
                                        <div class="col">
                                            <div class="mr-2">
                                                <label for="image" class="text-uppercase ml-0 thumbnail">Thumbnail/Icon</label>
                                                <figure class="position-relative w-150 mb-0">
                                                    <img id="image" src="<?php echo e(asset('media/placeholder.jpg')); ?>" class="img-thumbnail">
                                                    <input type="file" accept="image/jpg,image/jpeg,image/png" id="thumbnailImage" class="d-none">
                                                    <label class="btn btn-sm btn-secondary in-block m-0" style="padding:0.59375rem 1rem" for="thumbnailImage"> <i class="ft-image"></i> Choose Image</label>
                                                </figure>
                                            </div>
                                            <div class="mt-2">
                                                <button type="button" class="btn btn-sm btn-success mx-50 applycropbtn" onclick="applyCropping()" style="display: none">Done With Cropping</button>
                                            </div>
                                        </div>
                                        <div class="col generate-layer" style="display: none;">
                                            <div class="mr-2">
                                                <label for="image" class="text-uppercase ml-0"> Layer image</label>
                                                <figure class="position-relative w-150 mb-0">
                                                    <img src="<?php echo e(asset('media/placeholder.jpg')); ?>" class="img-thumbnail" id="layer-image" style="padding-top: 30px;">
                                                    <a href="#" download class="download-layer badge badge-info">Download</a>
                                                </figure>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                                <div class="col-sm-6 col-12 new-layer" style="display: none;">
                                    <div class="form-group d-flex flex-wrap justify-content-start">
                                        <div class="mr-2">
                                            <label for="image" class="text-uppercase ml-0"> Layer image</label>
                                            <figure class="position-relative w-150 mb-0">
                                                <img src="<?php echo e(asset('media/placeholder.jpg')); ?>" class="img-thumbnail">
                                                <input type="file" id="imageView1" class="d-none" onchange="readUrl(this, 'view1')">
                                                <label class="btn btn-sm btn-secondary in-block m-0" style="padding:0.59375rem 1rem" for="imageView1"> <i class="ft-image"></i> Choose Image</label>
                                            </figure>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-6 col-12 design-option color-options" style="padding-left: 8px;">
                                    <div class="form-group">
                                        <label class="text-uppercase">SW Code</label>
                                        <input name="product_id" id="productId" autocomplete="off" class="form-control border" onblur="autoSuggestion(event)" type="text" placeholder="SW 7005" required>
                                        <ul id="autocompleteResults" class="autocomplete-results" style="width: 400px;"></ul>
                                    </div>

                                    <div class="form-group">
                                        <label class="text-uppercase" style="margin-left: 0">RGB Color </label>
                                        <div>
                                            <input type="text" id="color_red" class="form-control border rgb-color" style="width: 60px;float: left;" placeholder="R" required>
                                            <input type="text" id="color_green" class="form-control border rgb-color" style="width: 60px;float: left;" placeholder="G" required>
                                            <input type="text" id="color_blue" class="form-control border rgb-color" style="width: 60px;float: left;" placeholder="B" required>
                                            <div class="clearfix"></div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="text-uppercase" style="margin-left: 0">HEX Color</label>
                                        <input name="hex" id="hex_color" class="form-control border hex-color" type="text" placeholder="#FFFFFF" required>
                                    </div>

                                    <div class="form-group" style="margin-bottom: 5px;">
                                        <label style="border: 1px solid #CCC;padding: 0 7px;">
                                            <a href="#" data-toggle="modal" data-target="#colorPicker">
                                                <span class="ft-edit-2"></span> Color Picker
                                            </a>
                                        </label>
                                    </div>
                                </div>

                                <div class="col-sm-6 col-12 design-option texture-options" style="width:200px;display: none;">
                                    <label class="text-uppercase mb-0">Thumbnail Preview</label>
                                    <div class="croppreview">
                                        <figure class="position-relative w-full mb-0">
                                            <img src="<?php echo e(asset('media/placeholder.jpg')); ?>" class="img-thumbnail preview">
                                        </figure>
                                    </div>

                                </div>

                                <?php if($design_type->image_view2 || $design_type->image_view3): ?>
                                    <div class="col-sm-12 col-12 design-option brick-imgs" style="width:200px;display: none;">
                                        <div class="col" style="padding-left: 2px;">
                                            <a href="#" class="pull-right add-brick adb-1" onclick="addBrickImg()"><i class="ft-plus"></i> Add</a>
    <!--                                        <label class="text-uppercase mb-0">Brick</label><br>
                                            <div class="d-inline-block custom-control custom-radio mr-1">
                                                <input type="radio" name="brick_image1" class="custom-control-input" id="b1-1" value="1" onchange="brickImage('1',this)">
                                                <label class="custom-control-label" for="b1-1">Upload Brick</label>
                                            </div>
                                            <div class="d-inline-block custom-control custom-radio" style="padding: 10px 25px;">
                                                <input type="radio" name="brick_image1" class="custom-control-input" id="b1-2" value="2" onchange="brickImage('1',this)">
                                                <label class="custom-control-label" for="b1-2">Crop Brick</label>
                                            </div>

                                            <div class="mr-2 upload-brick-1" style="display: none">
                                                <figure class="position-relative w-150 mb-0">
                                                    <img src="<?php echo e(asset('media/placeholder.jpg')); ?>" class="img-thumbnail brk-prev preview-b1">
                                                    <input type="file" accept="image/jpg,image/jpeg,image/png" id="brick-1" class="d-none" onchange="readUrl(this, 'brick1')">
                                                    <div class="ub1" style="display: none;">
                                                        <label class="btn btn-sm btn-secondary in-block m-0" style="padding:0.59375rem 1rem" for="brick-1"> <i class="ft-image"></i> Choose Image</label>
                                                    </div>
                                                </figure>
                                            </div>-->
                                        </div>
                                        <hr>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="form-row mt-2">
                                <div class="col">
                                    <div class="form-group design-data">
                                        <label class="d-inline-block text-uppercase m-0">Activate </label><br>
                                        <div class="d-inline-block custom-control custom-radio mr-1">
                                            <input type="radio" name="status" class="custom-control-input" id="yes1" value="1">
                                            <label class="custom-control-label" for="yes1">Yes</label>
                                        </div>
                                        <div class="d-inline-block custom-control custom-radio">
                                            <input type="radio" name="status" class="custom-control-input" id="no1" value="0" checked>
                                            <label class="custom-control-label" for="no1">No</label>
                                        </div>
                                    </div>
                                </div>
                                <?php if($design_type_categories): ?>
                                    <div class="col">
                                        <label class="text-uppercase ml-0">Category</label>
                                        <select id="category" name="category" class="form-control border" onchange="getSubCategory()">
                                            <option value="">Select Category</option>
                                            <?php $__currentLoopData = $design_type_categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($key); ?>"><?php echo e($cat); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                    <div class="col sub-category">
                                        <label class="text-uppercase ml-0">Sub Category</label>
                                        <select id="sub-category" name="sub_category" class="form-control border"></select>
                                    </div>
                                <?php endif; ?>

                            </div>

                            <div class="form-group library-check" style="display: none;">
                                <fieldset>
                                    <input class="chk-remember" type="checkbox" name="add_to_library" id="add_to_library" value="1">
                                    <label class="d-inline-block text-uppercase m-0" for="add_to_library">
                                        Add to Library
                                    </label>
                                </fieldset>
                            </div>
                        </div>

                        <section id="search_box_panel" class="search_box">
                            <h1>Image Search</h1>
                            <div class="flex flex-row" style="width: 40%;margin: 5px auto;">
                                <input type="text" id="searchQuery"  placeholder="Enter search query">
                                <a onclick="searchImages()" class="search_button">
                                    <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="24" height="24" viewBox="0 0 24 24">
                                        <path d="M 9 2 C 5.1458514 2 2 5.1458514 2 9 C 2 12.854149 5.1458514 16 9 16 C 10.747998 16 12.345009 15.348024 13.574219 14.28125 L 14 14.707031 L 14 16 L 20 22 L 22 20 L 16 14 L 14.707031 14 L 14.28125 13.574219 C 15.348024 12.345009 16 10.747998 16 9 C 16 5.1458514 12.854149 2 9 2 z M 9 4 C 11.773268 4 14 6.2267316 14 9 C 14 11.773268 11.773268 14 9 14 C 6.2267316 14 4 11.773268 4 9 C 4 6.2267316 6.2267316 4 9 4 z"></path>
                                    </svg>
                                </a>
                                <button onclick="clearSearch()">Clear</button>
                            </div>

                            <div id="imageResults"></div>
                            <div id="page_controls" style="display: none;">
                                <div id="pagination"></div>
                                <button id="previousPageBtn" onclick="previousPage()">Previous Page</button>
                                <button id="nextPageBtn" onclick="nextPage()">Next Page</button>
                            </div>
                            <!--<div id="img_grid"></div>-->
                        </section>

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

    <div id="colorPicker" class="modal fade" role="dialog" style="background: rgb(0 0 0 / 73%);">
        <div class="modal-dialog modal-lg" style="max-width: calc(45%);">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Color Picker</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i class="ft-x text-secondary"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="mr-1">
                        <figure class="position-relative mb-0">
                            <img src="" class="img-thumbnail clrPkr">
                            <input type="file" id="cp1" class="d-none" accept="image/jpg,image/jpeg,image/png" onchange="readUrl(this, 'colorPicker')">
                            <label class="btn btn-sm btn-secondary in-block m-0" style="padding:0.59375rem 1rem" for="cp1"> <i class="ft-image"></i> Choose Image</label>
                        </figure>
                    </div>
                </div>
                <div class="modal-footer">
                    <p class="mb-0">
                        <span class="haxColor"></span>
                    </p>
                    <button class="btn save-colors" style="display:none;color: #fff;">
                        Apply Color
                    </button>

                    <button class="btn btn-primary open-picker" style="display: none;color: #fff;">
                        <i class="ft-edit-2"></i> Pick Color
                    </button>
                </div>
            </div>

        </div>
    </div>
    <style>
        div#imageResults {
            min-height: 50vh;
        }
        a.close.close-icon {
            background: red;
            color: #fff;
            border-radius: 100%;
            box-shadow: 0px 0px 3px;
            padding: 3px;
            width: 28px;
            text-align: center;
            font-size: 18px;
            line-height: 24px;
        }


        div#img_grid {
            width: 100%;
            height: 400px;
        }
        section.search_box {
            padding: 15px;
            /*width: 50%;
            max-width: 450px;*/
            overflow-y: auto;
            height: calc(100% - 26px);
            display: none;
        }
        input#searchQuery {
            width: calc(100% - 110px);
        }
        section.search_box h1 {
            font-size: 24px;
            text-align: center;
            padding-bottom: 20px;
            border-bottom: 1px solid #ccc;
        }

        .search_box button, .search_box a.search_button {
            border: 0;
            background: #3a3b3d;
            padding: 2px 8px;
            box-shadow: 0px 0px 1px;
            color: #cecdcd;
            margin-left: 5px;
            fill: #fff;
        }

        .search_box button svg {
            fill: #cecdcd;
        }

        img.card-img-top {
            width: 100%;
            object-fit: contain;
            height: 160px;
        }

        img#blah6 {
            width: auto;
            height: 240px;
        }



        .autocomplete {
            position: relative;
            display: inline-block;
        }

        #autocompleteResults {
            list-style: none;
            padding: 0;
            margin: 0;
            position: absolute;
            width: 100%;
            z-index: 1;
            display: none;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .autocomplete-results li {
            padding: 8px;
            background-color: #fff;
            border-bottom: 1px solid #eee;
            cursor: pointer;
        }

        .autocomplete-results li:hover {
            background-color: #f9f9f9;
        }

    </style>

<?php $__env->stopSection(); ?>
<?php $__env->startPush('scripts'); ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.10.0/js/bootstrap-select.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.10.0/css/bootstrap-select.min.css" rel="stylesheet" />
    <style>
        select.form-control{height: 41px !important;}
        .design-color{
            width: 100%;
        }
        .card .card-header {
            padding: 1rem 1rem;
        }
        .badge-info{font-size: .7rem;}
        .bootstrap-select.btn-group .dropdown-menu.inner {
            padding: 0 15px;
        }
        .bootstrap-select.btn-group .dropdown-menu li {
            border-bottom: 1px solid #e1e1e1;
        }
        .bootstrap-select.btn-group .dropdown-menu li a {
            color: #000;padding: 3px 3px;
        }
        .bootstrap-select.btn-group .dropdown-toggle {
            background: none;
            border: 1px solid #e0e0e0;
        }
        .bootstrap-select.btn-group .dropdown-toggle:focus {
            background: none;border: 1px solid #e0e0e0;color:#000;
            outline: none;
        }
        .download-layer{
            position: absolute;bottom: 1px;right: 1px;padding: 1px 5px;
        }
        .sub-category{display: none;}




        .img-card {
            width: 20%;
            padding: 10px;
        }

        .row2 {
            display: flex;
            justify-content: space-between;
            align-items: stretch;
            align-content: space-around;
            flex-wrap: wrap;
        }

        .img-card .card .card-title {
            font-size: 12px;
            padding-right: initial;
            height: 45px;
        }

        .img-card p.card-text {
            padding: 0;
        }
    </style>
    <script>
        const path = '<?php echo e(asset("media/uploads/exterior/".$design_type_slug)); ?>';
        const date = new Date();
        let thumbnail = null,view1 = null, isChange = null, tempThumb = null;
        let brick1 = null,brick2 = null,brick3 = null,brick_pattern = null;
        let simg = '';
        var image = document.getElementById("image");
        var cropper, b = 1, cropper_type = "";
        const eyeDropper = new EyeDropper()
        const pickerBtn = document.querySelector('.open-picker')
        // base layers
        const baseLayer1 = "<?php echo e(($design_type->image_view1)?$design_type->image_view1:""); ?>";
        const baseLayer2 = "<?php echo e(($design_type->image_view2)?$design_type->image_view2:""); ?>";
        const baseLayer3 = "<?php echo e(($design_type->image_view3)?$design_type->image_view3:""); ?>";
        let LayerCanvas = document.createElement('canvas'),
            layerCtx = LayerCanvas.getContext("2d");
            LayerCanvas.width = 1440;
            LayerCanvas.height = 960;

        // Get FileType
        const fileType = (file) => {
            return file.type.split('/').pop().toLowerCase();
        }
        const imageValidation = () => {
            if(thumbnail == null){
                toastr.clear()
                toastr.error(`Thumbnail is required`);
                return false;
            }
        }
        const editImageValidation = () => {
            if(isChange == 'thumbnail'){
                if(thumbnail == null){
                    toastr.clear()
                    toastr.error(`Thumbnail is required`);
                    return false;
                }
            }
        }

        function designModal(...values){
            if(cropper){resetCropping();}
            isChange = null;
            thumbnail = null, brick1 =null,brick2 = null,brick3 = null,brick_pattern = null;
            var modal = $('#addDesignModal');
            $(".color-options").show();
            var form = document.getElementById('designForm');
            $("#search_box_panel, #page_controls").hide();
            $("#imageResults").empty();
            form.reset();
            $(".brick-imgs, .texture-options, .library-check, .generate-layer").hide();
            b = 1
            if(values[0] == true){
                modal.find('.modal-title').text('Edit Design')
                modal.find('#submitButton .button-text').text('Save Changes')
                const statusRadioButtons = $('#designForm input[name="status"]');
                $.each(statusRadioButtons, function(){
                    if($(this).val() == values[4]){
                        $(this).prop('checked', true);
                    }
                });
                const designTypeButtons = $('#designForm input[name="design_type"]');
                $.each(designTypeButtons, function(){
                    if($(this).val() == values[10]){
                        $(this).prop('checked', true);
                    }
                });
                modal.find('#title').val(values[2]);
                modal.find('#productId').val(values[3]);
                modal.find('#color_red').val(values[5]);
                modal.find('#color_green').val(values[6]);
                modal.find('#color_blue').val(values[7]);
                modal.find('#hex_color').val(values[8]);
                modal.find('#category').val(values[13]);

                if(values[5]!="" && values[6]!="" && values[7]!=""){
                    generateLayer(values[5],values[6],values[7]);
                }

                if(values[9]!="" && values[9]!="null"){
                    modal.find('#thumbnailImage').prev().attr('src', `${path}/${values[9]}`);
                    modal.find('.preview').attr('src', `${path}/${values[9]}`);
                    if(values[10]=="2"){
                        generateLayerWithPattern(`${path}/${values[9]}`);
                    }
                }else{
                    modal.find('#thumbnailImage').prev().attr('src', `<?php echo e(asset('media/placeholder.jpg')); ?>`);
                }

                if(values[12]!="" && values[12]!="null"){
                    modal.find('#imageView1').prev().attr('src', `${path}/${values[12]}`);
                }else{
                    modal.find('#imageView1').prev().attr('src', `<?php echo e(asset('media/placeholder.jpg')); ?>`);
                }

                if(values[10]=="2"){
                    $(".design-option, .new-layer").hide();
                    $(".brick-imgs").show();
                }
                if(values[10]=="3"){
                    $(".design-option, .brick-imgs").hide();
                    $(".new-layer").show();
                }

                $(".brk-2, .brk-3").remove();
                if(values[11] && values[11]!="null"){
                    var brickData = JSON.parse(values[11]);
                    if(brickData.brick1){
                        $(".brick-imgs").show();
                        $(".preview-b1").attr("src",`${path}/${brickData.brick1}`);
                        $(".upload-brick-1").show();
                        brick1 = brickData.brick1
                    }else{
                        $(".upload-brick-1").hide();
                        $(".preview-b1").attr("src",`<?php echo e(asset('media/placeholder.jpg')); ?>`);
                    }
                    if(brickData.brick2){
                        $(".add-brick").hide();
                        addBrickImg();
                        $(".preview-b2").attr("src",`${path}/${brickData.brick2}`);
                        $(".upload-brick-2").show();
                        brick2 = brickData.brick2
                    }
                    if(brickData.brick3){
                        $(".add-brick").hide();
                        addBrickImg();
                        $(".preview-b3").attr("src",`${path}/${brickData.brick3}`);
                        $(".upload-brick-3").show();
                        brick3 = brickData.brick3
                    }
                    if(brickData.thumb_pattern){
                        brick_pattern = brickData.thumb_pattern;
                    }
                }

                modal.find('#submitButton').attr('data-id', values[1]);
                modal.find('#submitButton').attr('onclick', 'submitForm(true)');
                modal.find('.design-data').show();
                modal.modal('show');
                getSubCategory(values[1],values[14]);
            }
            else{
                modal.find('#thumbnailImage').prev().attr('src', `<?php echo e(asset('media/placeholder.jpg')); ?>`);
                modal.find('.modal-title').text('Add New Design')
                modal.find('#submitButton .button-text').text('Add New')
                modal.find('#submitButton').attr('data-id', '');
                modal.find('#submitButton').attr('onclick', 'submitForm(false)');
                modal.find('.design-data').hide();
                modal.modal('show');
            }
        }

        function submitForm(editable){
            const title = $('#title').val();
            const productId = $('#productId').val();
            const status = $('input[name="status"]:checked').val();
            const design_type = $('input[name="design_type"]:checked').val();
            const add_to_library = $('input[name="add_to_library"]:checked').val();
            const category = $('select[name="category"]').val();
            const sub_category = $('select[name="sub_category"]').val();

            const designTypeId = $("#submitButton").attr('data-type-id');
            const bricks = [];
            // data form library or new add
            const data_from = $("input[name=data_from]:checked").val();
            let colorR = $('#color_red').val();
            let colorG = $('#color_green').val();
            let colorB = $('#color_blue').val();
            let colorHex = $('#hex_color').val();

            if(design_type=="1"){
                if((colorR != '' && colorG != '' && colorB !='') && colorHex==""){
                    colorHex = RGBtoHex(parseInt(colorR), parseInt(colorG), parseInt(colorB));
                    $("#hex_color").val(colorHex);
                }else if(colorHex!="" && (colorR == '' || colorG == '' || colorB =='')){
                    var rgb_color = HexToRGB(colorHex);
                    colorR = rgb_color[0];
                    colorG = rgb_color[1];
                    colorB = rgb_color[2];
                    $('#color_red').val(colorR);
                    $('#color_green').val(colorG);
                    $('#color_blue').val(colorB);
                }
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

            if(design_type=="1"){
                if((colorR == '' || colorG == '' || colorB =='') && colorHex ==''){
                    toastr.clear()
                    toastr.error('Please provide RGB or Hex Color');
                    return false;
                }
                if(colorR > 255 || colorG > 255 || colorB > 255){
                    toastr.clear()
                    toastr.error('Invalid color');
                    return false;
                }

                if(productId == ''){
                    toastr.clear()
                    toastr.error('SW Code field is required');
                    return false;
                }
                if(!(/^[A-Za-z0-9 ]+$/.test(productId))){
                    toastr.clear()
                    toastr.error('SW Code field should only contain alphabets.');
                    return false;
                }
            }else{
                if(editable == true){
                    if(editImageValidation() == false){
                        toastr.clear()
                        toastr.error('Please select thumbnail/texture image');
                        return false;
                    }
                } else{
                    if(imageValidation() == false){
                        toastr.clear()
                        toastr.error('Please select thumbnail/texture image');
                        return false;
                    }
                }
            }
            const formData = new FormData();
            formData.append('title', title);
            formData.append('product_id', productId);
            formData.append('colorR', colorR);
            formData.append('colorG', colorG);
            formData.append('colorB', colorB);
            formData.append('hex_color', colorHex);
            formData.append('status', status);
            formData.append('design_type', design_type);
            formData.append('thumbnail', (thumbnail)?thumbnail:"");
            formData.append('image_layer', (thumbnail)?view1:"");
            formData.append('add_to_library', add_to_library);
            formData.append('category', category);
            formData.append('sub_category', sub_category);

            if($(".brick-imgs").has('.col')){
                var m = $(".brick-imgs").find(".col")
                m.each(function(){
                    if($(this).find('input[type="radio"]').is(':checked')) {
                        var b1 = $(this).find('input[type="radio"]:checked').val();
                        var bn = $(this).find('input[type="radio"]:checked').attr("name");
                        bn = bn.replace("brick_image","");
                        bricks[bn] = b1;
                    }
                });
                formData.append('bricks_type', (bricks)?JSON.stringify(bricks):"");
                formData.append('brick1', (brick1)?brick1:"");
                formData.append('brick2', (brick2)?brick2:"");
                formData.append('brick3', (brick3)?brick3:"");
                formData.append('brick_pattern', (brick_pattern)?brick_pattern:"");
            }

            formData.append('design_type_id', designTypeId);
            $("#addDesignModal").find('#submitButton').addClass('disable');
            $("#addDesignModal").find('#submitButton .button-text').addClass('hide-button-text');
            $("#addDesignModal").find('#submitButton .spinner-border').addClass('show-spinner');
            console.log(formData);false;

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
                        let status = (response.status_id == 1)?"active":"deactive";
                        let statusClass = (response.status_id == 1)?"badge-success":"badge-danger";
                        let is_default = `<a href="javascript:;" onclick="updateDefault(${designId})" class="text-dark mr-25 btn btn-sm btn-warning">Set Default</a>`;
                        if(response.is_default == "1"){
                            is_default = `<span class="btn btn-info btn-sm text-uppercase">default</span>`;
                        }
                        if(response.thumbnail){
                            var thumbnail = `<div class="design-color" style="background:  rgb(${response.rgb_color});">
                                <img class="img-fluid" src="${path}/${response.thumbnail}" style="width:100%;">
                            </div>`;
                        }else{
                            var thumbnail = `<div class="design-color" style="background: rgb(${response.rgb_color});height:40px"></div>`;
                        }
                        let card = null;
                        card = `<td>
                                ${response.title}
                            </td>
                            <td>${response.product_id}</td>
                            <td>${thumbnail}</td>
                        <td>
                        <span class="badge ${statusClass} text-uppercase">${status}</span>
                            </td>
                            <td>
                                <div class="default-badge">${is_default}</div>
                            </td>
                            <td class="text-center">
                                <a href="javascript:;" onclick="designModal(true, ${response.id}, '${response.title}','${response.product_id}', ${response.status_id}, '${colorR}', '${colorG}', '${colorB}','${response.hex_color}','${response.thumbnail}','${response.design_type}','${response.additional_thumbs}','${response.layer}','${response.category}','${response.sub_category}')" data-toggle="tooltip" title="Edit Design" class="table-icon-btn mr-25"> <i class="ft-edit"></i> </a>
                                <a href="javascript:;" onclick="deleteSwal(${response.id})" data-toggle="tooltip" title="Delete Design" class="table-icon-btn"> <i class="ft-trash-2"></i> </a>
                            </td>`;
                        $(`#row${response.id}`).html(card);
                        $('#addDesignModal').modal('hide');
                        $("#addDesignModal").find('#submitButton').removeClass('disable');
                        $("#addDesignModal").find('#submitButton .button-text').removeClass('hide-button-text');
                        $("#addDesignModal").find('#submitButton .spinner-border').removeClass('show-spinner');
                        if(response.additional_thumbs){
                            window.location.reload();
                        }
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
                        console.log(response);
                        let status = (response.status_id == 1)?"active":"deactive";
                        let statusClass = (response.status_id == 1)?"badge-success":"badge-danger";
                        if(response.thumbnail!=''){
                            var thumbnail = `<div class="design-color" style="background:  rgb(${response.rgb_color});">
                                <img class="img-fluid" src="${path}/${response.thumbnail}" style="width:100%;">
                            </div>`;
                        }else{
                            var thumbnail = `<div class="design-color" style="background: rgb(${response.rgb_color});height:40px"></div>`;
                        }
                        let card = null;
                        card = `<tr id="row${response.id}">
                            <td>
                                ${response.title}
                                <div class="default-badge"></div>
                            </td>
                            <td>${response.product_id}</td>
                            <td>${thumbnail}</td>
                            <td>
                                <span class="badge ${statusClass} text-uppercase">${status}</span>
                            </td>
                            <td>
                                <div class="default-badge">
                                    <a href="javascript:;" onclick="updateDefault(${response.id})" class="text-dark mr-25 btn btn-sm btn-warning">Set Default</a>
                                </div>
                            </td>
                            <td class="text-center">
                                <a href="javascript:;" onclick="designModal(true, ${response.id}, '${response.title}','${response.product_id}', ${response.status_id}, '${colorR}', '${colorG}', '${colorB}','${response.hex_color}','${response.thumbnail}','${response.design_type}','${response.additional_thumbs}','${response.layer}','${response.category}','${response.sub_category}')" data-toggle="tooltip" title="Edit Design" class="table-icon-btn mr-25"> <i class="ft-edit"></i> </a>
                                <a href="javascript:;" onclick="deleteSwal(${response.id})" data-toggle="tooltip" title="Delete Design" class="table-icon-btn"> <i class="ft-trash-2"></i> </a>
                            </td>
                        </tr>`;
                        $('#recordBody').append(card);

                        $('#addDesignModal').modal('hide');
                        $("#addDesignModal").find('#submitButton').removeClass('disable');
                        $("#addDesignModal").find('#submitButton .button-text').removeClass('hide-button-text');
                        $("#addDesignModal").find('#submitButton .spinner-border').removeClass('show-spinner');
                        if(response.additional_thumbs){
                            window.location.reload();
                        }
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
                    $(`#row${id}`).remove();
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
                    $("#recordBody").find("tr").each(function (){
                        var rowid = $(this).attr("id").replace("row","");
                        $(this).find(".default-badge").html(`<a href="javascript:;" onclick="updateDefault(${rowid})" class="text-dark mr-25 btn btn-sm btn-warning">Set Default</a>`);
                    });

                    $(`#row${designId}`).find('.default-badge').html(`<span class="btn btn-info btn-sm text-uppercase">default</span>`);
                },
                error: function(error){
                    toastr.error(error.responseJSON);
                }
            })
        }

        function readUrl(input, element) {
            if (input.files && input.files[0])
            {
                var reader = new FileReader();
                var img_file = input.files[0];
                if (fileType(img_file) != "jpeg" && fileType(img_file) != "jpg" && fileType(img_file) != "png") {
                    toastr.clear()
                    toastr.error('Only jpeg, jpg, png formats are allowed. Please select valid file.');
                    return false;
                }else{
                    reader.onload = function (e) {
                        $(input).prev().attr('src', e.target.result);

                        if(element=="brick1" || element=="brick2" || element=="brick3"){
                            var brick_images = [];
                            $(".brk-prev").each(function(){
                                brick_images.push(this.src)
                            });
                            loadImages(brick_images, function(e) {
                                getPatternImage(e,brick_images.length);
                            });
                        }
                        if(element=="colorPicker"){
                            $(".open-picker").show();
                        }
                    };
                    reader.readAsDataURL(input.files[0]);
                    switch(element){
                        case 'thumbnail':
                            thumbnail = input.files[0];
                            isChange = 'thumbnail';
                            break;
                        case 'brick1':
                            brick1 = input.files[0];
                            isChange = 'brick1';
                            break;
                        case 'brick2':
                            brick2 = input.files[0];
                            isChange = 'brick2';
                            break;
                        case 'brick3':
                            brick3 = input.files[0];
                            isChange = 'brick3';
                            break;
                        case 'view1':
                            view1 = input.files[0];
                            isChange = 'view1';
                            break;
                        default:
                            break;
                    }
                }
            }
        }

        $("body").on("change", "#thumbnailImage", function (e) {
            cropper_type = "";
            $(".brick-imgs").hide();
            simg = '';
            var files = e.target.files;
            var done = function (url) {
                image.src = url;
            };
            var reader;
            var file;
            var url;
            if (files && files.length > 0) {
                if(cropper){resetCropping();}
                file = files[0];
                if (URL) {
                    done(URL.createObjectURL(file));
                    reader = new FileReader();
                    reader.onload = function (e) {
                        simg = reader.result;
                    };
                    reader.readAsDataURL(file);
                } else if (FileReader) {
                    reader = new FileReader();
                    reader.onload = function (e) {
                        done(reader.result);
                        simg = reader.result;
                    };
                    reader.readAsDataURL(file);
                }
            }

            var design_type = $("input[name=design_type]:checked").val();
            if(design_type=="1" || design_type=="3"){
                thumbnail = file;
                isChange = 'thumbnail';
                $('.applycropbtn').hide();
                $(".texture-options").hide();
            }else{
                $('.croppreview').addClass('croppreviewcss');
                cropper = new Cropper(image, {
                    viewMode: 1,
                    preview: ".croppreview",
                });
                $('.applycropbtn').show();
                $(".texture-options").show();
            }
        });








        function setGoogleImage(imageUrl) {
            fetch('<?php echo e(url("/api/upload-image")); ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                },
                body: JSON.stringify({ imageUrl: imageUrl }),
            })
                .then(response => response.json())
                .then(data => {

                    if(cropper){
                        cropper.destroy();
                        cropper = null;
                    }
                    $('#image').attr('src', '<?php echo e(asset("media/placeholder.jpg")); ?>');

                    $("#df2").trigger("click");
                    $("#dt-2").trigger("click");

                    console.log(data);

                    image.src = data.imagePath;

                    $('.croppreview').addClass('croppreviewcss');
                    cropper = new Cropper(image, {
                        viewMode: 1,
                        preview: ".croppreview",
                    });
                    $('.applycropbtn').show();
                    $(".texture-options").show();
                })
                .catch(error => console.error('Error:', error));
        }



        function applyCropping() {
            canvas = cropper.getCroppedCanvas();
            canvas.toBlob(function (blob) {
                url = URL.createObjectURL(blob);
                var reader = new FileReader();
                reader.readAsDataURL(blob);
                reader.onloadend = function () {
                    $('.applycropbtn').hide();
                    cropper.destroy();
                    cropper = null;
                    $('#image').attr('src', '<?php echo e(asset("media/placeholder.jpg")); ?>');
                    if(cropper_type==""){
                        thumbnail = reader.result;
                        isChange = 'thumbnail';

                        $('.croppreview').removeClass('croppreviewcss').html(`<figure class="position-relative w-full mb-0"><img src="${thumbnail}" class="img-thumbnail preview"></figure>`);
                        $("#thumbnailImage").val(null);
                        $(".brick-imgs").show();
                        $('.croppreview').css("width","120px");
                        generateLayerWithPattern(thumbnail);
                    }else{
                        if(cropper_type=="1"){
                            brick1 = reader.result;
                            isChange = 'brick1';
                        }else if(cropper_type=="2"){
                            brick2 = reader.result;
                            isChange = 'brick2';
                        }else{
                            brick3 = reader.result;
                            isChange = 'brick3';
                        }

                        $(".upload-brick-"+cropper_type).removeClass('croppreviewcss').css("height","auto");
                        $('.preview-b'+cropper_type).attr('src',`${reader.result}`);
                        var brick_images = [];
                        $(".brk-prev").each(function(){
                            brick_images.push(this.src)
                        });
                        loadImages(brick_images, function(e) {
                            getPatternImage(e,brick_images.length);
                        });
                    }
                };
            });
        }

        function resetCropping() {
            cropper.destroy();
            cropper = null;
            $('#image').attr('src', '<?php echo e(asset("media/placeholder.jpg")); ?>');
            $('.croppreview').removeClass('croppreviewcss').html(`<figure class="position-relative w-full mb-0"><img src="media/placeholder.jpg" class="img-thumbnail preview"></figure>`);
            $('.applycropbtn').hide();
            $(".texture-options").hide();
            thumbnail = null;
        }

        function brickImage(i,e){
            $(".upload-brick-"+i).show().removeClass('croppreviewcss');
            cropper_type = i;
            if(e.value=="1"){
                if(cropper){
                    cropper.destroy();
                    cropper = null;
                    $('#image').attr('src', '<?php echo e(asset("media/placeholder.jpg")); ?>');
                }
                $(".ub"+i).show();
                $(".preview-b"+i).css("padding-top","30px");
            }else{
                $(".ub"+i).hide();
                $(".preview-b"+i).css("padding-top","0");
                image.src = $(".preview").attr("src");

                $(".upload-brick-"+i).addClass('croppreviewcss');
                cropper = new Cropper(image, {
                    viewMode: 1,
                    preview: ".upload-brick-"+i,
                });
                $('.applycropbtn').show();
                $('.texture-options').show();
            }
        }

        function addBrickImg(){
            $(".add-brick").hide();
            b++;
            let bl = `<div class="col brk-${b}" style="padding-left: 2px;">
                        <a href="#" class="pull-right add-brick adb-${b}" onclick="addBrickImg()"><i class="ft-plus"></i> Add</a>
                        <label class="text-uppercase mb-0">Brick ${b}</label><br>
                        <div class="d-inline-block custom-control custom-radio mr-1">
                            <input type="radio" name="brick_image${b}" id="b${b}-1" class="custom-control-input" value="1" onchange="brickImage('${b}',this)">
                            <label class="custom-control-label" for="b${b}-1">Upload Brick</label>
                        </div>
                        <div class="d-inline-block custom-control custom-radio" style="padding: 10px 25px;">
                            <input type="radio" name="brick_image${b}" id="b${b}-2" class="custom-control-input" value="2" onchange="brickImage('${b}',this)">
                            <label class="custom-control-label" for="b${b}-2">Crop Brick</label>
                        </div>

                        <div class="mr-2 upload-brick-${b}" style="display: none">
                            <figure class="position-relative w-150 mb-0">
                                <img src="" class="img-thumbnail brk-prev preview-b${b}">
                                <input type="file" accept="image/jpg,image/jpeg,image/png" id="brick-${b}" class="d-none" onchange="readUrl(this, 'brick${b}')">
                                <div class="ub${b}" style="display: none;">
                                    <label class="btn btn-sm btn-secondary in-block m-0" style="padding:0.59375rem 1rem" for="brick-${b}"> <i class="ft-image"></i> Choose Image</label>
                                </div>
                            </figure>
                        </div>
                        <hr>
                    </div>`;
            $(".brick-imgs").prepend(bl);
            if(b >= 3){
                $(".add-brick").hide();
            }
        }
        // convert colors
        const HexToRGB = (hex) => {
            const red = parseInt(hex.substring(1, 3), 16);
            const green = parseInt(hex.substring(3, 5), 16);
            const blue = parseInt(hex.substring(5, 7), 16);
            return [red, green, blue];
        }
        const componentToHex = (c) => {
            const hex = c.toString(16);
            return hex.length == 1 ? "0" + hex : hex;
        }
        const RGBtoHex = (r, g, b) => {
            return "#" + componentToHex(r) + componentToHex(g) + componentToHex(b);
        }

        $(document).ready(function (){
            $(".rgb-color").keyup(function (){
                const colorR = $('#color_red').val();
                const colorG = $('#color_green').val();
                const colorB = $('#color_blue').val();
                if(colorR != '' && colorG != '' && colorB !=''){
                    var hexColor = RGBtoHex(parseInt(colorR), parseInt(colorG), parseInt(colorB));
                    $("#hex_color").val(hexColor);
                    generateLayer(colorR,colorG,colorB);
                }
            });

            $("#hex_color").keyup(function (){
                const colorHex = $(this).val();
                if(colorHex != '' && colorHex.length >= 7){
                    var rgb_color = HexToRGB(colorHex);
                    $('#color_red').val(rgb_color[0]);
                    $('#color_green').val(rgb_color[1]);
                    $('#color_blue').val(rgb_color[2]);
                    generateLayer(rgb_color[0],rgb_color[1],rgb_color[2]);
                }
            });

            $("input[name=design_type]").change(function (){
                $("#thumbnailImage").val(null);
                if(cropper){
                    resetCropping();
                }else{
                    $('#image').attr('src', '<?php echo e(asset("media/placeholder.jpg")); ?>');
                    thumbnail = null;
                }
                $(".design-option, .new-layer, .generate-layer").hide();
                var design_type = $(this).val();
                if(design_type=="1"){
                    $(".color-options").show();
                    $(".thumbnail").html("Thumbnail/Icon");
                }else if(design_type=="3"){
                    $(".thumbnail").html("Thumbnail");
                    $(".new-layer").show();
                }else{
                    $(".thumbnail").html("Thumbnail/Texture");
                }
            });

            pickerBtn.addEventListener('click', function() {
                eyeDropper.open()
                    .then(res => {
                        var rgb_color = HexToRGB(res.sRGBHex);
                        $(".haxColor").html(`Hex Color: ${res.sRGBHex}, <br> RGB (${rgb_color[0]}, ${rgb_color[1]}, ${rgb_color[2]})`);
                        $(".save-colors").show().css("background",res.sRGBHex);
                        // set value
                        $("#hex_color").val(res.sRGBHex);
                        $('#color_red').val(rgb_color[0]);
                        $('#color_green').val(rgb_color[1]);
                        $('#color_blue').val(rgb_color[2]);
                        generateLayer(rgb_color[0],rgb_color[1],rgb_color[2]);
                    })
                    .catch(err => {
                        console.log("User canceled the selection.");
                    })
            });

            $(".save-colors").click(function (){
                $("#colorPicker").modal("hide");
                $(".clrPkr").attr("src","");
                $(".cp1").val(null);
                $(".haxColor").html("");
                $(".open-picker, .save-colors").hide();
            });

            /* select color form library or add new */
            $("input[name=data_from]").change(function (){
                var data_from = $(this).val();
                if(data_from==1){
                    $(".get-library").show();
                    $(".design-data").hide();
                    $(".library-check").hide();
                    $("#search_box_panel").hide();
                }if(data_from==2) {
                    $(".get-library").hide();
                    $(".design-data").hide();
                    $(".library-check").hide();
                    $("#search_box_panel").show();
                }else{
                    $(".get-library").hide();
                    $(".design-data").show();
                    $(".library-check").show();
                    $("#search_box_panel").hide();
                }
            });

            $(".selectpicker").change(function(){
                let v = $(this).val();
                let c_val = v.split(',');
                var modal = $('#addDesignModal');
                modal.find('#title').val(c_val[0]);
                modal.find('#productId').val(c_val[1]);
                modal.find('#color_red').val(c_val[2]);
                modal.find('#color_green').val(c_val[3]);
                modal.find('#color_blue').val(c_val[4]);
                modal.find('#hex_color').val(c_val[5]);
                if(c_val[2] && c_val[3] && c_val[4]){
                    generateLayer(c_val[2],c_val[3],c_val[4]);
                }

                if(c_val[6]!="" && c_val[6]!="null"){
                    modal.find('#thumbnailImage').prev().attr('src', `${c_val[6]}`);
                    modal.find('.preview').attr('src', `${c_val[6]}`);
                    thumbnail = c_val[6];
                }else{
                    modal.find('#thumbnailImage').prev().attr('src', `<?php echo e(asset('media/placeholder.jpg')); ?>`);
                }

                $(".design-option").hide();
                if(c_val[5] || (c_val[2] && c_val[3] && c_val[4])){
                    $("#dt-1").prop('checked', true);
                    $(".color-options").show();
                    $(".thumbnail").html("Thumbnail/Icon");
                }else{
                    $("#dt-2").prop('checked', true);
                    $(".thumbnail").html("Thumbnail/Texture");
                }

                $(".design-data").show();
            });

            $(document).ready(function (){
                var category = "<?php echo e($design_type_slug); ?>".split("_");
                var formData = new FormData();
                formData.append('type', category[0]);
                $.ajax({
                    type: 'post',
                    url: "<?php echo e(url("api/get-colors")); ?>",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response){
                        console.log(response);
                        if(response.status=="success"){
                            let ol = `<option value="">Select Color</option>`;
                            $.each(response.colors, function (k,c){
                                ol += `<option value="${c.title},${c.sw_code},${c.red},${c.green},${c.blue},${c.hex_color},${c.texture}">${c.title} - ${c.sw_code}</option>`;
                            });
                            $(".selectpicker").html(ol);
                            $(".selectpicker").selectpicker("refresh");
                        }else{
                            console.log("color not found.");
                        }
                    }
                });
            });
        });

        async function getPatternImage(e,l){
            let resEle = document.createElement('canvas');
            let t = resEle.getContext("2d");
            let th = (5 * l), tw = 30;
            resEle.width = tw;
            resEle.height = th;
            let dx = 0, dy = 0;
            let i=0;
            $.each(e, function () {
                dy = (5*i);
                var w = this.width,
                    h = this.height;
                t.drawImage(this, 0, 0,w, h,dx,dy,tw,5);
                i++;
            });
            var myImageData = new Image();
            myImageData.src = resEle.toDataURL();
            myImageData.onload = function () {
                // reset the canvas with new dimensions
                resEle.width = th;
                resEle.height = tw;
                tw = resEle.width;
                th = resEle.height;
                t.save();
                // translate and rotate
                var j = 6;
                if(l=="2"){j=3}else if(l=="3"){j=2}
                t.translate(tw, th / tw-j);

                t.rotate(Math.PI / 2);
                t.drawImage(myImageData, 0, 0);
                t.restore();
                // clear the temporary image
                brick_pattern = resEle.toDataURL()
            }
        }

        function loadImages(e, t) {
            $("#mainLoader").show();
            var n = {},
                a = 0,
                i = 0;
            for (var o in e) i++;
            for (var o in e)
                (n[o] = new Image()),
                    (n[o].onload = function() {
                        ++a >= i && t(n);
                    }),
                    (n[o].src = e[o]),
                    (n[o].alt = o);
        }

        function convertImageToBase64(imgUrl, callback) {
            const image = new Image();
            //image.crossOrigin='anonymous';
            image.alt='texture';
            image.onload = () => {
                const canvas = document.createElement('canvas');
                const ctx = canvas.getContext('2d');
                canvas.height = image.naturalHeight;
                canvas.width = image.naturalWidth;
                ctx.drawImage(image, 0, 0);
                const dataUrl = canvas.toDataURL();
                callback && callback(dataUrl)
            }
            image.src = imgUrl;
        }

        function generateLayer(R,G,B){
            let sourcesView = [];
            let layerColors = [];
            if(baseLayer1!="" || baseLayer2!="" || baseLayer3!=""){
                if(baseLayer1){
                    sourcesView.push(path + "/"+ baseLayer1);
                    layerColors.push({color: [R,G,B],texture :""});
                }
                if(baseLayer2){
                    sourcesView.push(path + "/"+ baseLayer2);
                    layerColors.push({color: [R,G,B],texture :""});
                }
                if(baseLayer3){
                    sourcesView.push(path + "/"+ baseLayer3);
                    layerColors.push({color: [R,G,B],texture :""});
                }
                let title = $('#title').val();
                loadImages(sourcesView, function(e) {
                    $.each(e, function(i) {
                        drawImageScaled(this, layerCtx,i, layerColors[i]);
                        $('#layer-image').attr('src', LayerCanvas.toDataURL());
                        $('.download-layer').attr("href",LayerCanvas.toDataURL()).attr("download",`${title}.png`);
                        $(".generate-layer").show();
                    });
                });
            }
        }

        function generateLayerWithPattern(thumb){


            let sourcesView = [];
            let layerColors = [];
            let title = $('#title').val();
            let design_type = $('input[name="design_type"]:checked').val();
            if(design_type == "2"){
                if(baseLayer1!="" || baseLayer2!="" || baseLayer3!=""){
                    if(baseLayer1){
                        sourcesView.push(path + "/"+ baseLayer1);
                        layerColors.push({color: "",texture : thumb});
                    }
                    if(baseLayer2){
                        sourcesView.push(path + "/"+ baseLayer2);
                        layerColors.push({color: "",texture :""});
                    }
                    if(baseLayer3){
                        sourcesView.push(path + "/"+ baseLayer3);
                        layerColors.push({color: "",texture :""});
                    }
                    loadImages(sourcesView, function(e) {
                        $.each(e, function(i) {
                            // console.log(layerColors[i]);
                            drawImageScaled(this, layerCtx,i, layerColors[i]);
                            $('#layer-image').attr('src', LayerCanvas.toDataURL());
                            $('.download-layer').attr("href",LayerCanvas.toDataURL()).attr("download",`${title}.png`);
                            $(".generate-layer").show();
                        });
                    });
                }

            }
        }

        function drawImageScaled(e, t, ind, layerColor) {
            var n = LayerCanvas.width / e.width,
                a = LayerCanvas.height / e.height,
                i = Math.min(n, a),
                o = (LayerCanvas.width - e.width * i) / 2,
                s = (LayerCanvas.height - e.height * i) / 2;

            if(layerColor.color || layerColor.texture){
                if(layerColor.color){
                    var newColor = layerColor.color;
                    // var newColor = layerColors[layer_name].color.split(',');
                    var color_img = drawImage(e,newColor);
                    t.drawImage(color_img, 0, 0);
                }else{
                    var pattern_img = new Image;
                    pattern_img.onload = function() {
                        var color_img = drawPatternImage(e,pattern_img);
                        t.drawImage(color_img, 0, 0);
                        $('#layer-image').attr('src', LayerCanvas.toDataURL());
                    }
                    pattern_img.src = layerColor.texture;
                }
            }else{
                t.drawImage(e, 0, 0, e.width, e.height, o, s, e.width * i, e.height * i);
            }
        }

        // change layer color
        function changeColor(data,newcolor) {
            // Convert to unitary value for calculations
            newcolor[0] = newcolor[0] / 255;
            newcolor[1] = newcolor[1] / 255;
            newcolor[2] = newcolor[2] / 255;

            var r, g, b;
            for (var i = 0; i < data.length; i+= 4) {
                // Get unitary value of the pixel
                r = data[i] / 255;
                g = data[i+1] / 255;
                b = data[i+2] / 255;

                // Multiply the values and store them
                data[i] = Math.floor(255 * r * newcolor[0]);
                data[i+1] = Math.floor(255 * r * newcolor[1]);
                data[i+2] = Math.floor(255 * r * newcolor[2]);
            }
        }

        // draw image with canvas for change color
        function drawImage(imageObj,newColor) {
            var imageWidth = imageObj.width;
            var imageHeight = imageObj.height;
            var tempCanvas = document.createElement('canvas');
            var context = tempCanvas.getContext('2d');
            tempCanvas.width = LayerCanvas.width;
            tempCanvas.height = LayerCanvas.height;

            var n = tempCanvas.width / imageWidth,
                a = tempCanvas.height / imageHeight,
                i = Math.min(n, a),
                o = (tempCanvas.width - imageWidth * i) / 2,
                s = (tempCanvas.height - imageHeight * i) / 2;

            context.drawImage(imageObj, 0, 0, imageWidth, imageHeight, o, s, imageWidth * i, imageHeight * i);
            var imageData = context.getImageData(0, 0, imageWidth, imageHeight);

            changeColor(imageData.data,newColor);
            // Update the canvas with the new data
            context.putImageData(imageData, 0, 0);
            return tempCanvas;
        }

        // draw pattern image
        function drawPatternImage(img1,patImg,newColor=null){
            var imageWidth = img1.width;
            var imageHeight = img1.height;
            var tempCanvas = document.createElement('canvas');
            var ctx = tempCanvas.getContext('2d');
            tempCanvas.width = LayerCanvas.width;
            tempCanvas.height = LayerCanvas.height;
            var n = tempCanvas.width / imageWidth,
                a = tempCanvas.height / imageHeight,
                i = Math.min(n, a),
                o = (tempCanvas.width - imageWidth * i) / 2,
                s = (tempCanvas.height - imageHeight * i) / 2;

            // create a pattern
            ctx.fillStyle = ctx.createPattern(patImg, "repeat");
            // fill canvas with pattern
            ctx.fillRect(0, 0, LayerCanvas.width, LayerCanvas.height);
            // use blending mode multiply
            ctx.globalCompositeOperation = "multiply";

            // draw layer on top
            ctx.drawImage(img1, 0, 0, imageWidth, imageHeight, o, s, imageWidth * i, imageHeight * i);
            // change composition mode
            ctx.globalCompositeOperation = "destination-in";

            // draw to cut-out layer
            ctx.drawImage(img1, 0, 0, imageWidth, imageHeight, o, s, imageWidth * i, imageHeight * i);
            if(newColor){
                var imageData = ctx.getImageData(0, 0, imageWidth, imageHeight);
                changeColor(imageData.data,newColor);
                // Update the canvas with the new data
                ctx.putImageData(imageData, 0, 0);
            }
            return tempCanvas;
        }

        function getSubCategory(designId=null,sub_category=null){
            var categoryId = $("#category").val();
            $(".sub-category").hide();
            if(categoryId){
                $.ajax({
                    type: 'get',
                    url: '<?php echo e(url("/api/get-sub-category")); ?>',
                    data: {category_id: categoryId,sub_category:sub_category,designId: designId},
                    success: function(response){
                        if(response){
                            $("#sub-category").html(response);
                            $(".sub-category").show();
                        }else{
                            $(".sub-category").hide();
                        }
                    },
                    error: function(error){
                        toastr.error(error.responseJSON);
                    }
                })
            }
        }






        var image_no = 1;
        let startIndex = 1; // Initial start index for pagination
        const imagesPerPage = 10;
        let totalImagesToFetch = 30; // Set the total number of images to fetch
        let currentPage = 1;
        var cropper;
        var lastSearchQuery = '';
        var design_type_data = [];


        function searchImages() {
            if(document.getElementById('searchQuery').value == '') {
                alert("Search Input required");
                return false;
            }
            // seamless pattern tile texture
            const searchQuery = document.getElementById('searchQuery').value + '';
            if(lastSearchQuery != searchQuery){
                currentPage = 1;
                startIndex = 1;
                lastSearchQuery = searchQuery;
            }

            //const searchQuery = document.getElementById('searchQuery').value;
            const apiKey = 'AIzaSyCABfWgi907oLMTOUDNPDfeP-tHYDtXsqM'; // Replace with your actual API key
            const cx = 'a5661d1d5e7dd41f8'; // Replace with your actual custom search engine ID
            const totalPages = Math.ceil(totalImagesToFetch / imagesPerPage);
            // Update the pagination
            document.getElementById('pagination').innerHTML = `Page ${currentPage} of ${totalPages}`;

            const apiUrl = `https://www.googleapis.com/customsearch/v1?q=${searchQuery}&key=${apiKey}&cx=${cx}&searchType=image&start=${startIndex}&num=${imagesPerPage}`;

            fetch(apiUrl)
                .then(response => response.json())
                .then(data => displayImages(data.items))
                .catch(error => console.error('Error:', error));
        }

        function nextPage() {
            startIndex += imagesPerPage;
            currentPage += 1;
            searchImages();
        }

        function previousPage() {
            if (startIndex > 1) {
                startIndex -= imagesPerPage;
                currentPage -= 1;
                searchImages();
            }
        }

        function displayImages(images) {
            document.getElementById("page_controls").style.display = "block";
            const imageResults = document.getElementById('imageResults');
            imageResults.innerHTML = '';

            let img_html = '<div class="row2">';



            images.forEach(image => {
                /*<p class="card-text">${image.htmlSnippet}</p>*/
                img_html +=`
                <div class="img-card">
                    <div class="card mb-0" style="">
                        <img  src="${image.link}" onclick="setGoogleImage('${image.link}')" class="card-img-top g-img" alt="${image.title}">
                        <div class="card-body">
                            <h5 class="card-title">${image.htmlTitle}</h5>
                            <a href="${image.image.contextLink}" target="_blank" class="">${image.displayLink}</a>
                        </div>
                    </div>
                </div>
                `;


                /*const imgElement = document.createElement('img');
                imgElement.src = image.link;
                imgElement.alt = image.title;
                imgElement.style.width = '120px';
                imgElement.style.margin = '5px';
                //imgElement.crossorigin = 'anonymous';
                imgElement.addEventListener('click', () => {
                    document.getElementById('mainLoader').style.display = 'block';
                    //crop_image(imgElement.src);
                    uploadImageUrl(imgElement.src)
                });
                imageResults.appendChild(imgElement);*/
            });


            img_html += '</div>';

            imageResults.innerHTML = img_html;
        }
        function clearSearch() {
            startIndex =1;
            currentPage = 1;
            document.getElementById('searchQuery').value = '';
            document.getElementById('imageResults').innerHTML = '';
            document.getElementById("page_controls").style.display = "none";
        }

        function closeSearch() {
            document.getElementById('search_box_panel').classList.add('d-none');
            //document.getElementById('search_panel').classList.remove('d-none');
            document.getElementById("page_controls").style.display = "none";
        }


        /*function uploadImageUrl(imageUrl) {
            fetch('<?php echo e(url("/api/upload-image")); ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                },
                body: JSON.stringify({ imageUrl: imageUrl }),
            })
                .then(response => response.json())
                .then(data => {

                })
                .catch(error => console.error('Error:', error));
        }*/


        $(document).ready(function() {
            $("#productId").on("input", function() {

                if(!$("#df1").is(":checked")){
                    return false;
                }

                const inputValue = $(this).val().toLowerCase();

                // Make an AJAX request to your server
                $.ajax({
                    url: "<?php echo e(url("/api/get-colors")); ?>",
                    method: "GET",
                    data: { query: inputValue },
                    success: function(results) {
                        displayResults(results);
                    },
                    error: function(error) {
                        console.error("Error fetching autocomplete results:", error);
                    }
                });
            });

            function displayResults(results) {
                const resultList = $("#autocompleteResults");
                resultList.empty();

                if (results.length === 0) {
                    resultList.hide();
                    return;
                }

                results.forEach(item => {

                    let item_html = `<div style="font-size: 20px;"><span style="width: 30px;height: 25px;margin-right: 10px;float: left; background-color: ${item.hex};"></span> ${item.sw_code}</div>`;
                    const listItem = $("<li>").html(item_html);
                    /*listItem.attr("data-slug", item.hex);
                    listItem.attr("data-thumbnail", item.rgb);*/

                    listItem.click(function() {
                        $("#productId").val(item.sw_code);
                        $("#hex_color").val(item.hex);

                        let rgbString = item.rgb;
                        // Regular expression to match the numbers inside the parentheses

                        let numbersOnly = rgbString.slice(4, -1);

                        // Split the string into an array of individual numbers
                        let rgbArray = numbersOnly.split(',');

                        // Extracted values
                        let red = rgbArray[0];
                        let green = rgbArray[1];
                        let blue = rgbArray[2];

                        console.log(red, green, blue);

                        $("#color_red").val(red);
                        $("#color_green").val(green);
                        $("#color_blue").val(blue);



                        resultList.hide();
                        // You can use item.slug and item.thumbnail as needed
                        console.log("Selected Item:", item);
                    });

                    resultList.append(listItem);
                });

                resultList.show();
            }

            // Close the autocomplete results if the user clicks outside the input and results
            $(document).on("click", function(event) {
                if (!$(event.target).closest(".autocomplete").length) {
                    $("#autocompleteResults").hide();
                }
            });
        });


    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.design-layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\biorev\ule-2\cgi-bin\resources\views/admin/home-designs.blade.php ENDPATH**/ ?>