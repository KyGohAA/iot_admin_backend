<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>{{App\Language::trans('Utility Management Portal')}}</title>

	<!-- Bootstrap CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
	<link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="{{asset('bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.css')}}?ver={{App\Setting::version()}}">
	<link rel="stylesheet" href="{{asset('bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker3.css')}}?ver={{App\Setting::version()}}">

	<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.2/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
	<![endif]-->
	<style type="text/css">
		/*sunway main color: #da262e*/
		body {
			padding-top: 60px;
			background-color: rgb(248, 248, 248);
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
		.navbar-inverse {
			background-color: #da262e;
			border-color: #da262e;
		}
		.navbar-inverse .navbar-brand {
			color: #ffffff;
		}
		.navbar-inverse .navbar-nav>li>a {
			color: #ffffff;
		}
		.navbar-inverse .navbar-nav>.active>a, 
		.navbar-inverse .navbar-nav>.active>a:focus, 
		.navbar-inverse .navbar-nav>.active>a:hover {
			background-color: #da262e;
		}
		.btn {
			border-radius: 0px;
		}
		.panel-default>.panel-heading {
			background-color: transparent;
		}
		.panel {
			border-top: 2px solid #da262e;
			border-radius: 0px;
			background-color: transparent;
		}
		.panel-heading {
			border-radius: 0px;
		}
		.panel-heading h4 {
			margin: 0px;
			font-weight: 300;
		}
		.panel-footer h2 {
			font-weight: 300;
		}
		.title {
			font-weight: 300;
		}
		.border-right {
			border-right: 1px solid #cccccc;
		}
		.input-amount {
			width: 100%;
			line-height: 30px;
			font-size: 26px;
			font-weight: 300;
			text-align: right;
		}
		.input-room {
			margin-bottom: 15px;
			line-height: 36px;
			height: 36px;
			border-radius: 0px;
			border-color: #cccccc;
			-webkit-appearance: initial;
			padding: 0px 10px;
		}
		.input-room:focus,
		.input-amount:focus {
			outline: 0;
		}
		.table-row {
		    display: table;
		}
		.table-row [class*="table-col-"] {
		    float: none;
		    display: table-cell;
		    vertical-align: bottom;
		}
		.progress-tab .active {
			padding-top: 5px;
			border-top: 1px solid #cccccc;
		}
		.img-responsive {
			width: 100%;
		}
		.margin-bottom-50 {
			margin-bottom: 50px;
		}
		.navbar-nav li.active, 
		.navbar-inverse .navbar-nav>.active>a, 
		.navbar-inverse .navbar-nav>.active>a:focus, 
		.navbar-inverse .navbar-nav>.active>a:hover, 
		.navbar-inverse .navbar-nav>li>a:focus, 
		.navbar-inverse .navbar-nav>li>a:hover,
		.navbar-inverse .navbar-nav>.open>a, 
		.navbar-inverse .navbar-nav>.open>a:focus, 
		.navbar-inverse .navbar-nav>.open>a:hover,
		.dropdown-menu>.active>a, 
		.dropdown-menu>.active>a:focus, 
		.dropdown-menu>.active>a:hover {
			color: #f2f2f2;
			background-color: #c22229;
			transition: all 0.7s ease;
		}
		.navbar-inverse .navbar-toggle:focus, 
		.navbar-inverse .navbar-toggle:hover, {
			background-color: #da262e;
		}
		.navbar-inverse .navbar-toggle {
			border-color: #ffffff;
		}
		.navbar-inverse .navbar-collapse, 
		.navbar-inverse .navbar-form {
			border-color: #ffffff;
		}
		.receipt-page {
			margin: 22vh auto;
		}
		.receipt-page,
		.receipt-page h1 {
			font-weight: 300;
		}
		@media screen and (max-width: 480px) {
			.table-row {
			    display: initial;
			}
			.table-row [class*="table-col-"] {
			    float: initial;
			    display: initial;
			    vertical-align: middle;
			}
			.navbar-inverse .navbar-toggle:focus, 
			.navbar-inverse .navbar-toggle:hover {
				color: #f2f2f2;
				background-color: #c22229;
				transition: all 0.7s ease;
			}
			.navbar-inverse .navbar-nav .open .dropdown-menu>.active>a, 
			.navbar-inverse .navbar-nav .open .dropdown-menu>.active>a:focus, 
			.navbar-inverse .navbar-nav .open .dropdown-menu>.active>a:hover {
				color: #ff0905;
				background-color: #ffffff;
			}
		}
	</style>
</head>
<body>
	<div class="navbar navbar-inverse navbar-fixed-top">
		<div class="container">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
					<span class="sr-only">{{App\Language::trans('Toggle navigation')}}</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="#">Sunway M.C.</a>
			</div>
			<div id="navbar" class="collapse navbar-collapse">
				<ul class="nav navbar-nav">
					<li><a href="{{action('WebUtilityChargesController@getPreparePayment')}}">{{App\Language::trans('Payment')}}</a></li>
					<li><a href="{{action('WebUtilityChargesController@getHistoryStatement')}}">{{App\Language::trans('History Statement')}}</a></li>
					<li><a href="{{action('WebUtilityChargesController@getHistoryUsage')}}">{{App\Language::trans('History Usage')}}</a></li>
					<li><a href="{{action('WebUtilityChargesController@getHelp')}}">{{App\Language::trans('Help')}}</a></li>
				</ul>
				<ul class="nav navbar-nav navbar-right">
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">{{App\Language::trans('Select Group')}} <span class="caret"></span></a>
						<ul class="dropdown-menu">
							@php 
								$leaf_api = new App\LeafAPI(); 
							@endphp
							@foreach($leaf_api->get_groups() as $group)
								@if(App\Company::get_group_id() == $group['id_group'])
									<li class="{{App\Company::get_group_id() == $group['id_group'] ? 'active':''}}"><a href="{{action('WebUtilityChargesController@getSwitchGroup', ['group_id'=>$group['id_group']])}}">{{$group['group_name']}}</a></li>
								@endif
							@endforeach
						</ul>
					</li>
					<li>
						<a href="{{action('WebUtilityChargesController@getLogout')}}">{{App\Language::trans('Logout')}}</a>
					</li>
				</ul>
			</div><!--/.nav-collapse -->
		</div>
	</div>
	<div class="container">
		@yield('content')
		<div class="container">
			<hr>
			<p class="text-center">Copyright © 2017-2018 <a href="#">Leaf Smart City</a>. All rights reserved.</p>
		</div>
	</div> <!-- /container -->
	<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
	<!-- jQuery -->
	<script src="//code.jquery.com/jquery.js"></script>
	<!-- Bootstrap JavaScript -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
	<script src="{{asset('bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.js')}}"></script>
	<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
	<script type="text/javascript">
		function init_double(me) {
			var double = $(me).val();
			$(me).val(parseFloat(double).toFixed(2));
		}
		$(document).ready(function(){
		    var url = window.location.href;
		    var n = url.indexOf("?");
		    url = url.substring(0, n != -1 ? n : url.length);
		    var sidebar = $(".navbar-nav").find("li");

		    // var sidebar_li = sidebar.children();
		    sidebar.each(function(){
	        	var href = $(this).find("a").attr("href");
	            if (href == url) {
	                $(this).addClass("active");
	            }            
		    });
		});
		if ($(".input-daterange").length) {
		    $(".input-daterange").datepicker({
		        format: "dd-mm-yyyy",
		    });
		}
		@yield('script')
	</script>
</body>
</html>