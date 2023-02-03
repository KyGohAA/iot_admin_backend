@extends('web_stores.layouts.app')
@section('content')
<style type="text/css">
	.img-responsive {
		width: 100%;
	}
</style>
<div class="home-toolbar toolbar tabbar">
	<div class="toolbar-inner">
		<a href="#tab-home" class="tab-link tab-link-active">{{App\Language::trans('Home')}}</a>
		<a href="#tab-deals" class="tab-link">{{App\Language::trans('Deals')}}</a>
		<a href="#tab-hot-sell" class="tab-link">{{App\Language::trans('Hot Sell')}}</a>
		<a href="#tab-promo" class="tab-link">{{App\Language::trans('Promo')}}</a>
	</div>
</div>
<div class="tabs-swipeable-wrap">
	<div class="tabs">
		<div id="tab-home" class="page-content tab tab-active">
			<div class="block">
				@include('_version_02.web_stores.apps.partials.dashboard._home')
			</div>
		</div>
		<div id="tab-deals" class="page-content tab">
			<div class="block">
				@include('_version_02.web_stores.apps.partials.dashboard._deals')
			</div>
		</div>
		<div id="tab-hot-sell" class="page-content tab">
			<div class="block">
				@include('_version_02.web_stores.apps.partials.dashboard._hot_sell')
			</div>
		</div>
		<div id="tab-promo" class="page-content tab">
			<div class="block">
				@foreach($promotions as $promotion)
					@if($promotion['promotion_photo_path'])
						<div class="card">
							<img class="img-responsive lazy lazy-fade-in" src="{{$leaf_acc_api->get_image_src($promotion['promotion_photo_path'])}}">
						</div>
					@endif
				@endforeach
			</div>
		</div>
	</div>
</div>
@endsection
@section('script')
@endsection