@extends('_version_02.commons.layouts.admin')
@section('content')
{!! Form::model($model, ['class'=>'form-horizontal']) !!}
@include('_version_02.commons.layouts.partials._alert')

@php 
	$leaf_api = new App\LeafAPI();
	$leaf_product = $leaf_api->get_product_by_leaf_product_id_and_category($model['leaf_product_id']); 
	$member_available_slot =  $leaf_product['fee_type_user_per_unit'] - count($model->items) ;
@endphp
<!-- get_product_by_product_id_and_category -->
<section class="hk-sec-wrapper">
    <h5 class="hk-sec-title">{{App\Language::trans('Membership Detail')}}</h5><hr>
   		
   		<div class="row">
			<div class="col-md-6">
				<div class="form-group{{ $errors->has('status') ? ' has-error' : '' }}">
					{!! Form::label('status', App\Language::trans('Status'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-12">
						 <div class="row">	
						 	<div class="col-md-3">
							    <div class="custom-control custom-radio">
							        <input type="radio" id="status_on" name="status" checked class="custom-control-input">
							        <label class="custom-control-label" for="status_on">{{App\ExtendModel::status_true_word()}}</label>
							    </div>
							</div>
							<div class="col-md-3">
							    <div class="custom-control custom-radio">
							        <input type="radio" id="status_off" name="status"  class="custom-control-input">
							        <label class="custom-control-label" for="status_off">{{App\ExtendModel::status_false_word()}}</label>
							    </div>
							</div>
						 </div>
						 {!!$errors->first('status', '<label for="status" class="help-block error">:message</label>')!!}
					</div>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-md-6">
				<div class="form-group{{ $errors->has('type') ? ' has-error' : '' }}">
					{!! Form::label('type', App\Language::trans('Membership Package'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						{!! Form::text('type', null, ['class'=>'form-control','required','readonly']) !!}
                        {!!$errors->first('type', '<label for="type" class="help-block error">:message</label>')!!}
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group{{ $errors->has('price') ? ' has-error' : '' }}">
					{!! Form::label('price', App\Language::trans('Price'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						{!! Form::text('price', null, ['class'=>'form-control','required','readonly']) !!}
                        {!!$errors->first('price', '<label for="price" class="help-block error">:message</label>')!!}
					</div>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-md-6">
				<div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
					{!! Form::label('name', App\Language::trans('Member Per Package'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						{!! Form::text('name', $leaf_product['fee_type_user_per_unit'], ['class'=>'form-control','required','readonly']) !!}
	                    {!!$errors->first('name', '<label for="name" class="help-block error">:message</label>')!!}
					</div>
				</div>
			</div>

			<div class="col-md-6">
				<div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
					{!! Form::label('name', App\Language::trans('Allow Age Range'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						{!! Form::text('name', 'From '.$leaf_product['fee_type_user_min_age'].' to '.$leaf_product['fee_type_user_min_age'] , ['class'=>'form-control','required','readonly']) !!}
	                    {!!$errors->first('name', '<label for="name" class="help-block error">:message</label>')!!}
					</div>
				</div>
			</div>
		</div>

		

		<h5 class="hk-sec-title">{{App\Language::trans('Members')}}</h5><hr>
		@for($i = 0 ; $i < $member_available_slot ; $i ++)
			<div class="row">
				<div class="col-lg-12">
					<div class="card card-profile-feed bg-secondary">
			            <div class="card-header card-header-action">
							<div class="media align-items-center">
								<div class="media-img-wrap d-flex mr-10">
									<div class="avatar avatar-sm">
										<img src="{{asset('img/img-thumb.jpg')}}" alt="user" class="avatar-img rounded-circle">
									</div>
								</div>
								<div class="media-body">
									<div class="text-capitalize font-weight-500 text-dark">Empty</div>
									<div class="font-13"></div>
								</div>
							</div>
						</div>	
					 </div>
				</div>
			</div>
		@endfor

		@foreach($model->items as $member)
			<div class="row">
				<div class="col-lg-12">
					<div class="card card-profile-feed">
			            <div class="card-header card-header-action">
							<div class="media align-items-center">
								<div class="media-img-wrap d-flex mr-10">
									<div class="avatar avatar-sm">
										<img src="{{$member['profile_photo'] == '' ?  asset('img/img-thumb.jpg')  : $member['house_member_photo']}}" alt="user" class="avatar-img rounded-circle">
									</div>
								</div>
								<div class="media-body">
									<div class="text-capitalize font-weight-500 text-dark">{{$member['name']}}</div>
									<div class="font-13">{{$member['email']}}</div>
								</div>
							</div>
						</div>

							 <div class="table-wrap">
                                <div class="table-responsive">
                                    <table class="table table-sm mb-0">
                                        <thead>
                                            <tr>
                                                <th>Field</th>
                                                <th>Attributes</th>
                               
                                            </tr>
                                        </thead>
                                        <tbody>
                                				@php  
                                    				$age = App\Setting::calculate_age($member['dob'],'-');
                                    				$is_allow_to_join = $leaf_product['fee_type_user_min_age'] > $age ? ( $leaf_product['fee_type_user_min_age'] < $age ? true : false) : false;
                                    			@endphp

     				                			<tr class="table-{{!$is_allow_to_join ? 'danger' : 'success'}}">
							            			<td>Age</td>
							            			<td>{{$age}}</td>
									            </tr>

									            <tr class="table-{{!$is_allow_to_join ? 'danger' : 'success'}}">
							            			<td>DATE OF BIRTH</td>
							            			<td>{{$member['dob']}}</td>
									            </tr>

                                            	@foreach($member->toArray() as $key => $value)
                                            		 @if($key != 'dob')
	                                                	<tr class="table-{{$value =='' ? 'danger' : 'success'}}">
															@if(in_array( $key , App\MembershipItem::COLUMN_FOR_MEMBER_APPLICATION , TRUE))
											            		<td>{{ucwords(str_replace('_', ' ', $key))}}</td>
											            		<td>{{$member[$key]}}</td>
											            	@endif
											            	
											            </tr>
											        @endif   
									            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
					 </div>
				</div>
			</div>
		@endforeach

		
		
</section>
@include('_version_02.commons.layouts.partials._form_floaring_footer_standard')
{!! Form::close() !!}
@endsection
@section('script')
@endsection


