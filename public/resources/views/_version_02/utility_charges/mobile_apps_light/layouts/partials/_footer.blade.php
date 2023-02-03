    

    </div>
    <!-- /HK Wrapper -->  

    <!-- FOOTER  -->
<footer id="footer">
<div class="container">


<!-- background-color:#fff; -->
 <div class="row copyright">
         <ul class="box-set" style="z-index: auto; position: fixed; bottom: 0px; left: 0px; width:100%;padding-bottom:2%;">
            <li class="box" style="margin:0;width:25%;text-align:center;box-sizing:border-box"><a onclick=" init_loading_overlay();" class="nav-link" href="{{action('AppsUtilityChargesController@getDashboard')}}?session_token={{$session_token}}"><i class="material-icons">home</i><br>Home</a></li>
            <li  class="box" style="margin:0;width:25%;text-align:center;box-sizing:border-box"><a onclick=" init_loading_overlay();" class="nav-link" href="{{action('AppsUtilityChargesController@getTransactionListing', [$session_token])}}" ><i class="material-icons">event</i><br>Transaction</a></li>
            <li  class="box" style="margin:0;width:25%;text-align:center;box-sizing:border-box"><a onclick=" init_loading_overlay();" class="nav-link" href="{{action('AppsUtilityChargesController@getTopUp', [$session_token])}}?amount=2" ><i class="material-icons"></i><br>Top Up</a></li>
            <li  class="box" style="margin:0;width:25%;text-align:center;box-sizing:border-box"><a onclick=" init_loading_overlay();" class="nav-link" href="{{action('AppsUtilityChargesController@getHelp', [$session_token])}}" ><i class="material-icons">account_box</i><br>FAQ</a></li>
        </ul>

  </div>  




</div>
</footer>



    <!-- jQuery -->
    <script src="{{asset('version_2/vendors/jquery/dist/jquery.min.js')}}"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="{{asset('version_2/vendors/popper.js/dist/umd/popper.min.js')}}"></script>
    <script src="{{asset('version_2/vendors/bootstrap/dist/js/bootstrap.min.js')}}"></script>

    <!-- Jasny-bootstrap  JavaScript -->
    <script src="{{asset('version_2/vendors/jasny-bootstrap/dist/js/jasny-bootstrap.min.js')}}"></script>

    <!-- Form Wizard JavaScript -->
    <!-- <script src="{{asset('js/'.App\Setting::UI_VERSION_UI.'/plugins/jquery.steps-1.1.0/jquery.steps.min.js')}}"></script> -->
     <script src="https://kit.fontawesome.com/a1db5a0e99.js" crossorigin="anonymous"></script>
    <script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>
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
     <script src="{{asset('version_2/vendors/select2/dist/js/select2.full.min.js')}}"></script>
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

    <script src="{{asset('js/'.App\Setting::UI_VERSION_UI.'/power_meter_backend_operate.js')}}?ver={{App\Setting::version()}}"></script>
    <script src="{{asset('js/'.App\Setting::UI_VERSION_UI.'/main_route.js')}}?ver={{App\Setting::version()}}"></script>
    <script src="{{asset('js/'.App\Setting::UI_VERSION_UI.'/main_init_ui.js')}}?ver={{App\Setting::version()}}"></script>
    <script src="{{asset('js/'.App\Setting::UI_VERSION_UI.'/main_app_e_commerce.js')}}?ver={{App\Setting::version()}}"></script>
    
<script>

   var decimalPoint  = "2";
  var getUserDailyUsageUrl = "{{action('AppsUtilityChargesController@getUserDailyLineChart')}}";
  var getIETransactionSummaryUrl = "{{action('AppsIETransactionsController@getIETransactionSummary')}}";
  var stringPleaseWait = "{{App\Language::trans('Please wait...')}}";
  var confirmMsg = "{{App\Language::trans('Are you sure?')}}";
  var setLanguageUrl = "{{action('LanguagesController@getLanguage')}}";


  @yield('script')

      var current_url = window.location.href;
      if(current_url.includes('admin/dashboard')){
      
         get_dashboard_data();
         init_transaction_chat();
         init_bar_chart();
      }
  
     $('.loading-label').click(function (event) 
     {  
          event.preventDefault();
         
          var targeted_url = '';
          url = $(this).attr("href");
          if (typeof url  === 'undefined') {
             url = window.location.href ;
          }
              
            window.history.replaceState({}, '', url);
              $( "#main_content" ).load(url + " #main_content", () => {
                     init_loading_overlay();
                    if(url.includes('admin/meter/status')){
                        init_power_meter_hosue_listing('house_listing_div');
                    }else if(url.includes('edit') || url.includes('new') || url.includes('export_by')){
                        init_floating_footer();
                    }else if(url.includes('index')){
                        init_data_table();
                    }else if(url.includes('admin/dashboard')){
                       get_dashboard_data();
                       init_transaction_chat();
                       init_bar_chart();
                    }else if(url.includes('payment-received/new') || url.includes('payment-received/edit')){
                        $('[data-mask]').inputmask()

                        show_only_div_step_by_step_no(get_current_step());
                        if($('#customer_id').val() != undefined && $('#customer_id').val() != ""){
                        init_customer_info();
                        }

                        $('#alert_msg_div').css("display", "none");
                        // init_payment_received_ui();
                        $(document).on('submit','form.form-horizontal',function(){
                        check_payment_received();
                        });

                        if(url.includes('payment-received/edit')){
                        $('#customer_id').css("display", "block");
                        }

                    }else if(url.includes('ar-refunds/new')){
                        init_ar_refund_UI();
                    }else if(url.includes('admin/dashboardWIP')){
                        get_latest_meter_daily_reading();
                        get_latest_meter_daily_reading_by_daily_record_summary();
                    }else if(url.includes('admin/meter/subsidiaries')){
                        init_meter_susidy_ui();
                    }else if(url.includes('admin/umrah/users/new') || url.includes('admin/umrah/users/edit/') ){
                        init_date_picker_with_time('power_mangement_start_charging_date');
                    }else if(url.includes('admin/products/new') ){
                        init_date_range_new_ui_by_id('date_range');
                    }else if(url.includes('admin/settings') ){
                        init_date_date_picker_new_ui_by_id('system_live_date');
                    }else if(url.includes('ietransactions/new') ){
                        init_date_date_picker_new_ui_by_id('document_date');
                    }

                    init_hide_loading_overlay();
                }); 
              //}
      });


</script>
</body>
</html>
