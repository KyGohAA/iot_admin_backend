@include('_version_02.commons.layouts.partials._header')
@include('_version_02.commons.layouts.partials._left_sidebar')
    <!-- Main Content -->
    <div class="hk-pg-wrapper">

    	<!-- Container mt-xl-50 mt-sm-30-->
        <div class="container-fluid  mt-5 content">
        	<!-- Breadcrumb -->
    			<!-- Title -->
    			
    			<!-- /Title -->
                <!-- /Breadcrumb -->
                <div class="row">
                    <div class="col-xl-12" id='main_content'>
                              @include('_version_02.commons.layouts.partials._content_title')  
          				      @yield('content')
          			</div>
          		 </div>
        </div>
        <!-- /Container -->
        @include('_version_02.commons.layouts.partials._content_footer')
        <!-- /Footer -->
    </div>
    <!-- /Main Content -->
@include('_version_02.commons.layouts.partials._footer')
@include('_version_02.commons.layouts.partials._table_plugin')
@include('_version_02.commons.layouts.partials._datatable_plugin')