@extends('web_stores.layouts.app')
@section('content')
<div class="row margin-top-15">
	@foreach($categories as $category)
		<div class="col-50">
			<a class="external" href="{{action('AppsWebStoresController@getCategory', ['store_category_id'=>$category['id_company_category'],'category_name'=>$category['company_category_name']])}}">
				<img class="img-responsive full-width lazy lazy-fade-in" src="{{$leaf_acc_api->get_image_src($category['company_category_photo_path'])}}">
				<p class="text-center text-normal word_in_one_row">{{$category['company_category_name']}}</p>
			</a>
		</div>
	@endforeach
</div>
@stop
@section('script')
@stop