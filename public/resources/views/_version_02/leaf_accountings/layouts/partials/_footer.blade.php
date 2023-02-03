    

    </div>
    <!-- /HK Wrapper -->  
    <!-- jQuery -->
    <script src="{{asset('version_2/vendors/jquery/dist/jquery.min.js')}}"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="{{asset('version_2/vendors/popper.js/dist/umd/popper.min.js')}}"></script>
    <script src="{{asset('version_2/vendors/bootstrap/dist/js/bootstrap.min.js')}}"></script>

    <!-- Jasny-bootstrap  JavaScript -->
    <script src="{{asset('version_2/vendors/jasny-bootstrap/dist/js/jasny-bootstrap.min.js')}}"></script>

    <!-- Form Wizard JavaScript -->
    <!-- <script src="{{asset('js/'.App\Setting::UI_VERSION.'/plugins/jquery.steps-1.1.0/jquery.steps.min.js')}}"></script> -->
    <script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>
    <script src="{{asset('version_2/dist/js/external/jquery.steps.min.js')}}"></script> 
    
    <script src="{{asset('version_2/dist/js/form-wizard-data.js')}}"></script>

    <!-- Bootstrap Input spinner JavaScript  
    <script src="{{asset('version_2/vendors/bootstrap-input-spinner/src/bootstrap-input-spinner.js')}}"></script>--> 


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

    <!-- FLOT CHARTS -->
    <script src="{{asset('bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.js')}}"></script>
    <script src="{{asset('bower_components/bootstrap-daterangepicker/daterangepicker.js')}}"></script>

    <script src="{{asset('bower_components/Flot/jquery.flot.js')}}"></script>
    <!-- FLOT RESIZE PLUGIN - allows the chart to redraw when the window is resized -->
    <script src="{{asset('bower_components/Flot/jquery.flot.resize.js')}}"></script>
    <!-- FLOT PIE PLUGIN - also used to draw donut charts -->
    <script src="{{asset('bower_components/Flot/jquery.flot.pie.js')}}"></script>
    <!-- FLOT CATEGORIES PLUGIN - Used to draw bar charts -->
    <script src="{{asset('bower_components/Flot/jquery.flot.categories.js')}}"></script>
    <script src="{{asset('bower_components/jquery-knob/js/jquery.knob.js')}}"></script>

    <!-- EChartJS JavaScript -->
    <script src="{{asset('version_2/vendors/echarts/dist/echarts-en.min.js')}}"></script>

   
    <!-- Select2 JavaScript -->
     <script src="{{asset('version_2/vendors/select2/dist/js/select2.full.min.js')}}"></script> -->
   <!--  <script src="{{asset('version_2/dist/js/select2-data.js')}}"></script> -->

  

    <!-- ChartJS -->
    <script src="{{asset('bower_components/chart.js/Chart.js')}}"></script>

    <!-- Ion JavaScript -->
    <script src="{{asset('version_2/vendors/ion-rangeslider/js/ion.rangeSlider.min.js')}}"></script>
    <script src="{{asset('version_2/dist/js/rangeslider-data.js')}}"></script>
    <!-- Daterangepicker JavaScript -->
    <script src="{{asset('version_2/vendors/moment/min/moment.min.js')}}"></script>
    
    <script src="{{asset('version_2/vendors/daterangepicker/daterangepicker.js')}}"></script>
    <script src="{{asset('version_2/dist/js/daterangepicker-data.js')}}"></script>

    <!-- Waiting Me Loading JavaScript -->
    <script src="{{asset('plugins/Loading-overlay/waitMe.min.js')}}"></script>
    

    <!-- scrollable -->
    <script src="{{asset('version_2/dist/js/jquery-scrolltofixed-min.js')}}"></script>

    

     <!-- Comment if need original input-->
    <script src="{{asset('js/jquery.mask.js')}}"></script>
    <script src="{{asset('js/i_mask.js')}}?ver={{App\Setting::version()}}"></script>

    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>
    <!-- Cropper 
    <script src="{{asset('version_2/vendors/cropperjs/dist/cropper.min.js')}}"></script>
    <script src="{{asset('version_2/dist/js/cropperjs-data.js')}}"></script>
    -->
    <!-- Init JavaScript -->
    <script src="{{asset('version_2/dist/js/init.js')}}"></script>
    <script src="{{asset('version_2/dist/js/dashboard2-data.js')}}"></script>

    <script src="{{asset('plugins/multi-select-checkbox/jquery.multiselect.js')}}"></script>

    
    <script src="{{asset('js/'.App\Setting::UI_VERSION.'/main_route.js')}}?ver={{App\Setting::version()}}"></script>
    <script src="{{asset('js/'.App\Setting::UI_VERSION.'/main_init_ui.js')}}?ver={{App\Setting::version()}}"></script>
    <script src="{{asset('js/'.App\Setting::UI_VERSION.'/main_app_e_commerce.js')}}?ver={{App\Setting::version()}}"></script>
    
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
  var getProductDetailUpdate = "{{action('OCProductsController@getProductDetailUpdate')}}";
  var getMemberDetailUrl = "{{action('MembershipsController@getMemberDetail')}}";
  var getCreateOrUpdateMembershipUrl = "{{action('MembershipsController@getCreateOrUpdateMembership')}}";
  var productInfoByLeafProductIdUrl = "{{action('ProductsController@getInfoByLeafProductId')}}";
  var default_pic_url = '{{ URL::asset("img/img-thumb.jpg") }}' ; 
  var default_asset_url = '{{ URL::asset("") }}' ; 
  var customerInfoUrl = "{{action('CustomersController@getInfo')}}";  
  var customerMeterPaymentReceivedInfoUrl = "{{action('UMeterPaymentReceivedsController@getInfo')}}";  
  var getRecoverTransactionUrl = "{{action('UMeterPaymentReceivedDebugsController@getRecoverTransaction')}}";  
  
  @yield('script')


     $('.nav-link').click(function (event) {

        // Avoid the link click from loading a new page
       // event.preventDefault();

        // Load the content from the link's href attribute
        //$('.content').load($(this).attr('href'));
      });

</script>
</body>
</html>
