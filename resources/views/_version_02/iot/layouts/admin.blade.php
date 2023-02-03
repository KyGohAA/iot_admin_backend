@include('_version_02.iot.layouts.partials._header')
@include('_version_02.iot.layouts.partials._left_sidebar')
    <!-- Main Content -->
     <div id="main-content">
        <div class="container-fluid">
            <div class="block-header">
                <!-- <div class="row">
                    <div class="col-lg-6 col-md-8 col-sm-12">
                        <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a>Dashboard</h2>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.html"><i class="icon-home"></i></a></li>
                            <li class="breadcrumb-item active">Dashboard</li>
                        </ul>
                    </div>            
                </div> -->
                @yield('content')
            </div>       
        </div>
    </div>
    <!-- /Main Content -->
@include('_version_02.iot.layouts.partials._footer')
@include('_version_02.iot.layouts.partials._table_plugin')
@include('_version_02.iot.layouts.partials._datatable_plugin')