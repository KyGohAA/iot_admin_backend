@extends('web_stores.layouts.app')
@section('content')
<style type="text/css">
	.img-responsive {
		width: 100%;
		max-height: 40vh;	
	}
</style>
<div data-pagination='{"el": ".swiper-pagination"}' data-space-between="1" class="swiper-container swiper-init product-swiper">
	<div class="swiper-pagination"></div>
	<div class="swiper-wrapper">
		@foreach($product['vendor_product_photos'] as $photo)
			<div class="swiper-slide">
				<img class="img-responsive" src="{{$leaf_acc_api->get_image_src($photo['vendor_product_photo_path'])}}">
				{{-- <div class="ratio-70" style="background-image: url({{$leaf_acc_api->get_image_src($photo['vendor_product_photo_path'])}});"></div> --}}
			</div>
		@endforeach
	</div>
</div>
<div class="block" style="padding-bottom: 50px;">
	<h2>{{$product['vendor_product_name']}}</h2>
	<hr>
	<h4>Product Desc:</h4>
	<span class="product-desc">
		<div class="div-responsive">
			{!!nl2br($product['vendor_product_description'])!!}
		</div>
	</span>
	<hr>
	<h3>{{App\Language::trans('Price List')}}:-</h3>
	<div class="row no-gap">
		@foreach($product['vendor_product_prices'] as $price)
			@if($price['vendor_product_price_is_online'])
				<div class="col-50">{{$price['vendor_product_price_quantity'].' => '.$product['currency_code'].($price['vendor_product_price_price'])}}</div>
			@endif
		@endforeach
	</div>
	<div class="gap"></div>
	<hr>
	<div class="row">
		<div class="col-50">
			<h3>{{App\Language::trans('Quantity')}}:</h3>
		</div>
		<div class="col-50">
			<p class="segmented segmented-round">
				<button class="button button-round btn-plus">+</button>
				<button class="btn-qty-value button qty-display" disabled="true">0</button>
				<button class="button button-round btn-minus">-</button>
			</p>
			<input type="hidden" name="id_vendor_product" id="id_vendor_product" value="<?php echo $product['id_vendor_product']; ?>">
			<input type="hidden" name="vendor_product_quantity" id="vendor_product_quantity" value="0">
		</div>
	</div>
</div>
<div class="toolbar tabbar tabbar-label" style="position: fixed; bottom: 0; top: initial;">
	<div class="toolbar-inner">
		<a class="tab-link add_wishlist">
			<i id="bag-icon" class="f7-icons">heart</i>
			<span class="tabbar-label">{{App\Language::trans('Add Wishlist')}}</span>
		</a>
		<a class="tab-link add_cart">
			<i id="bag-icon" class="f7-icons">bag</i>
			<span class="tabbar-label">{{App\Language::trans('Add Cart')}}</span>
		</a>
		<a class="tab-link external" href="{{action('AppsWebStoresController@getStore',['secret_token'=>$product['company']['company_secret_token'],'store_id'=>$product['vendor_product_is_owner'],'store_name'=>$product['company']['company_name_display']])}}">
			<i id="bookmark-icon" class="f7-icons">home</i>
			<span class="tabbar-label">{{App\Language::trans('Store')}}</span>
		</a>
	</div>
</div>
@stop
@section('script')
	{{-- get_quantity_from_cart(); --}}

	$(".btn-plus").on("click", function(){
		var label 	=	$(".btn-qty-value");
		var input 	=	$("#vendor_product_quantity");
		var recent 	=	parseFloat(label.html());
		var total 	=	recent+1;
		label.html(total);
		input.val(total);
	});
	$(".btn-minus").on("click", function(){
		var label 	=	$(".btn-qty-value");
		var input 	=	$("#vendor_product_quantity");
		var recent 	=	parseFloat(label.html());
		var total 	=	recent-1;
		if(recent > 0) {
			label.html(total);
			input.val(total);
		}
	});

	$$('.add_wishlist').on('click', function () {
		var result = add_to_wishlist_func();
		app.dialog.alert("{{App\Language::trans('Product was saved to wishlist.')}}", "{{App\Language::trans('Wishlist Added')}}");
	});

	$$('.add_cart').on('click', function () {
	    var quantity = parseFloat($("#vendor_product_quantity").val());
	    if(quantity > 0) {
			var result = add_to_cart_func();
			app.dialog.alert("{{App\Language::trans('Product was updated to cart.')}}", "{{App\Language::trans('Cart Added')}}");
		} else {
			app.dialog.alert("{{App\Language::trans('Please select quantity before add cart.')}}", "{{App\Language::trans('Failed')}}");
		}
	});
	$(".div-responsive").find("img").each(function(){
		$(this).css("width", "100%");
	});
@stop