@include('utility_charges.layouts.partials.web_apps._header')
@include('utility_charges.layouts.partials.web_apps._left_sidebar')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			{{$page_title}}
		</h1>
		<!-- <ol class="breadcrumb">
			<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
			<li><a href="#">Examples</a></li>
			<li class="active">Blank page</li>
		</ol> -->
	</section>

	<!-- Main content -->
	<section class="content">
		@yield('content')
	</section>
	<!-- /.content -->
</div>
<!-- /.content-wrapper -->
@include('utility_charges.layouts.partials.web_apps._footer')
