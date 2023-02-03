@extends('umrah.layouts.app')
@section('content')
<ul data-role="listview" data-count-theme="b">
	@foreach($vouchers as $voucher)
		<li><a href="{{$voucher['total_quantity'] > 0 ? action('AppsController@getVoucherDetail', [$voucher->id]):'#'}}">{{$voucher['name']}}<span class="ui-li-count">{{$voucher['total_quantity']}}</span></a></li>
	@endforeach
</ul>
@endsection
@section('script')
@endsection