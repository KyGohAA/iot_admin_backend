@extends('web_stores.layouts.app')
@section('content')
<div class="list">
	<ul>
		@foreach($model as $row)
			<a class="external" href="{{action('AppsWebStoresController@getOrderDetail', [$row['id_sell_order']])}}">
				<div class="card">
					<div class="card-header">
						{{App\Language::trans('Order No :')}} {{$row['sell_order_doc_no']}}
					</div>
					<div class="card-content card-content-padding">
						<div class="row margin-bottom-10">
							<div class="col-70">
								{{App\Language::trans('Total Amount').' : '.$row['currency_code'].$row['sell_order_total_amount']}}
							</div>
						</div>
						<div class="row">
							<div class="col-50">
								{{App\Language::trans('Order Status').' : '.$row['sell_order_status']}}
							</div>
							<div class="col-50">
								{{App\Language::trans('Payment Status').' : '}}{{$row['sell_order_paid'] ? App\Language::trans('Paid'):App\Language::trans('Unpaid')}}
							</div>
						</div>
					</div>
				</div>
			</a>
		@endforeach
	</ul>
</div>
@stop
@section('script')
@stop