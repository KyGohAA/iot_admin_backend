@extends('_version_02.commons.layouts.admin')
@section('content')
{!! Form::model($model, ['class'=>'form-horizontal']) !!}
@include('_version_02.commons.layouts.partials._alert')
<section class="hk-sec-wrapper">
    <h5 class="hk-sec-title">{{App\Language::trans('State Detail')}}</h5><hr>
    
    <div class="row">
			<div class="col-md-6">
				<div class="form-group{{ $errors->has('country_id') ? ' has-error' : '' }}">
					{!! Form::label('country_id', App\Language::trans('Country'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						{!! Form::select('country_id', App\Country::combobox(), null, ['class'=>'form-control','autofocus','required']) !!}
                        {!!$errors->first('country_id', '<label for="country_id" class="help-block error">:message</label>')!!}
					</div>
				</div>
			</div>
		</div>
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
		</div>
		<div class="row">
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
</section>
@include('_version_02.commons.layouts.partials._form_floaring_footer_standard')
{!! Form::close() !!}
@endsection
@section('script')
@endsection