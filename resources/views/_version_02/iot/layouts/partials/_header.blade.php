<!doctype html>
<html lang="en"
<head>
<title>LEAF SMART IOT</title>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
<meta name="description" content="Lucid Bootstrap 4.1.1 Admin Template">
<meta name="author" content="WrapTheme, design by: ThemeMakker.com">

<link rel="icon" href="favicon.ico" type="image/x-icon">
<!-- VENDOR CSS -->
<link rel="stylesheet" href="{{asset('version_2/iot/assets/vendor/bootstrap/css/bootstrap.min.css')}}">
<link rel="stylesheet" href="{{asset('version_2/iot/assets/vendor/font-awesome/css/font-awesome.min.css')}}">
<link rel="stylesheet" href="{{asset('version_2/iot/assets/vendor/jvectormap/jquery-jvectormap-2.0.3.min.css')}}">
<link rel="stylesheet" href="{{asset('version_2/iot/assets/vendor/jquery-datatable/dataTables.bootstrap4.min.css')}}">
<!-- <link rel="stylesheet" href="{{asset('version_2/iot/assets/vendor/chartist/css/chartist.min.css')}}"> -->


<link rel="stylesheet" href="{{asset('version_2/iot/assets/vendor/nestable/jquery-nestable.css')}}">
<link rel="stylesheet" href="{{asset('version_2/iot/assets/vendor/sweetalert/sweetalert.css')}}">
<link rel="stylesheet" href="{{asset('version_2/iot/assets/vendor/bootstrap-datepicker/css/bootstrap-datepicker3.min.css')}}">


 <link rel="stylesheet" href="{{asset('version_2/vendors/morris.js/morris.css')}}">

<!-- Morris Charts CSS -->
<link rel="stylesheet" href="{{asset('version_2/vendors/morris.js/morris.css')}}">

<!-- MAIN CSS -->
<link rel="stylesheet" href="{{asset('version_2/iot/assets/css/main.css')}}">

<link rel="stylesheet" href="{{asset('version_2/iot/assets/css/light/main.css')}}">

<link rel="stylesheet" href="{{asset('version_2/iot/assets/css/color_skins.css')}}">
</head>
<body class="theme-blue">

<!-- Page Loader -->
<div class="page-loader-wrapper">
    <div class="loader">
        <div class="m-t-30"><img src="{{isset($company['system_logo_path']) ? asset($company['system_logo_path']) : asset('_app_icon.png')}}" width="48" height="48" alt="Lucid"></div>
        <p>Loading ...</p>
    </div>
</div>
<!-- Overlay For Sidebars -->

<div id="wrapper">

    <nav class="navbar navbar-fixed-top">
        <div class="container-fluid">
            <div class="navbar-btn">
                <button type="button" class="btn-toggle-offcanvas"><i class="lnr lnr-menu fa fa-bars"></i></button>
            </div>

            <div class="navbar-brand">
                <a href="{{action('IOTUniversalsController@getDashboard')}}"><img style="width:40px; height:40px" src="{{isset($company['system_logo_path']) ? asset($company['system_logo_path']) : asset('_app_icon.png')}}" alt="Lucid Logo" class="img-responsive logo"></a><label style='padding-left:10px;padding-top:7px;'>LEAF SMART IOT</label>
            </div>
            
            
            <div class="navbar-right">
                <!-- <form id="navbar-search" class="navbar-form search-form">
                    <input value="" class="form-control" placeholder="Search here..." type="text">
                    <button type="button" class="btn btn-default"><i class="icon-magnifier"></i></button>
                </form> -->

                

                <div id="navbar-menu">
                    <ul class="nav navbar-nav">
                        
                        <li>
                            <a href="{{action('OpencartUsersController@getLogout')}}" class="icon-menu"><i class="icon-login"></i></a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>