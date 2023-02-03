@extends('web_stores.layouts.app')
@section('content')
<h4 class="store-title">{{$page_title}}</h4>
<ul class="store-category">
	@foreach($companies as $store)
		@php $background=false; @endphp
		@if(isset($store['company_photos']))
			@foreach($store['company_photos'] as $photo)
				@if($photo['company_photo_is_cover'])
					@php $background=true; @endphp
					<li>
						<a class="external" href="{{action('AppsWebStoresController@getStore', ['secret_token'=>$store['company_secret_token'], 'store_id'=>$store['id_company'], 'store_name'=>$store['company_name_display']])}}">
							<div class="ratio-70 lazy lazy-fade-in" data-background="{{$leaf_acc_api->get_image_src($photo['company_photo_path'])}}">
							</div>
							<div class="desc">
								<p class="word_in_three_row">
									{{$store['company_name_display']}}<br>
									{!!nl2br($store['company_description'])!!}
								</p>
							</div>
						</a>
					</li>
				@endif
			@endforeach
		@endif
	@endforeach
</ul>
@stop
@section('script')
@stop