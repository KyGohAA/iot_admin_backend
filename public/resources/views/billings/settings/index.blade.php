@extends('commons.layouts.admin')
@section('content')
<!-- Nav tabs -->
<ul class="nav nav-tabs margin-bottom-15" role="tablist">
	<li role="presentation" class="active">
		<a href="#basic_setting" aria-controls="basic_setting" role="tab" data-toggle="tab">{{App\Language::trans('Basic Setting')}}</a>
	</li>
	<li role="presentation">
		<a href="#advance_setting" aria-controls="advance_setting" role="tab" data-toggle="tab">{{App\Language::trans('Advance Setting')}}</a>
	</li>
</ul>

<!-- Tab panes -->
<div class="tab-content">
	<div role="tabpanel" class="tab-pane active" id="basic_setting">
		{!!Form::model($model, array("url"=>null,"method"=>"post","class"=>"form-horizontal","files"=>true))!!}
		@include('commons.layouts.partials._alert')

		<div class="box">
			<div class="box-header with-border">
				<h3 class="box-title">{{App\Language::trans('Company Logo')}}</h3>
				<div class="box-tools pull-right">
				</div>
			</div>
			<div class="box-body">
			  <div class="row">
			 	 <div class="col-md-6">
					 	 @if($model->logo_photo_path)
			               <div class="col-md-4">
			                  <img class="img-responsive" width="150" src="{{asset($model->logo_photo_path)}}">
			                  <label for="logo_photo_path">{{App\Language::trans('Logo Photo')}}</label>
			                  <!--    <div class="text-center checkbox-custom checkbox-danger mb5">
			                     {!!Form::checkbox("company_logo_del", $model->id_company, false, array("id"=>"company_logo_del"))!!}
			                     <label for="company_logo_del">{{App\Language::trans('Remove file')}}</label>
			                     </div> -->
			               </div>
			               @endif
			      </div>
			   </div>
			</div>
		</div>

		<div class="box">
			<div class="box-header with-border">
				<h3 class="box-title">{{App\Language::trans('Detail Form')}}</h3>
				</div>
			<div class="box-body">
				<div class="row">

					<div class="col-md-6">
					 	<div class="form-group {!!$errors->first('logo_photo_path') ? 'has-error' : ''!!}">
		                  <label for="logo_photo_path" class="control-label col-sm-4">{{App\Language::trans('Company Logo')}}</label>
		                  <div class="col-sm-8">
		                     {!!Form::file("logo_photo_path", array("id"=>"logo_photo_path","class"=>"form-control"))!!}
		                     {!!$errors->first('logo_photo_path', '<span for="logo_photo_path" class="help-block error">:message</span>')!!}
		                  </div>
		               </div>
					</div>

					<div class="col-md-6">
						<div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
							{!! Form::label('name', App\Language::trans('Name'), ['class'=>'control-label col-md-4']) !!}
							<div class="col-md-8">
								{!! Form::text('name', null, ['class'=>'form-control','autofocus','required']) !!}
		                        {!!$errors->first('name', '<label for="name" class="help-block error">:message</label>')!!}
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="box">
			<div class="box-header with-border">
				<h3 class="box-title">{{App\Language::trans('Address Information')}}</h3>
			</div>
			<div class="box-body">
				<div class="row">
					<div class="col-md-6">
						<div class="form-group{{ $errors->has('address') ? ' has-error' : '' }}">
							{!! Form::label('address', App\Language::trans('Address'), ['class'=>'control-label col-md-4']) !!}
							<div class="col-md-8">
								{!! Form::text('address', null, ['class'=>'form-control']) !!}
		                        {!!$errors->first('address', '<label for="address" class="help-block error">:message</label>')!!}
							</div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group{{ $errors->has('postcode') ? ' has-error' : '' }}">
							{!! Form::label('postcode', App\Language::trans('Postcode'), ['class'=>'control-label col-md-4']) !!}
							<div class="col-md-8">
								{!! Form::text('postcode', null, ['class'=>'form-control']) !!}
		                        {!!$errors->first('postcode', '<label for="postcode" class="help-block error">:message</label>')!!}
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group{{ $errors->has('country_id') ? ' has-error' : '' }}">
							{!! Form::label('country_id', App\Language::trans('Country'), ['class'=>'control-label col-md-4']) !!}
							<div class="col-md-8">
								{!! Form::select('country_id', App\Country::combobox(), null, ['class'=>'form-control select2','onchange'=>'init_state_selectbox(this)']) !!}
		                        {!!$errors->first('country_id', '<label for="country_id" class="help-block error">:message</label>')!!}
							</div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group{{ $errors->has('state_id') ? ' has-error' : '' }}">
							{!! Form::label('state_id', App\Language::trans('State'), ['class'=>'control-label col-md-4']) !!}
							<div class="col-md-8">
								{!! Form::select('state_id', App\State::combobox(old('country_id') ? old('country_id'):$model->country_id), null, ['class'=>'form-control','onchange'=>'init_city_selectbox(this)']) !!}
		                        {!!$errors->first('state_id', '<label for="state_id" class="help-block error">:message</label>')!!}
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group{{ $errors->has('city_id') ? ' has-error' : '' }}">
							{!! Form::label('city_id', App\Language::trans('City'), ['class'=>'control-label col-md-4']) !!}
							<div class="col-md-8">
								{!! Form::select('city_id', App\City::combobox(old('state_id') ? old('state_id'):$model->state_id), null, ['class'=>'form-control']) !!}
		                        {!!$errors->first('city_id', '<label for="city_id" class="help-block error">:message</label>')!!}
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="box">
			<div class="box-header with-border">
				<h3 class="box-title">{{App\Language::trans('Contact Information')}}</h3>
			</div>
			<div class="box-body">
				<div class="row">
					<div class="col-md-6">
						<div class="form-group{{ $errors->has('contact_person') ? ' has-error' : '' }}">
							{!! Form::label('contact_person', App\Language::trans('Contact Person'), ['class'=>'control-label col-md-4']) !!}
							<div class="col-md-8">
								{!! Form::text('contact_person', null, ['class'=>'form-control']) !!}
		                        {!!$errors->first('contact_person', '<label for="contact_person" class="help-block error">:message</label>')!!}
							</div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
							{!! Form::label('email', App\Language::trans('Email'), ['class'=>'control-label col-md-4']) !!}
							<div class="col-md-8">
								{!! Form::text('email', null, ['class'=>'form-control']) !!}
		                        {!!$errors->first('email', '<label for="email" class="help-block error">:message</label>')!!}
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group{{ $errors->has('tel') ? ' has-error' : '' }}">
							{!! Form::label('tel', App\Language::trans('Tel'), ['class'=>'control-label col-md-4']) !!}
							<div class="col-md-8">
								{!! Form::text('tel', null, ['class'=>'form-control']) !!}
		                        {!!$errors->first('tel', '<label for="tel" class="help-block error">:message</label>')!!}
							</div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group{{ $errors->has('mobile') ? ' has-error' : '' }}">
							{!! Form::label('mobile', App\Language::trans('Mobile'), ['class'=>'control-label col-md-4']) !!}
							<div class="col-md-8">
								{!! Form::text('mobile', null, ['class'=>'form-control']) !!}
		                        {!!$errors->first('mobile', '<label for="mobile" class="help-block error">:message</label>')!!}
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group{{ $errors->has('website') ? ' has-error' : '' }}">
							{!! Form::label('website', App\Language::trans('Website'), ['class'=>'control-label col-md-4']) !!}
							<div class="col-md-8">
								{!! Form::text('website', null, ['class'=>'form-control']) !!}
		                        {!!$errors->first('website', '<label for="website" class="help-block error">:message</label>')!!}
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="box-footer">
				<div class="row">
					<div class="col-md-offset-2 col-md-10">
						<button type="submit" class="btn btn-primary"><i class="fa fa-floppy-o fa-fw"></i>{{App\Language::trans('Save')}}</button>
						<a href="{{action('StoresController@getIndex')}}" class="btn btn-danger"><i class="fa fa-ban fa-fw"></i>{{App\Language::trans('Close')}}</a>
					</div>
				</div>
			</div>
		</div>
		{!! Form::close() !!}
	</div>
	<div role="tabpanel" class="tab-pane" id="advance_setting">
		{!! Form::model($model, ['class'=>'form-horizontal']) !!}
		@include('commons.layouts.partials._alert')
		<div class="box">
			<div class="box-header with-border">
				<h3 class="box-title">{{App\Language::trans('Credit Form')}}</h3>
			</div>
			<div class="box-body">
				<div class="row">
					<div class="col-md-6">
						<div class="form-group{{ $errors->has('is_min_credit') ? ' has-error' : '' }}">
							{!! Form::label('is_min_credit', App\Language::trans('Min. Credit ?'), ['class'=>'control-label col-md-4']) !!}
							<div class="col-md-8">
								<label class="radio-inline">
									{!! Form::radio('is_min_credit', 1, true, ['id'=>'is_min_credit_on']) !!} {{App\Language::trans('Enabled')}}
								</label>
								<label class="radio-inline">
									{!! Form::radio('is_min_credit', 0, false, ['id'=>'is_min_credit_off']) !!} {{App\Language::trans('Disabled')}}
								</label>
		                        {!!$errors->first('is_min_credit', '<label for="is_min_credit" class="help-block error">:message</label>')!!}
							</div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group{{ $errors->has('min_credit') ? ' has-error' : '' }}">
							{!! Form::label('min_credit', App\Language::trans('Min. Credit'), ['class'=>'control-label col-md-4']) !!}
							<div class="col-md-8">
								{!! Form::number('min_credit', null, ['class'=>'form-control','step'=>'.01','min'=>'0']) !!}
		                        {!!$errors->first('min_credit', '<label for="min_credit" class="help-block error">:message</label>')!!}
							</div>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-md-6">
						<div class="form-group{{ $errors->has('is_transaction_charge') ? ' has-error' : '' }}">
							{!! Form::label('is_transaction_charge', App\Language::trans('Transaction Charge ?'), ['class'=>'control-label col-md-4']) !!}
							<div class="col-md-8">
								<label class="radio-inline">
									{!! Form::radio('is_transaction_charge', 1, true, ['id'=>'is_transaction_charge_on']) !!} {{App\Language::trans('Enabled')}}
								</label>
								<label class="radio-inline">
									{!! Form::radio('is_transaction_charge', 0, false, ['id'=>'is_transaction_charge_off']) !!} {{App\Language::trans('Disabled')}}
								</label>
		                        {!!$errors->first('is_transaction_charge', '<label for="is_transaction_charge" class="help-block error">:message</label>')!!}
							</div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group{{ $errors->has('transaction_percent') ? ' has-error' : '' }}">
							{!! Form::label('transaction_percent', App\Language::trans('Transaction Percent'), ['class'=>'control-label col-md-4']) !!}
							<div class="col-md-8">
								<div class="input-group">
	  								{!! Form::number('transaction_percent', null, ['class'=>'form-control','step'=>'.01','min'=>'0']) !!}
									<span class="input-group-addon">%</span>
								</div>
		                        {!!$errors->first('transaction_percent', '<label for="transaction_percent" class="help-block error">:message</label>')!!}
							</div>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-md-6">
						<div class="form-group{{ $errors->has('is_prepaid') ? ' has-error' : '' }}">
							{!! Form::label('is_prepaid', App\Language::trans('Payment setting'), ['class'=>'control-label col-md-4']) !!}
							<div class="col-md-8">
								<label class="radio-inline">
									{!! Form::radio('is_prepaid', 1, true, ['id'=>'is_prepaid_on']) !!} {{App\Language::trans('Prepaid')}}
								</label>
								<label class="radio-inline">
									{!! Form::radio('is_prepaid', 0, false, ['id'=>'is_prepaid_off']) !!} {{App\Language::trans('Postpaid')}}
								</label>
		                        {!!$errors->first('is_prepaid', '<label for="is_prepaid" class="help-block error">:message</label>')!!}
							</div>
						</div>
					</div>

					<div class="col-md-6">
						<div class="form-group{{ $errors->has('is_prepaid') ? ' has-error' : '' }}">
							{!! Form::label('is_mobile_app_allow_payment', App\Language::trans('Is Mobile Apps Allow Payment'), ['class'=>'control-label col-md-4']) !!}
							<div class="col-md-8">
								<label class="radio-inline">
									{!! Form::radio('is_mobile_app_allow_payment', 1, true, ['id'=>'is_mobile_app_allow_payment_on']) !!} {{App\Language::trans('On')}}
								</label>
								<label class="radio-inline">
									{!! Form::radio('is_mobile_app_allow_payment', 0, false, ['id'=>'is_mobile_app_allow_payment_off']) !!} {{App\Language::trans('Off')}}
								</label>
		                        {!!$errors->first('is_mobile_app_allow_payment', '<label for="is_mobile_app_allow_payment" class="help-block error">:message</label>')!!}
							</div>
						</div>
					</div>
				</div>
				
				<div class="row">
					<div class="col-md-6">
						<div class="form-group{{ $errors->has('is_inclusive') ? ' has-error' : '' }}">
							{!! Form::label('is_inclusive', App\Language::trans('SST Setting'), ['class'=>'control-label col-md-4']) !!}
							<div class="col-md-8">
								<label class="radio-inline">
									{!! Form::radio('is_inclusive', 1, true, ['id'=>'is_inclusive_on']) !!} {{App\Language::trans('Inclusive')}}
								</label>
								<label class="radio-inline">
									{!! Form::radio('is_inclusive', 0, false, ['id'=>'is_inclusive_off']) !!} {{App\Language::trans('Exclusive')}}
								</label>
		                        {!!$errors->first('is_inclusive', '<label for="is_inclusive" class="help-block error">:message</label>')!!}
							</div>
						</div>
					</div>

					<div class="col-md-6">
						<div class="form-group{{ $errors->has('is_inclusive') ? ' has-error' : '' }}">
							{!! Form::label('is_inclusive', App\Language::trans('Mobile App Usage Display Setting'), ['class'=>'control-label col-md-4']) !!}
							<div class="col-md-8">
								<label class="radio-inline">
									{!! Form::radio('is_inclusive', 1, true, ['id'=>'is_inclusive_on']) !!} {{App\Language::trans('Accumulate')}}
								</label>
								<label class="radio-inline">
									{!! Form::radio('is_inclusive', 0, false, ['id'=>'is_inclusive_off']) !!} {{App\Language::trans('Monthly')}}
								</label>
		                        {!!$errors->first('is_inclusive', '<label for="is_inclusive" class="help-block error">:message</label>')!!}
							</div>
						</div>
					</div>
				</div>
				

				<div class="row">
					<div class="col-md-6">
						<div class="form-group{{ $errors->has('due_date_duration') ? ' has-error' : '' }}">
							{!! Form::label('due_date_duration', App\Language::trans('Due Date Duration'), ['class'=>'control-label col-md-4']) !!}
							<div class="col-md-8">
								{!! Form::number('due_date_duration', null, ['class'=>'form-control','min'=>'0']) !!}
		                        {!!$errors->first('due_date_duration', '<label for="due_date_duration" class="help-block error">:message</label>')!!}
							</div>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-md-6">
						<div class="form-group{{ $errors->has('monthly_cut_off_day') ? ' has-error' : '' }}">
							{!! Form::label('monthly_cut_off_day', App\Language::trans('Monthly cut off date'), ['class'=>'control-label col-md-4']) !!}
							<div class="col-md-8">
								{!! Form::number('monthly_cut_off_day', null, ['class'=>'form-control','min'=>'0']) !!}
		                        {!!$errors->first('monthly_cut_off_day', '<label for="monthly_cut_off_day" class="help-block error">:message</label>')!!}
							</div>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-md-6">
						<div class="form-group{{ $errors->has('system_live_date') ? ' has-error' : '' }}">
							{!! Form::label('system_live_date', App\Language::trans('System Live Date'), ['class'=>'control-label col-md-4']) !!}
							<div class="col-md-8">
								{!! Form::text('system_live_date', null, ['class'=>'form-control']) !!}
		                        {!!$errors->first('system_live_date', '<label for="system_live_date" class="help-block error">:message</label>')!!}
							</div>
						</div>
					</div>
				</div>
				
			</div>
			<div class="box-footer">
				<div class="row">
					<div class="col-md-offset-2 col-md-10">
						<button type="submit" class="btn btn-primary"><i class="fa fa-floppy-o fa-fw"></i>{{App\Language::trans('Save')}}</button>
						<a href="{{action('StoresController@getIndex')}}" class="btn btn-danger"><i class="fa fa-ban fa-fw"></i>{{App\Language::trans('Close')}}</a>
					</div>
				</div>
			</div>
		</div>
		{!!Form::close()!!}
	</div>
</div>
@stop
@section('script')
init_daterange_leaf_ui("input[name=system_live_date]");
@if(!$model->is_min_credit)
	$("input[name=min_credit]").closest(".form-group").hide();
@endif
$("#is_min_credit_on").on("click", function(){
	$("input[name=min_credit]").closest(".form-group").show("slow");
})
$("#is_min_credit_off").on("click", function(){
	$("input[name=min_credit]").closest(".form-group").hide("slow");
})
@if(!$model->is_transaction_charge)
	$("input[name=transaction_percent]").closest(".form-group").hide();
@endif
$("#is_transaction_charge_on").on("click", function(){
	$("input[name=transaction_percent]").closest(".form-group").show("slow");
})
$("#is_transaction_charge_off").on("click", function(){
	$("input[name=transaction_percent]").closest(".form-group").hide("slow");
})
@stop