<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <title>{{isset($page_variables['page_title']) ? $page_variables['page_title'] : $page_title}}</title>
    <meta name="description" content="A responsive bootstrap 4 admin dashboard template by hencework" />

    <!-- Favicon -->
    <link rel="shortcut icon" href="{{asset('favicon.ico')}}">
    <link rel="icon" href="{{asset('favicon.ico')}}" type="image/x-icon">
    
     <!-- Morris Charts CSS -->
    <link rel="stylesheet" href="{{asset('version_2/vendors/morris.js/morris.css')}}">
  
    <!-- jquery-steps css -->
    <link rel="stylesheet" href="{{asset('version_2/vendors/jquery-steps/demo/css/jquery.steps.css')}}">

   
    <!-- Toggles CSS -->
    <link rel="stylesheet" href="{{asset('version_2/vendors/jquery-toggles/css/toggles.css')}}">
    <link rel="stylesheet" href="{{asset('version_2/vendors/jquery-toggles/css/themes/toggles-light.css')}}">
    <link rel="stylesheet" href="{{asset('version_2/vendors/tablesaw/dist/tablesaw.css')}}">

    <!-- Data Table CSS -->
    <link rel="stylesheet" href="{{asset('version_2/vendors/datatables.net-dt/css/jquery.dataTables.min.css')}}">
    <link rel="stylesheet" href="{{asset('version_2/vendors/datatables.net-responsive-dt/css/responsive.dataTables.min.css')}}">

    <!-- Toastr CSS -->
    <link rel="stylesheet" href="{{asset('version_2/vendors/jquery-toast-plugin/dist/jquery.toast.min.css')}}">

    <!-- select2 CSS -->
    <link rel="stylesheet" href="{{asset('version_2/vendors/select2/dist/css/select2.min.css')}}">

     <!-- Cropperjs CSS -->
    <link rel="stylesheet" href="{{asset('version_2/vendors/cropperjs/dist/cropper.min.css')}}">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{asset('version_2/dist/css/style.css')}}">

    <!-- wait me loading css -->
    <link rel="stylesheet" href="{{asset('plugins/Loading-overlay/waitMe.min.css')}}">
         <!-- Pickr CSS -->
    <link rel="stylesheet" href="{{asset('version_2/vendors/pickr-widget/dist/pickr.min.css')}}">

    <!-- Daterangepicker CSS -->
    <link rel="stylesheet" href="{{asset('version_2/vendors/daterangepicker/daterangepicker.css')}}">
    <link rel="stylesheet" href="{{asset('version_2/dist/css/leaf/e-commerce.css')}}">
   
    
</head>
<body id="overlay">
    
    <!-- Preloader -->
    <div class="preloader-it">
        <div class="loader-pendulums"></div>
    </div>
    <!-- /Preloader -->
    
    <!-- HK Wrapper -->
    <div class="hk-wrapper hk-vertical-nav">

        <!-- Top Navbar -->
        <nav class="navbar navbar-expand-xl navbar-light fixed-top hk-navbar">
           
            <span class="navbar-brand"> {{isset($page_variables['page_title']) ? $page_variables['page_title'] : $page_title}} </span>


            @php
                $notification_listing = App\Setting::get_notification_by_leaf_group_id();
            @endphp   
            <ul class="navbar-nav hk-navbar-content">
                <!-- <li class="nav-item">
                    <a id="navbar_search_btn" class="nav-link nav-link-hover" href=""><span class="feather-icon"><i data-feather="search"></i></span></a>
                </li>
                <li class="nav-item">
                    <a id="settings_toggle_btn" class="nav-link nav-link-hover" href=""><span class="feather-icon"><i data-feather="settings"></i></span></a>
                </li> -->
                <li class="nav-item dropdown dropdown-notifications">
                    <a class="nav-link dropdown-toggle no-caret" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span class="feather-icon"><i data-feather="bell"></i></span>
                        @if(count($notification_listing) > 0)
                             <span class="badge-wrap"><span class="badge badge-primary badge-indicator badge-indicator-sm badge-pill pulse"></span></span>
                        @endif
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" data-dropdown-in="fadeIn" data-dropdown-out="fadeOut">
                        <h6 class="dropdown-header">Notifications <!-- <a href="" class="">View all</a> --></h6>
                        <div class="notifications-nicescroll-bar"> 
                         
                        @foreach($notification_listing as $row)      
                            <a href="" class="dropdown-item">
                                <div class="media">
                                    <div class="media-img-wrap">
                                        <div class="avatar avatar-sm">
                                            <span class="avatar-text avatar-text-primary rounded-circle">
                                                    <span class="initial-wrap"><span><i class="zmdi zmdi-account font-18"></i></span></span>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="media-body">
                                        <div>
                                            <div class="notifications-text">{{$row}}</div>
                                            <div class="notifications-time">{{date('Y-m-d', strtotime('now'))}}</div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                            <!-- <div class="dropdown-divider"></div> -->
                        
                        </div>
                    </div>
                </li>
               
            </ul>
        </nav>
       
        <!-- /Top Navbar -->