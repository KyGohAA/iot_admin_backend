@extends('utility_charges.layouts.admin')
@section('content')
<div class="error-page">
<div class="error-page">
	<h2 class="headline text-red" style="margin-top: 0px;">{{$datas['error_code']}}</h2>

	<div class="error-content">
		<h3><i class="fa fa-warning text-red"></i> {{$datas['message']}}</h3>

		<p>
		{{App\Language::trans('We will work on fixing that right away.')}}
		{{App\Language::trans('Meanwhile, you may ')}}<a href="{{action('DashboardsController@getIndex')}}">{{App\Language::trans('return to dashboard')}}.</a>
		</p>
	</div>
</div>

@endsection
@section('script')
@endsection