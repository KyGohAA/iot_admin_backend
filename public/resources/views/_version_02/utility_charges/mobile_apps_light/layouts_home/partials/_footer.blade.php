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
</footer>



<!-- jQuery 3 -->
<script src="{{asset('bower_components/jquery/dist/jquery.min.js')}}"></script>
<script src="{{asset('leaf_acconting_mobile/js/materialize.min.js')}}"></script>
<script src="{{asset('bower_components/bootstrap/dist/js/bootstrap.min.js')}}"></script>


<!-- Owl carousel -->
<script src="{{asset('leaf_acconting_mobile/lib/owlcarousel/owl.carousel.min.js')}}"></script>
<!-- Magnific Popup core JS file -->
<script src="{{asset('leaf_acconting_mobile/lib/Magnific-Popup-master/dist/jquery.magnific-popup.js')}}"></script>
<!-- Slick JS -->
<script src="{{asset('leaf_acconting_mobile/lib/slick/slick/slick.min.js')}}"></script>
<!-- Custom script -->
<script src="{{asset('leaf_acconting_mobile/js/custom.js')}}"></script>
<script src="{{asset('js/_version_02/main_leaf_accounting_mobile_apps.js')}}?ver={{App\Setting::version()}}"></script>
<script src="{{asset('js/_version_02/power_meter_backend_operate.js')}}?ver={{App\Setting::version()}}"></script>
<script src="{{asset('plugins/Loading-overlay/waitMe.min.js')}}"></script>
<!-- FeatherIcons JavaScript -->
<script src="{{asset('version_2/dist/js/feather.min.js')}}"></script>


 <!-- Slimscroll JavaScript -->
    <script src="{{asset('version_2/dist/js/jquery.slimscroll.js')}}"></script>

<!-- ChartJS
<script src="{{asset('bower_components/Chart.js/Chart.js')}}"></script> -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.min.js"></script>

<!-- Daterangepicker JavaScript -->
<script src="{{asset('version_2/vendors/moment/min/moment.min.js')}}"></script>

 <script src="{{asset('version_2/vendors/daterangepicker/daterangepicker.js')}}"></script>
<script src="{{asset('version_2/dist/js/daterangepicker-data_2.js')}}"></script>

<script src="https://cdn.jsdelivr.net/npm/jquery-editable-select@2.2.5/dist/jquery-editable-select.min.js"></script>
 <script src="{{asset('version_2/dist/js/init.js')}}"></script>




<script>

  var decimalPoint  = "2";
  var getUserDailyUsageUrl = "{{action('AppsUtilityChargesController@getUserDailyLineChart')}}";
  var getIETransactionSummaryUrl = "{{action('AppsIETransactionsController@getIETransactionSummary')}}";
  var stringPleaseWait = "{{App\Language::trans('Please wait...')}}";
  var confirmMsg = "{{App\Language::trans('Are you sure?')}}";
  var setLanguageUrl = "{{action('LanguagesController@getLanguage')}}";

@yield('script')
  
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
