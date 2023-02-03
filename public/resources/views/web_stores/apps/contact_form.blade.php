@extends('web_stores.layouts.app')
@section('content')
<div class="block-title">{{$page_title}}</div>
{!!Form::open()!!}
	<div class="list inline-labels no-hairlines-md">
		<ul>
			<li class="item-content item-input">
				<div class="item-inner">
					<div class="item-title item-label">{{App\Language::trans('Name')}}</div>
					<div class="item-input-wrap">
						{!!Form::text('name', isset($model['id']) ? $model['name']:null, ['id'=>'name','placeholder'=>App\Language::trans('Fullname')])!!}
						<span class="input-clear-button"></span>
					</div>
				</div>
			</li>
			<li class="item-content item-input">
				<div class="item-inner">
					<div class="item-title item-label">{{App\Language::trans('Address')}}</div>
					<div class="item-input-wrap">
						{!!Form::text('address1', isset($model['id']) ? $model['address1']:null, ['id'=>'address1','placeholder'=>App\Language::trans('No | Lot | Jalan')])!!}
						{!!Form::text('address2', isset($model['id']) ? $model['address2']:null, ['id'=>'address2','placeholder'=>App\Language::trans('Taman | Park')])!!}
					</div>
				</div>
			</li>
			<li class="item-content item-input">
				<div class="item-inner">
					<div class="item-title item-label">{{App\Language::trans('Postcode')}}</div>
					<div class="item-input-wrap">
						{!!Form::text('postcode', isset($model['id']) ? $model['postcode']:null, ['id'=>'postcode','placeholder'=>App\Language::trans('Postcode')])!!}
					</div>
				</div>
			</li>
			<li class="item-content item-input">
				<div class="item-inner">
					<div class="item-title item-label">{{App\Language::trans('Country')}}</div>
					<div class="item-input-wrap">
						{!!Form::select('country_id', $leaf_api->get_country_list(), isset($model['id']) ? $model['id_country']:null, ['id'=>'country_id','onchange'=>'init_state_selectbox(this)','placeholder'=>App\Language::trans('Please select one...')])!!}
					</div>
				</div>
			</li>
			<li class="item-content item-input">
				<div class="item-inner">
					<div class="item-title item-label">{{App\Language::trans('State')}}</div>
					<div class="item-input-wrap">
						{!!Form::select('state_id', $leaf_api->get_state_list(isset($model['id']) ? $model['id_country']:0), isset($model['id']) ? $model['id_state']:null, ['id'=>'state_id','placeholder'=>App\Language::trans('Please select one...')])!!}
					</div>
				</div>
			</li>
			<li class="item-content item-input">
				<div class="item-inner">
					<div class="item-title item-label">{{App\Language::trans('City')}}</div>
					<div class="item-input-wrap">
						{!!Form::text('postcode', isset($model['id']) ? $model['postcode']:null, ['id'=>'postcode','placeholder'=>App\Language::trans('City')])!!}
					</div>
				</div>
			</li>
			<li class="item-content item-input">
				<div class="item-inner">
					<div class="item-title item-label">{{App\Language::trans('Mobile')}}</div>
					<div class="item-input-wrap">
						{!!Form::text('mobile', isset($model['id']) ? $model['mobile']:null, ['id'=>'mobile','placeholder'=>App\Language::trans('Mobile')])!!}
					</div>
				</div>
			</li>
			<li class="item-content item-input">
				<div class="item-inner">
					<div class="item-title item-label">{{App\Language::trans('Email')}}</div>
					<div class="item-input-wrap">
						{!!Form::text('email', isset($model['id']) ? $model['email']:null, ['id'=>'email','placeholder'=>App\Language::trans('Email')])!!}
					</div>
				</div>
			</li>
			@if(isset($model['id']) && $model['id'])
				<li class="item-cehckbox item-content">
					<div class="item-inner">
						<div class="item-title item-label">{{App\Language::trans('Is Default')}}</div>
						<div class="item-input-wrap">
							<label class="checkbox">
								{!!Form::checkbox('is_default', 1, $model['is_default'] ? true:false, ['id'=>'is_default'])!!}
								<i class="icon-checkbox"></i>
							</label>
						</div>
					</div>
				</li>
			@endif
			<li><button class="item-link list-button">{{App\Language::trans('Save Contact')}}</button></li>
		</ul>
	</div>
{!!Form::close()!!}
@stop
@section('script')
	var statesComboboxUrl = "{{action('AppsWebStoresController@getStateCombobox')}}";
@stop