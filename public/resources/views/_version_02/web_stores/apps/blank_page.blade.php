@extends('web_stores.layouts.app')
@section('content')
<div class="card">
	<div class="card-header">{{App\Language::trans('Message')}}</div>
	<div class="card-content card-content-padding">
		<!-- Card content -->
		{{$message}}
	</div>
</div>
@stop
@section('script')
@stop