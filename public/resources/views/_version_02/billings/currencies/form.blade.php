@extends('billings.layouts.admin')
@section('content')
{!! Form::model($model, ['class'=>'form-horizontal']) !!}
@include('_version_02.billings.layouts.partials._alert')
<div class="box">
	<div class="box-header with-border">
		<h3 class="box-title">{{App\Language::trans('Detail Form')}}</h3>
		<div class="box-tools pull-right">
			<a href="{{action('CurrenciesController@getNew')}}" class="btn btn-block btn-info">
				<i class="fa fa-file"></i> {{App\Language::trans('New File')}}
			</a>
		</div>
	</div>
	<div class="box-body">
		<div class="row">
			<div class="col-md-6">
				<div class="form-group{{ $errors->has('code') ? ' has-error' : '' }}">
					{!! Form::label('code', App\Language::trans('Code'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						{!! Form::text('code', null, ['class'=>'form-control','autofocus','required']) !!}
                        {!!$errors->first('code', '<label for="code" class="help-block error">:message</label>')!!}
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-6">
				<div class="form-group{{ $errors->has('symbol') ? ' has-error' : '' }}">
					{!! Form::label('symbol', App\Language::trans('Symbol'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						{!! Form::text('symbol', null, ['class'=>'form-control','required']) !!}
                        {!!$errors->first('symbol', '<label for="symbol" class="help-block error">:message</label>')!!}
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-6">
				<div class="form-group{{ $errors->has('status') ? ' has-error' : '' }}">
					{!! Form::label('status', App\Language::trans('Status'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						<label class="radio-inline">
							{!! Form::radio('status', 1, true) !!} {{App\Language::trans('Active')}}
						</label>
						<label class="radio-inline">
							{!! Form::radio('status', 0, false) !!} {{App\Language::trans('Inactive')}}
						</label>
                        {!!$errors->first('status', '<label for="status" class="help-block error">:message</label>')!!}
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group{{ $errors->has('is_default') ? ' has-error' : '' }}">
					{!! Form::label('is_default', App\Language::trans('Is Default'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						<label class="radio-inline">
							{!! Form::radio('is_default', 1, false) !!} {{App\Language::trans('Yes')}}
						</label>
						<label class="radio-inline">
							{!! Form::radio('is_default', 0, true) !!} {{App\Language::trans('No')}}
						</label>
                        {!!$errors->first('is_default', '<label for="is_default" class="help-block error">:message</label>')!!}
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-6">
				<div class="form-group{{ $errors->has('is_primary') ? ' has-error' : '' }}">
					{!! Form::label('is_primary', App\Language::trans('Is Primary'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						<label class="radio-inline">
							{!! Form::radio('is_primary', 1, false, ['class'=>'is_primary_on']) !!} {{App\Language::trans('Yes')}}
						</label>
						<label class="radio-inline">
							{!! Form::radio('is_primary', 0, true, ['class'=>'is_primary_off']) !!} {{App\Language::trans('No')}}
						</label>
                        {!!$errors->first('is_primary', '<label for="is_primary" class="help-block error">:message</label>')!!}
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group{{ $errors->has('rate') ? ' has-error' : '' }}">
					{!! Form::label('rate', App\Language::trans('Rate'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						{!! Form::number('rate', $model->id ? $model->rate:0, ['min'=>'0','step'=>'0.01','class'=>'form-control','required']) !!}
                        {!!$errors->first('rate', '<label for="rate" class="help-block error">:message</label>')!!}
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="form-group{{ $errors->has('remark') ? ' has-error' : '' }}">
					{!! Form::label('remark', App\Language::trans('Remark'), ['class'=>'control-label col-md-2']) !!}
					<div class="col-md-10">
						{!! Form::textarea('remark', null, ['rows'=>'5','class'=>'form-control']) !!}
                        {!!$errors->first('remark', '<label for="remark" class="help-block error">:message</label>')!!}
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="box-footer">
		<div class="row">
			<div class="col-md-offset-2 col-md-10">
				<button type="submit" class="btn btn-primary"><i class="fa fa-floppy-o fa-fw"></i>{{App\Language::trans('Save')}}</button>
				<a href="{{action('CurrenciesController@getIndex')}}" class="btn btn-danger"><i class="fa fa-ban fa-fw"></i>{{App\Language::trans('Close')}}</a>
			</div>
		</div>
	</div>
</div>
{!! Form::close() !!}
@endsection
@section('script')
@if($model->is_primary)
	$("input[name=rate]").closest(".form-group").hide();
@endif
$(".is_primary_on").on("click", function(){
	$("input[name=rate]").closest(".form-group").hide("slow");
});
$(".is_primary_off").on("click", function(){
	$("input[name=rate]").closest(".form-group").show("slow");
});
@endsection