    </div>

<script>
      var getDeviceChartData = "<?php echo e(action('IOTUniversalsController@getLineChart')); ?>";
      var getIotSummaryData = "<?php echo e(action('IOTUniversalsController@getIotSummaryData')); ?>";
      var getDashboardChartData = "<?php echo e(action('IOTUniversalsController@getDashboardChartData')); ?>";


      
</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.min.js"></script>

<!-- Javascript -->
<script src="<?php echo e(asset('version_2/iot/assets/bundles/libscripts.bundle.js')); ?>"></script>    
<script src="<?php echo e(asset('version_2/iot/assets/bundles/vendorscripts.bundle.js')); ?>"></script>

<script src="<?php echo e(asset('version_2/iot/assets/bundles/chartist.bundle.js')); ?>"></script>
<script src="<?php echo e(asset('version_2/iot/assets/bundles/jvectormap.bundle.js')); ?>"></script> <!-- JVectorMap Plugin Js -->


<!-- Jquery DataTable Plugin Js --> 
<script src="<?php echo e(asset('version_2/iot/assets/bundles/datatablescripts.bundle.js')); ?>"></script>
<script src="<?php echo e(asset('version_2/iot/assets/bundles/mainscripts.bundle.js')); ?>"></script>
<script src="<?php echo e(asset('version_2/iot/assets/js/pages/tables/jquery-datatable.js')); ?>"></script>

<script src="<?php echo e(asset('version_2/iot/assets/vendor/nestable/jquery.nestable.js')); ?>"></script> <!-- Jquery Nestable -->
<script src="<?php echo e(asset('version_2/iot/assets/vendor/sweetalert/sweetalert.min.js')); ?>"></script> <!-- SweetAlert Plugin Js --> 
<script src="<?php echo e(asset('version_2/iot/assets/vendor/bootstrap-datepicker/js/bootstrap-datepicker.min.js')); ?>"></script><!-- bootstrap datepicker Plugin Js --> 
<script src="<?php echo e(asset('version_2/iot/assets/js/pages/ui/sortable-nestable.js')); ?>"></script>
<script src="<?php echo e(asset('version_2/iot/assets/js/pages/ui/dialogs.js')); ?>"></script>


<!-- Morris Charts JavaScript -->
    <script src="<?php echo e(asset('version_2/vendors/raphael/raphael.min.js')); ?>"></script>
    <script src="<?php echo e(asset('version_2/vendors/morris.js/morris.min.js')); ?>"></script>

    <!-- Easy pie chart JS -->
    <script src="<?php echo e(asset('version_2/vendors/easy-pie-chart/dist/jquery.easypiechart.min.js')); ?>"></script>
    <script src="<?php echo e(asset('version_2/iot/assets/vendor/jquery-sparkline/js/jquery.sparkline.min.js')); ?>"></script>

    <script src="<?php echo e(asset('version_2/iot/assets/bundles/mainscripts.bundle.js')); ?>"></script>
    
<script src="<?php echo e(asset('version_2/iot/assets/js/index.js')); ?>"></script>
<script src="<?php echo e(asset('js/_version_02/iot.js')); ?>"></script>

</body>
</html>
