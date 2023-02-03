@extends('umrah.layouts.app')
@section('content')
@if(count($stores))
	<ul data-role="listview">
		@foreach($stores as $store)
			<li><a href="{{action('AppsController@getVouchers', [$store->store_id])}}">{{$store->display_relationed('store','name')}}</a></li>
		@endforeach
	</ul>
@else
	<h4 class="text-center text-vertical-middle">{{App\Language::trans('You do not have any store voucher.')}}</h4>
@endif
@endsection
@section('script')
@endsection