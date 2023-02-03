@extends('_version_02.commons.layouts.admin')
@section('content')
{!! Form::model($model, ['class'=>'form-horizontal']) !!}
@include('_version_02.commons.layouts.partials._alert')
<section class="hk-sec-wrapper">
    <h5 class="hk-sec-title">{{App\Language::trans('User Detail')}}</h5><hr>
    
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
			<div class="form-group{{ $errors->has('days') ? ' has-error' : '' }}">
				{!! Form::label('days', App\Language::trans('Days'), ['class'=>'control-label col-md-4']) !!}
				<div class="col-md-8">
					{!! Form::number('days', null, ['min'=>'0','class'=>'form-control','required']) !!}
                    {!!$errors->first('days', '<label for="days" class="help-block error">:message</label>')!!}
				</div>
			</div>
		</div>
	
		<div class="col-md-6">
			<div class="form-group{{ $errors->has('is_default') ? ' has-error' : '' }}">
				{!! Form::label('is_default', App\Language::trans('Is Default'), ['class'=>'control-label col-md-4']) !!}
				<div class="col-md-12">
					 <div class="row">	
					 	<div class="col-md-3">
						    <div class="custom-control custom-radio">
						        <input type="radio" id="is_default_on" name="is_default" checked class="custom-control-input">
						        <label class="custom-control-label" for="is_default_on">{{App\ExtendModel::answer_true_word()}}</label>
						    </div>
						</div>
						<div class="col-md-3">
						    <div class="custom-control custom-radio">
						        <input type="radio" id="is_default_off" name="is_default"  class="custom-control-input">
						        <label class="custom-control-label" for="is_default_off">{{App\ExtendModel::answer_false_word()}}</label>
						    </div>
						</div>
					 </div>
					 {!!$errors->first('is_default', '<label for="is_default" class="help-block error">:message</label>')!!}
				</div>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-md-12">
			<div class="form-group{{ $errors->has('remark') ? ' has-error' : '' }}">
				{!! Form::label('remark', App\Language::trans('Remark'), ['class'=>'control-label col-md-2']) !!}
				<div class="col-md-10">
					{!! Form::textarea('remark', null, ['rows'=>5,'class'=>'form-control']) !!}
                    {!!$errors->first('remark', '<label for="remark" class="help-block error">:message</label>')!!}
				</div>
			</div>
		</div>
	</div>
</section>

@include('_version_02.commons.layouts.partials._form_floaring_footer_standard')
{!! Form::close() !!}
@endsection
@section('script')
@endsection