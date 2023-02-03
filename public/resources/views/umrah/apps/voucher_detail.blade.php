@extends('umrah.layouts.app')
@section('content')
<center>
	<img class="margin-top-20 full-width" src="data:image/png;base64, {!! base64_encode(QrCode::format('png')->margin(1)->size(300)->generate(action('AppsController@getVoucherClaim',['voucher'=>$model->encrypt($model->id)]))) !!} ">
	<hr>
	<h3>{{$voucher->name}}</h3>
	<small class="quantity">{{App\Language::trans('Available quantity : ').$voucher->total_quantity($voucher->id)}}</small><br>
	<small class="price">{{$voucher->amount}}</small><br>
	<hr>
	<small class="note text-danger">{{App\Language::trans('Note : Open & allow store scan this page to claim your food.')}}</small>
</center>
@endsection
@section('script')
@endsection