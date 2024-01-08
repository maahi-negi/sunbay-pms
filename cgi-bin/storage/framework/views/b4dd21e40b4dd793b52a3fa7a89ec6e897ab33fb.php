 <!-- BEGIN: Main Menu-->
 <div class="main-menu material-menu menu-fixed menu-light menu-accordion menu-shadow" data-scroll-to-active="true">
    <div class="main-menu-content">
        <div class="d-flex align-items-center justify-content-between side-menu-head-title-wrap">
            <h5 class="side-menu-head-title p-0">Admin Panel</h5>
            <a onclick="toggleMenu()" class="nav-link nav-menu-main menu-toggle p-0 m-0 text-white " href="javascript:;"><i class="ft-menu"></i></a>
        </div>
        <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">
            <li class="nav-item <?php echo e((isset($menu) && ($menu == 'home')) ?'active':''); ?> ">
                <a href="<?php echo e(route('home')); ?>"><span class="menu-title" data-i18n="Dashboard"><i class="la la-tachometer"></i>Dashboard</span></a>
            </li>
             <li class="nav-item <?php echo e(($menu == 'uploads')?'active':''); ?>">
                <a href="<?php echo e(route('bulk-data')); ?>"><span class="menu-title" data-i18n="Dashboard"><i class="la la-cloud-upload"></i>Uploads</span></a>
            </li>
            <li class="nav-item <?php echo e((isset($menu) && ($menu == 'homeplans')) ?'active':''); ?> ">
                <a href="<?php echo e(route('homeplans')); ?>"><span class="menu-title" data-i18n="Homeplans"><i class="la la-home"></i>Homeplans</span></a>
            </li>

            <li class="nav-item <?php echo e((isset($menu) && ($menu == 'colorlibrary')) ?'active':''); ?> ">
                <a href="<?php echo e(route('colorlibrary')); ?>"><span class="menu-title" data-i18n="Color"><i class="la la-archive"></i>ColorLibrary</span></a>
            </li>
            <li class="nav-item <?php echo e((isset($menu) && ($menu == 'patternlibrary')) ?'active':''); ?> ">
                <a href="<?php echo e(route('patternlibrary')); ?>"><span class="menu-title" data-i18n="Pattern"><i class="la la-archive"></i>PatternLibrary</span></a>
            </li>
            <li class="nav-item <?php echo e((isset($menu) && ($menu == 'settings')) ?'active':''); ?> ">
                <a href="<?php echo e(route('settings')); ?>"><span class="menu-title" data-i18n="settings"><i class="ft-settings"></i>Settings</span></a>
            </li>
        </ul>
    </div>
</div>
<!-- END: Main Menu-->
<?php $__env->startPush('scripts'); ?>;
<script type="text/javascript">
    if(window.innerWidth > 768){
        $('body').addClass('menu-open');
    }
    else{
        $('body').addClass('menu-hide');
    }
    function toggleMenu() {
        if ($('body').hasClass('menu-open')) {
            $('body').removeClass('menu-open').addClass('menu-hide');
        } else {
            $('body').addClass('menu-open').removeClass('menu-hide');
        }
    }
</script>
<?php $__env->stopPush(); ?>
<?php /**PATH /home/xhome/public_html/ule-uat.xhome360.com/cgi-bin/resources/views/elements/menu.blade.php ENDPATH**/ ?>