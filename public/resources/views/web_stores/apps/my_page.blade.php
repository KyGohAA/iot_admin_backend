@extends('web_stores.layouts.app')
@section('content')
<div class="list">
    <ul>
    	<li>
    		<a class="external" href="{{action('AppsWebStoresController@getOrderHistory')}}">
    			<div class="item-content">
	    			<div class="item-media">
		    			<i class="f7-icons">box</i>
	    			</div>
	    			<div class="item-inner">
	    				<div class="item-title">
			    			{{App\Language::trans('My Orders')}}
		    			</div>
	    			</div>
	    		</div>
	    	</a>
    	</li>
    	<li>
    		<a class="external" href="{{action('AppsWebStoresController@getWishlist')}}">
    			<div class="item-content">
	    			<div class="item-media">
		    			<i class="f7-icons">heart</i>
	    			</div>
	    			<div class="item-inner">
	    				<div class="item-title">
			    			{{App\Language::trans('My Wishlist')}}
		    			</div>
	    			</div>
	    		</div>
	    	</a>
    	</li>
    	<li>
    		<a class="external" href="https://cloud.leaf.com.my/web/settings.php?mode=user&headerfooter=none&contentonly=1&app_secret={{App\LeafAPI::main_app_secret}}&session_token={{$session_token}}">
    			<div class="item-content">
	    			<div class="item-media">
		    			<i class="f7-icons">settings</i>
	    			</div>
	    			<div class="item-inner">
	    				<div class="item-title">
			    			{{App\Language::trans('Settings')}}
		    			</div>
	    			</div>
	    		</div>
	    	</a>
    	</li>
    	<li>
    		<a class="external" href="{{action('AppsWebStoresController@getRefundPolicy', ['secret_token'=>$secret_token, 'store_id'=>$store_id])}}">
    			<div class="item-content">
	    			<div class="item-media">
		    			<i class="f7-icons">document_text</i>
	    			</div>
	    			<div class="item-inner">
	    				<div class="item-title">
			    			{{App\Language::trans('Refund Policies')}}
		    			</div>
	    			</div>
	    		</div>
	    	</a>
    	</li>
    	<li>
    		<a class="external" href="{{action('AppsWebStoresController@getShippingPolicy', ['secret_token'=>$secret_token, 'store_id'=>$store_id])}}">
    			<div class="item-content">
	    			<div class="item-media">
		    			<i class="f7-icons">document_text</i>
	    			</div>
	    			<div class="item-inner">
	    				<div class="item-title">
			    			{{App\Language::trans('Shipping Policies')}}
		    			</div>
	    			</div>
	    		</div>
	    	</a>
    	</li>
    	<li>
    		<a class="external" href="{{action('AppsWebStoresController@getFAQ')}}">
    			<div class="item-content">
	    			<div class="item-media">
		    			<i class="f7-icons">help</i>
	    			</div>
	    			<div class="item-inner">
	    				<div class="item-title">
			    			{{App\Language::trans('Need Help?')}}
		    			</div>
	    			</div>
	    		</div>
	    	</a>
    	</li>
    </ul>
</div>
@stop
@section('script')
	function init_under_maintenance() {
		app.dialog.alert("{{App\Language::trans('This button is under maintenance.')}}", "{{App\Language::trans('Under Maintenance')}}");
		return false;
	}
@stop