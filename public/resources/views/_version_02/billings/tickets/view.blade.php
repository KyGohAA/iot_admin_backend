@extends('_version_02.commons.layouts.admin')
@section('content')
{!! Form::model($model, ['class'=>'form-horizontal']) !!}
@include('_version_02.commons.layouts.partials._alert')
<div class="box">
	<div class="box-header with-border">
		<h3 class="box-title">{{App\Language::trans('Detail Form')}}</h3>
		<div class="box-tools pull-right">
			<a href="{{action('TicketsController@getNew')}}" class="btn btn-block btn-info">
				<i class="fa fa-file"></i> {{App\Language::trans('New File')}}
			</a>
		</div>
	</div>
	<div class="box-body">
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
				<div class="form-group{{ $errors->has('customer_name') ? ' has-error' : '' }}">
					{!! Form::label('customer_name', App\Language::trans('Customer Name'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						<p class="form-control-static">{{$model->customer_name}}</p>
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
		<div class="row">
			<div class="col-md-12">
				<div class="form-group{{ $errors->has('complaint') ? ' has-error' : '' }}">
					{!! Form::label('complaint', App\Language::trans('Complaint'), ['class'=>'control-label col-md-2']) !!}
					<div class="col-md-10">
						<p class="form-control-static">{!!$model->complaint!!}</p>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="box">
	<div class="box-header with-border">
		<h3 class="box-title">{{App\Language::trans('Solution Listing')}}</h3>
		<div class="box-tools pull-right">
			<a href="{{action('TicketsController@getNew')}}" class="btn btn-block btn-info">
				<i class="fa fa-file"></i> {{App\Language::trans('New File')}}
			</a>
		</div>
	</div>
	<div class="box-body">
		@foreach($model->solutions as $index => $row)
			@if($index >= 1)
				<hr>
			@endif
			<div class="row">
				<div class="col-md-6">
					{{-- settled by --}}
					<div class="form-group{{ $errors->has('settled_by') ? ' has-error' : '' }}">
						{!! Form::label('settled_by', App\Language::trans('Settled By'), ['class'=>'control-label col-md-4']) !!}
						<div class="col-md-8">
							<p class="form-control-static">{{$row->settled_by}}</p>
						</div>
					</div>
				</div>
				<div class="col-md-6">
					{{-- settled_at --}}
					<div class="form-group{{ $errors->has('settled_at') ? ' has-error' : '' }}">
						{!! Form::label('settled_at', App\Language::trans('Settled Date'), ['class'=>'control-label col-md-4']) !!}
						<div class="col-md-8">
							<p class="form-control-static">{{$row->settled_at}}</p>
						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-6">
					{{-- solution --}}
					<div class="form-group{{ $errors->has('solution') ? ' has-error' : '' }}">
						{!! Form::label('solution', App\Language::trans('Solution'), ['class'=>'control-label col-md-4']) !!}
						<div class="col-md-8">
							<p class="form-control-static">{!!$row->solution!!}</p>
						</div>
					</div>
				</div>
			</div>
		@endforeach
	</div>
	<div class="box-footer">
		<div class="row">
			<div class="col-md-offset-2 col-md-10">
				<a href="{{action('TicketsController@getIndex')}}" class="btn btn-danger"><i class="fa fa-ban fa-fw"></i>{{App\Language::trans('Close')}}</a>
			</div>
		</div>
	</div>
</div>
{!! Form::close() !!}
@endsection
@section('script')
init_datepicker($(".settled_at"));
@endsection