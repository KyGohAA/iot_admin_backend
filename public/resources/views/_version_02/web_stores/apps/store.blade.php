@extends('web_stores.layouts.app')
@section('content')
<h4 class="store-title">{{$store['company_name_display']}}</h4>
<div data-pagination='{"el": ".swiper-pagination"}' data-space-between="1" class="swiper-container swiper-init photo-swiper">
	<div class="swiper-pagination"></div>
	<div class="swiper-wrapper">
	@foreach($store['company_photos'] as $photo)
		<div class="swiper-slide">
			<img class="width-100 lazy lazy-fade-in" src="{{$leaf_acc_api->get_image_src($photo['company_photo_path'])}}">
		</div>
	@endforeach
	</div>
</div>
<p class="contact-phone margin-left-25">
	{{App\Language::trans('Contact Number')}} : {{$store['company_tel']}}<br>
	{{App\Language::trans('Shop Locations')}} : {{$store['company_address1']}}
</p>
@if($store['leaf_acc'])
	<a class="external" href="{{App\Setting::get_chat_link($store['leaf_acc'])}}">
		<i class="f7-icons icon-chat">chats</i>
	</a>
@endif
<div class="row no-gap">
	@foreach($products as $product)
		<div class="col-50 product">
			@foreach($product['vendor_product_photos'] as $photo)
				@if($photo['vendor_product_photo_is_cover'])
					<a class="external product-list" href="{{action('AppsWebStoresController@getProduct', ['product_id'=>$product['id_vendor_product'],'secret_token'=>$store['company_secret_token']])}}">
						<div class="img-holder">
							<img class="width-100" src="{{$leaf_acc_api->get_image_src($photo['vendor_product_photo_path'])}}">
						</div>
						<p class="desc">{{$product['vendor_product_name']}}</p>
						<p class="price">
							{{$leaf_acc_api->get_cheaper_price($product)}}
						</p>
					</a>
				@endif
			@endforeach
		</div>
	@endforeach
</div>
@stop
@section('script')
@stop