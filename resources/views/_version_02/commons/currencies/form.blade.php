@extends('_version_02.commons.layouts.admin')
@section('content')
{!! Form::model($model, ['class'=>'form-horizontal']) !!}
@include('_version_02.commons.layouts.partials._alert')
<section class="hk-sec-wrapper">
    <h5 class="hk-sec-title">{{App\Language::trans('Currency Detail')}}</h5><hr>
    
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
				<div class="form-group{{ $errors->has('rate') ? ' has-error' : '' }}">
					{!! Form::label('rate', App\Language::trans('Rate'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						{!! Form::number('rate', $model->id ? $model->rate:0, ['min'=>'0','step'=>'0.01','class'=>'form-control','required']) !!}
                        {!!$errors->first('rate', '<label for="rate" class="help-block error">:message</label>')!!}
					</div>
				</div>
			</div>

			<div class="col-md-6">
				<div class="form-group{{ $errors->has('is_primary') ? ' has-error' : '' }}">
					{!! Form::label('is_primary', App\Language::trans('Is Primary'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-12">
						 <div class="row">	
						 	<div class="col-md-3">
							    <div class="custom-control custom-radio">
							        <input type="radio" id="is_primary_on" name="is_primary" checked class="custom-control-input">
							        <label class="custom-control-label" for="is_primary_on">{{App\Language::trans('Yes')}}</label>
							    </div>
							</div>
							<div class="col-md-3">
							    <div class="custom-control custom-radio">
							        <input type="radio" id="is_primary_off" name="is_primary"  class="custom-control-input">
							        <label class="custom-control-label" for="is_primary_off">{{App\Language::trans('No')}}</label>
							    </div>
							</div>
						 </div>
						 {!!$errors->first('is_primary', '<label for="is_primary" class="help-block error">:message</label>')!!}
					</div>
				</div>
			</div>
		</div>


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

			<div class="col-md-6">
				<div class="form-group{{ $errors->has('is_default') ? ' has-error' : '' }}">
					{!! Form::label('is_default', App\Language::trans('Is Default'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-12">
						 <div class="row">	
						 	<div class="col-md-3">
							    <div class="custom-control custom-radio">
							        <input type="radio" id="is_default_on" name="is_default" checked class="custom-control-input">
							        <label class="custom-control-label" for="is_default_on">{{App\Language::trans('Yes')}}</label>
							    </div>
							</div>
							<div class="col-md-3">
							    <div class="custom-control custom-radio">
							        <input type="radio" id="is_default_off" name="is_default"  class="custom-control-input">
							        <label class="custom-control-label" for="is_default_off">{{App\Language::trans('No')}}</label>
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
						{!! Form::textarea('remark', null, ['rows'=>'5','class'=>'form-control']) !!}
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