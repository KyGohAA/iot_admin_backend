@extends('web_stores.layouts.app')
@section('content')
<h4 class="margin-left-15">{{$page_title}}</h4>
<div class="list">
	<ul>
		@foreach($contact_lists as $contact)
			<li class="swipeout">
				<div class="swipeout-content">
					<a href="{{action('AppsWebStoresController@getCheckout', ['contact_id'=>$contact['id']])}}" class="item-link item-content external">
						<div class="item-inner item-cell addresses-list">
							<div class="item-row">
								<div class="item-cell">{{$contact['name']}}</div>
								<div class="item-cell text-right">{{$contact['is_default'] == true ? '[Default]':''}}</div>
							</div>
							<div class="item-row">
								<div class="item-cell">{{$contact['mobile']}}</div>
							</div>
							<div class="item-row">
								<div class="item-cell">{{$contact['email']}}</div>
							</div>
							<div class="item-row">
								<div class="item-cell">{{$contact['address1'].$contact['address2']}}</div>
							</div>
							<div class="item-row">
								<div class="item-cell">{{$contact['postcode'].', '.$contact['city']}}</div>
							</div>
							<div class="item-row">
								<div class="item-cell">{{$leaf_api->get_state_name($contact['id_state'], $contact['id_country']).', '.$leaf_api->get_country_name($contact['id_country'])}}</div>
							</div>
						</div>
					</a>
				</div>
				<div class="swipeout-actions-right">
					<a href="{{action('AppsWebStoresController@getEditContact', [$contact['id']])}}" class="color-orange alert-mark external">{{App\Language::trans('Edit')}}</a>
				</div>
			</li>
		@endforeach
		<li>
			<a href="{{action('AppsWebStoresController@getNewContact')}}" class="item-content external">
				<div class="item-inner item-cell addresses-list">
					<div class="item-row line-height-36">
						<div class="item-cell"><h3>{{App\Language::trans('Add a new address')}}</h3></div>
						<div class="item-cell text-right"><i class="f7-icons address-home">add</i></div>
					</div>
				</div>
			</a>
		</li>
	</ul>
</div>
@stop
@section('script')
@stop