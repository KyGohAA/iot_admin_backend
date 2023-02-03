<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>{{$page_title}}</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="{{asset('bower_components/bootstrap/dist/css/bootstrap.min.css')}}?ver={{App\Setting::version()}}">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{asset('bower_components/font-awesome/css/font-awesome.min.css')}}?ver={{App\Setting::version()}}">
  <!-- Ionicons -->
  <link rel="stylesheet" href="{{asset('bower_components/Ionicons/css/ionicons.min.css')}}?ver={{App\Setting::version()}}">

  <link rel="stylesheet" href="{{asset('bower_components/morris.js/morris.css')}}?ver={{App\Setting::version()}}">

 
  <!-- Theme style -->
  <link rel="stylesheet" href="{{asset('css/AdminLTE.min.css')}}?ver={{App\Setting::version()}}">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="{{asset('css/skins/_all-skins.min.css')}}?ver={{App\Setting::version()}}">
  <link rel="stylesheet" href="{{asset('css/main.css')}}?ver={{App\Setting::version()}}">
  <link rel="stylesheet" href="{{asset('css/progress_stepper.css')}}?ver={{App\Setting::version()}}">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  

  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

   <style>
        .div_background_3 {
            background: #d2d6de;
            background-image: url("http://www.schindlerroofing.com/wp-content/uploads/2018/03/car-parking-1024x498.jpg");
             /* Full height */
            height: 25%; 

            /* Center and scale the image nicely */
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
          }


           .div_background_2 {
            background: #d2d6de;
            background-image: url("https://www.travaux-maison.net/wp-content/uploads/2018/03/badminton-jardin.png");
             /* Full height */
            height: 25%; 

            /* Center and scale the image nicely */
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
          }

           .div_background_1 {
            background: #d2d6de;
            background-image: url("http://www.amsires.com/images/property-leasing-management.jpg");
             /* Full height */
            height: 25%; 

            /* Center and scale the image nicely */
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
          }
        
        
     
    </style>



</head>
<body class="hold-transition skin-blue sidebar-mini">
<!-- Site wrapper -->


