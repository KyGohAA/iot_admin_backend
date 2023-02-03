
    </div>
    <!-- /HK Wrapper -->  

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

    <!-- Toastr JS -->
    <script src="{{asset('version_2/vendors/jquery-toast-plugin/dist/jquery.toast.min.js')}}"></script>

    <!-- Counter Animation JavaScript -->
    <script src="{{asset('version_2/vendors/waypoints/lib/jquery.waypoints.min.js')}}"></script>
    <script src="{{asset('version_2/vendors/jquery.counterup/jquery.counterup.min.js')}}"></script>

    <!-- Morris Charts JavaScript -->
    <script src="{{asset('version_2/vendors/raphael/raphael.min.js')}}"></script>
    <script src="{{asset('version_2/vendors/morris.js/morris.min.js')}}"></script>

    <!-- Easy pie chart JS -->
    <script src="{{asset('version_2/vendors/easy-pie-chart/dist/jquery.easypiechart.min.js')}}"></script>

    <!-- Flot Charts JavaScript -->
    <script src="{{asset('version_2/vendors/flot/excanvas.min.js')}}"></script>
    <script src="{{asset('version_2/vendors/flot/jquery.flot.js')}}"></script>
    <script src="{{asset('version_2/vendors/flot/jquery.flot.pie.js')}}"></script>
    <script src="{{asset('version_2/vendors/flot/jquery.flot.resize.js')}}"></script>
    <script src="{{asset('version_2/vendors/flot/jquery.flot.time.js')}}"></script>
    <script src="{{asset('version_2/vendors/flot/jquery.flot.stack.js')}}"></script>
    <script src="{{asset('version_2/vendors/flot/jquery.flot.crosshair.js')}}"></script>
    <script src="{{asset('version_2/vendors/jquery.flot.tooltip/js/jquery.flot.tooltip.min.js')}}"></script>

    <!-- EChartJS JavaScript -->
    <script src="{{asset('version_2/vendors/echarts/dist/echarts-en.min.js')}}"></script>

    <!-- Init JavaScript -->
    <script src="{{asset('version_2/dist/js/init.js')}}"></script>
    <script src="{{asset('version_2/dist/js/dashboard2-data.js')}}"></script>
    <script src="{{asset('js/main.js')}}?ver={{App\Setting::version()}}"></script>
    <script src="{{asset('js/'.App\Setting::UI_VERSION.'/main_route.js')}}?ver={{App\Setting::version()}}"></script>
    <!-- ChartJS -->
    <script src="{{asset('bower_components/chart.js/Chart.js')}}"></script>


<script>

  var decimalPoint  = "2";
  var customerInvoiceUrl = "{{action('AccountingsController@getInoviceByCustomerId')}}";
  var customerDocumentByCustomerIdAndTypeUrl = "{{action('AccountingsController@getCustomerDocumentByCustomerIdAndType')}}";
  var customerDocumentUrl = "{{action('AccountingsController@getCustomerDocumentByTypeAndId')}}";
  var roomInfoUrl = "{{action('UMeterRegistersController@getRoomInfo')}}";
  var utilityChargeEstimatedUrl = "{{action('UtilityChargesController@getChargeEstimated')}}";
  var roomsComboboxUrl = "{{action('UMeterRegistersController@getCombobox')}}";
  var statesComboboxUrl = "{{action('StatesController@getCombobox')}}";
  var citiesComboboxUrl = "{{action('CitiesController@getCombobox')}}";
  var utilityChargeListUrl = "{{action('UtilityChargesController@getList')}}";
  var stringPleaseWait = "{{App\Language::trans('Please wait...')}}";
  var stringPleaseWaitReport = "{{App\Language::trans('Report generating , please wait for a moment...')}}";
  var confirmMsg = "{{App\Language::trans('Are you sure?')}}";
  var errorRemoveRow = "{{App\Language::trans('This table at least one row left.')}}";
  var enableLabel = "{{App\Language::trans('Enabled')}}";
  var disableLabel = "{{App\Language::trans('Disabled')}}";
  var checkCustomerLatest = "{{action('CustomersController@getLatest')}}";
  var getCurrencyModelByIdUrl = "{{action('CurrenciesController@getCurrencyModelById')}}";
  var getARTransactionSummaryUrl = "{{action('ARPaymentReceivedsController@getARTransactionSummary')}}";
  var getMeterDetailUrl = "{{action('UMeterRegistersController@getMeterDetail')}}";
  var getLatestDailyMeterReading = "{{action('ReportsController@getLatestDailyMeterReading')}}";
  var getLatestDailyMeterReadingByDailyRecordSummary = "{{action('ReportsController@getLatestDailyMeterReadingByDailyRecordSummary')}}";
  var getOCProductDetailUrl = "{{action('OCProductsController@getProductDetail')}}";
  var getAllProductsFromEgo888 = "{{action('OCProductsController@getAllProductsFromEgo888')}}";
  var getProductPriceUpdate = "{{action('OCProductsController@getProductPriceUpdate')}}";
  @yield('script')
</script>
</body>
</html>
