<div id="footer____cancel_css" style="background-color:#59abf7;z-index: auto; position: fixed; bottom: 0px; left: 0px; width:100%;">
    <div class="nav-tabs-custom" style="margin:0;">
        <ul class="nav nav-tabs">
            <li class="active" style="margin:0;width:25%;text-align:center;box-sizing:border-box"><a href="#tab_1" data-toggle="tab"><i class="material-icons">card_membership</i><br>Membership</a></li>
            <li style="margin:0;width:25%;text-align:center;box-sizing:border-box"><a href="#tab_2" data-toggle="tab"><i class="material-icons">account_balance_wallet</i><br>Payment</a></li>
            <li style="margin:0;width:25%;text-align:center;box-sizing:border-box"><a href="#tab_3" data-toggle="tab"><i class="material-icons">event</i><br>History</a></li>
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
<script src="{{asset('js/app_main_2.js')}}?ver={{App\Setting::version()}}"></script>
<!-- <script src="{{asset('js/app_main.js')}}?ver={{App\Setting::version()}}"></script>
<script src="{{asset('js/app_main_2.js')}}?ver={{App\Setting::version()}}"></script> -->

<link href="https://fonts.googleapis.com/icon?family=Material+Icons"
      rel="stylesheet">


<script>
    var statesComboboxUrl = "{{action('StatesController@getCombobox')}}";
    var citiesComboboxUrl = "{{action('CitiesController@getCombobox')}}";
    var utilityChargeListUrl = "{{action('UtilityChargesController@getList')}}";
    var confirmMsg = "{{App\Language::trans('Are you sure?')}}";
    var errorRemoveRow = "{{App\Language::trans('This table at least one row left.')}}";
    var enableLabel = "{{App\Language::trans('Enabled')}}";
    var disableLabel = "{{App\Language::trans('Disabled')}}";
    var usageLineChartDataUrl = "{{action('AppsUtilityChargesController@getDataTest')}}"; 
  	var paymentInfoByProductIdUrl = "{{action('AppAccountingDashboardsController@getPaymentInfoByProductId')}}";
    var productInfoUrl = "{{action('ProductsController@getInfo')}}";
    @yield('script')

</script>
</body>

</html>