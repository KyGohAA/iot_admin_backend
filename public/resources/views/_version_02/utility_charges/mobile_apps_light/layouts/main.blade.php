@include('_version_02.utility_charges.mobile_apps_light.layouts.partials._header')
@include('_version_02.utility_charges.mobile_apps_light.layouts.partials._left_sidebar')
    <!-- Main Content -->
    <div class="hk-pg-wrapper">

    	<!-- Container -->
        <!-- mt-xl-50 mt-sm-30 mt-15  -->
        <div class="container-fluid content" style="margin-top:-40px;">
        	<!-- Breadcrumb -->
    			<!-- Title -->
    			
    			<!-- /Title -->
                <!-- /Breadcrumb -->
                <div class="row">
                    <div class="col-xl-12" id='main_content'>
          				      @yield('content')
          			</div>
          		 </div>
        </div>
        <!-- /Container -->
       
        <!-- /Footer -->
    </div>
    <!-- /Main Content -->
@include('_version_02.utility_charges.mobile_apps_light.layouts.partials._footer')
@include('_version_02.utility_charges.mobile_apps_light.layouts.partials._table_plugin')
@include('_version_02.utility_charges.mobile_apps_light.layouts.partials._datatable_plugin')