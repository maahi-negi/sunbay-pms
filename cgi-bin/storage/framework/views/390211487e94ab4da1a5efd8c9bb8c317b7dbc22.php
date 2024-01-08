<style>
    .download-action-wrap {
        width: 100%;
    }
    .toggle-btn{
        text-transform: uppercase;
        /*  position: absolute;
         right: 10px; */
        font-size: 12px;
        color: #fff;
        /*   top: 4px; */
        opacity: 1;
        text-align: center;
        line-height: 25px;
        margin-bottom: 5px;
        background: #666;
    }
    .toggle-btn.active{
    text-transform: uppercase;
        /*  position: absolute;
         left: 25px; */
        font-size: 12px;
        color: #fff;
        /*  top: 4px; */
        background: #9fcc3a;
    }
    .open-sections{
        margin-bottom: 10px;
        border: solid #444;
        border-width: 1px 0;
        margin: 0px -15px 10px -25px;
        padding: 8px 10px;
        background: #111;
    }
    .open-close-option{
        font-size: 14px !important;
        font-weight: 500;
        text-shadow: 1px 1px 1px #333;
    }
    .design-options-wrapper{
        padding-top:0px;
    }
    .navoptions{
        padding: 0px 10px;
        position: relative;
    }
    .nav-option-listing {
        position: relative;
        width: 100%;
        overflow: hidden;
    }
    .navoptions .top-arrow{
        position: absolute;
        top: 0px;
        text-align: center;
        width: 100%;
        background: #3a3b3d;
        z-index: 111;
    }
    .navoptions .top-arrow svg{
        cursor: pointer;
        stroke: #7d7d7d;
    }
    .navoptions .top-arrow svg:hover{
        stroke: #fff;
    }
    .navoptions .bottom-arrow{
        position: absolute;
        bottom: 0;
        text-align: center;
        width: 100%;
        z-index: 11;
        background: #3a3b3d;
    }
    .navoptions .bottom-arrow svg{
        cursor: pointer;
        stroke: #7d7d7d;
    }
    .navoptions .bottom-arrow svg:hover{
        stroke: #fff;
    }
    .design-package-wrapper {
        max-height: calc(100vh - 160px);
        position: relative;
        top: 0;
        overflow: hidden;
        margin-top: 11px;
    }
    .design-package-wrapper.bottom-disable{
        margin-top: 28px;
        margin-bottom: 10px;
    }
    .design-options-wrapper.top-disable{
        margin-top: 10px;
        margin-bottom: 28px;
    }
    .options-loop-box{padding-bottom: 8px;}

    .design-options-wrapper.both-enable{
        margin-top: 28px;
        margin-bottom: 28px;
    }
    @media  screen and (max-width: 1366px) and (max-height: 768px) {
        .design-options-wrapper.both-enable {
            margin-top: 35px;margin-bottom: 30px;
        }
        .design-package-wrapper.bottom-disable {
            height: 470px !important;
        }
    }

    .category-filters .filters-wrapper{
        height: 30px !important;position: unset !important;width: 95% !important;
    }
    .category-filters .filters-wrapper .select-fil-d-main .select-filter-input {
        height: 25px;padding: 2px 20px 2px 5px;
    }

    @media  screen and (max-width: 601px) and (max-height: 962px) {
        .options-loop-box {padding-bottom: 6px;}
    }
    @media  screen and (max-width: 962px) and (max-height: 601px) and (orientation: landscape) {
        .options-loop-box {padding-bottom: 7px;}
    }

    /*updates 1-feb-2023*/
    .sidemenu {height: calc(100%);padding: 10px 10px 50px;}
    .main-wrapper {height: calc(100%);}
    .download-action-container {bottom: 3px;}
    .design-package-wrapper {max-height: calc(100vh - 100px);}
    .design-package-wrapper {margin-top: 10px;}
    .form-control {font-size: 0.9rem;}
</style>

