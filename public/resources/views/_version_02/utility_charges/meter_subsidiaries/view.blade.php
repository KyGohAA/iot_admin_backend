@extends('_version_02.commons.layouts.admin')
@section('content')
{!! Form::model($model, ['class'=>'form-horizontal']) !!}
@include('_version_02.commons.layouts.partials._alert')
<div class="box">
	<div class="box-header with-border">
		<h3 class="box-title">{{App\Language::trans('Information')}}</h3>
	</div>
	<div class="box-body">

		<div class="row">
			<div class="col-md-6">
				<div class="form-group{{ $errors->has('code') ? ' has-error' : '' }}">
					{!! Form::label('code', App\Language::trans('Code'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						<p class="form-control-static">{{$model->code}}</p>
					</div>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-md-6">
				<div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
					{!! Form::label('text', App\Language::trans('Name'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						<p class="form-control-static">{{$model->name}}</p>
					</div>
				</div>
			</div>
		</div>


		<div class="row">
			<div class="col-md-6">
				<div class="form-group{{ $errors->has('amount') ? ' has-error' : '' }}">
					{!! Form::label('amount', App\Language::trans('Amount'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
							<p class="form-control-static">{{$model->amount}}</p>	
					</div>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-md-6">
				<div class="form-group{{ $errors->has('room_type') ? ' has-error' : '' }}">
					{!! Form::label('room_type', App\Language::trans('Room Type'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
							<p class="form-control-static">{{$model->display_room_type_string('room_type')}}</p>	
					</div>
				</div>
			</div>	
		</div>



		<div class="row">
			<div class="col-md-6">
				<div class="form-group{{ $errors->has('starting_date') ? ' has-error' : '' }}">
					{!! Form::label('starting_date', App\Language::trans('From Month'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
							<p class="form-control-static">{{$model->setDate($model->starting_date)}}</p>	
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group{{ $errors->has('month_ended') ? ' has-error' : '' }}">
					{!! Form::label('ending_date', App\Language::trans('To Month'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
							<p class="form-control-static">{{$model->setDate($model->ending_date)}}</p>	
					</div>
				</div>
			</div>
		</div>

		
		<div class="row">
			<div class="col-md-6">
				<div class="form-group{{ $errors->has('implementation_date') ? ' has-error' : '' }}">
					{!! Form::label('implementation_date', App\Language::trans('Implementation Date'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
							<p class="form-control-static">{{$model->code}}</p>	
					</div>
				</div>
			</div>


			<div class="col-md-6">
				<div class="form-group{{ $errors->has('status') ? ' has-error' : '' }}">
					{!! Form::label('status', App\Language::trans('Status'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
							<p class="form-control-static">{{$model->display_status_string('status')}}</p>				
					</div>
				</div>
			</div>
		</div>


		<div class="row">
			<div class="col-md-6">
				<div class="form-group{{ $errors->has('remark') ? ' has-error' : '' }}">
					{!! Form::label('remark', App\Language::trans('Remark'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
							<p class="form-control-static">{{$model->code}}</p>	
					</div>
				</div>
			</div>
		</div>

	</div>
	<div class="box-footer">
		<div class="row">
			<div class="col-md-offset-2 col-md-10">
				<a href="{{action('UMeterSubsidiariesController@getIndex')}}" class="btn btn-danger"><i class="fa fa-ban fa-fw"></i>{{App\Language::trans('Close')}}</a>
			</div>
		</div>
	</div>
</div>
{!! Form::close() !!}
@stop
@section('script')

$(".input-daterange").datepicker({
	format: "dd-mm-yyyy",
});
@stop