@extends('_version_02.commons.layouts.admin')
@section('content')
{!! Form::model($model, ['class'=>'form-horizontal']) !!}
@include('_version_02.commons.layouts.partials._alert')
<section class="hk-sec-wrapper">
    <h5 class="hk-sec-title">{{App\Language::trans('Ticket Detail')}}</h5><hr>
    <div class="row">
		<div class="col-md-6">
			<div class="form-group{{ $errors->has('document_no') ? ' has-error' : '' }}">
				{!! Form::label('document_no', App\Language::trans('Document No'), ['class'=>'control-label col-md-4']) !!}
				<div class="col-md-8">
					<p class="form-control-static">{{$model->document_no}}</p>
				</div>
			</div>
		</div>
		<div class="col-md-6">
			<div class="form-group{{ $errors->has('document_date') ? ' has-error' : '' }}">
				{!! Form::label('document_date', App\Language::trans('Document Date'), ['class'=>'control-label col-md-4']) !!}
				<div class="col-md-8">
					<p class="form-control-static">{{$model->document_date}}</p>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-6">
			<div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
				{!! Form::label('name', App\Language::trans('Customer Name'), ['class'=>'control-label col-md-4']) !!}
				<div class="col-md-8">
					<p class="form-control-static">{{$model->name}}</p>
				</div>
			</div>
		</div>
		<div class="col-md-6">
			<div class="form-group{{ $errors->has('complaint_date') ? ' has-error' : '' }}">
				{!! Form::label('complaint_date', App\Language::trans('Complaint Date'), ['class'=>'control-label col-md-4']) !!}
				<div class="col-md-8">
					<p class="form-control-static">{{$model->complaint_date}}</p>
				</div>
			</div>
		</div>
	</div>
</section>

<div class="card-columns card-column-1">
	<div class="card card-profile-feed mb-0 rounded-bottom-0">
		<div class="card-header card-header-action">
			<div class="media align-items-center">
				<div class="media-img-wrap d-flex mr-10">
					<div class="avatar avatar-sm">
						<img src='{{App\User::get_profile_pic_by_user_id($model->created_by)}}' alt="user" class="avatar-img rounded">
					</div>
				</div>
				<div class="media-body">
					<div class="text-capitalize font-weight-500 text-dark">{{$model->name}}</div>
					<div class="font-13">{{$model->complaint_date}}</div>
				</div>
			</div>
			<div class="d-flex align-items-center card-action-wrap">
				<div class="inline-block dropdown">
					<a class="dropdown-toggle no-caret" data-toggle="dropdown" href="#" aria-expanded="false" role="button"><i class="ion ion-ios-more"></i></a>
					<div class="dropdown-menu dropdown-menu-right">
						<a class="dropdown-item" href="#">Action</a>
						<a class="dropdown-item" href="#">Another action</a>
						<a class="dropdown-item" href="#">Something else here</a>
						<div class="dropdown-divider"></div>
						<a class="dropdown-item" href="#">Separated link</a>
					</div>
				</div>
			</div>
		</div>
		<div class="card-body">
			<p class="card-text mb-30">{!!$model->description!!}</p>
			<!-- <div class="feed-img-layout">
				<div class="row h-200p">
					<div class="col-6 h-100">
						<div class="feed-img h-100" style="background-image:url(dist/img/slide4.jpg);"></div>
					</div>
					<div class="col-6 h-100">
						<div class="row h-100">
							<div class="col-sm-12 h-95p mb-10">
								<div class="feed-img h-100" style="background-image:url(dist/img/slide1.jpg);"></div>
							</div>
							<div class="col-sm-12 h-95p">
								<div class="row h-100">
									<div class="col-6 h-100">
										<div class="feed-img h-100" style="background-image:url(dist/img/slide2.jpg);"></div>
									</div>
									<div class="col-6 h-100">
										<div class="feed-img h-100" style="background-image:url(dist/img/slide3.jpg);"></div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div> -->
		</div>
		<div class="card-footer justify-content-between">
			<div>
				<a href="#">{{count($model->solutions)}} Feedback(s)</a>
			</div>
		</div>
	</div>
	<div class="card card-profile-feed border-top-0 rounded-top-0">
		
	@foreach($model->solutions as $index => $row)
		<div class="card-body">
			<div class="media">
				<div class="media-img-wrap d-flex mr-10">
					<div class="avatar avatar-sm">
						<img src='{{App\User::get_profile_pic_by_user_id($row->created_by)}}' alt="user" class="avatar-img rounded">
					</div>
				</div>
				<div class="media-body">
					<div class="text-capitalize font-14 font-weight-500 text-dark">{{$row->settled_by}}</div>
					<div class="font-15"><p>{!!$row->solution!!}</p></div>
					<div class="d-flex mt-10">
						<span class="font-14 text-light mr-15">{{$row->created_at}}</span>
					</div>
				</div>
			</div>
		</div>
	@endforeach

		<div class="card-footer">
			<div class="media w-100 align-items-center">
				<div class="media-img-wrap d-flex mr-15">
					<div class="avatar avatar-sm">
						<img src="{{Auth::user()->profile_jpg()}}" alt="user" class="avatar-img rounded">
					</div>
				</div>
				<div class="media-body">
					<textarea name="solution" id="solution" class="form-control filled-input bg-transparent" rows="1" placeholder="{{App\Language::trans('Leave a feedback')}}"></textarea>
				</div>
			</div>
		</div>


	</div>
</div>

@include('_version_02.commons.layouts.partials._form_floaring_footer_standard')

{!! Form::close() !!}
@endsection
@section('script')
init_datepicker($(".settled_at"));
@endsection