<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>{{App\Language::trans('Login Page')}}</title>

	<!-- Bootstrap CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">

	<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.2/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
	<![endif]-->
	<style type="text/css">
		body {
			padding-top: 15%;
			padding-bottom: 15%;
			background-image: url({{asset('img/utility_charges/login_background.jpg')}});
			background-size: auto;
			background-position: center;
			background-repeat: no-repeat;
		}
		.form-signin {
			max-width: 330px;
			padding: 15px;
			margin: 0 auto;
			background-color: rgba(0, 0, 0, 0.5);
			color: #ffffff;
		}
		.form-signin .form-box {
			text-shadow: 1px 1px 1px #000000;
		}
		.form-signin .form-signin-heading,
		.form-signin .checkbox {
			margin-bottom: 10px;
		}
		.form-signin .checkbox {
			font-weight: normal;
		}
		.form-signin .form-control {
			position: relative;
			height: auto;
			-webkit-box-sizing: border-box;
			-moz-box-sizing: border-box;
			box-sizing: border-box;
			padding: 10px;
			font-size: 16px;
		}
		.form-signin .form-control:focus {
			z-index: 2;
		}
		.form-signin input[type="email"] {
			outline: none;
			margin-bottom: -1px;
			border-bottom-right-radius: 0;
			border-bottom-left-radius: 0;
		}
		.form-signin input[type="password"] {
			outline: none;
			margin-bottom: 10px;
			border-top-left-radius: 0;
			border-top-right-radius: 0;
		}
	</style>
</head>
<body>
	<div class="container">
		{!!Form::model($model, ['class'=>'form-signin'])!!}
			@if(session('status_level'))
				<div class="alert alert-{{session('status_level')}} alert-dismissible" role="alert">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					{{session('status_msg')}}
				</div>
			@endif
			<div class="form-box">
				<h2 class="form-signin-heading">{{App\Language::trans('Please sign in')}}</h2>
				{!!Form::label('user_email', App\Language::trans('Email address'), ['class'=>'sr-only'])!!}
				{!!Form::email('user_email', null, ['class'=>'form-control','placeholder'=>App\Language::trans('Email Address'),'required','autofocus'])!!}
				{!!Form::label('user_password', App\Language::trans('Password'), ['class'=>'sr-only'])!!}
				{!!Form::password('user_password', ['class'=>'form-control','placeholder'=>App\Language::trans('Password'),'required'])!!}
				<div class="checkbox">
					<label>
						{!!Form::checkbox('remember-me', 1, true)!!} {{App\Language::trans('Remember me')}}
					</label>
				</div>
				</div>
				<button class="btn btn-lg btn-primary btn-block" type="submit">{{App\Language::trans('Sign in')}}</button>
			</div>
		{!!Form::close()!!}
	</div> <!-- /container -->
	<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
	<!-- jQuery -->
	<script src="//code.jquery.com/jquery.js"></script>
	<!-- Bootstrap JavaScript -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
	<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
</body>
</html>