@extends('_version_02.commons.layouts.admin')
@section('content')
{!! Form::model($model, ['class'=>'form-horizontal']) !!}
@include('_version_02.commons.layouts.partials._alert')
<section class="hk-sec-wrapper">
    <h5 class="hk-sec-title">{{App\Language::trans('User Detail')}}</h5><hr>

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
		</div>

		<div class="row">
			<div class="col-md-6">
				<div class="form-group{{ $errors->has('user_group_id') ? ' has-error' : '' }}">
					{!! Form::label('user_group_id', App\Language::trans('User Group'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						{!! Form::select('user_group_id', App\UserGroup::combobox(), isset($model['user_group_id']) ? $model['user_group_id']:0, ['class'=>'form-control']) !!}
                        {!!$errors->first('user_group_id', '<label for="user_group_id" class="help-block error">:message</label>')!!}
					</div>
				</div>
			</div>
		</div>


		<div class="row">
			<div class="col-md-12">
				<div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
					{!! Form::label('user_id', App\Language::trans('User Email'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-{{isset($model['email']) ? '4' : '12' }}">
						@if(isset($model['email']))
							{!! Form::text('email', null, ['class'=>'form-control','readonly']) !!}
						@else
							{!! Form::select('email[]', App\User::combobox_email_vs_email(), null, ['class'=>'form-control','multiple'=>'true']) !!}
						@endif
                        {!!$errors->first('email', '<label for="email" class="help-block error">:message</label>')!!}
					</div>
				</div>
			</div>
		</div>


		

		
		@if(App\Company::is_allow_to_access_module(App\Setting::LABEL_MODULE_POWER_MANAGEMENT))
			<h5 class="hk-sec-title mt-20">{{App\Language::trans('Power Management Module')}}</h5><hr>
			<div class="row">
				<div class="col-md-6">
						<div class="form-group{{ $errors->has('power_mangement_start_charging_date') ? ' has-error' : '' }}">
							{!! Form::label('power_mangement_start_charging_date', App\Language::trans('Power Management Start Charging Date'), ['class'=>'control-label col-md-6']) !!}
							<div class="col-md-8">
								{!! Form::text('power_mangement_start_charging_date', null, ['class'=>'form-control']) !!}
		                        {!!$errors->first('power_mangement_start_charging_date', '<label for="power_mangement_start_charging_date" class="help-block error">:message</label>')!!}
							</div>
						</div>
				</div>
			</div>
		@endif
</section>
@include('_version_02.commons.layouts.partials._form_floaring_footer_standard')
{!! Form::close() !!}
@endsection
@section('script')
@endsection