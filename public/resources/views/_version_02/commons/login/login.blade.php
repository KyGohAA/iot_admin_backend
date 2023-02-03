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
    <link rel="shortcut icon" href="{{asset('favicon.ico')}}">
    <link rel="icon" href="{{asset('favicon.ico')}}" type="image/x-icon">

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
            <header class="d-flex justify-content-between align-items-center">
                <a class="d-flex auth-brand" href="#">
                    <img class="brand-img" src="{{$company['system_logo_path'] != '' ? asset($company['system_logo_path']) : asset('_app_icon.png')}}" alt="brand" />
                </a>
                <div class="btn-group btn-group-sm">
                    <!-- <a href="#" class="btn btn-outline-secondary">Help</a> -->
                    @if($company['system_logo_path'] == '')
                        <a href="http://www.leaf.com.my/" class="btn btn-outline-secondary">About Us</a>
                    @endif
                </div>
            </header>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-xl-5 pa-0">
                        <div id="owl_demo_1" class="owl-carousel dots-on-item owl-theme">
                            <div class="fadeOut item auth-cover-img overlay-wrap" style="background-image:url({{asset('img/opencarts/login/login_2.jpg')}});">
                                <div class="auth-cover-info py-xl-0 pt-100 pb-50">
                                    <div class="auth-cover-content text-center w-xxl-75 w-sm-90 w-xs-100">
                                        <h1 class="display-3 text-white mb-20">Understand and look deep into nature.</h1>
                                        <p class="text-white"></p>
                                    </div>
                                </div>
                                <div class="bg-overlay bg-trans-dark-50"></div>
                            </div>
                            <div class="fadeOut item auth-cover-img overlay-wrap" style="background-image:url({{asset('img/opencarts/login/login_3.jpg')}});">
                                <div class="auth-cover-info py-xl-0 pt-100 pb-50">
                                    <div class="auth-cover-content text-center w-xxl-75 w-sm-90 w-xs-100">
                                        <h1 class="display-3 text-white mb-20">Experience matters for good applications.</h1>
                                        <p class="text-white"></p>
                                    </div>
                                </div>
								<div class="bg-overlay bg-trans-dark-50"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-7 pa-0">
                        <div class="auth-form-wrap py-xl-0 py-50">
                            <div class="auth-form w-xxl-55 w-xl-75 w-sm-90 w-xs-100">
                                {!!Form::model($model, ['class'=>'form-signin'])!!}
                                    <h1 class="display-4 mb-10">Welcome Back :)</h1>
                                    <p class="mb-30">Sign in to your account and enjoy unlimited perks.</p>
                                    @if(session('status_level'))
										<div class="alert alert-{{session('status_level')}} alert-dismissible" role="alert">
											<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
											{{session('status_msg')}}
										</div>
									@endif
                                    <div class="form-group">
                                        {!!Form::email('user_email', null, ['class'=>'form-control','placeholder'=>App\Language::trans('Email Address'),'required','autofocus' ,'type'=> 'email'])!!}
                                    </div>
                                    <div class="form-group">
                                        <div class="input-group">
                                            {!!Form::password('user_password', ['class'=>'form-control','placeholder'=>App\Language::trans('Password'),'required'])!!}
                                            <div class="input-group-append">
                                                <span class="input-group-text"><span class="feather-icon"><i data-feather="eye-off"></i></span></span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="custom-control custom-checkbox mb-25">
                                        <input class="custom-control-input" id="remember_me" type="checkbox" checked>
                      					{!!Form::label('remember_me', App\Language::trans('Remember me'), ['class'=>'custom-control-label font-14'])!!}
                                    </div>
                                    <button class="btn btn-primary btn-block loading-label" type="submit">Login</button>
                                   <!--  <p class="font-14 text-center mt-15">Having trouble logging in?</p>
                                    <div class="option-sep">or</div>
                                    <div class="form-row">
                                        <div class="col-sm-6 mb-20">
                                            <button class="btn btn-indigo btn-block btn-wth-icon"> <span class="icon-label"><i class="fa fa-facebook"></i> </span><span class="btn-text">Login with facebook</span></button>
                                        </div>
                                        <div class="col-sm-6 mb-20">
                                            <button class="btn btn-sky btn-block btn-wth-icon"> <span class="icon-label"><i class="fa fa-twitter"></i> </span><span class="btn-text">Login with Twitter</span></button>
                                        </div>
                                    </div>
                                    <p class="text-center">Do have an account yet? <a href="#">Sign Up</a></p> -->
                                {!!Form::close()!!}
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

    <!-- Owl JavaScript -->
    <script src="{{asset('version_2/vendors/owl.carousel/dist/owl.carousel.min.js')}}"></script>

    <!-- FeatherIcons JavaScript -->
    <script src="{{asset('version_2/dist/js/feather.min.js')}}"></script>

    <!-- Init JavaScript -->
    <script src="{{asset('version_2/dist/js/init.js')}}"></script>
    <script src="{{asset('version_2/dist/js/login-data.js')}}"></script>
    <script type="text/javascript">
       
        function init_loading_overlay() {

            $("#overlay").css("z-index","1000");
            $('#overlay').waitMe({
                //none, rotateplane, stretch, orbit, roundBounce, win8, 
                //win8_linear, ios, facebook, rotation, timer, pulse, 
                //progressBar, bouncePulse or img
                effect: 'win8_linear',
                //place text under the effect (string).
                text: stringPleaseWait,
                //background for container (string).
                bg: 'rgba(0,0,0,0.3)',
                //color for background animation and text (string).
                color: '#ffffff',
                //max size
                maxSize: '',
                //wait time im ms to close
                waitTime: -1,
                //url to image
                source: '',
                //or 'horizontal'
                textPos: 'vertical',
                //font size
                fontSize: '16px',
                // callback
                onClose: function() {}
            });
        }
    </script>
</body>

</html>





