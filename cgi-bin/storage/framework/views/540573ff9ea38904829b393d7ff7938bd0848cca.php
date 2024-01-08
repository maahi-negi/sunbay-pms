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
            let sourcesView1 = <?php echo json_encode($sources1, JSON_PRETTY_PRINT)?>,
                sourcesView2 = <?php echo json_encode($sources2, JSON_PRETTY_PRINT)?>;
            let layerColors = [];
        </script>
    <?php endif; ?>
    <?php echo $__env->make('includes.scripts', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $("#mainLoader").show();
        $(document).ready(function (){
            $(".main-wrapper").addClass("main-wrapper-sidebar");
            $("#sideMenu").addClass("d-flex").show();
        });
    </script>
</body>
</html>
<?php /**PATH /home/xhome/public_html/ule-uat.xhome360.com/cgi-bin/resources/views/layouts/main.blade.php ENDPATH**/ ?>