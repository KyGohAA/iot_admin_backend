
<!DOCTYPE html>
<!-- 
Template Name: Griffin - Responsive Bootstrap 4 Admin Dashboard Template
Author: Hencework
Support: support@hencework.com

License: You must have a valid license purchased only from templatemonster to legally use the template for your project.
-->
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <title>{{isset($page_variable['page_title']) ? $page_variable['page_title'] : (isset($page_title) ? $page_title : "")}}</title>
    <meta name="description" content="A responsive bootstrap 4 admin dashboard template by hencework" />

    <!-- Favicon -->
    <link rel="shortcut icon" href="favicon.ico">
    <link rel="icon" href="favicon.ico" type="image/x-icon">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{asset('version_2/dist/css/style.css')}}">
</head>

<body>
    <!-- Preloader -->
    <div class="preloader-it">
        <div class="loader-pendulums"></div>
    </div>
    <!-- /Preloader -->

    <!-- HK Wrapper -->
    <div class="hk-wrapper">

        <!-- Main Content -->
        <div class="hk-pg-wrapper hk-auth-wrapper">
            <header class="d-flex justify-content-end align-items-center">
                <div class="btn-group btn-group-sm">
                    <a href="#" class="btn btn-outline-secondary">Help</a>
                    
                </div>
            </header>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-xl-12 pa-0">
                        <div class="auth-form-wrap pt-xl-0 pt-70">
                            <div class="auth-form w-xl-25 w-sm-50 w-100">
                                <a class="auth-brand text-center d-block mb-45" href="#">
                                    <img class="brand-img" src="{{asset('_app_icon.png')}}" alt="brand" style="width:100px; height:200px;" />
                                </a>
                                    <h1 class="display-4 mb-10 text-center">{{$datas['error_code']}} - {{$datas['message']}}</h1>
                                    <p class="mb-30 text-center">{{App\Language::trans('Meanwhile, you may ')}}<a href="{{action('DashboardsController@getDashboard')}}">{{App\Language::trans('return to dashboard')}}.</a></p>                                    
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Main Content -->

    </div>
    <!-- /HK Wrapper -->

    <!-- jQuery -->
    <script src="{{asset('version_2/vendors/jquery/dist/jquery.min.js')}}"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="{{asset('version_2/vendors/popper.js/dist/umd/popper.min.js')}}"></script>
    <script src="{{asset('version_2/vendors/bootstrap/dist/js/bootstrap.min.js')}}"></script>

    <!-- Slimscroll JavaScript -->
    <script src="{{asset('version_2/dist/js/jquery.slimscroll.js')}}"></script>

    <!-- Fancy Dropdown JS -->
    <script src="{{asset('version_2/dist/js/dropdown-bootstrap-extended.js')}}"></script>

    <!-- FeatherIcons JavaScript -->
    <script src="{{asset('version_2/dist/js/feather.min.js')}}"></script>

    <!-- Init JavaScript -->
    <script src="{{asset('version_2/dist/js/init.js')}}"></script>
</body>

</html>

