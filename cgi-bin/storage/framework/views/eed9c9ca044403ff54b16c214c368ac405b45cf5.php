<!DOCTYPE html>
<html>
<?php echo $__env->make('includes.head', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<style>
    .preloader{position:fixed;top:0;left:0;width:100vw;height:100vh;z-index:1000;text-align:center;background:#fff;padding-top:calc(50vh - 25px);z-index:11111}
    .inner-breadcrumb{padding:8px 0;position:absolute;top:0;width:calc(100vw - 140px);z-index:1115}
    .inner-breadcrumb .d-flex .back-inner-btn{padding:7px 15px;font-size:12px;background:#199bfe;color:#fff;position:relative;line-height:2rem}
    .inner-breadcrumb .d-flex .back-inner-btn:after{content:'';position:absolute;top:0;right:-1.95rem;border-top:2rem solid #199bfe;border-right:2rem solid transparent;width:0}
    .inner-breadcrumb .d-flex ul{list-style:none;margin:0;padding:0;font-size:12px;text-align:right}
    .inner-breadcrumb .d-flex ul li{display:inline-block;padding:7px 15px;font-size:12px;background:#199bfe;color:#fff;position:relative;line-height:1.125rem;margin-right:2rem}
    .inner-breadcrumb .d-flex ul li:last-child{margin-right:0}
    .inner-breadcrumb .d-flex ul li a{color:#fff}
    .inner-breadcrumb .d-flex ul li:before{content:'';position:absolute;top:0;left:-1.95rem;border-bottom:2rem solid #199bfe;border-left:2rem solid transparent;width:0}
    .inner-breadcrumb .d-flex ul li:not(:last-child):after{content:'';position:absolute;top:0;right:-1.95rem;border-top:2rem solid #199bfe;border-right:2rem solid transparent;width:0}
    .sidemenu{z-index: 1112;}
    .header{z-index: 1113;}
    @media  screen and (max-width: 991px) {
        .header{width:60%;border-right:none}
        .header .logo,.header .logo a img{width:100px}
        .inner-breadcrumb{top:38px;width:calc(100vw)}
    }
    @media  screen and (orientation: landscape) and (max-width: 991px) and (min-width: 738px) {
        .mob-logo{display:none}
        .inner-breadcrumb{top:-8px;width:calc(100vw - 40px);left:40px}
    }
    @media  screen and (orientation: landscape) and (max-width: 825px) {
        .inner-breadcrumb .d-flex .back-inner-btn{display:none;display:none}
    }
    @media  screen and (max-width: 768px) and (min-width: 668px) {
        .inner-breadcrumb .d-flex .back-inner-btn{display:none}
    }
    @media  screen and (max-width: 370px) {
        .inner-breadcrumb .d-flex .back-inner-btn{display:none}
    }
    @media  screen and (max-width: 667px) {
        .inner-breadcrumb .d-flex ul li{display:block;margin-bottom:5px;margin-right:0}
        .inner-breadcrumb .d-flex ul li:not(:last-child):after{border:none;right:0}
    }
    @media  screen and (orientation: landscape) and (max-width: 737px) and (min-width: 597px) {
        .mob-logo{display:none}
        .inner-breadcrumb{top:-8px;width:calc(100vw - 40px);left:40px}
        .inner-breadcrumb .d-flex ul li{display:inline-block;padding:7px;font-size:10px}
        .inner-breadcrumb .d-flex ul li:before,.inner-breadcrumb .d-flex ul li:after{border-width:0}
    }
    @media  screen and (max-width: 597px) {
        .floor-img-holder.active{width: 100%;}
        .floor-img-holder.active img{height: calc(80vh); width: auto; margin: auto; top: 15vh;}
    }
    @media  screen and (max-width: 768px) and (orientation: landscape){
        .inner-breadcrumb{z-index:1115}
    }
    @media  screen and (max-width: 768px) and (orientation: portrait){
        .inner-breadcrumb{z-index:10}
    }
</style>
<body>
    <?php echo $__env->make('includes.header-footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php echo $__env->yieldContent('content'); ?>
    <?php if(Route::currentRouteName() != 'front.floorplan'): ?>
        <script>
            const minPrice = 0,
                maxPrice = 10000,
                sourcesView1 = <?php echo json_encode($sources1, JSON_PRETTY_PRINT)?>,
                sourcesView2 = <?php echo json_encode($sources2, JSON_PRETTY_PRINT)?>;
        </script>
    <?php endif; ?>
    <?php echo $__env->make('includes.scripts', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <script type="text/javascript" src="<?php echo e(asset('js/iframe.js?v='.time())); ?>"></script>
</body>
</html>
<?php /**PATH D:\xampp\htdocs\biorev\tkr-xhome360\cgi-bin\resources\views/layouts/main.blade.php ENDPATH**/ ?>