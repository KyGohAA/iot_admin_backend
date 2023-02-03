<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <title><?php echo e(isset($page_variables['page_title']) ? $page_variables['page_title'] : $page_title); ?></title>
    <meta name="description" content="A responsive bootstrap 4 admin dashboard template by hencework" />

    <!-- Favicon -->
    <link rel="shortcut icon" href="<?php echo e(asset('favicon.ico')); ?>">
    <link rel="icon" href="<?php echo e(asset('favicon.ico')); ?>" type="image/x-icon">
    
     <!-- Morris Charts CSS -->
    <link rel="stylesheet" href="<?php echo e(asset('version_2/vendors/morris.js/morris.css')); ?>">
  
    <!-- jquery-steps css -->
    <link rel="stylesheet" href="<?php echo e(asset('version_2/vendors/jquery-steps/demo/css/jquery.steps.css')); ?>">

   
    <!-- Toggles CSS -->
    <link rel="stylesheet" href="<?php echo e(asset('version_2/vendors/jquery-toggles/css/toggles.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('version_2/vendors/jquery-toggles/css/themes/toggles-light.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('version_2/vendors/tablesaw/dist/tablesaw.css')); ?>">

    <!-- Data Table CSS -->
    <link rel="stylesheet" href="<?php echo e(asset('version_2/vendors/datatables.net-dt/css/jquery.dataTables.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('version_2/vendors/datatables.net-responsive-dt/css/responsive.dataTables.min.css')); ?>">

    <!-- Toastr CSS -->
    <link rel="stylesheet" href="<?php echo e(asset('version_2/vendors/jquery-toast-plugin/dist/jquery.toast.min.css')); ?>">

    <!-- select2 CSS -->
    <link rel="stylesheet" href="<?php echo e(asset('version_2/vendors/select2/dist/css/select2.min.css')); ?>">

     <!-- Cropperjs CSS -->
    <link rel="stylesheet" href="<?php echo e(asset('version_2/vendors/cropperjs/dist/cropper.min.css')); ?>">


    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?php echo e(asset('version_2/dist/css/style.css')); ?>?ver=<?php echo e(App\Setting::version()); ?>"></script>

    <!-- wait me loading css -->
    <link rel="stylesheet" href="<?php echo e(asset('plugins/Loading-overlay/waitMe.min.css')); ?>">
         <!-- Pickr CSS -->
    <link rel="stylesheet" href="<?php echo e(asset('version_2/vendors/pickr-widget/dist/pickr.min.css')); ?>">

    <!-- Daterangepicker CSS -->
    <link rel="stylesheet" href="<?php echo e(asset('version_2/vendors/daterangepicker/daterangepicker.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('version_2/dist/css/leaf/e-commerce.css')); ?>">


    <!-- Mobile App Footer CSS -->
    <link rel="stylesheet" href="<?php echo e(asset('leaf_acconting_mobile/css/new.css')); ?>">

        
    <link rel="stylesheet" href="<?php echo e(asset('version_2/vendors/fontawesome-free-5.15.1-web/css/fontawesome.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('version_2/vendors/fontawesome-free-5.15.1-web/css/brands.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('version_2/vendors/fontawesome-free-5.15.1-web/css/solid.css')); ?>">
</head>
    
</head>
<body id="overlay">
    
    <!-- Preloader -->
    <div class="preloader-it">
        <div class="loader-pendulums"></div>
    </div>
    <!-- /Preloader -->
    
    <!-- HK Wrapper -->
    <div class="hk-wrapper hk-vertical-nav">
