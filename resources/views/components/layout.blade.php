<!DOCTYPE html>
<html lang="pt_BR">

<x-header />

<body class="hold-transition sidebar-mini">
    <div id="waiting" class="box-waiting">
        <div class="loading-icon">
            <i class="fas fa-spinner"></i>
        </div>
        
    </div>

    <div class="wrapper">

        <x-navbar />

        <x-menu />

        <!-- Content Wrapper. Contains page content -->
        {{ $slot }}
        
        <!-- /.content-wrapper -->

        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
        </aside>
        <!-- /.control-sidebar -->

        <x-footer />
    </div>


    <!-- jQuery -->
    <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
    <!-- Bootstrap -->
    <script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- AdminLTE -->
    <script src="{{ asset('dist/js/adminlte.js') }}"></script>

    <!-- OPTIONAL SCRIPTS -->
    <script src="{{ asset('plugins/chart.js/Chart.min.js') }}"></script>
    <!-- AdminLTE for demo purposes -->
    <!-- <script src="{{ asset('dist/js/demo.js') }}"></script> -->
    <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
    <script src="{{ asset('dist/js/pages/dashboard3.js') }}"></script>
</body>

</html>