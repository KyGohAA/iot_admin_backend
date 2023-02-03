@extends('umrah.layouts.app')
@section('content')
<ul class="listview">
	<h3 class="text-uppercase">General URL</h3>
	<li><span class="col-md-3">Documentation url </span> 
		<span class="col-md-9">= {{action('AppsController@getDashboard')}}</span>
	</li>
	<li><span class="col-md-3">Login url </span> 
		<span class="col-md-9">= {{action('AuthsController@getLogin')}}?session_token=</span>
	</li>
	<li><span class="col-md-3">Check Login url </span> 
		<span class="col-md-9">= {{action('AuthsController@getCheckLogin')}}</span>
	</li>
	<li><span class="col-md-3">Logout url </span> 
		<span class="col-md-9">= {{action('AuthsController@getLogout')}}</span>
	</li>
	<h3 class="text-uppercase">Umrah URL</h3>
	<li><span class="col-md-3">Stores Listing url </span> 
		<span class="col-md-9">= {{action('AppsController@getStores')}}</span>
	</li>
	<li><span class="col-md-3">Vouchers Listing url </span> 
		<span class="col-md-9">= {{action('AppsController@getVouchers', [1])}}</span>
	</li>
	<li><span class="col-md-3">Voucher Detail url </span> 
		<span class="col-md-9">= {{action('AppsController@getVoucherDetail', [1])}}</span>
	</li>
	<li><span class="col-md-3">Voucher Claim url </span> 
		<span class="col-md-9">= {{action('AppsController@getVoucherClaim')}}</span>
	</li>
	<li><span class="col-md-3">To Do List Categories url </span> 
		<span class="col-md-9">= {{action('AppsController@getToDoListCategories')}}</span>
	</li>
	<li><span class="col-md-3">To Do Lists url </span> 
		<span class="col-md-9">= {{action('AppsController@getToDoLists')}}</span>
	</li>
	<li><span class="col-md-3">To Do List (Single Page) url </span> 
		<span class="col-md-9">= {{action('AppsController@getToDoListView', [1])}}</span>
	</li>
	<li><span class="col-md-3">Map Location url </span> 
		<span class="col-md-9">= {{action('AppsController@getMap')}}</span>
	</li>
	<h3 class="text-uppercase">Store URL</h3>
	<li><span class="col-md-3">Search Result</span>
		<span class="col-md-9">{{action('AppsWebStoresController@getSearchResult')}}?secret_token={company_secret_token}&store_id={id_company}&search={search_value}</span>
	</li>
	<li><span class="col-md-3">Checkout</span>
		<span class="col-md-9">{{action('AppsWebStoresController@getCheckout')}}?secret_token={company_secret_token}&store_id={id_company}</span>
	</li>
	<li><span class="col-md-3">Dashboard</span>
		<span class="col-md-9">{{action('AppsWebStoresController@getDashboard')}}?secret_token={company_secret_token}&store_id={id_company}</span>
	</li>
	<li><span class="col-md-3">Category List</span>
		<span class="col-md-9">{{action('AppsWebStoresController@getCategoryList')}}?secret_token={company_secret_token}&store_id={id_company}</span>
	</li>
	<li><span class="col-md-3">Search Histories</span>
		<span class="col-md-9">{{action('AppsWebStoresController@getSearchHistory')}}?secret_token={company_secret_token}&store_id={id_company}</span>
	</li>
	<li><span class="col-md-3">Cart</span>
		<span class="col-md-9">{{action('AppsWebStoresController@getCart')}}?secret_token={company_secret_token}&store_id={id_company}</span>
	</li>
	<li><span class="col-md-3">My Page</span>
		<span class="col-md-9">{{action('AppsWebStoresController@getMypage')}}?secret_token={company_secret_token}&store_id={id_company}</span>
	</li>
	<li><span class="col-md-3">Login</span>
		<span class="col-md-9">{{action('AppsWebStoresController@getLogin')}}?secret_token={company_secret_token}&store_id={id_company}</span>
	</li>
	<li><span class="col-md-3">Logout</span>
		<span class="col-md-9">{{action('AppsWebStoresController@getLogout')}}?secret_token={company_secret_token}&store_id={id_company}</span>
	</li>
	<h3 class="text-uppercase">Utility Charges</h3>
	<li><span class="col-md-3">Dashboard</span>
		<span class="col-md-9">{{action('AppsUtilityChargesController@getDashboard')}}</span>
	</li>
	<li><span class="col-md-3">Payment History</span>
		<span class="col-md-9">{{action('AppsUtilityChargesController@getPaymentHistory')}}</span>
	</li>

	<li><span class="col-md-3">Apps Invoicing</span>
		<span class="col-md-9">{{action('AppsUtilityChargesController@getDashboard')}}</span>
	</li>

	<li><span class="col-md-3">Apps Invoicing_2</span>
		<span class="col-md-9">{{action('AppAccountingDashboardsController@getDashboard')}}</span>
	</li>

</ul>
@endsection
@section('script')
@endsection