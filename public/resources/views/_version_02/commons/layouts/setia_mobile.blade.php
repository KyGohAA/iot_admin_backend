@include('_version_02.commons.layouts.setia_partials._header')
    <!-- Main Content -->
    <div class="hk-pg-wrapper">

    	<!-- Container -->
        <div class="container-fluid mt-xl-50 mt-sm-30 mt-15 content">
        	<!-- Breadcrumb -->
    			<!-- Title -->
    			<!-- @include('_version_02.commons.layouts.partials._content_title') -->
    			<!-- /Title -->
                <!-- /Breadcrumb -->
                <div class="row">
                    <div class="col-xl-12" id='main_content'>
          				      @yield('content')
          			</div>
          		 </div>
        </div>
        <!-- /Container -->
        @include('_version_02.commons.layouts.setia_partials._content_footer')
        <!-- /Footer -->
    </div>
    <!-- /Main Content -->
@include('_version_02.commons.layouts.setia_partials._footer')