<div class="sidemenu custom-scroll scroll-width-thin d-flex flex-column justify-content-between" id="sideMenu">
    <div class="navoptions transition-all">
        <h5 id="elevation-title" class="d-none"><?php echo e((isset($homeData->title))?$homeData->title:""); ?></h5>
        <div class="nav-option-listing transition-all">
            <?php if(isset($homeData->designCategories)): ?>
                <div class=" design-options-wrapper design-package-wrapper transition-all" data-childerns="<?php echo e(count($homeData->designCategories)); ?>" data-ts="2" data-as="1" id="options-listing-data">
                    <?php $__currentLoopData = $homeData->designCategories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $design_type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="options-loop-box" id="options-icon-<?php echo e($design_type->categoryId); ?>">
                            <div class="design-icons-wrap image-icons-wrap">
                                <div class="design-icons back-image" style="background: url(<?php echo e($design_type->thumbailImage); ?>) no-repeat;background-position: center center;background-size: contain;"></div>
                                <span class="text-white">
                                    <i data-target="#<?php echo e(str_replace(" ","-",strtolower($design_type->categoryName))); ?>Data" class="check-design-option" data-feather="check-circle"></i>
                                </span>
                            </div>
                            <p class="text-capitalize"><?php echo e($design_type->categoryName); ?></p>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <div class="sidemenu extended">
                        <div class="heading-wrapper justify-content-between align-items-center">
                            <h5 class="m-0">Choose Colors</h5>
                            <i id="closeMenu" data-feather="x-circle"></i>
                        </div>
                        <div class="content-actions-wrap justify-content-between">
                            <input type="text" id="searchInput" placeholder="Search" onkeyup="showFilteredData()">
                            <div class="filters-wrapper" id="sortWrap">
                                <div class="select-fil-d-main">
                                    <select type="button" class="select-filter-input js-example-basic-single" id="sortInput">
                                        <option value="asc">SORT By - Name A-Z</option>
                                        <option value="desc">SORT By - Name Z-A</option>
                                    </select>
                                </div>
                            </div>
                            <div class="content-actions-icons-wrap">
                                <i id="searchIcon" data-feather="search"></i>
                                <!--  <i id="filterIcon" data-feather="filter"></i> -->
                                <i id="sortIcon" data-feather="list"></i>
                            </div>
                        </div>
                        <div class="custom-scroll designs-scroll scroll-width-thin">
                            <div class="filters-wrapper" id="filtersWrap">
                                <div class="select-fil-d-main">
                                    <select type="button" class="select-filter-input js-example-basic-single" name="value">
                                        <option></option>
                                    </select>
                                </div>
                                <div class="select-fil-d-main dropdown" style="display:none">
                                    <button type="button" class="select-filter-input dropdown-toggle mb-2" data-toggle="dropdown">
                                        Price
                                        <span class="material-icons" style="top:0;">
                                        arrow_drop_up
                                        </span>
                                        <span class="material-icons">
                                        arrow_drop_down
                                        </span>
                                        <span class="float-right filter-button-badge price-badge"></span>
                                    </button>
                                    <div class="dropdown-menu">
                                        <h6 class="border-bottom" style="padding-bottom:5px;">Price Range</h6>
                                        <div class="pl-2 pr-2">
                                            <div class="value-box price">
                                                <span class="start-value"></span>
                                                <span>-</span>
                                                <span class="end-value"></span>
                                            </div>
                                            <input type="text" class="js-range-slider price-filter" name="price_range"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="select-fil-d-main dropdown">
                                    <button type="button" class="select-filter-input dropdown-toggle" data-toggle="dropdown">
                                        Rating
                                        <span class="material-icons" style="top:0;">
                                        arrow_drop_up
                                        </span>
                                        <span class="material-icons">
                                        arrow_drop_down
                                        </span>
                                        <span class="float-right filter-button-badge rating-badge"></span>
                                    </button>
                                    <div class="dropdown-menu">
                                        <h6 class="border-bottom" style="padding-bottom:5px;">Rating Range</h6>
                                        <div class="pl-2 pr-2">
                                            <div class="value-box rating">
                                                <span class="start-value"></span>
                                                <span>-</span>
                                                <span class="end-value"></span>
                                            </div>
                                            <input type="text" class="js-range-slider rating-filter" name="price_range"/>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <?php $__currentLoopData = $homeData->designCategories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $design_type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div id="<?php echo e(str_replace(" ","-",strtolower($design_type->categoryName))); ?>Data" class="content-container custom-scroll scroll-width-thin">
                                    <div class="designs-wrapper container">
                                        <div class="row">
                                            <?php if(isset($design_type->options)): ?>
                                                <?php $__currentLoopData = $design_type->options; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $design): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <?php $arr = explode(" ",$design->title);
                                                    array_splice($arr,0,1);
                                                    $newStr = implode(" ",$arr);
                                                    ?>
                                                    <div class="col-sm-4 col-6">
                                                        <div class="design-container image-icons-wrap mb-1 <?php echo e(($design->isDefault == 1)?'color-active':''); ?>">
                                                            <div data-designCategoryId="<?php echo e($design_type->categoryId); ?>" data-design-title="<?php echo e($design_type->categoryName); ?>" data-value="<?php echo e($design->title); ?>" data-id="<?php echo e($design->optionId); ?>" class="w-100 design back-image <?php echo e(($design->isDefault == 1)?'fade-image':''); ?>" style="background-image: url('<?php echo e($design->thumbnailImage); ?>'); background-repeat:no-repeat; background-size: contain; background-position: center;"></div>
                                                            <span class="text-white d-flex <?php echo e(($design->isDefault == 1)?'show-buttons':''); ?>">
                                                                <i data-feather="check-circle" class="mr-1 check-color-option <?php echo e(($design->isDefault == 1)?'button-active':''); ?>"
                                                                   data-design-group-view1=''
                                                                   data-design-group-view2=''
                                                                   data-design-type='<?php echo e(str_replace(" ","-",strtolower($design_type->categoryName))); ?>'
                                                                   data-design-view1='<?php echo e(($design->layerImage)?$design->layerImage:""); ?>'
                                                                   data-design-view2='' data-open-view='' data-open-view2=''>
                                                                </i>
                                                            </span>
                                                        </div>

                                                        <p class="text-capitalize p-0 m-0" data-price="<?php echo e($design->price); ?>">
                                                            <?php echo e(str_replace('.', '', explode(' ',$design->title)[0])); ?><br><?php echo e($newStr); ?>

                                                        </p>
                                                    </div>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <?php if(!isset($homeData->error)): ?>
        <div class="download-action-container d-flex">
            <div class="download-action-wrap text-center mb-1" onclick="submitVisualizer()">
                <p class="m-0">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-envelope" viewBox="0 0 16 16"> <path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V4Zm2-1a1 1 0 0 0-1 1v.217l7 4.2 7-4.2V4a1 1 0 0 0-1-1H2Zm13 2.383-4.708 2.825L15 11.105V5.383Zm-.034 6.876-5.64-3.471L8 9.583l-1.326-.795-5.64 3.47A1 1 0 0 0 2 13h12a1 1 0 0 0 .966-.741ZM1 11.105l4.708-2.897L1 5.383v5.722Z"/> </svg>
                    Submit
                </p>
            </div>
        </div>
    <?php endif; ?>
