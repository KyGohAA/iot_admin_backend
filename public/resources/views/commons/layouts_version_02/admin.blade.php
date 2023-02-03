@include('commons.layouts_version_02.partials._header')
@include('commons.layouts_version_02.partials._left_sidebar')
    <!-- Main Content -->
    <div class="hk-pg-wrapper">
    	<!-- Container -->
        <div class="container-fluid mt-xl-50 mt-sm-30 mt-15">
        	<!-- Breadcrumb -->
    			<!-- Title -->
    			@include('commons.layouts_version_02.partials._content_title')
    			<!-- /Title -->
                <!-- /Breadcrumb -->
                <div class="row">
                    <div class="col-xl-12">
          				      @yield('content')
          			   </div>
          		 </div>
        </div>
        <!-- /Container -->
        @include('commons.layouts_version_02.partials._content_footer')
        <!-- /Footer -->
    </div>
    <!-- /Main Content -->
@include('commons.layouts_version_02.partials._footer')