@extends('umrah.layouts.admin')
@section('content')
{!! Form::model($model, ['class'=>'form-horizontal']) !!}
@include('_version_02.umrah.layouts.partials._alert')
<div class="box">
	<div class="box-header with-border">
		<h3 class="box-title">{{App\Language::trans('Detail Form')}}</h3>
		<div class="box-tools pull-right">
			<a href="{{action('VouchersController@getNew')}}" class="btn btn-block btn-info">
				<i class="fa fa-file"></i> {{App\Language::trans('New File')}}
			</a>
		</div>
	</div>
	<div class="box-body">
		<div class="row">
			<div class="col-md-6">
				<div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
					{!! Form::label('name', App\Language::trans('Name'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						{!! Form::text('name', null, ['class'=>'form-control','autofocus','required']) !!}
                        {!!$errors->first('name', '<label for="name" class="help-block error">:message</label>')!!}
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group{{ $errors->has('store_id') ? ' has-error' : '' }}">
					{!! Form::label('store_id', App\Language::trans('Store'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						{!! Form::select('store_id', App\Store::combobox(), null, ['class'=>'form-control','required']) !!}
                        {!!$errors->first('store_id', '<label for="store_id" class="help-block error">:message</label>')!!}
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-6">
				<div class="form-group{{ $errors->has('valid_duration') ? ' has-error' : '' }}">
					{!! Form::label('valid_duration', App\Language::trans('Valid Duration'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						{!! Form::number('valid_duration', 1, ['class'=>'form-control','min'=>1]) !!}
						<span class="help-block">{{App\Language::trans('Note: Valid duration start count from date issued.')}}</span>
                        {!!$errors->first('valid_duration', '<label for="valid_duration" class="help-block error">:message</label>')!!}
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group{{ $errors->has('amount') ? ' has-error' : '' }}">
					{!! Form::label('amount', App\Language::trans('Amount'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						{!! Form::text('amount', null, ['class'=>'form-control','required']) !!}
                        {!!$errors->first('amount', '<label for="amount" class="help-block error">:message</label>')!!}
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
					{!! Form::label('description', App\Language::trans('Description'), ['class'=>'control-label col-md-2']) !!}
					<div class="col-md-10">
						{!! Form::textarea('description', null, ['rows'=>5,'class'=>'form-control']) !!}
                        {!!$errors->first('description', '<label for="description" class="help-block error">:message</label>')!!}
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="box-footer">
		<div class="row">
			<div class="col-md-offset-2 col-md-10">
				<button type="submit" class="btn btn-primary"><i class="fa fa-floppy-o fa-fw"></i>{{App\Language::trans('Save')}}</button>
				<a href="{{action('VouchersController@getIndex')}}" class="btn btn-danger"><i class="fa fa-ban fa-fw"></i>{{App\Language::trans('Close')}}</a>
			</div>
		</div>
	</div>
</div>
{!! Form::close() !!}
@endsection
@section('script')
@endsection