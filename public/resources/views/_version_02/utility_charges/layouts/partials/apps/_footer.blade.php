<div id="footer____cancel_css" style="background-color:#59abf7;z-index: auto; position: fixed; bottom: 0px; left: 0px; width:100%;">
    <div class="nav-tabs-custom" style="margin:0;">
        <ul class="nav nav-tabs">
            <li class="active" style="margin:0;width:25%;text-align:center;box-sizing:border-box"><a href="#tab_1" data-toggle="tab"><i class="material-icons">update</i><br>Meter</a></li>
            <li style="margin:0;width:25%;text-align:center;box-sizing:border-box"><a href="#tab_2" data-toggle="tab"><i class="material-icons">event</i><br>Billing</a></li>
            <li style="margin:0;width:25%;text-align:center;box-sizing:border-box"><a href="#tab_3" data-toggle="tab"><i class="material-icons"></i><br>Top Up</a></li>
            <li style="margin:0;width:25%;text-align:center;box-sizing:border-box"><a href="#tab_4" data-toggle="tab"><i class="material-icons"></i><br>Help</a></li>
        </ul>
    </div> 
</div>


<!-- jQuery 3 -->
<script src="{{asset('bower_components/jquery/dist/jquery.min.js')}}"></script>
<!-- Bootstrap 3.3.7 -->
<script src="{{asset('bower_components/bootstrap/dist/js/bootstrap.min.js')}}"></script>
<!-- SlimScroll -->
<script src="{{asset('bower_components/jquery-slimscroll/jquery.slimscroll.min.js')}}"></script>
<!-- FastClick -->
<script src="{{asset('bower_components/fastclick/lib/fastclick.js')}}"></script>

<!-- Morris.js charts -->
<script src="{{asset('bower_components/raphael/raphael.min.js')}}"></script>
<script src="{{asset('bower_components/morris.js/morris.min.js')}}"></script>

<!-- scrollable -->
<script src="{{asset('js/jquery-scrolltofixed.js')}}"></script>
<!-- InputMask -->
<script src="{{asset('plugins/input-mask/jquery.inputmask.js')}}"></script>
<script src="{{asset('plugins/input-mask/jquery.inputmask.extensions.js')}}"></script>
<script src="{{asset('plugins/input-mask/jquery.inputmask.date.extensions.js')}}"></script>
<script src="{{asset('plugins/input-mask/jquery.inputmask.numeric.extensions.js')}}"></script>
<!-- AdminLTE App -->
<script src="{{asset('js/adminlte.min.js')}}"></script>
<!-- FLOT CHARTS -->
<script src="{{asset('bower_components/Flot/jquery.flot.js')}}"></script>
<!-- FLOT RESIZE PLUGIN - allows the chart to redraw when the window is resized -->
<script src="{{asset('bower_components/Flot/jquery.flot.resize.js')}}"></script>
<!-- FLOT PIE PLUGIN - also used to draw donut charts -->
<script src="{{asset('bower_components/Flot/jquery.flot.pie.js')}}"></script>
<!-- FLOT CATEGORIES PLUGIN - Used to draw bar charts -->
<script src="{{asset('bower_components/Flot/jquery.flot.categories.js')}}"></script>
<script src="{{asset('bower_components/jquery-knob/js/jquery.knob.js')}}"></script>
<!-- AdminLTE for demo purposes -->
<script src="{{asset('js/demo.js')}}?ver={{App\Setting::version()}}"></script>
<script src="{{asset('js/main.js')}}?ver={{App\Setting::version()}}"></script>
<script src="{{asset('js/app_main.js')}}?ver={{App\Setting::version()}}"></script>
<script src="{{asset('js/app_main_2.js')}}?ver={{App\Setting::version()}}"></script>

<link href="https://fonts.googleapis.com/icon?family=Material+Icons"
      rel="stylesheet">




 <!-- jQuery -->
    <script src="{{asset('version_2/vendors/jquery/dist/jquery.min.js')}}"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="{{asset('version_2/vendors/popper.js/dist/umd/popper.min.js')}}"></script>
    <script src="{{asset('version_2/vendors/bootstrap/dist/js/bootstrap.min.js')}}"></script>

    <!-- Slimscroll JavaScript -->
    <script src="{{asset('version_2/dist/js/jquery.slimscroll.js')}}"></script>

    <!-- Fancy Dropdown JS -->
    <script src="{{asset('version_2/dist/js/dropdown-bootstrap-extended.js')}}"></script>

    <!-- FeatherIcons JavaScript -->
    <script src="{{asset('version_2/dist/js/feather.min.js')}}"></script>

    <!-- Toggles JavaScript -->
    <script src="{{asset('version_2/vendors/jquery-toggles/toggles.min.js')}}"></script>
    <script src="{{asset('version_2/dist/js/toggle-data.js')}}"></script>
    
    <!-- Counter Animation JavaScript -->
    <script src="{{asset('version_2/vendors/waypoints/lib/jquery.waypoints.min.js')}}"></script>
    <script src="{{asset('version_2/vendors/jquery.counterup/jquery.counterup.min.js')}}"></script>
    
    <!-- Morris Charts JavaScript -->
    <script src="{{asset('version_2/vendors/raphael/raphael.min.js')}}"></script>
    <script src="{{asset('version_2/vendors/morris.js/morris.min.js')}}"></script>
    
    <!-- EChartJS JavaScript -->
    <script src="{{asset('version_2/vendors/echarts/dist/echarts-en.min.js')}}"></script>
    
    <!-- Sparkline JavaScript -->
    <script src="{{asset('version_2/vendors/jquery.sparkline/dist/jquery.sparkline.min.js')}}"></script>
    
    <!-- Owl JavaScript -->
    <script src="{{asset('version_2/vendors/owl.carousel/dist/owl.carousel.min.js')}}"></script>
    
    <!-- Init JavaScript -->
    <script src="{{asset('version_2/dist/js/init.js')}}"></script>
    <script src="{{asset('version_2/dist/js/ecStat.min.js')}}"></script>
    <script src="{{asset('version_2/dist/js/dashboard5-data.js')}}"></script>

    
<script>
    var statesComboboxUrl = "{{action('StatesController@getCombobox')}}";
    var citiesComboboxUrl = "{{action('CitiesController@getCombobox')}}";
    var utilityChargeListUrl = "{{action('UtilityChargesController@getList')}}";
    var confirmMsg = "{{App\Language::trans('Are you sure?')}}";
    var errorRemoveRow = "{{App\Language::trans('This table at least one row left.')}}";
    var enableLabel = "{{App\Language::trans('Enabled')}}";
    var disableLabel = "{{App\Language::trans('Disabled')}}";
    var usageLineChartDataUrl = "{{action('AppsUtilityChargesController@getDataTest')}}"; 
    @yield('script')
</script>
</body>

</html>