</div>
<!-- Feature Modal  -->
<div class="modal fade right inner-sliding-modal" id="featureModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="false">
    <div class="modal-dialog modal-full-height modal-right modal-notify modal-info" role="document">
        <div class="modal-content">
            <!--Header-->
            <div class="modal-header">
                <div class="d-flex">
                    <span class="material-icons mr-2" style="background: #7bc22a;">
                    color_lens
                    </span>
                    <h5>Product Info</h5>
                </div>
                <div data-toggle="collapse" data-target="#floor-tab-1" class="close-tab" data-dismiss="modal" aria-label="Close">
                    <span class="material-icons cross">
                    cancel
                    </span>
                </div>
            </div>
            <!--Body-->
            <!-- Spinner/Loader -->
            <svg xmlns:svg="http://www.w3.org/2000/svg" id="loader" style="position: absolute;top: 50%;left: 50%;transform: translate(-50%, -50%);opacity:0; transition: 0.3s ease opacity;" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.0" width="40px" height="40px" viewBox="0 0 128 128" xml:space="preserve"><g><linearGradient id="linear-gradient"><stop offset="0%" stop-color="#ffffff" fill-opacity="0"/><stop offset="100%" stop-color="#4d4d4d" fill-opacity="1"/></linearGradient><path d="M63.85 0A63.85 63.85 0 1 1 0 63.85 63.85 63.85 0 0 1 63.85 0zm.65 19.5a44 44 0 1 1-44 44 44 44 0 0 1 44-44z" fill="url(#linear-gradient)" fill-rule="evenodd"/><animateTransform attributeName="transform" type="rotate" from="0 64 64" to="360 64 64" dur="1080ms" repeatCount="indefinite"></animateTransform></g></svg>
            <div class="modal-body pl-4 pb-4 custom-scroll scroll-width-thin" style="display:none;">
                <div class="feature-wrapper">
                    <div class="feature-image-wrapper pb-3">
                        <img class="w-100 border border-dark" src="">
                    </div>
                    <div class="d-flex mb-2 info-wrapper">
                        <span class="material-icons" style="font-size: 40px;padding: 0 10px 0 0px;">
                            texture
                        </span>
                        <div class="feature-text">
                            <h6 class="d-block" style="font-size: 12px;text-transform: uppercase;color: #666;line-height: 1.8; margin:0;">
                                Material
                            </h6>
                            <p class="d-block m-0 f-material" style="font-size: 16px;line-height: 0.6; text-transform:capitalize;">
                                Wood
                            </p>
                        </div>
                    </div>
                    <div class="d-flex mb-2 info-wrapper">
                        <span class="material-icons" style="font-size: 40px;padding: 0 10px 0 0px;">
                            construction
                        </span>
                        <div class="feature-text">
                            <h6 class="d-block" style="font-size: 12px;text-transform: uppercase;color: #666;line-height: 1.8; margin:0;">
                                Manufacturer
                            </h6>
                            <p class="d-block m-0 f-manufacturer" style="font-size: 16px;line-height: 0.6; text-transform:capitalize;">
                                John Doe
                            </p>
                        </div>
                    </div>
                    <div class="d-flex mb-2 info-wrapper">
                        <span class="material-icons" style="font-size: 40px;padding: 0 10px 0 0px;">
                            portrait
                        </span>
                        <div class="feature-text">
                            <h6 class="d-block" style="font-size: 12px;text-transform: uppercase;color: #666;line-height: 1.8; margin:0;">
                                Name
                            </h6>
                            <p class="d-block m-0 f-name" style="font-size: 16px;line-height: 0.6; text-transform:capitalize;">
                                Design 1
                            </p>
                        </div>
                    </div>
                    <div class="d-flex mb-2 info-wrapper">
                        <span class="material-icons" style="font-size: 40px;padding: 0 10px 0 0px;">
                            attach_money
                        </span>
                        <div class="feature-text">
                            <h6 class="d-block" style="font-size: 12px;text-transform: uppercase;color: #666;line-height: 1.8; margin:0;">
                                Price
                            </h6>
                            <p class="d-block m-0 f-price" style="font-size: 16px;line-height: 0.6; text-transform:capitalize;">
                                $1,000
                            </p>
                        </div>
                    </div>
                    <div class="d-flex info-wrapper">
                        <span class="material-icons" style="font-size: 40px;padding: 0 10px 0 0px;">
                            lock_open
                        </span>
                        <div class="feature-text">
                            <h6 class="d-block" style="font-size: 12px;text-transform: uppercase;color: #666;line-height: 1.8; margin:0;">
                                Id
                            </h6>
                            <p class="d-block m-0 f-id" style="font-size: 16px;line-height: 0.6; text-transform:capitalize;">
                                66545
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php /**PATH D:\xampp\htdocs\biorev\tkr-xhome360\cgi-bin\resources\views/includes/sidemenu.blade.php ENDPATH**/ ?>