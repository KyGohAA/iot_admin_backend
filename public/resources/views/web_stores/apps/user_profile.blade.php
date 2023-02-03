@extends('web_stores.layouts.app')
@section('content')
<div class="card">
	<div class="card-header">{{App\Language::trans('User Profile')}}</div>
	<div class="card-content card-content-padding">
		<iframe class="width-100 height-100-vh" src=""></iframe>
	</div>
</div>
@stop
@section('script')
@stop