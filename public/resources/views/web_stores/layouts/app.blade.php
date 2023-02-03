<!DOCTYPE html>
<html>
<head>
	<!-- Required meta tags-->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no, viewport-fit=cover">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<!-- Color theme for statusbar -->
	<meta name="theme-color" content="#2196f3">
	<!-- Your app title -->
	<title>{{App\Language::trans('Web Store').'-'.App\Setting::version()}}</title>
	<!-- Path to Framework7 Library CSS -->
	<link rel="stylesheet" href="{{asset('framework7/css/framework7.min.css')}}">
	<link rel="stylesheet" href="{{asset('framework7/css/framework7.ios.css')}}">
	<link rel="stylesheet" href="{{asset('framework7/css/framework7-icons.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('plugins/slick/slick/slick-theme.css')}}"/>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/bxslider/4.2.12/jquery.bxslider.css">
	<link rel="stylesheet" href="{{asset('css/web_stores/ionicons.css')}}?ver{{App\Setting::version()}}">
	<link rel="stylesheet" href="{{asset('css/web_stores/main.css')}}?ver{{App\Setting::version()}}">
	<!-- Path to your custom app styles-->
	{{-- <link rel="stylesheet" href="path/to/my-app.css"> --}}
</head>
<body onbeforeunload="turn_off_loading()">
	<!-- App root element -->
	<div id="app">
		<!-- Statusbar overlay -->
		<div class="statusbar"></div>

		<!-- Your main view, should have "view-main" class -->
		<div class="view view-main">
			<!-- Initial Page, "data-name" contains page name -->
			<div data-name="home" class="page">
				@if($toolbar)
					<div class="navbar">
						<div class="navbar-inner sliding">
							<div class="title">{{$page_title}}</div>
						</div>
					</div>
				@else
				@endif
				@yield('navbar')

				<div class="page-content hide-navbar-on-scroll">
					@yield('content')
				</div>
			</div>
		</div>
	</div>
	<script src="//code.jquery.com/jquery.js"></script>
	<!-- Path to Framework7 Library JS-->
	<script type="text/javascript" src="{{asset('framework7/js/framework7.min.js')}}"></script>
	<!-- Path to your app js-->
	<script type="text/javascript" src="{{asset('framework7/js/my-app.js')}}"></script>
	<script src="https://cdn.jsdelivr.net/bxslider/4.2.12/jquery.bxslider.min.js"></script>
	<script type="text/javascript" src="{{asset('plugins/slick/slick/slick.min.js')}}"></script>
	<script type="text/javascript" src="{{asset('plugins/cookie/src/js.cookie.js')}}"></script>
	<script type="text/javascript" src="{{asset('js/main.js')}}?ver{{App\Setting::version()}}"></script>
	<script type="text/javascript">
		var cartLabel = "{{App\Setting::cart_label}}";
		var wishlistLabel = "{{App\Setting::wishlist_label}}";
		var remove_item_message = "{{App\Language::trans('Are you confirm to remove?')}}";
		var decimalPoint = "2";
		var $$ = Dom7;

		$(".external").on("click", function(){
			app.preloader.show();
		});
		function turn_off_loading() {
			setTimeout(function(){
				app.preloader.hide();
			}, 1000);			
		}
		$(".ripple, a.link, a.item-link, .button, .modal-button, .tab-link, .label-radio, .label-checkbox, .actions-modal-button, a.searchbar-clear, .floating-button, .radio").on("click", function() {
			var that = $(this);
			setTimeout(function(){
				$(that).find(".ripple-wave").remove();
			}, 500);
		});
		@yield('script')
	</script>
	@yield('extend_script')
</body>
</html>