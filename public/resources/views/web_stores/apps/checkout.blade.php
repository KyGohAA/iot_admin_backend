@extends('web_stores.layouts.app')
@section('content')
<style type="text/css">
	.hidden {
		display: none;
	}
</style>
<h4 class="margin-left-15">{{$page_title}}</h4>
{!!Form::open(['url'=>url()->full(),'id'=>'checkout-form'])!!}
<div class="list">
	<ul>
		<li>
			<a href="{{action('AppsWebStoresController@getContactLists')}}" class="item-link item-content external">
				{!!Form::hidden('contact_address', $contact['id'])!!}
				<div class="item-inner item-cell addresses-list">
					<div class="item-row">
						<div class="item-cell"><i class="f7-icons address-home">home</i> {{App\Language::trans('Delivery Address')}}</div>
					</div>
					<div class="item-row margin-left-20">
						<div class="item-cell">{{$contact['name']}}</div>
						<div class="item-cell text-right">{{$contact['mobile']}}</div>
					</div>
					<div class="item-row margin-left-20">
						<div class="item-cell">{{$contact['email']}}</div>
					</div>
					<div class="item-row margin-left-20">
						<div class="item-cell">{{$contact['address1'].$contact['address2']}}</div>
					</div>
					<div class="item-row margin-left-20">
						<div class="item-cell">{{$contact['postcode'].', '.$contact['city']}}</div>
					</div>
					<div class="item-row margin-left-20">
						<div class="item-cell">{{$leaf_api->get_state_name($contact['id_state'], $contact['id_country']).', '.$leaf_api->get_country_name($contact['id_country'])}}</div>
					</div>
				</div>
			</a>
		</li>
	</ul>
	<div class="delivery-address-stripped-background"></div>
</div>
@if(count($carts))
	@foreach($carts as $row)
		@php $i=0; @endphp
		<div class="card items-card">
		    <div class="card-header">{{$row['company_name']}}</div>
    		@php $currency_code = $row['currency_code']; @endphp
		    <div class="card-content card-content-padding">
		    	@foreach($row['items'] as $index => $item)
		    		@if(isset($item['document_status']))
				    	@if($i > 0)
					    	<hr>
				    	@endif
				    	<div class="item">
					    	<div class="row">
					    		{!!Form::hidden('item['.$item['product_id'].'][product_id]', $item['product_id'])!!}
					    		<div class="col-30">
					    			<div class="ratio-70" style="background-image: url({{$leaf_acc_api->get_image_src($item['product_photo_url'])}});"></div>
					    		</div>
					    		<div class="col-70">
					    			<span class="one_row_words">{{$item['product_name']}}</span>
					    			<span class="one_row_words text-right">x <span class="quantity">{{$item['product_quantity']}}</span></span>
					    			<span class="one_row_words text-danger text-right">{{$row['currency_code']}}<span class="price"> {{$setting->getDouble($item['product_price'])}}</span></span>
					    			<span class="hidden weight">{{$setting->getDouble($item['product_price'])}}</span>
					    		</div>
					    	</div>
					    	<div class="item-delivery-methods">
						    		@foreach($item['document_status'] as $index => $document_status)
							    		<label class="radio delivery-radio">
							    			<input type="radio" name="item[{{$item['product_id']}}][document_status]" value="{{$document_status['id_document_status']}}" {{$index == 1 ? 'checked="checked"':''}} data-is-online-pay="{{$document_status['document_status_is_online_pay']}}" data-is-delivery="{{$document_status['document_status_is_deliver']}}" onchange="init_total_summary()">
							    			<i class="icon-radio"></i>
							    			{{$document_status['document_status_string']}}
							    		</label>
						    		@endforeach
					    	</div>
				    	</div>
				    	@php 
				    		$i++;
				    		$total += ($item['product_quantity'] * $item['product_price']); 
			    		@endphp
		    		@endif
		    	@endforeach
		    </div>
		</div>
	@endforeach
	@if($total > 0)
		<div class="card">
			<div class="card-header">{{App\Language::trans('Payment Method')}}</div>
			<div class="card-content card-content-padding">
	    		<label class="radio delivery-radio">
	    			<input type="radio" name="payment_method" value="fpx" checked="checked">
	    			<i class="icon-radio"></i>
	    			{{App\Language::trans('FPX')}}
	    		</label>
	    		<label class="radio delivery-radio">
	    			<input type="radio" name="payment_method" value="creditcard">
	    			<i class="icon-radio"></i>
	    			{{App\Language::trans('Credit Card')}}
	    		</label>
	    		<label class="radio delivery-radio">
	    			<input type="radio" name="payment_method" value="paypal">
	    			<i class="icon-radio"></i>
	    			{{App\Language::trans('PayPal')}}
	    		</label>
	    		<label class="radio delivery-radio">
	    			<input type="radio" name="payment_method" value="ecpay">
	    			<i class="icon-radio"></i>
	    			{{App\Language::trans('EcPay')}}
	    		</label>
			</div>
		</div>
	@endif
	<div class="card">
		<div class="card-header">{{App\Language::trans('Shipping Cost & Weight')}}</div>
		<div class="card-content card-content-padding">
			<p>{{App\Language::trans('Total Weight :')}} <font class="total_weight">0</font> KG</p>
			<p>{{App\Language::trans('Total Amount :')}} {{$currency_code}}<font class="total_weight_cost">{{$setting->getDouble(0)}}</font></p>
		</div>
	</div>
@endif
{!!Form::close()!!}
<div class="toolbar tabbar">
	<div class="toolbar-inner row">
		<a class="tab-link col-70 text-danger"><font>{{App\Language::trans('Total Payment')}} : {{$currency_code}}<font class="total_payment">{{$setting->getDouble($total)}}</font></font></a>
		@if($total > 0)
			<a class="tab-link text-right col-30 btn-order">
				{{App\Language::trans('Place Order')}}
			</a>
		@endif
	</div>
</div>
@stop
@section('script')
	$$(".btn-order").on("click", function(){
		$("form").submit();
	});
	init_total_summary();
	function init_total_summary() {
		var isOnlinePay = 0;
		var isDelivery = 0;
		$("input[name*=document_status]:checked").each(function(){
			var item = $(this).closest(".item");
			if($(this).data("is-online-pay")) {
				var quantity = item.find(".quantity").html();
				var price = item.find(".price").html();
				isOnlinePay += parseFloat(quantity) * parseFloat(price);
			}
			if($(this).data("is-delivery")) {
				isDelivery += parseFloat(item.find(".weight").html());
			}
		});
		{{-- init delivery cost if exist --}}
		$.get("{{action('AppsWebStoresController@getDeliveryCost', ['secret_token'=>$secret_token])}}", {weight:isDelivery}, function(data){
			isOnlinePay += parseFloat(data.total);
			$(".total_weight").html(isDelivery);
			$(".total_weight_cost").html(init_decimal_point(data.total));
			$(".total_payment").html(init_decimal_point(isOnlinePay));
		},"json");
	}
@stop