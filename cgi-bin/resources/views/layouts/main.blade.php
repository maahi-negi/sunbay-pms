<!DOCTYPE html>
<html>
@include('includes.head')
<body>
    @include('includes.header-footer')
    @yield('content')
    @if(Route::currentRouteName() != 'front.floorplan')
        <script>
            const minPrice = 0,
                maxPrice = 10000;
            let sourcesView1 = <?php echo json_encode($sources1, JSON_PRETTY_PRINT)?>,
                sourcesView2 = <?php echo json_encode($sources2, JSON_PRETTY_PRINT)?>;
            let layerColors = [];
        </script>
    @endif
    @include('includes.scripts')

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
