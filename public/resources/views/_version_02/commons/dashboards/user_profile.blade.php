@extends('_version_02.commons.layouts.admin')
@section('content')


	@if(isset($membership_detail['membership_start_date']) && $membership_detail['membership_start_date'] != '')
		<div class="row">
			<div class="col-lg-12">
				<div class="card card-profile-feed">
		            <div class="card-header card-header-action">
						<div class="media align-items-center">
							<div class="media-img-wrap d-flex mr-10">
								<div class="avatar avatar-sm">
									<img src="{{Auth::user()->profile_jpg()}}" alt="user" class="avatar-img rounded-circle">
								</div>
							</div>
							<div class="media-body">
								<div class="text-capitalize font-weight-500 text-dark">{{Auth::user()->fullname}}</div>
								<div class="font-13">{{$membership_detail['membership_type']}}</div>
							</div>
						</div>
						<div class="d-flex align-items-center card-action-wrap">
							<!-- <div class="inline-block dropdown">
								<a class="dropdown-toggle no-caret" data-toggle="dropdown" href="#" aria-expanded="false" role="button"><i class="ion ion-ios-settings"></i></a>
								<div class="dropdown-menu dropdown-menu-right">
									<a class="dropdown-item" href="#">Action</a>
									<a class="dropdown-item" href="#">Another action</a>
									<a class="dropdown-item" href="#">Something else here</a>
									<div class="dropdown-divider"></div>
									<a class="dropdown-item" href="#">Separated link</a>
								</div>
							</div> -->
						</div>
					</div>
					<div class="row text-center">
						<div class="col-4 border-right pr-0">
							<div class="pa-15">
								@php
									$valid_period = App\Setting::get_date_different_in_day(date('Y-m-d', strtotime('now')),$membership_detail['membership_end_date']);
								@endphp
								@if($valid_period > 0)
									<span class="d-block display-6 text-dark mb-5">{{$valid_period}}</span>
									<span class="d-block text-capitalize font-14"> {{App\Language::trans('Days Valid')}}</span>		
								@else
									<span class="d-block display-6 text-dark mb-5">{{abs($valid_period)}}</span>
									<span class="d-block text-capitalize font-14"> {{App\Language::trans('Days Expired')}}</span>	
								@endif
											
							</div>
						</div>
						<div class="col-4 border-right px-0">
							<div class="pa-15">
								<span class="d-block display-6 text-dark mb-5">{{count($membership_detail['members'])}}</span>
								<span class="d-block text-capitalize font-14"> {{App\Language::trans('Members')}}</span>
							</div>
						</div>
						<div class="col-4 pl-0">
							<div class="pa-15">
								<span class="d-block display-6 text-dark mb-5">{{$membership_detail['is_payable_member'] == 'true' ? App\Language::trans('Approve') : App\Language::trans('Pending') }}</span>
								<span class="d-block text-capitalize font-14">Payment Status</span>
							</div>
						</div>
					</div>
					<ul class="list-group list-group-flush">
						
			                <li class="list-group-item"><span><i class="ion ion-md-calendar font-18 text-light-20 mr-10"></i><span></span></span><span class="ml-5 text-dark">{{App\Language::trans('Valid from')}} {{$membership_detail['membership_start_date']}} {{App\Language::trans('till')}} {{$membership_detail['membership_end_date']}}</span></li>
			                <li class="list-group-item"><span><i class="ion ion-md-briefcase font-18 text-light-20 mr-10"></i><span></span></span><span class="ml-5 text-dark">{{$membership_detail['member_detail']['house_member_phonenumber']}}</span></li>
			                <li class="list-group-item"><span><i class="ion ion-md-home font-18 text-light-20 mr-10"></i><span></span></span><span class="ml-5 text-dark">{{$membership_detail['member_detail']['house_member_address']}}</span></li>
			                <li class="list-group-item"><span><i class="ion ion-md-pin font-18 text-light-20 mr-10"></i><span></span></span><span class="ml-5 text-dark">{{$membership_detail['member_detail']['house_member_email']}}</span></li>
		            </ul>
				 </div>
			
				<div class="card card-profile-feed">
					<div class="card-header card-header-action">
						<h6><span>{{App\Language::trans('Members')}} <span class="badge badge-soft-primary ml-5"></span></span></h6>
						<a href="#" class="font-14 ml-auto"></a>
					</div>
					<div class="card-body pb-5">
						<div class="hk-row text-center">
							@foreach($membership_detail['members'] as $member)
								<div class="col-3 mb-15">
									<div class="w-100">
										<img src={{$member["house_member_photo"] == "" ?  asset('img/img-thumb.jpg')  : $member["house_member_photo"]}} alt="user" class="avatar avatar-md rounded-circle">
									</div>
									<span class="d-block font-14 text-truncate">{{$member['house_member_name']}}</span>
								</div>
							@endforeach
						</div>
					</div>
				</div>
				@if($is_allow_to_pay)
				   <a href="{{action('ARPaymentReceivedsController@getNewMembership')}}" class="btn btn-success btn-block btn-wth-icon mt-15">  
				   		<span class="icon-label"><span class="feather-icon"><i data-feather="credit-card"></i></span></span>
				   		<span class="btn-text">{{App\Language::trans('Membership Renewal')}}</span>
				   	</a>
				   	<br>
				@endif

			</div>
		</div>
	@else
		@php
			$company = App\Company::get_model_by_leaf_group_id(App\Company::get_group_id());
		@endphp
		<div class="row">
			<div class="col-lg-12">
				<div class="card card-profile-feed">
		            <div class="card-header card-header-action">
						<div class="media align-items-center">
							<div class="media-img-wrap d-flex mr-10">
								<div class="avatar avatar-sm">
									<img src="{{Auth::user()->profile_jpg()}}" alt="user" class="avatar-img rounded-circle">
								</div>
							</div>
							<div class="media-body">
								<div class="text-capitalize font-weight-500 text-dark">{{Auth::user()->fullname}}</div>
								<div class="font-13">{{$membership_detail['membership_type']}}</div>
							</div>
						</div>
						<div class="d-flex align-items-center card-action-wrap">
							<!-- <div class="inline-block dropdown">
								<a class="dropdown-toggle no-caret" data-toggle="dropdown" href="#" aria-expanded="false" role="button"><i class="ion ion-ios-settings"></i></a>
								<div class="dropdown-menu dropdown-menu-right">
									<a class="dropdown-item" href="#">Action</a>
									<a class="dropdown-item" href="#">Another action</a>
									<a class="dropdown-item" href="#">Something else here</a>
									<div class="dropdown-divider"></div>
									<a class="dropdown-item" href="#">Separated link</a>
								</div>
							</div> -->
						</div>
					</div>
				
					
				 </div>
			
				<div class="card card-profile-feed">
					<div class="card-header card-header-action">
						<h6><span>{{App\Language::trans('Group Information')}} <span class="badge badge-soft-primary ml-5"></span></span></h6>
						<a href="#" class="font-14 ml-auto"></a>
					</div>
					<div class="card-body pb-5">
						<div class="hk-row text-center">
							<!-- Apply membership to enjoy the facility in our club house. -->
						</div>
						<ul class="list-group list-group-flush">
			                <li class="list-group-item"><span><i class="ion ion-md-home font-18 text-light-20 mr-10"></i><span></span></span><span class="ml-5 text-dark">{{$company['name']}}</span></li>
			                <li class="list-group-item"><span><i class="ion ion-md-pin font-18 text-light-20 mr-10"></i><span></span></span><span class="ml-5 text-dark">{{$company->get_address()}}</span></li>
		         	   </ul>
					</div>
				</div>

				@if(App\Company::is_allow_to_access_module(App\Setting::LABEL_MODULE_ACCOUNTING))
				   <a href="{{action('ARPaymentReceivedsController@getNewMembership')}}" class="btn btn-success btn-block btn-wth-icon mt-15">  
				   		<span class="icon-label"><span class="feather-icon"><i data-feather="credit-card"></i></span></span>
				   		<span class="btn-text">{{App\Language::trans('Apply Membership')}}</span>
				   	</a>
				@endif
				   	<br>
			

			</div>
		</div>
	@endif




@endsection
@section('script')
$.get("{{action('DashboardsController@getDashboardCount')}}", function(data){
	$(".outstanding_count").html(data.outstanding_count);
	$(".min_credit_count").html(data.min_credit_count);
},"json");

@endsection