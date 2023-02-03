    

    </div>
    <!-- /HK Wrapper -->  

    <!-- FOOTER  -->
<footer id="footer">
<div class="container">


<?php if(isset($is_synchorizing_data) && $is_synchorizing_data == true): ?>
  
         <div class="row copyright">
             <ul class="box-set" style="background: rgb(92,92,92);
                background: linear-gradient(198deg, rgba(92,92,92,0.8911939775910365) 0%, rgba(189,189,189,0.3309698879551821) 1%, rgba(55,44,44,0) 12%, rgba(176,176,176,0) 27%, rgba(119,119,119,0.17970938375350143) 97%);z-index: auto; position: fixed; bottom: 0px; left: 0px; width:100%;padding-bottom:2%;">
                                <li class="box" style="margin:0;width:100%;text-align:center;box-sizing:border-box"><a onclick=" init_loading_overlay();" class="nav-link bottom-black" href="<?php echo e(action('AppsUtilityChargesController@getDashboard')); ?>?session_token=<?php echo e($session_token); ?>"><i class="material-icons">home</i><p style="line-height:1px;"><font size="1">Home</font></p></a></li>
                              
                            </ul>

                      </div>  
<?php elseif(!isset($is_house_keeping)): ?>
     <div class="row copyright"  style=" opacity: 1">
             <ul class="box-set" style="background: rgb(92,92,92);
background: linear-gradient(198deg, rgba(92,92,92,0.8911939775910365) 0%, rgba(189,189,189,0.3309698879551821) 1%, rgba(55,44,44,0) 12%, rgba(176,176,176,0) 27%, rgba(119,119,119,0.17970938375350143) 97%);z-index: auto; position: fixed; bottom: 0px; left: 0px; width:100%;padding-bottom:2%;max-height:10%;">
                <li class="box" style="margin:0;width:25%;text-align:center;box-sizing:border-box"><a onclick=" init_loading_overlay();" class="nav-link bottom-black" href="<?php echo e(action('AppsUtilityChargesController@getDashboard')); ?>?session_token=<?php echo e($session_token); ?>"><i class="material-icons">home</i><p style="line-height:1px;"><font size="1">Home</font></p></a></li>
                <li  class="box" style="margin:0;width:25%;text-align:center;box-sizing:border-box"><a onclick=" init_loading_overlay();" class="nav-link bottom-black" href="<?php echo e(action('AppsUtilityChargesController@getTransactionListing')); ?>?session_token=<?php echo e($session_token); ?>" ><i class="material-icons">event</i><p style="line-height:1px;"><font size="1">Transaction</font></p></a></li>
                <li  class="box" style="margin:0;width:25%;text-align:center;box-sizing:border-box"><a onclick=" init_loading_overlay();" class="nav-link bottom-black" href="<?php echo e(action('AppsUtilityChargesController@getTopUp')); ?>?session_token=<?php echo e($session_token); ?>&amount=2" ><i class="material-icons"></i><p style="line-height:1px;"><font size="1">Top Up</font></p></a></li>
                <li  class="box" style="margin:0;width:25%;text-align:center;box-sizing:border-box"><a onclick=" init_loading_overlay();" class="nav-link bottom-black" href="<?php echo e(action('AppsUtilityChargesController@getHelp')); ?>?session_token=<?php echo e($session_token); ?>" ><i class="material-icons">account_box</i><p style="line-height:1px;"><font size="1">FAQ</font></p></a></li>
            </ul>

      </div>  

<?php else: ?>
<!-- background-color:#fff; -->
 <div class="row copyright" style=" opacity: 1">
         <ul class="box-set" style="background: rgb(92,92,92);
background: linear-gradient(198deg, rgba(92,92,92,0.8911939775910365) 0%, rgba(189,189,189,0.3309698879551821) 1%, rgba(55,44,44,0) 12%, rgba(176,176,176,0) 27%, rgba(119,119,119,0.17970938375350143) 97%);z-index: auto; position: fixed; bottom: 0px; left: 0px; width:100%;padding-bottom:2%;">
            <li class="box" style="margin:0;width:33%;text-align:center;box-sizing:border-box"><a onclick=" init_loading_overlay();" class="nav-link bottom-black" href="<?php echo e(action('AppsUtilityChargesController@getHouseKeepingDashboard')); ?>?session_token=<?php echo e($session_token); ?>"><i class="material-icons">home</i><p style="line-height:1px;"><<font size="1">Home</font></p></a></li>
            <li  class="box" style="margin:0;width:33%;text-align:center;box-sizing:border-box"><a onclick=" init_loading_overlay();" class="nav-link bottom-black" href="<?php echo e(action('AppsUtilityChargesController@getHouseKeepingHistory')); ?>?session_token=<?php echo e($session_token); ?>" ><i class="material-icons">event</i><p style="line-height:1px;"><<font size="1">History</font></p></a></li>   
            <li  class="box" style="margin:0;width:33%;text-align:center;box-sizing:border-box"><a onclick=" init_loading_overlay();" class="nav-link bottom-black" href="<?php echo e(action('AppsUtilityChargesController@getHelpHouseKeeping')); ?>?session_token=<?php echo e($session_token); ?>" ><i class="material-icons">account_box</i><p style="line-height:1px;"><<font size="1">FAQ</font></p></a></li>
        </ul>

  </div>  
