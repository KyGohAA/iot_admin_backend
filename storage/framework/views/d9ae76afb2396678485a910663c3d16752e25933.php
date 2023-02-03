<!-- FOOTER  -->
<footer id="footer">
<div class="container">


<?php if(isset($is_synchorizing_data) && $is_synchorizing_data == true): ?>
  
         <div class="row copyright">
             <ul class="box-set" style="background: rgb(92,92,92);
                background: linear-gradient(198deg, rgba(92,92,92,0.8911939775910365) 0%, rgba(189,189,189,0.3309698879551821) 1%, rgba(55,44,44,0) 12%, rgba(176,176,176,0) 27%, rgba(119,119,119,0.17970938375350143) 97%);z-index: auto; position: fixed; bottom: 0px; left: 0px; width:100%;padding-bottom:%;">
                                <li class="box" style="margin:0;width:100%;text-align:center;box-sizing:border-box"><a onclick=" init_loading_overlay();" class="nav-link" href="<?php echo e(action('AppsUtilityChargesController@getDashboard')); ?>?session_token=<?php echo e($session_token); ?>"><i class="material-icons">home</i><br><font size="1">Home</font></a></li>
                              
                            </ul>

                      </div>  
<?php elseif(!isset($is_house_keeping)): ?>
     <div class="row copyright"  style=" opacity: 1">
             <ul class="box-set" style="background: rgb(92,92,92);
background: linear-gradient(198deg, rgba(92,92,92,0.8911939775910365) 0%, rgba(189,189,189,0.3309698879551821) 1%, rgba(55,44,44,0) 12%, rgba(176,176,176,0) 27%, rgba(119,119,119,0.17970938375350143) 97%);z-index: auto; position: fixed; bottom: 0px; left: 0px; width:100%;padding-bottom:%;">
                <li class="box" style="margin:0;width:25%;text-align:center;box-sizing:border-box"><a onclick=" init_loading_overlay();" class="nav-link" href="<?php echo e(action('AppsUtilityChargesController@getDashboard')); ?>?session_token=<?php echo e($session_token); ?>"><i class="material-icons">home</i><br><font size="1">Home</font></a></li>
                <li  class="box" style="margin:0;width:25%;text-align:center;box-sizing:border-box"><a onclick=" init_loading_overlay();" class="nav-link" href="<?php echo e(action('AppsUtilityChargesController@getTransactionListing')); ?>?session_token=<?php echo e($session_token); ?>" ><i class="material-icons">event</i><br><font size="1">Transaction</font></a></li>
                <li  class="box" style="margin:0;width:25%;text-align:center;box-sizing:border-box"><a onclick=" init_loading_overlay();" class="nav-link" href="<?php echo e(action('AppsUtilityChargesController@getTopUp')); ?>?session_token=<?php echo e($session_token); ?>&amount=2" ><i class="material-icons"></i><br><font size="1">Top Up</font></a></li>
                <li  class="box" style="margin:0;width:25%;text-align:center;box-sizing:border-box"><a onclick=" init_loading_overlay();" class="nav-link" href="<?php echo e(action('AppsUtilityChargesController@getHelp')); ?>?session_token=<?php echo e($session_token); ?>" ><i class="material-icons">account_box</i><br><font size="1">FAQ</font></a></li>
            </ul>

      </div>  

<?php else: ?>
<!-- background-color:#fff; -->
 <div class="row copyright" style=" opacity: 1">
         <ul class="box-set" style="background: rgb(92,92,92);
background: linear-gradient(198deg, rgba(92,92,92,0.8911939775910365) 0%, rgba(189,189,189,0.3309698879551821) 1%, rgba(55,44,44,0) 12%, rgba(176,176,176,0) 27%, rgba(119,119,119,0.17970938375350143) 97%);z-index: auto; position: fixed; bottom: 0px; left: 0px; width:100%;padding-bottom:%;">
            <li class="box" style="margin:0;width:33%;text-align:center;box-sizing:border-box"><a onclick=" init_loading_overlay();" class="nav-link" href="<?php echo e(action('AppsUtilityChargesController@getHouseKeepingDashboard')); ?>?session_token=<?php echo e($session_token); ?>"><i class="material-icons">home</i><br><font size="1">Home</font></a></li>
            <li  class="box" style="margin:0;width:33%;text-align:center;box-sizing:border-box"><a onclick=" init_loading_overlay();" class="nav-link" href="<?php echo e(action('AppsUtilityChargesController@getHouseKeepingHistory')); ?>?session_token=<?php echo e($session_token); ?>" ><i class="material-icons">event</i><br><font size="1">History</font></a></li>   
            <li  class="box" style="margin:0;width:33%;text-align:center;box-sizing:border-box"><a onclick=" init_loading_overlay();" class="nav-link" href="<?php echo e(action('AppsUtilityChargesController@getHelpHouseKeeping')); ?>?session_token=<?php echo e($session_token); ?>" ><i class="material-icons">account_box</i><br><font size="1">FAQ</font></a></li>
        </ul>

  </div>  
