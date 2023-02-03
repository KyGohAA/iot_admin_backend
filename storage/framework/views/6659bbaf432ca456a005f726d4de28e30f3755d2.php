<!doctype html>
<html lang="en">

<head>
<title>:: Lucid University :: Login</title>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
<meta name="description" content="Lucid Bootstrap 4.1.1 Admin Template">
<meta name="author" content="WrapTheme, design by: ThemeMakker.com">

<link rel="icon" href="favicon.ico" type="image/x-icon">
<!-- VENDOR CSS -->
<link rel="stylesheet" href="<?php echo e(asset('version_2/iot/assets/vendor/bootstrap/css/bootstrap.min.css')); ?>">
<link rel="stylesheet" href="<?php echo e(asset('version_2/iot/assets/vendor/font-awesome/css/font-awesome.min.css')); ?>">

<!-- MAIN CSS -->
<link rel="stylesheet" href="<?php echo e(asset('version_2/iot/assets/css/main.css')); ?>">
<link rel="stylesheet" href="<?php echo e(asset('version_2/iot/assets/css/color_skins.css')); ?>">
</head>

<body class="theme-blue">
    <!-- WRAPPER -->
    <div id="wrapper">
        <div class="vertical-align-wrap">
            <div class="vertical-align-middle auth-main">
                <div class="auth-box">
                    <div class="top">
                        <img style='height:60px;width:60px;display: inline;margin-bottom:15px;' src="<?php echo e(isset($company['system_logo_path']) ? asset($company['system_logo_path']) : asset('_app_icon.png')); ?>" alt="Lucid">
                        <h2 style="display: inline;color: white;padding-left:15px;padding-top:15px;">Leaf Smart IOT</h2>
                    </div>
                    <div class="card">
                        <div class="header">
                            <p class="lead">Login to your account</p>
                        </div>
                        <div class="body">
                            <!-- <form class="form-auth-small" action="index.html"> -->
                            <?php echo Form::model($model, ['class'=>'form-auth-small']); ?>

                                <!-- <div class="form-group">
                                    <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Login For</button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="javascript:void(0);">Admin</a>
                                        <a class="dropdown-item" href="javascript:void(0);">Professors</a>
                                        <a class="dropdown-item" href="javascript:void(0);">Student</a>
                                    </div>
                                </div> -->
                                <?php if(session('status_level')): ?>
                                    <div class="alert alert-<?php echo e(session('status_level')); ?> alert-dismissible" role="alert">
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                        <?php echo e(session('status_msg')); ?>

                                    </div>
                                <?php endif; ?>
                                <div class="form-group">
                                    <label for="signin-email" class="control-label sr-only">Email</label>
                                    <input type="email" class="form-control" id="user_email" name="user_email" value="" placeholder="Email">
                                </div>
                                <div class="form-group">
                                    <label for="signin-password" class="control-label sr-only">Password</label>
                                    <input type="password" class="form-control" id="user_password" name="user_password" value="" placeholder="Password">
                                </div>
                                <div class="form-group clearfix">
                                    <label class="fancy-checkbox element-left">
                                        <input type="checkbox">
                                        <span>Remember me</span>
                                    </label>                                
                                </div>
                                <button type="submit" class="btn btn-primary btn-lg btn-block">LOGIN</button>
                                <!-- <div class="bottom">
                                    <span class="helper-text m-b-10"><i class="fa fa-lock"></i> <a href="page-forgot-password.html">Forgot password?</a></span>
                                    <span>Don't have an account? <a href="page-register.html">Register</a></span>
                                </div> -->
                            <!-- </form> -->
                            <?php echo Form::close(); ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END WRAPPER -->
    
    <!-- Javascript -->
<script src="<?php echo e(asset('version_2/iot/assets/bundles/libscripts.bundle.js')); ?>"></script>    
<script src="<?php echo e(asset('version_2/iot/assets/bundles/vendorscripts.bundle.js')); ?>"></script>
    
<script src="<?php echo e(asset('version_2/iot/assets/bundles/mainscripts.bundle.js')); ?>"></script>
</body>
</html>
