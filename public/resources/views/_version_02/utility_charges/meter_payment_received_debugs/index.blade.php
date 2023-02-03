@extends('_version_02.commons.layouts.admin')
@section('content')
@include('_version_02.commons.layouts.partials._alert')
@include('_version_02.utility_charges.meter_payment_received_debugs.partials._utransaction_debug_modal')
<section class="hk-sec-wrapper">
    <div class="row">
        <div class="col-sm">
            <div class="row">
           
                <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12 mb-30" onclick="init_loading_overlay();">
                	 <a href="{{action('UMeterPaymentReceivedDebugsController@getTransactionListing')}}">
	                    <div class="card text-white bg-primary">
	                        <div class="card-header">Transaction Listing</div>
	                        <div class="card-body">
	                            <p class="card-text">List all success and fail transaction(s) of current project to payment gateway.</p>
	                        </div>
	                    </div>
                     </a>
                </div>

                <!-- <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12 mb-30">
                    <div class="card text-white bg-secondary ">
                        <div class="card-header">Header</div>
                        <div class="card-body">
                            <h5 class="card-title text-white">Secondary Card</h5>
                            <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                        </div>
                    </div>
                </div> -->
                <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12 mb-30" onclick="init_transaction_debug_modal('transaction_recovery_modal');">
                    <div class="card text-white bg-success">
                        <div class="card-header">Re-query</div>
                        <div class="card-body">
                            <p class="card-text">Re-query payment gateway to check if any transaction fail to capture.</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12 mb-30" onclick="init_transaction_debug_modal('individual_detail_modal');">
                    <div class="card text-white bg-secondary">
                        <div class="card-header">Check by User</div>
                        <div class="card-body">
                            <p class="card-text">Details view on user transaction, display and patch the user transaction.</p>
                        </div>
                    </div>
                </div>    

                <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12 mb-30" onclick="init_loading_overlay();">
                     <a href="{{action('UMeterPaymentReceivedDebugsController@getTransactionListing')}}">
                        <div class="card text-white bg-danger">
                            <div class="card-header">Debug Log</div>
                            <div class="card-body">
                                <p class="card-text">List all result and log(s) of Debug module.</p>
                            </div>
                        </div>
                     </a>
                </div> 

            </div>
        </div>
    </div>


@if(isset($is_transaction_listing))
     <div class="row">
        <div class="col-sm">
            <div class="table-wrap">
                <table id="leaf_data_table" class="table tablesaw table-bordered table-hover mb-0 w-100 pb-30" data-tablesaw-minimap data-tablesaw-mode-switch>
                    <thead>
                        <tr>
                            @php 
                            	$priority_counter = 1 ; 
                            @endphp
                            @foreach($cols as $col)
									@if($col == 'id')
										<th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="0">#</th>
									@elseif(str_contains($col, '_id'))
										<th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="{{$priority_counter}}">{{App\Language::trans(ucwords(str_replace('_id', '', $col)))}}</th>
									@else
										<th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="{{$priority_counter}}">{{App\Language::trans(ucwords(str_replace('_', ' ', $col)))}}</th>
									@endif
				
								@php $priority_counter ++ ; @endphp
							@endforeach
							<!-- <th class="text-center">{{App\Language::trans('Action')}}</th> -->
                        </tr>
                    </thead>
                    <tbody>
                        @php $priority_counter = 1 ; @endphp
                        @foreach($model as $index => $row)
						<tr>
							<td class="text-center">{{$index+1}}</td>
							@foreach($row->toArray() as $key => $value)		
								@if($key != 'id' && $key != 'is_paid' && $key != 'pay_by' && $key != 'amount')					
									<td class="text-center">{{$value}}</td>	
								@elseif($key == 'amount')
									<td class="text-center">{{$row->setDouble($value)}}</td>
								@elseif($key == 'is_paid')
									<td class="text-center">{{($value == true ? 'Success' : 'Fail')}}</td>	
								@elseif($key == 'pay_by')
									<td class="text-center">{{App\Customer::get_customer_name_by_id($value)}}</td>	
								@endif			
							@endforeach
							<!-- <td class="text-center">
								<a onclick="return confirm(confirmMsg)" class="loading-label" href="{{action('PaymentTestingAllowListsController@getIndex', [$row->id])}}">{{App\Language::trans('Select')}}</a> |  -->
								<!-- <a href="{{action('HelpsController@getView', [$row->id])}}">{{App\Language::trans('View')}}</a> | 
								<a onclick="return confirm(confirmMsg)" class="loading-label" href="{{action('HelpsController@getDelete', [$row->id])}}">{{App\Language::trans('Del')}}</a> -->
							<!-- </td> -->
						</tr>
						@endforeach

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
@endif

@endsection
@section('script')
@endsection