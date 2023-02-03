@if(count($main_store['company_photos']))
	<div data-speed="900" data-pagination='{"el": ".swiper-pagination"}' data-space-between="50" class="swiper-container swiper-init demo-swiper banner">
		<div class="swiper-pagination"></div>
		<div class="swiper-wrapper">
			@foreach($main_store['company_photos'] as $row)
				<div class="swiper-slide">
					<div class="ratio-70 lazy" style="background: url({{$leaf_acc_api->get_image_src($row['company_photo_path'])}});"></div>
				</div>
			@endforeach
		</div>
	</div>
@endif
@if(isset($latest_opening['store']))
	<h4 class="margin-left-15">{{App\Language::trans('Latest Opening')}}</h4>
	<!-- swiper properties in JSON format in data-swiper attribute -->
	<div data-pagination='{"el": ".swiper-pagination"}' data-space-between="0" data-slides-per-view="3" class="swiper-container swiper-init photo-swiper">
		<div class="swiper-pagination"></div>
		<div class="swiper-wrapper">
				@foreach($latest_opening['store'] as $store)
					@if(count($store['company_photos']))
						@foreach($store['company_photos'] as $photo_index => $photo)
							@if($photo_index == 1 && $photo['company_photo_path'])
								<div class="swiper-slide">
									<a class="col-33 ratio lazy lazy-fade-in external" href="{{action('AppsWebStoresController@getStore', ['secret_token'=>$store['company_secret_token'],'store_id'=>$store['id_company'],'store_name'=>$store['company_name_display']])}}" style="background-image: url({{$leaf_acc_api->get_image_src($photo['company_photo_path'])}});">
									</a>
								</div>
							@endif
						@endforeach
					@endif
				@endforeach
		</div>
	</div>
@endif
<div class="row margin-top-15">
	@foreach($categories as $category)
		<div class="col-33">
			<a class="external" href="{{action('AppsWebStoresController@getCategory', ['store_category_id'=>$category['id_company_category'],'category_name'=>$category['company_category_name']])}}">
				<img class="img-responsive full-width lazy lazy-fade-in" src="{{$leaf_acc_api->get_image_src($category['company_category_photo_path'])}}">
				<p class="text-center text-normal word_in_one_row">{{$category['company_category_name']}}</p>
			</a>
		</div>
	@endforeach
</div>
