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
			<div class="col-md-12">
				<div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
					{!! Form::label('user_id', App\Language::trans('User Email'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-{{isset($model['email']) ? '4' : '12' }}">
						{!! Form::text('email', null, ['class'=>'form-control']) !!}
                        {!!$errors->first('email', '<label for="email" class="help-block error">:message</label>')!!}
					</div>
				</div>
			</div>
		</div>


		<div class="row">
			<div class="col-md-12">
				<div class="form-group{{ $errors->has('fullname') ? ' has-error' : '' }}">
					{!! Form::label('fullname', App\Language::trans('Name'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-{{isset($model['fullname']) ? '4' : '12' }}">
						{!! Form::text('fullname', null, ['class'=>'form-control']) !!}
                        {!!$errors->first('fullname', '<label for="fullname" class="help-block error">:message</label>')!!}
					</div>
				</div>
			</div>

			<div class="col-md-12">
				<div class="form-group{{ $errors->has('phone_number') ? ' has-error' : '' }}">
					{!! Form::label('phone_number', App\Language::trans('Phone Number'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-{{isset($model['phone_number']) ? '4' : '12' }}">
						{!! Form::text('phone_number', null, ['class'=>'form-control']) !!}
                        {!!$errors->first('phone_number', '<label for="phone_number" class="help-block error">:message</label>')!!}
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