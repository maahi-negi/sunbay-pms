<style>
    .download-action-wrap {
        width: 100%;
    }
    .toggle-btn{
        text-transform: uppercase;
        font-size: 12px;
        color: #fff;
        opacity: 1;
        text-align: center;
        line-height: 25px;
        margin-bottom: 5px;
        background: #666;
    }
    .toggle-btn.active{
        text-transform: uppercase;
        font-size: 12px;
        color: #fff;
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
    .design-package-wrapper {max-height: calc(100vh - 130px);}
    .design-package-wrapper {margin-top: 10px;}
    .form-control {font-size: 0.9rem;}
    #submitButton{pointer-events:unset}
    #submitButton.disable{pointer-events:none}
    #submitButton .spinner-border{display:none}
    #submitButton .spinner-border.show-spinner{display:inline-block}
    .download-action-wrap p {
        font-size: 12px;padding: 10px 0;
    }
    @media  screen and (min-width: 992px) {
        .main-wrapper {margin-left: 0;}
        .main-wrapper-sidebar{margin-left: 140px;}
        .header {
            display: flex;
            justify-content: center;
            align-items: normal;
        }
    }
    .design-options-wrapper .sidemenu.extended .content-container.add-sort-height {
        height: calc(100vh - 184px);
    }
    #submit_visualizer{z-index: 9999;}
    .select2-dropdown{z-index: 9999;}
</style>
<?php $sub_categories = [];?>
<div class="sidemenu custom-scroll scroll-width-thin flex-column justify-content-between" id="sideMenu" style="display: none;">
    <div class="navoptions transition-all">
        <h5 id="elevation-title" class="d-none"><?php echo e(ucwords(strtolower($design_group->title))); ?></h5>
        <div class="nav-option-listing transition-all">

            <div class=" design-options-wrapper design-package-wrapper transition-all" data-ts="2" data-as="1" id="options-listing-data">
                <?php $__currentLoopData = $design_types; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $design_type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="options-loop-box" id="options-icon-<?php echo e($design_type->id); ?>">
                        <div class="design-icons-wrap image-icons-wrap">
                            <div class="design-icons back-image" style="background: url(<?php echo e(asset($typepath.$design_type->thumbnail)); ?>) no-repeat;background-position: center center;background-size: contain;"></div>
                            <span class="text-white">
                                <i data-target="#<?php echo e($design_type->slug); ?>Data" class="check-design-option" data-feather="check-circle"></i>
                            </span>
                        </div>
                        <p class="text-capitalize"><?php echo e($design_type->title); ?></p>
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
                        <?php $__currentLoopData = $design_types; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $design_type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div id="<?php echo e($design_type->slug); ?>Data" class="content-container custom-scroll scroll-width-thin">
                                <?php if(isset($design_type->categories) && count($design_type->categories)>1): ?>
                                    <div class="category-filters">
                                        <div class="filters-wrapper open">
                                            <div class="select-fil-d-main">
                                                <select type="button" class="select-filter-input filter-category categories" data-design="<?php echo e($design_type->slug); ?>" data-type="cate">
                                                    <option value="">Select Category</option>
                                                    <?php $__currentLoopData = $design_type->categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <?php if($c->parent_id==""): ?>
                                                            <option value="<?php echo e($c->id); ?>"><?php echo e($c->category); ?></option>
                                                        <?php else: ?>
                                                            <?php $sub_categories[$c->parent_id][$c->id] = $c->category; ?>
                                                        <?php endif; ?>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="filters-wrapper open sub-category" style="display: none;">
                                            <div class="select-fil-d-main">
                                                <select type="button" class="select-filter-input filter-category filter-subcategory" data-design="<?php echo e($design_type->slug); ?>" data-type="subcate">
                                                    <option value="">Select Sub Category</option>
                                                </select>
                                            </div>
                                        </div>

                                    </div>
                                <?php endif; ?>

                                <div class="designs-wrapper container">
                                    <div class="row">
                                        <?php $__currentLoopData = $design_type->designs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $design): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php
                                            $arr = explode(" ",$design->title);
                                            array_splice($arr,0,1);
                                            $newStr = implode(" ",$arr);
                                            $additional_pattern = "";
                                            if($design->additional_thumbs){
                                                $additional_thumbs = json_decode($design->additional_thumbs,true);
                                                $additional_pattern = @$additional_thumbs['thumb_pattern'];
                                            }
                                            ?>
                                            <div class="col-sm-4 col-6">
                                                <div class="design-container image-icons-wrap mb-1 <?php echo e(($design->is_default == 1)?'color-active':''); ?> <?php echo e(($design->category)?"cate$design->category":""); ?> <?php echo e(($design->sub_category)?"subcate$design->sub_category":""); ?>">
                                                <?php if($design->thumbnail): ?>
                                                    <?php
                                                        $thumbnail = asset($designpath.$design_type->slug.'_'.$design_type->id.'/'.$design->thumbnail);
                                                        $background = "url('$thumbnail')"
                                                    ?>
                                                <?php else: ?>
                                                    <?php $background = "rgb($design->rgb_color)" ?>
                                                <?php endif; ?>
                                                <!-- layer or color -->
                                                    <?php if($design->design_type=="3" && $design->layer): ?>
                                                        <?php
                                                            $image_view1 = ($design->layer)?asset($designpath.$design_type->slug."_".$design_type->id."/".$design->layer):"";
                                                            $image_view2 = "";
                                                            $image_view3 = "";
                                                        ?>
                                                    <?php else: ?>
                                                        <?php
                                                            $image_view1 = ($design_type->image_view1)?asset($designpath.$design_type->slug."_".$design_type->id."/".$design_type->image_view1):"";
                                                            $image_view2 = ($design_type->image_view2)?asset($designpath.$design_type->slug."_".$design_type->id."/".$design_type->image_view2):"";
                                                            $image_view3 = ($design_type->image_view3)?asset($designpath.$design_type->slug."_".$design_type->id."/".$design_type->image_view3):"";
                                                        ?>
                                                    <?php endif; ?>

                                                    <div data-design-title="<?php echo e($design_type->title); ?>" data-value="<?php echo e($design->title); ?>" class="w-100 design back-image <?php echo e(($design->is_default == 1)?'fade-image':''); ?>" style="background: <?php echo e($background); ?>; background-repeat:repeat; background-size: contain; background-position: center;"></div>
                                                    <span class="text-white d-flex <?php echo e(($design->is_default == 1)?'show-buttons':''); ?>">
                                                        <i data-feather="check-circle" class="mr-1 check-color-option <?php echo e(($design->is_default == 1)?'button-active':''); ?>"
                                                           data-design-group-view1='<?php echo e(($design_group->base_image_view1)?asset($designpath.$design_group->base_image_view1):""); ?>'
                                                           data-design-group-view2='<?php echo e(($design_group->base_image_view2)?asset($designpath.$design_group->base_image_view2):""); ?>'
                                                           data-design-type='<?php echo e($design_type->slug); ?>'
                                                           data-design-view1='<?php echo e($image_view1); ?>'
                                                           data-design-view2='<?php echo e($image_view2); ?>'
                                                           data-design-view3='<?php echo e($image_view3); ?>'
                                                           data-rbg-color='<?php echo e(($design->rgb_color)?$design->rgb_color:""); ?>'
                                                           data-type='<?php echo e(($design->design_type)?$design->design_type:"1"); ?>'
                                                           data-texture='<?php echo e(($design->thumbnail)?asset($designpath.$design_type->slug."_".$design_type->id."/".$design->thumbnail):""); ?>'
                                                           data-additional-texture='<?php echo e($additional_pattern); ?>'
                                                        ></i>
                                                    </span>
                                                </div>
                                                <p class="text-capitalize p-0 m-0" data-price="<?php echo e($design->price); ?>">
                                                    <?php echo e(str_replace('.', '', explode(' ',$design->title)[0])); ?><br><?php echo e($newStr); ?>

                                                </p>
                                            </div>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                        <?php if(Auth::check()): ?>
                                            <div class="col-sm-4 col-6 add-new-option">
                                                <div class="image-icons-wrap mb-1">
                                                    <div data-design-title="<?php echo e($design_type->title); ?>" class="w-100 design back-image" style="border: 1px solid #272323;"></div>
                                                    <span class="text-white" style="opacity: 1;">
                                                        <i data-feather="plus-circle" class="mr-1" onclick="add_new_option('<?php echo e($design_type->slug); ?>','<?php echo e($design_type->id); ?>','<?php echo e($design_type->title); ?>')"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        <?php endif; ?>

                                    </div>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <div class="download-action-container d-flex">

    </div>
</div>
<script>
    var sub_categories = <?=json_encode($sub_categories)?>;
    // console.log(sub_categories);
</script>
<?php /**PATH D:\xampp\htdocs\biorev\ule-2\cgi-bin\resources\views/includes/sidemenu.blade.php ENDPATH**/ ?>