<?php endif; ?>




</div>
</footer>



<!-- jQuery 3 -->
<script src="<?php echo e(asset('bower_components/jquery/dist/jquery.min.js')); ?>"></script>
<script src="<?php echo e(asset('leaf_acconting_mobile/js/materialize.min.js')); ?>"></script>
<script src="<?php echo e(asset('bower_components/bootstrap/dist/js/bootstrap.min.js')); ?>"></script>


 

<!-- Owl carousel -->
<script src="<?php echo e(asset('leaf_acconting_mobile/lib/owlcarousel/owl.carousel.min.js')); ?>"></script>
<!-- Magnific Popup core JS file -->
<script src="<?php echo e(asset('leaf_acconting_mobile/lib/Magnific-Popup-master/dist/jquery.magnific-popup.js')); ?>"></script>
<!-- Slick JS -->
<script src="<?php echo e(asset('leaf_acconting_mobile/lib/slick/slick/slick.min.js')); ?>"></script>
<!-- Custom script -->
<script src="<?php echo e(asset('leaf_acconting_mobile/js/custom.js')); ?>"></script>
<script src="<?php echo e(asset('js/_version_02/main_leaf_accounting_mobile_apps.js')); ?>?ver=<?php echo e(App\Setting::version()); ?>"></script>
<script src="<?php echo e(asset('js/_version_02/power_meter_backend_operate.js')); ?>?ver=<?php echo e(App\Setting::version()); ?>"></script>
<script src="<?php echo e(asset('plugins/Loading-overlay/waitMe.min.js')); ?>"></script>
<!-- FeatherIcons JavaScript -->
<script src="<?php echo e(asset('version_2/dist/js/feather.min.js')); ?>"></script>


 <!-- Slimscroll JavaScript -->
    <script src="<?php echo e(asset('version_2/dist/js/jquery.slimscroll.js')); ?>"></script>

<!-- ChartJS
<script src="<?php echo e(asset('bower_components/Chart.js/Chart.js')); ?>"></script> -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.min.js"></script>

<!-- Daterangepicker JavaScript -->
<script src="<?php echo e(asset('version_2/vendors/moment/min/moment.min.js')); ?>"></script>

<script src="<?php echo e(asset('version_2/vendors/daterangepicker/daterangepicker.js')); ?>"></script>
<script src="<?php echo e(asset('version_2/dist/js/daterangepicker-data_2.js')); ?>"></script>

<script src="https://cdn.jsdelivr.net/npm/jquery-editable-select@2.2.5/dist/jquery-editable-select.min.js"></script>
 <script src="<?php echo e(asset('version_2/dist/js/init.js')); ?>"></script>

<script type="text/javascript" src="//wurfl.io/wurfl.js"></script>



<script defer src="<?php echo e(asset('version_2/vendors/fontawesome-free-5.15.1-web/js/brands.js')); ?>"></script>
<script defer src="<?php echo e(asset('version_2/vendors/fontawesome-free-5.15.1-web/js/solid.js')); ?>"></script>
<script defer src="<?php echo e(asset('version_2/vendors/fontawesome-free-5.15.1-web/js/fontawesome.js')); ?>"></script>


<script>

  var decimalPoint  = "2";
  var getUserDailyUsageUrl = "<?php echo e(action('AppsUtilityChargesController@getUserDailyLineChart')); ?>";
  var getIETransactionSummaryUrl = "<?php echo e(action('AppsIETransactionsController@getIETransactionSummary')); ?>";
  var stringPleaseWait = "<?php echo e(App\Language::trans('Please wait...')); ?>";
  var confirmMsg = "<?php echo e(App\Language::trans('Are you sure?')); ?>";
  var setLanguageUrl = "<?php echo e(action('LanguagesController@getLanguage')); ?>";
  var getUserPowerMeterAccountSummaryDataUrl = "<?php echo e(action('AppsUtilityChargesController@getUserPowerMeterAccountSummaryData')); ?>";
  var getNewVisitLogUrl = "<?php echo e(action('AppsUtilityChargesController@getNewVisitLog')); ?>";
  var roomsComboboxUrl = "<?php echo e(action('UMeterRegistersController@getCombobox')); ?>";

  
<?php echo $__env->yieldContent('script'); ?>
  
    var url = window.location.href;
     $('.nav-link').click(function (event) {
          init_loading_overlay();
      });


     $('.nav-link-attach').click(function (event) {

         input = document.getElementById('receipt_filename');
         if (input){
          
          if(input.files[0]){
            init_loading_overlay();
           }else{
            if(url.includes('/apps/ietransactions/income')){
              init_loading_overlay();
            }
           }
         }else{
            
         }
      });

</script>

</body>
</html>
