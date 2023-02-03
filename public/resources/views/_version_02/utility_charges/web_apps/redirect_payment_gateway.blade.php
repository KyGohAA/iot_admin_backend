@extends('utility_charges.layouts.web_apps')
@section('content')
<div class="row progress-tab">
	<div class="col-sm-4">
		<center>1. {{App\Language::trans('Details')}}</center>
	</div>
	<div class="col-sm-4">
		<center>2. {{App\Language::trans('Payment')}}</center>
	</div>
	<div class="col-sm-4 active">
		<center>3. {{App\Language::trans('Receipt')}}</center>
	</div>
</div>
<div class="receipt-page text-center">
	<i class="fa fa-thumbs-o-{{$status_level}} fa-fw fa-5x text-primary" aria-hidden="true"></i>
	@if($status_level == 'up')
		<h1>{{App\Language::trans('Your payment is Successful')}}</h1>
		<p>{{App\Language::trans('You will receive your e-receipt in your email shortly. An Sms notification will also be sent to your phone.')}}</p>
		<p>If you do not receive your e-receipt within next 30minutes. please contact our customer service hotline at 03-1234 5678. or email us for futher assistance.</p>
	@else
		<h1>{{App\Language::trans('Your payment is Failed')}}</h1>
		<p>please contact our customer service hotline at 03-1234 5678. or email us for futher assistance.</p>
	@endif
	<span class="help-block">{{App\Language::trans('Order id')}}: 12938712873612387</span>
	<span class="help-block">Email: {{Auth::user()->email}}</span>
	<a class="btn btn-default" href="{{action('WebUtilityChargesController@getPreparePayment')}}">{{App\Language::trans('Continue with us')}}</a>
</div>
@stop
@section('script')
@stop