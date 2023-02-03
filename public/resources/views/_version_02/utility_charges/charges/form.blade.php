@extends('_version_02.commons.layouts.admin')
@section('content')
{!! Form::model($model, ['class'=>'form-horizontal']) !!}
@include('_version_02.commons.layouts.partials._alert')
<section class="hk-sec-wrapper">
    <h5 class="hk-sec-title">{{App\Language::trans($page_variables['page_title'])}}</h5><hr>

    	<div class="row">
			<div class="col-md-6">
				<div class="form-group{{ $errors->has('code') ? ' has-error' : '' }}">
					{!! Form::label('code', App\Language::trans('Code'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						{!! Form::text('code', null, ['class'=>'form-control','required']) !!}
                        {!!$errors->first('code', '<label for="code" class="help-block error">:message</label>')!!}
					</div>
				</div>
			</div>

			<div class="col-md-6">
				<div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
					{!! Form::label('name', App\Language::trans('Name'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						{!! Form::text('name', null, ['class'=>'form-control','required']) !!}
                        {!!$errors->first('name', '<label for="name" class="help-block error">:message</label>')!!}
					</div>
				</div>
			</div>
		</div>

		<div class="row">

				<div class="col-md-6">
					<div class="form-group{{ $errors->has('is_hourly') ? ' has-error' : '' }}">	
						{!! Form::label('is_hourly', App\Language::trans('Is Charge Hourly'), ['class'=>'control-label col-md-12']) !!}
						<div class="col-md-12">
							  <div class="row">	
							 	<div class="col-md-3">
								    <div class="custom-control custom-radio">
								        <input type="radio" id="is_hourly" name="is_hourly" value=1  class="custom-control-input" {{isset($model->is_hourly) == true ? ($model->is_hourly == true ? 'checked' : '') : 'checked'}}>
								        <label class="custom-control-label" for="is_hourly">{{App\ExtendModel::answer_true_word()}}</label>
								    </div>
								</div>
								<div class="col-md-3">
								    <div class="custom-control custom-radio">
								         <input type="radio" id="is_hourly_off" name="is_hourly" value=0 class="custom-control-input" {{isset($model->is_hourly) == true ? ($model->is_hourly == false ? 'checked' : '') : ''}}>
								        <label class="custom-control-label" for="is_hourly_off">{{App\ExtendModel::answer_false_word()}}</label>
								    </div>
								</div>
							 </div>
							 {!!$errors->first('is_hourly', '<label for="is_hourly" class="help-block error">:message</label>')!!}
						</div>
					</div>
				</div>	

				<div class="col-md-6">
					<div class="form-group{{ $errors->has('hourly_rate') ? ' has-error' : '' }}">
						{!! Form::label('hourly_rate', App\Language::trans('Hourly / Rate'), ['class'=>'control-label col-md-4']) !!}
						<div class="col-md-8">
							{!! Form::number('hourly_rate', null, ['class'=>'form-control','min'=>'0','step'=>'0.01']) !!}
	                        {!!$errors->first('hourly_rate', '<label for="hourly_rate" class="help-block error">:message</label>')!!}
						</div>
					</div>
				</div>
		</div>

    	<div class="row">
				<div class="col-md-6">
					<div class="form-group{{ $errors->has('status') ? ' has-error' : '' }}">	
						{!! Form::label('status', App\Language::trans('Status'), ['class'=>'control-label col-md-12']) !!}
						<div class="col-md-12">
							  <div class="row">	
							 	<div class="col-md-3">
								    <div class="custom-control custom-radio">
								        <input type="radio" id="status" name="status" value=1  class="custom-control-input" {{isset($model->status) == true ? ($model->status == true ? 'checked' : '') : 'checked'}}>
								        <label class="custom-control-label" for="status">{{App\Language::trans('Enabled')}}</label>
								    </div>
								</div>
								<div class="col-md-3">
								    <div class="custom-control custom-radio">
								         <input type="radio" id="status_off" name="status" value=0 class="custom-control-input" {{isset($model->status) == true ? ($model->status == false ? 'checked' : '') : ''}}>
								        <label class="custom-control-label" for="status_off">{{App\Language::trans('Disabled')}}</label>
								    </div>
								</div>
							 </div>
							 {!!$errors->first('status', '<label for="status" class="help-block error">:message</label>')!!}
						</div>
					</div>
				</div>
		</div>

		@include('_version_02.utility_charges.charges.__partials.__price_range')
		@include('_version_02.commons.layouts.partials._form_floaring_footer_standard')
</section>
{!! Form::close() !!}
@endsection
@section('script')
@if($model->is_hourly || old('is_hourly'))
	$("#prices").hide();
@else
	$("input[name=hourly_rate]").closest(".form-group").hide();
@endif
$("#is_hourly").on("click", function(){
	$("input[name=hourly_rate]").closest(".form-group").show();
	$("#prices").hide();
	$("#prices_section").hide();
});
$("#is_hourly_off").on("click", function(){
	$("#prices").show();
	$("#prices_section").show();
	$("input[name=hourly_rate]").closest(".form-group").hide();
});
@endsection