<?php endif; ?>

</div>
</footer>


    <!-- jQuery -->
    <script src="<?php echo e(asset('version_2/vendors/jquery/dist/jquery.min.js')); ?>"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="<?php echo e(asset('version_2/vendors/popper.js/dist/umd/popper.min.js')); ?>"></script>
    <script src="<?php echo e(asset('version_2/vendors/bootstrap/dist/js/bootstrap.min.js')); ?>"></script>

    <!-- Jasny-bootstrap  JavaScript -->
    <script src="<?php echo e(asset('version_2/vendors/jasny-bootstrap/dist/js/jasny-bootstrap.min.js')); ?>"></script>

    <!-- Form Wizard JavaScript -->
    <!-- <script src="<?php echo e(asset('js/'.App\Setting::UI_VERSION_UI.'/plugins/jquery.steps-1.1.0/jquery.steps.min.js')); ?>"></script> -->
     <script src="https://kit.fontawesome.com/a1db5a0e99.js" crossorigin="anonymous"></script>
    <script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>
    <script src="<?php echo e(asset('version_2/dist/js/external/jquery.steps.min.js')); ?>"></script> 
    
    <script src="<?php echo e(asset('version_2/dist/js/form-wizard-data.js')); ?>"></script>

    <!-- Bootstrap Input spinner JavaScript  
    <script src="<?php echo e(asset('version_2/vendors/bootstrap-input-spinner/src/bootstrap-input-spinner.js')); ?>"></script>--> 


    <!-- Slimscroll JavaScript -->
    <script src="<?php echo e(asset('version_2/dist/js/jquery.slimscroll.js')); ?>"></script>

    <!-- Fancy Dropdown JS -->
    <script src="<?php echo e(asset('version_2/dist/js/dropdown-bootstrap-extended.js')); ?>"></script>

    <!-- FeatherIcons JavaScript -->
    <script src="<?php echo e(asset('version_2/dist/js/feather.min.js')); ?>"></script>

    <!-- Toggles JavaScript -->
    <script src="<?php echo e(asset('version_2/vendors/jquery-toggles/toggles.min.js')); ?>"></script>
    <script src="<?php echo e(asset('version_2/dist/js/toggle-data.js')); ?>"></script>

    <!-- Toastr JS -->
    <script src="<?php echo e(asset('version_2/vendors/jquery-toast-plugin/dist/jquery.toast.min.js')); ?>"></script>

    <!-- Counter Animation JavaScript -->
    <script src="<?php echo e(asset('version_2/vendors/waypoints/lib/jquery.waypoints.min.js')); ?>"></script>
    <script src="<?php echo e(asset('version_2/vendors/jquery.counterup/jquery.counterup.min.js')); ?>"></script>

    <!-- Morris Charts JavaScript -->
    <script src="<?php echo e(asset('version_2/vendors/raphael/raphael.min.js')); ?>"></script>
    <script src="<?php echo e(asset('version_2/vendors/morris.js/morris.min.js')); ?>"></script>

    <!-- Easy pie chart JS -->
    <script src="<?php echo e(asset('version_2/vendors/easy-pie-chart/dist/jquery.easypiechart.min.js')); ?>"></script>

    <!-- FLOT CHARTS -->
    <script src="<?php echo e(asset('bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.js')); ?>"></script>
    <script src="<?php echo e(asset('bower_components/bootstrap-daterangepicker/daterangepicker.js')); ?>"></script>

    <script src="<?php echo e(asset('bower_components/Flot/jquery.flot.js')); ?>"></script>
    <!-- FLOT RESIZE PLUGIN - allows the chart to redraw when the window is resized -->
    <script src="<?php echo e(asset('bower_components/Flot/jquery.flot.resize.js')); ?>"></script>
    <!-- FLOT PIE PLUGIN - also used to draw donut charts -->
    <script src="<?php echo e(asset('bower_components/Flot/jquery.flot.pie.js')); ?>"></script>
    <!-- FLOT CATEGORIES PLUGIN - Used to draw bar charts -->
    <script src="<?php echo e(asset('bower_components/Flot/jquery.flot.categories.js')); ?>"></script>
    <script src="<?php echo e(asset('bower_components/jquery-knob/js/jquery.knob.js')); ?>"></script>

    <!-- EChartJS JavaScript -->
    <script src="<?php echo e(asset('version_2/vendors/echarts/dist/echarts-en.min.js')); ?>"></script>

   
    <!-- Select2 JavaScript -->
     <script src="<?php echo e(asset('version_2/vendors/select2/dist/js/select2.full.min.js')); ?>"></script>
   <!--  <script src="<?php echo e(asset('version_2/dist/js/select2-data.js')); ?>"></script> -->

  

    <!-- ChartJS -->
    <script src="<?php echo e(asset('bower_components/chart.js/Chart.js')); ?>"></script>

    <!-- Ion JavaScript -->
    <script src="<?php echo e(asset('version_2/vendors/ion-rangeslider/js/ion.rangeSlider.min.js')); ?>"></script>
    <script src="<?php echo e(asset('version_2/dist/js/rangeslider-data.js')); ?>"></script>
    <!-- Daterangepicker JavaScript -->
    <script src="<?php echo e(asset('version_2/vendors/moment/min/moment.min.js')); ?>"></script>
    
    <script src="<?php echo e(asset('version_2/vendors/daterangepicker/daterangepicker.js')); ?>"></script>
    <script src="<?php echo e(asset('version_2/dist/js/daterangepicker-data.js')); ?>"></script>

    <!-- Waiting Me Loading JavaScript -->
    <script src="<?php echo e(asset('plugins/Loading-overlay/waitMe.min.js')); ?>"></script>
    

    <!-- scrollable -->
    <script src="<?php echo e(asset('version_2/dist/js/jquery-scrolltofixed-min.js')); ?>"></script>

    

     <!-- Comment if need original input-->
    <script src="<?php echo e(asset('js/jquery.mask.js')); ?>"></script>
    <script src="<?php echo e(asset('js/i_mask.js')); ?>?ver=<?php echo e(App\Setting::version()); ?>"></script>

    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>
    <!-- Cropper 
    <script src="<?php echo e(asset('version_2/vendors/cropperjs/dist/cropper.min.js')); ?>"></script>
    <script src="<?php echo e(asset('version_2/dist/js/cropperjs-data.js')); ?>"></script>
    -->
    <!-- Init JavaScript -->
    <script src="<?php echo e(asset('version_2/dist/js/init.js')); ?>"></script>
    <script src="<?php echo e(asset('version_2/dist/js/dashboard2-data.js')); ?>"></script>

    <script src="<?php echo e(asset('plugins/multi-select-checkbox/jquery.multiselect.js')); ?>"></script>

    <script src="<?php echo e(asset('js/'.App\Setting::UI_VERSION_UI.'/power_meter_backend_operate.js')); ?>?ver=<?php echo e(App\Setting::version()); ?>"></script>
    <script src="<?php echo e(asset('js/'.App\Setting::UI_VERSION_UI.'/main_route.js')); ?>?ver=<?php echo e(App\Setting::version()); ?>"></script>
    <script src="<?php echo e(asset('js/'.App\Setting::UI_VERSION_UI.'/main_init_ui.js')); ?>?ver=<?php echo e(App\Setting::version()); ?>"></script>
    <script src="<?php echo e(asset('js/'.App\Setting::UI_VERSION_UI.'/main_app_e_commerce.js')); ?>?ver=<?php echo e(App\Setting::version()); ?>"></script>
    <script type="text/javascript" src="//wurfl.io/wurfl.js"></script>

    <script src="<?php echo e(asset('version_2/vendors/owl.carousel/dist/owl.carousel.min.js')); ?>"></script>


<script>

  var decimalPoint  = "2";
  var getUserDailyUsageUrl = "<?php echo e(action('AppsUtilityChargesController@getUserDailyLineChart')); ?>";
  var getIETransactionSummaryUrl = "<?php echo e(action('AppsIETransactionsController@getIETransactionSummary')); ?>";
  var stringPleaseWait = "<?php echo e(App\Language::trans('Please wait...')); ?>";
  var confirmMsg = "<?php echo e(App\Language::trans('Are you sure?')); ?>";
  var setLanguageUrl = "<?php echo e(action('LanguagesController@getLanguage')); ?>";
  var getNewVisitLogUrl = "<?php echo e(action('AppsUtilityChargesController@getNewVisitLog')); ?>";
  var getUserMonthlyUsageUrl = "<?php echo e(action('AppsUtilityChargesController@getUserMonthlyUsage')); ?>";
  var roomsComboboxUrl = "<?php echo e(action('UMeterRegistersController@getCombobox')); ?>";
  var generatePowerMeterAccountUrl = "<?php echo e(action('AppsUtilityChargesController@getNewUserAccount')); ?>";
  <?php echo $__env->yieldContent('script'); ?>

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
