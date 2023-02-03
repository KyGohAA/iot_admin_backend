    

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

   

    <script src="{{asset('js/'.App\Setting::UI_VERSION_UI.'/power_meter_backend_operate.js')}}?ver={{App\Setting::version()}}"></script>
    <script src="{{asset('js/'.App\Setting::UI_VERSION_UI.'/main_route.js')}}?ver={{App\Setting::version()}}"></script>
    <script src="{{asset('js/'.App\Setting::UI_VERSION_UI.'/main_init_ui.js')}}?ver={{App\Setting::version()}}"></script>
    <script src="{{asset('js/'.App\Setting::UI_VERSION_UI.'/main_app_e_commerce.js')}}?ver={{App\Setting::version()}}"></script>

    <!-- Tinymce JavaScript -->
    <script src="{{asset('version_2/dist/js/tinymce-data.js')}}"></script>
    <script src="{{asset('version_2/vendors/tinymce/tinymce.min.js')}}"></script>

     <!-- Select2 JavaScript -->
     <script src="{{asset('version_2/vendors/select2/dist/js/select2.full.min.js')}}"></script>
   <!--  <script src="{{asset('version_2/dist/js/select2-data.js')}}"></script>  -->
     <script src="{{asset('plugins/multi-select-checkbox/jquery.multiselect.js')}}"></script>
   
@if(isset($getChartJs))
    <script async src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/1.0.2/Chart.min.js"></script>
    <script async src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.3.0-rc.1/Chart.js"></script>
