@extends('web_stores.layouts.app')
@section('content')
<h4 class="margin-left-15">{{$page_title}}</h4>
@if(count($carts))
	@foreach($carts as $row)
		@php $i=0; @endphp
		<div class="card">
		    <div class="card-header">{{$row['company_name']}}</div>
		    <div class="card-content card-content-padding">
		    	@foreach($row['items'] as $index => $item)
			    	@if($i > 0)
				    	<hr>
			    	@endif
			    	<div class="row item">
			    		<div class="col-10">
			    			{!!Form::checkbox('products['.$index.'][product_id]', $item['product_id'], true, ['onclick'=>'init_cart_select_all()'])!!}
			    		</div>
			    		<div class="col-20">
			    			<div class="ratio-70" style="background-image: url({{$leaf_acc_api->get_image_src($item['product_photo_url'])}});"></div>
			    		</div>
			    		<div class="col-70">
			    			<span class="one_row_words">{{$item['product_name']}}</span>
			    			<div class="row">
			    				<div class="col-50">
									<span class="segmented segmented-raised">
										<button class="btn-plus button button-outline" onclick="init_update_cart(this, 'plus')">+</button>
										<button class="btn-qty-value button qty-display" disabled="true">{{$item['product_quantity']}}</button>
										{!!Form::hidden('products['.$index.'][product_quantity]', $item['product_quantity'], ['class'=>'product_quantity'])!!}
										<button class="btn-minus button button-outline" onclick="init_update_cart(this, 'minus')">-</button>
									</span>
			    				</div>
					    		<div class="col-10" style="margin-right: 10px;">
					    			<a onclick="init_remove_item(this)" href="javascript:void(0)"><i class="f7-icons text-danger">trash</i></a>
					    		</div>
			    			</div>
			    			<span class="text-danger">{{$row['currency_code']}}<span class="price"> {{$setting->getDouble($item['product_price'])}}</span></span>
			    		</div>
			    	</div>
			    	@php 
			    		$i++;
			    		$total += ($item['product_quantity'] * $item['product_price']); 
		    		@endphp
		    	@endforeach
		    </div>
		</div>
	@endforeach
@endif
<div class="block">
	<hr>
	<div class="row">
		<div class="col-20s">{!!Form::checkbox('select_all', null, true, ['onclick'=>'init_cart_check_list(this)'])!!} {{App\Language::trans('Select All')}}</div>
		<div class="col-40 text-right">
			{{App\Language::trans('Total :')}}
			<b>
				<span class="text-danger">
					{{($currency_code)}}
					<span class="total">{{$setting->getDouble($total)}}</span>
				</span>
			</b>
		</div>
		<a class="col-40 button button-fill button-checkout external" href="{{action('AppsWebStoresController@getCheckout', ['store_name'=>$page_title,'session_token'=>$session_token,'secret_token'=>$secret_token,'store_id'=>$store_id])}}">{{App\Language::trans('Checkout')}}</a>
	</div>
</div>
@stop
@section('script')
@stop