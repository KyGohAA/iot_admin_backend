@extends('web_stores.layouts.app')
@section('content')
<style type="text/css">
	.f7-icons, .framework7-icons {
		font-size: 18px;
	}
	.button.color-orange {
		border: 1px solid #ff9500;
	}
</style>
{{-- delivery --}}
<div class="card card-outline">
  <div class="card-content card-content-padding">
  	<div class="row">
  		<div class="col-10"><i class="f7-icons">paper_plane</i></div>
  		<div class="col-90">
  			<strong>{{App\Language::trans('Delivery Address')}}</strong><br>
  			{{$model['header']['sell_order_customer_contact_person']}}<br>
  			{{$model['header']['sell_order_customer_ship_to_address1'].$model['header']['sell_order_customer_ship_to_address2']}}<br>
  			{{$model['header']['sell_order_customer_ship_to_postcode'].', '.$model['header']['sell_order_customer_ship_to_city_town']}}<br>
  			{{$leaf_api->get_state_name($model['header']['id_state_sell_order_ship_to'], $model['header']['id_country_sell_order_ship_to']).', '.$leaf_api->get_country_name($model['header']['id_country_sell_order_ship_to'])}}
  		</div>
  	</div>
  </div>
</div>
{{-- items --}}
<div class="card card-outline">
  <div class="card-content card-content-padding">
	<div class="list media-list">
	  <ul>
	  	@foreach($model['items']['sell_order_items'] as $row)
	    <li>
	      <a href="#" class="item-content">
	        <div class="item-media"><img src="{{$leaf_acc_api->get_image_src($row['vendor_product_photo_path'])}}" width="80"/></div>
	        <div class="item-inner">
	          <div class="item-title-row">
	            <div class="item-title">{{$row['sell_order_item_name']}}</div>
	            <div class="item-after">{{App\Language::trans('Qty').' : '.$row['sell_order_quantity']}}</div>
	          </div>
	          <div class="item-subtitle text-danger"><small>{{$model['header']['currency_code'].' '.$row['sell_order_unit_price']}}</small></div>
	          <div class="item-text text-danger">{{App\Language::trans('Total Amount')}} : {{$model['header']['currency_code'].' '.$row['sell_order_total_inclu_tax']}}</div>
	        </div>
	    </a>
	    </li>
	  	@endforeach
	  </ul>
	</div>
  </div>
</div>
{{-- payment --}}
<div class="card card-outline">
  <div class="card-content card-content-padding">
  	<div class="row">
  		<div class="col-10">
  			<i class="f7-icons">card</i>
  		</div>
  		<div class="col-60">
		  	<strong>{{App\Language::trans('Payment Information')}}</strong><br>
  		</div>
  		<div class="col-30 text-right">
  		</div>
  	</div>
  	<div class="row">
  		<div class="col-10">
  		</div>
  		<div class="col-60">
		  	{{App\Language::trans('Gateway').' : '.ucfirst($payment_detail['payment_service'])}}<br>
  		</div>
  		<div class="col-30 text-right">
  		</div>
  	</div>
  	<div class="row">
  		<div class="col-10">
  		</div>
  		<div class="col-60">
		  	{{App\Language::trans('Paid').' : '.($payment_detail['payment_paid'] ? App\Language::trans('Yes'):App\Language::trans('No'))}}<br>
  		</div>
  		<div class="col-30 text-right">
  		</div>
  	</div>
  	<div class="row">
  		<div class="col-10">
  		</div>
  		<div class="col-60">
		  	{{App\Language::trans('Total Amount')}} : 
  		</div>
  		<div class="col-30 text-right">
  			{{$payment_detail['payment_currency_code'].' '.$leaf_api->getDouble($payment_detail['payment_total_amount'])}}
  		</div>
  	</div>
  </div>
</div>

{{-- chat & visit shop button --}}
<div class="block">
  <p class="row">
    <button class="col button color-orange contact_seller">{{App\Language::trans('Contact Seller')}}</button>
    <button class="col button color-orange visit_shop">{{App\Language::trans('Visit Shop')}}</button>
  </p>
</div>
@stop
@section('script')
  	@if($main_store['leaf_acc'])
		$(".contact_seller").on("click", function(){
			window.location.href = "{!!App\Setting::get_chat_link($main_store['leaf_acc'])!!}";
		})
	@endif
	$(".visit_shop").on("click", function(){
		window.location.href = "{!!action('AppsWebStoresController@getStore', ['secret_token'=>$main_store['company_secret_token'], 'store_id'=>$main_store['id_company'],'store_name'=>$main_store['company_name_display']])!!}";
	})
@stop