@endif
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
  var stringPleaseWaitReportPdf    = "{{App\Language::trans('Report is generating on new tab, please wait for a moment... ')}}";
  var stringPleaseWaitReportExcel = "{{App\Language::trans('Report is generating , please wait for a moment...')}}";
  var stringPleaseWaitReportHtml = "{{App\Language::trans('Report is generating , it will download once complete ...')}}";

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
  var getUpdateMeterRegisterUrl  = "{{action('UMeterRegistersController@getUpdateMeterRegister')}}"; 

  
  var getDashboardDataUrl = "{{action('DashboardsController@getDashboardData')}}"; 
  var getUserDataUrl = "{{action('AppsUtilityChargesController@getUserData')}}"; 
   
    //power meter
  var power_meter_house_listing_url = "{{action('UMeterRegistersController@getStatusData')}}";
  var power_meter_room_detail_url =  "{{action('UMeterRegistersController@getHouseRoomDetail')}}";
  var generate_report_url = "{{action('ReportsController@getMonthlySales')}}"; 

  var get_status_detail_url = "{{action('UMeterRegistersController@getStatusDetailData')}}"; 
  var getUserDailyUsageUrl = "{{action('AppsUtilityChargesController@getUserDailyLineChart')}}";

  @yield('script')

     $(document).ready(function(){
      
            var url = window.location.href;
            var n = url.indexOf("/new");
            if (n == -1) {
              var n = url.indexOf("/edit");
            };
            if (n == -1) {
              var n = url.indexOf("/view");
            };
            if (n == -1) {
              var n = url.indexOf("?");
            };
            url = url.substring(0, n != -1 ? n : url.length);
            var sidebar = $(".sidebar-menu").find("li");

            // var sidebar_li = sidebar.children();
            sidebar.each(function(){
                if ($(this).find("ul").length) {
                    $(this).find("ul li").each(function(){
                        var href = $(this).find("a").attr("href");
                        if (href == url) {
                            $(this).addClass("active");
                            $(this).parentsUntil($("ul.sidebar-menu"), ".treeview-menu").show();
                            $(this).parentsUntil($("ul.sidebar-menu"), ".treeview").addClass("menu-open");
                        }
                    });
                } else {
                  var href = $(this).find("a").attr("href");
                    if (href == url) {
                        $(this).addClass("active");
                    }            
                }
            });
            if ($(".latest-opening").length) {
                $(".latest-opening").slick({
                    slidesToShow: 3,
                    slidesToScroll: 1,
                    autoplay: true,
                    autoplaySpeed: 2000,
                });
            }
            if ($(".input-daterange").length) {
                $(".input-daterange").datepicker({
                    format: "dd-mm-yyyy",
                });
            }
            $("#export_by_pdf").on("click", function(){
                $(this).closest("form").attr("target", "_parent");
            });
            $("#export_by_html").on("click", function(){
                $(this).closest("form").attr("target", "_blank");
            });
            $("a").each(function(){
                $(this).attr("data-prefetch",true);
            });
            if ($("select").length) {
                init_select2($("select"));
            }


     $('.loading-label').click(function (event) 
     {  
          event.preventDefault();

          var targeted_url = '';
          url = $(this).attr("href");
          if (typeof url  === 'undefined') {
             url = window.location.href ;
          }
               init_loading_overlay();
              window.history.replaceState({}, '', url);
              $( "#main_content" ).load(url + " #main_content", () => {

                    if(url.includes('admin/dashboard')){
                       get_dashboard_data();
                       init_transaction_chat();
                       init_bar_chart();
                    }



                    if(current_url.includes('admin/meter/status/detail')){
                      console.log('start');
                      update_power_meter_summary_table('leaf_data_table');
                    }

                    if(url.includes('admin/meter/status')){
                        init_power_meter_hosue_listing('house_listing_div');
                    }else if(url.includes('edit') || url.includes('new') || url.includes('export_by')){
                        init_floating_footer();
                    }else if(url.includes('index')){
                        init_data_table();
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
                    }else if(url.includes('ietransactions/new') ){
                        init_date_date_picker_new_ui_by_id('document_date');
                    }

                    if(url.includes('meter/status/detail') ){
                     
                        init_meter_status_detail_ui();
                    }

                    

                    init_hide_loading_overlay();
                }); 
              //}
      });



        });
     //end of initial

      var current_url = window.location.href;
      if(current_url.includes('meter/subsidiaries/edit') || current_url.includes('meter/subsidiaries/new')){
        init_room_type_subsidize_handle('#room_type');
        init_select2($("select"));
      }
      
      if(current_url.includes('admin/dashboard')){
     
         get_dashboard_data();
         init_transaction_chat();
         init_bar_chart();
      }

      if(current_url.includes('admin/meter/status/detail')){
        //alert('start');
        update_power_meter_summary_table('leaf_data_table');
      }

      if(current_url.includes('admin/charges')){
     
        
          if($('#is_hourly').val() == 0)
          {
            $("input[name=hourly_rate]").closest(".form-group").show();
              $("#prices").addClass('hide'); //.hide();
              $("#prices_section").addClass('hide'); //.hide();
          }else{
              $("input[name=hourly_rate]").closest(".form-group").hide();
              $("#prices").removeClass('hide'); //.hide();
              $("#prices_section").removeClass('hide'); //.hide();
          }
      }

      if(current_url.includes('admin/meter/status') && !current_url.includes('admin/meter/status/detail')){
         init_power_meter_hosue_listing('house_listing_div');
      }else if(current_url.includes('edit') || current_url.includes('new') || current_url.includes('export_by')){
          init_floating_footer();
      }else if(current_url.includes('index')){
          //init_data_table();
      }else if(current_url.includes('admin/dashboard')){
         get_dashboard_data();
         init_transaction_chat();
         init_bar_chart();
      }else if(current_url.includes('payment-received/new') || current_url.includes('payment-received/edit')){
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

          if(current_url.includes('payment-received/edit')){
          $('#customer_id').css("display", "block");
          }

      }else if(current_url.includes('ar-refunds/new')){
          init_ar_refund_UI();
      }else if(current_url.includes('admin/dashboardWIP')){
          get_latest_meter_daily_reading();
          get_latest_meter_daily_reading_by_daily_record_summary();
      }else if(current_url.includes('admin/meter/subsidiaries')){
          init_meter_susidy_ui();
      }else if(current_url.includes('admin/umrah/users/new') || current_url.includes('admin/umrah/users/edit/') ){
          init_date_picker_with_time('power_mangement_start_charging_date');
      }else if(current_url.includes('admin/products/new') ){
          init_date_range_new_ui_by_id('date_range');
      }else if(current_url.includes('admin/settings') ){
          init_date_date_picker_new_ui_by_id('system_live_date');
      }





</script>
</body>
</html>
