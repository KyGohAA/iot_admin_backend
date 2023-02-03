@extends('_version_02.commons.layouts.admin')
@section('content')
{!! Form::model($model, ['class'=>'form-horizontal',"files"=>true]) !!}
@include('_version_02.commons.layouts.partials._alert')
<section class="hk-sec-wrapper">
<div class="box">

	 <!-- Nav tabs -->
	<ul class="nav nav-light nav-tabs bo" role="tablist">
	
		<li role="presentation" class="nav-item active">
			<a href="#general_setting" aria-controls="general_setting" class="d-flex h-60p align-items-center nav-link active" role="tab" data-toggle="tab"><h5>{{App\Language::trans('General Setting')}}</h5></a>
		</li>

		@if(App\Company::is_allow_to_access_module(App\Setting::LABEL_MODULE_POWER_MANAGEMENT))
			<li role="presentation" class="nav-item">
				<a href="#power_management" aria-controls="power_management" class="d-flex h-60p align-items-center nav-link" role="tab" data-toggle="tab"><h5>{{App\Language::trans('Power Management')}}</h5></a>
			</li>
		@endif

		@if(App\Company::is_allow_to_access_module(App\Setting::LABEL_MODULE_ACCOUNTING))
			<li role="presentation" class="nav-item">
				<a href="#club_house" aria-controls="club_house" class="d-flex h-60p align-items-center nav-link" role="tab" data-toggle="tab"><h5>{{App\Language::trans('Club House')}}</h5></a>
			</li>
		@endif

		@if(App\Company::is_allow_to_access_module(App\Setting::LABEL_MODULE_ACCOUNTING))
			<li role="presentation" class="nav-item">
				<a href="#leaf_accounting" aria-controls="leaf_accounting" class="d-flex h-60p align-items-center nav-link" role="tab" data-toggle="tab"><h5>{{App\Language::trans('Leaf Accounting')}}</h5></a>
			</li>
		@endif
	</ul>
	<hr>
		
	<!-- Tab panes -->
	<div class="tab-content">	
	    <div role="tabpanel" class="tab-pane active" id="general_setting">
	    	
	    	 <h6 class="hk-sec-title">{{App\Language::trans('Company Information')}}</h6><hr>
	    	 <div class="row">
			    <div class="col-sm">
			        <div class="form-row">
			            <div class="col-md-12 mb-15">
			               @if($model->logo_photo_path)
			               <div class="col-md-4">
			                  <img class="img-fluid img-thumbnail img-responsive" width="150" height ="150" src="{{asset($model->logo_photo_path)}}">
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

	   		<div class="row mb-15">
					<div class="col-md-6">
					 	<div class="form-group {!!$errors->first('logo_photo_path') ? 'has-error' : ''!!}">
		                  <label for="logo_photo_path" class="control-label col-md-12">{{App\Language::trans('Company Logo')}}</label>
		                  <div class="col-md-8">
		                     {!!Form::file("logo_photo_path", array("id"=>"logo_photo_path","class"=>"form-control"))!!}
		                     {!!$errors->first('logo_photo_path', '<span for="logo_photo_path" class="help-block error">:message</span>')!!}
		                  </div>
		               </div>
					</div>

					<div class="col-md-6">
						<div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
							{!! Form::label('name', App\Language::trans('Name'), ['class'=>'control-label col-md-12']) !!}
							<div class="col-md-8">
								{!! Form::text('name', null, ['class'=>'form-control','autofocus','required']) !!}
		                        {!!$errors->first('name', '<label for="name" class="help-block error">:message</label>')!!}
							</div>
						</div>
					</div>
			</div> 

		
			<h6 class="hk-sec-title">{{App\Language::trans('System Information')}}</h6><hr>


	    	 <div class="row">
			    <div class="col-sm">
			        <div class="form-row">
			            <div class="col-md-12 mb-15">
			               @if($model->favicon_photo_path)
			               <div class="col-md-4">
			                  <img class="img-fluid img-thumbnail img-responsive" width="150" height ="150" src="{{asset($model->favicon_photo_path)}}">
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

			<div class="row mb-15">
					<div class="col-md-6">
					 	<div class="form-group {!!$errors->first('favicon_photo_path') ? 'has-error' : ''!!}">
		                  <label for="favicon_photo_path" class="control-label col-md-12">{{App\Language::trans('System Favicon')}}</label>
		                  <div class="col-md-8">
		                     {!!Form::file("favicon_photo_path", array("id"=>"favicon_photo_path","class"=>"form-control"))!!}
		                     {!!$errors->first('favicon_photo_path', '<span for="favicon_photo_path" class="help-block error">:message</span>')!!}
		                  </div>
		               </div>
					</div>

					<div class="col-md-6">
						<div class="form-group{{ $errors->has('system_name') ? ' has-error' : '' }}">
							{!! Form::label('system_name', App\Language::trans('System Name'), ['class'=>'control-label col-md-12']) !!}
							<div class="col-md-8">
								{!! Form::text('system_name', null, ['class'=>'form-control','required']) !!}
		                        {!!$errors->first('system_name', '<label for="system_name" class="help-block error">:message</label>')!!}
							</div>
						</div>
					</div>
			</div> 

			<h6 class="hk-sec-title">{{App\Language::trans('Address Information')}}</h6><hr>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group{{ $errors->has('address') ? ' has-error' : '' }}">
							{!! Form::label('address', App\Language::trans('Address'), ['class'=>'control-label col-md-12']) !!}
							<div class="col-md-8">
								{!! Form::text('address', null, ['class'=>'form-control']) !!}
		                        {!!$errors->first('address', '<label for="address" class="help-block error">:message</label>')!!}
							</div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group{{ $errors->has('postcode') ? ' has-error' : '' }}">
							{!! Form::label('postcode', App\Language::trans('Postcode'), ['class'=>'control-label col-md-12']) !!}
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
							{!! Form::label('country_id', App\Language::trans('Country'), ['class'=>'control-label col-md-12']) !!}
							<div class="col-md-8">
								{!! Form::select('country_id', App\Country::combobox(), null, ['class'=>'form-control','onchange'=>'init_state_selectbox(this)']) !!}
		                        {!!$errors->first('country_id', '<label for="country_id" class="help-block error">:message</label>')!!}
							</div>
						</div>
					</div>

					<div class="col-md-6">
						<div class="form-group{{ $errors->has('state_id') ? ' has-error' : '' }}">
							{!! Form::label('state_id', App\Language::trans('State'), ['class'=>'control-label col-md-12']) !!}
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
							{!! Form::label('city_id', App\Language::trans('City'), ['class'=>'control-label col-md-12']) !!}
							<div class="col-md-8">
								{!! Form::select('city_id', App\City::combobox(old('state_id') ? old('state_id'):$model->state_id), null, ['class'=>'form-control']) !!}
		                        {!!$errors->first('city_id', '<label for="city_id" class="help-block error">:message</label>')!!}
							</div>
						</div>
					</div>
				</div>

				<br>
				<h6 class="hk-sec-title">{{App\Language::trans('Contact Information')}}</h6> <hr>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group{{ $errors->has('contact_person') ? ' has-error' : '' }}">
							{!! Form::label('contact_person', App\Language::trans('Contact Person'), ['class'=>'control-label col-md-12']) !!}
							<div class="col-md-8">
								{!! Form::text('contact_person', null, ['class'=>'form-control']) !!}
		                        {!!$errors->first('contact_person', '<label for="contact_person" class="help-block error">:message</label>')!!}
							</div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
							{!! Form::label('email', App\Language::trans('Email'), ['class'=>'control-label col-md-12']) !!}
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
							{!! Form::label('tel', App\Language::trans('Tel'), ['class'=>'control-label col-md-12']) !!}
							<div class="col-md-8">
								{!! Form::text('tel', null, ['class'=>'form-control']) !!}
		                        {!!$errors->first('tel', '<label for="tel" class="help-block error">:message</label>')!!}
							</div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group{{ $errors->has('mobile') ? ' has-error' : '' }}">
							{!! Form::label('mobile', App\Language::trans('Mobile'), ['class'=>'control-label col-md-12']) !!}
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
							{!! Form::label('website', App\Language::trans('Website'), ['class'=>'control-label col-md-12']) !!}
							<div class="col-md-8">
								{!! Form::text('website', null, ['class'=>'form-control']) !!}
		                        {!!$errors->first('website', '<label for="website" class="help-block error">:message</label>')!!}
							</div>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-md-6">
						<div class="form-group{{ $errors->has('system_live_date') ? ' has-error' : '' }}">
							{!! Form::label('system_live_date', App\Language::trans('System Live Date'), ['class'=>'control-label col-md-12']) !!}
							<div class="col-md-8">
								{!! Form::text('system_live_date', null, ['class'=>'form-control']) !!}
		                        {!!$errors->first('system_live_date', '<label for="system_live_date" class="help-block error">:message</label>')!!}
							</div>
						</div>
					</div>

					@if(Auth::User()->is_super_admin)
						<div class="col-md-6">
							<div class="form-group{{ $errors->has('selected_module') ? ' has-error' : '' }}">
								{!! Form::label('selected_module', App\Language::trans('Modules'), ['class'=>'control-label col-md-12']) !!}
								<div class="col-md-8">
									{!! Form::select('selected_module[]', App\Setting::module_combobox(), strlen($model->selected_module) >  1 ? json_decode($model->selected_module,true):null, ['class'=>'form-control select2 chosen-select',"multiple"=>true,"id"=>"selected_module"]) !!}
			                        {!!$errors->first('selected_module', '<label for="country_id" class="help-block error">:message</label>')!!}
								</div>
							</div>
						</div>
					@endif

				</div>

			<!-- End Tab Panel -->	
			</div>


		<div role="tabpanel" class="tab-pane" id="power_management">		
			<h6 class="hk-sec-title">{{App\Language::trans('Billing Setting')}}</h6> <hr>

			<div class="row">
					<div class="col-md-6">
						<div class="form-group{{ $errors->has('is_mobile_app_allow_payment') ? ' has-error' : '' }}">
							{!! Form::label('is_mobile_app_allow_payment', App\Language::trans('Is Mobile Apps Allow Payment'), ['class'=>'control-label col-md-12']) !!}
							<div class="col-md-12">
								 <div class="row">	
								 	<div class="col-md-3">
									    <div class="custom-control custom-radio">
									        <input type="radio" id="is_mobile_app_allow_payment_on" name="is_mobile_app_allow_payment" checked class="custom-control-input">
									        <label class="custom-control-label" for="is_mobile_app_allow_payment_on">{{App\Language::trans('Enabled')}}</label>
									    </div>
									</div>
									<div class="col-md-3">
									    <div class="custom-control custom-radio">
									        <input type="radio" id="is_mobile_app_allow_payment_off" name="is_mobile_app_allow_payment"  class="custom-control-input">
									        <label class="custom-control-label" for="is_mobile_app_allow_payment_off">{{App\Language::trans('Disabled')}}</label>
									    </div>
									</div>
								 </div>
								 {!!$errors->first('is_mobile_app_allow_payment', '<label for="is_mobile_app_allow_payment" class="help-block error">:message</label>')!!}
							</div>
						</div>
					</div>

					<div class="col-md-6">
						<div class="form-group{{ $errors->has('is_prepaid') ? ' has-error' : '' }}">
							{!! Form::label('is_prepaid', App\Language::trans('Payment setting'), ['class'=>'control-label col-md-12']) !!}
							<div class="col-md-12">
								 <div class="row">	
								 	<div class="col-md-3">
									    <div class="custom-control custom-radio">
									        <input type="radio" id="is_prepaid_on" name="is_prepaid" checked class="custom-control-input">
									        <label class="custom-control-label" for="is_prepaid_on">{{App\Language::trans('Prepaid')}}</label>
									    </div>
									</div>
									<div class="col-md-3">
									    <div class="custom-control custom-radio">
									        <input type="radio" id="is_prepaid_off" name="is_prepaid"  class="custom-control-input">
									        <label class="custom-control-label" for="is_prepaid_off">{{App\Language::trans('Postpaid')}}</label>
									    </div>
									</div>
								 </div>
								 {!!$errors->first('is_prepaid', '<label for="is_prepaid" class="help-block error">:message</label>')!!}
							</div>
						</div>
					</div>
				</div>

	    	 <div class="row">
					<div class="col-md-6">
						<div class="form-group{{ $errors->has('is_min_credit') ? ' has-error' : '' }}">
							{!! Form::label('is_min_credit', App\Language::trans('Min. Credit'), ['class'=>'control-label col-md-12']) !!}
							<div class="col-md-12">
								 <div class="row">	
								 	<div class="col-md-3">
									    <div class="custom-control custom-radio">
									        <input type="radio" id="is_min_credit_on" name="is_min_credit" checked class="custom-control-input">
									        <label class="custom-control-label" for="is_min_credit_on">{{App\Language::trans('Enabled')}}</label>
									    </div>
									</div>
									<div class="col-md-3">
									    <div class="custom-control custom-radio">
									        <input type="radio" id="is_min_credit_off" name="is_min_credit"  class="custom-control-input">
									        <label class="custom-control-label" for="is_min_credit_off">{{App\Language::trans('Disabled')}}</label>
									    </div>
									</div>
								 </div>
								 {!!$errors->first('is_min_credit', '<label for="is_min_credit" class="help-block error">:message</label>')!!}
							</div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group{{ $errors->has('min_credit') ? ' has-error' : '' }}">
							{!! Form::label('min_credit', App\Language::trans('Min. Credit'), ['class'=>'control-label col-md-12']) !!}
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
							{!! Form::label('is_transaction_charge', App\Language::trans('Transaction Charge'), ['class'=>'control-label col-md-12']) !!}
							<div class="col-md-12">
								 <div class="row">	
								 	<div class="col-md-3">
									    <div class="custom-control custom-radio">
									        <input type="radio" id="is_transaction_charge_on" name="is_transaction_charge" checked class="custom-control-input">
									        <label class="custom-control-label" for="is_transaction_charge_on">{{App\Language::trans('Enabled')}}</label>
									    </div>
									</div>
									<div class="col-md-3">
									    <div class="custom-control custom-radio">
									        <input type="radio" id="is_transaction_charge_off" name="is_transaction_charge"  class="custom-control-input">
									        <label class="custom-control-label" for="is_transaction_charge_off">{{App\Language::trans('Disabled')}}</label>
									    </div>
									</div>
								 </div>
								 {!!$errors->first('is_transaction_charge', '<label for="is_transaction_charge" class="help-block error">:message</label>')!!}
							</div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group{{ $errors->has('transaction_percent') ? ' has-error' : '' }}">
							{!! Form::label('transaction_percent', App\Language::trans('Transaction Percent'), ['class'=>'control-label col-md-12']) !!}
							<div class="col-md-8">
								<div class="input-group">
	  								{!! Form::number('transaction_percent', null, ['class'=>'form-control','step'=>'.01','min'=>'0']) !!}
									<div class="input-group-append">
                                        <span class="input-group-text" id="basic-addon2">%</span>
                                    </div>
								</div>
		                        {!!$errors->first('transaction_percent', '<label for="transaction_percent" class="help-block error">:message</label>')!!}
							</div>
						</div>
					</div>
				</div>

				
				
				<div class="row">
					<div class="col-md-6">
						<div class="form-group{{ $errors->has('is_inclusive') ? ' has-error' : '' }}">
							{!! Form::label('is_inclusive', App\Language::trans('SST Setting'), ['class'=>'control-label col-md-12']) !!}
							<div class="col-md-12">
								 <div class="row">	
								 	<div class="col-md-3">
									    <div class="custom-control custom-radio">
									        <input type="radio" id="is_inclusive_on" name="is_inclusive" checked class="custom-control-input">
									        <label class="custom-control-label" for="is_inclusive_on">{{App\Language::trans('Inclusive')}}</label>
									    </div>
									</div>
									<div class="col-md-3">
									    <div class="custom-control custom-radio">
									        <input type="radio" id="is_inclusive_off" name="is_inclusive"  class="custom-control-input">
									        <label class="custom-control-label" for="is_inclusive_off">{{App\Language::trans('Exclusive')}}</label>
									    </div>
									</div>
								 </div>
								 {!!$errors->first('is_inclusive', '<label for="is_inclusive" class="help-block error">:message</label>')!!}
							</div>
						</div>
					</div>

					<div class="col-md-6">
						<div class="form-group{{ $errors->has('is_pay_by_accumulate') ? ' has-error' : '' }}">
							{!! Form::label('is_pay_by_accumulate', App\Language::trans('Mobile App Usage Display Setting'), ['class'=>'control-label col-md-12']) !!}			
							<div class="col-md-12">
								 <div class="row">	
								 	<div class="col-md-3">
									    <div class="custom-control custom-radio">
									        <input type="radio" id="is_pay_by_accumulate_on" name="is_pay_by_accumulate" checked class="custom-control-input">
									        <label class="custom-control-label" for="is_pay_by_accumulate_on">{{App\Language::trans('Accumulate')}}</label>
									    </div>
									</div>
									<div class="col-md-3">
									    <div class="custom-control custom-radio">
									        <input type="radio" id="is_pay_by_accumulate_off" name="is_pay_by_accumulate"  class="custom-control-input">
									        <label class="custom-control-label" for="is_pay_by_accumulate_off">{{App\Language::trans('Monthly')}}</label>
									    </div>
									</div>
								 </div>
								 {!!$errors->first('is_pay_by_accumulate', '<label for="is_pay_by_accumulate" class="help-block error">:message</label>')!!}
							</div>
						</div>
					</div>
				</div>

				<div class="row mb-15">
					<div class="col-md-6">
						<div class="form-group{{ $errors->has('monthly_cut_off_day') ? ' has-error' : '' }}">
							{!! Form::label('monthly_cut_off_day', App\Language::trans('Monthly cut off date'), ['class'=>'control-label col-md-12']) !!}
							<div class="col-md-8">
								{!! Form::number('monthly_cut_off_day', null, ['class'=>'form-control','min'=>'1','max'=>'31','autofocus']) !!}
				                {!!$errors->first('monthly_cut_off_day', '<label for="monthly_cut_off_day" class="help-block error">:message</label>')!!}
							</div>
						</div>
					</div>

					<div class="col-md-6">
						<div class="form-group{{ $errors->has('due_date_duration') ? ' has-error' : '' }}">
							{!! Form::label('due_date_duration', App\Language::trans('Due Date Duration'), ['class'=>'control-label col-md-12']) !!}
							<div class="col-md-8">
								{!! Form::number('due_date_duration', null, ['class'=>'form-control','min'=>'0']) !!}
				                {!!$errors->first('due_date_duration', '<label for="due_date_duration" class="help-block error">:message</label>')!!}
							</div>
						</div>
					</div>
				</div>

			<!-- <h6 class="hk-sec-title">{{App\Language::trans('Refund Process Setting')}}</h6> <hr>
		    	 
	    	 <div class="row">
					<div class="col-md-6">
						<div class="form-group{{ $errors->has('selected_module') ? ' has-error' : '' }}">
							{!! Form::label('selected_module', App\Language::trans('Refund Report To'), ['class'=>'control-label col-md-12']) !!}
							<div class="col-md-8">
								 {!!Form::select("tester_id[]", App\Customer::combobox_from_leaf(), strlen($model->tester_id) >  1 ? json_decode($model->tester_id,true):null, array("style"=>"width: 100%;", "multiple class"=>"chosen-select","class"=>"form-control select2","id"=>"tester_id","multiple"=>true))!!}
		                        {!!$errors->first('selected_module', '<label for="country_id" class="help-block error">:message</label>')!!}
							</div>
						</div>
					</div>
			</div>
 -->
 			@php
	 			if(isset($model->id)){

					$power_meter_operational_setting = json_decode($model->power_meter_operational_setting);
					$power_meter_mailbox_setting = json_decode($model->power_meter_mailbox_setting);
				}
			@endphp

			<h6 class="hk-sec-title mt-15">{{App\Language::trans('Power Meter Operational Setting')}}</h6> <hr>
		   		
				<div class="row">
					<div class="col-md-6">
						<div class="form-group{{ $errors->has('power_meter_operational_setting[is_auto_turn_off_meter]') ? ' has-error' : '' }}">
							{!! Form::label('power_meter_operational_setting[is_auto_turn_off_meter]', App\Language::trans('Turn Off Meter When No Credit '), ['class'=>'control-label col-md-12']) !!}
							<div class="col-md-12">
								 <div class="row">	
								 	<div class="col-md-3">
									    <div class="custom-control custom-radio">
									        <input type="radio" id="power_meter_operational_setting[is_auto_turn_off_meter]_on" name="power_meter_operational_setting[is_auto_turn_off_meter]" checked class="custom-control-input">
									        <label class="custom-control-label" for="power_meter_operational_setting[is_auto_turn_off_meter]_on">{{App\Language::trans('Auto')}}</label>
									    </div>
									</div>
									<div class="col-md-3">
									    <div class="custom-control custom-radio">
									        <input type="radio" id="power_meter_operational_setting[is_auto_turn_off_meter]_off" name="power_meter_operational_setting[is_auto_turn_off_meter]"  class="custom-control-input">
									        <label class="custom-control-label" for="power_meter_operational_setting[is_auto_turn_off_meter]_off">{{App\Language::trans('Manual')}}</label>
									    </div>
									</div>
								 </div>
								 {!!$errors->first('power_meter_operational_setting[is_auto_turn_off_meter]', '<label for="power_meter_operational_setting[is_auto_turn_off_meter]" class="help-block error">:message</label>')!!}
							</div>
						</div>
					</div>
				</div>


		   		 <div class="row">
					<div class="col-md-6">
						<div class="form-group{{ $errors->has('power_meter_operational_setting[credit_threshold]') ? ' has-error' : '' }}">
							{!! Form::label('power_meter_operational_setting[credit_threshold]', App\Language::trans('Credit Threshold (RM)'), ['class'=>'control-label col-md-12']) !!}
							<div class="col-md-8">
								{!! Form::number('power_meter_operational_setting[credit_threshold]', (isset($model->id) ? (isset($power_meter_operational_setting->credit_threshold) ? $power_meter_operational_setting->credit_threshold : '') : null), ['class'=>'form-control','min'=>'0.01','max'=>'999999','step'=>'0.01','autofocus']) !!}
				                {!!$errors->first('power_meter_operational_setting[credit_threshold]', '<label for="power_meter_operational_setting[credit_threshold]" class="help-block error">:message</label>')!!}
							</div>
						</div>
					</div>

					<div class="col-md-6">
						<div class="form-group{{ $errors->has('power_meter_operational_setting[grace_period_before_stop_supply]') ? ' has-error' : '' }}">
							{!! Form::label('power_meter_operational_setting[grace_period_before_stop_supply]', App\Language::trans('Grace Peiod (minutes) After Credit Below Threshold'), ['class' => 'control-label col-md-12']) !!}
							<div class="col-md-8">
								{!! Form::text('power_meter_operational_setting[grace_period_before_stop_supply]', (isset($model->id) ? (isset($power_meter_operational_setting->grace_period_before_stop_supply) ? $power_meter_operational_setting->grace_period_before_stop_supply : '') : null), ['class'=>'form-control','min'=>'1','max'=>'999999','autofocus']) !!}
				                {!!$errors->first('power_meter_operational_setting[grace_period_before_stop_supply]', '<label for="power_meter_operational_setting[grace_period_before_stop_supply]" class="help-block error">:message</label>')!!}
							</div>
						</div>
					</div>
				</div>


				 <div class="row">
					<div class="col-md-6">
						<div class="form-group{{ $errors->has('power_meter_operational_setting[warning_email_interval]') ? ' has-error' : '' }}">
							{!! Form::label('power_meter_operational_setting[warning_email_interval]', App\Language::trans('Interval (minutes) To Send Warning Email'), ['class'=>'control-label col-md-12']) !!}
							<div class="col-md-8">
								{!! Form::number('power_meter_operational_setting[warning_email_interval]', (isset($model->id) ? (isset($power_meter_operational_setting->warning_email_interval) ? $power_meter_operational_setting->warning_email_interval : '') : null), ['class'=>'form-control','min'=>'1','max'=>'999999','autofocus']) !!}
				                {!!$errors->first('power_meter_operational_setting[warning_email_interval]', '<label for="power_meter_operational_setting[warning_email_interval]" class="help-block error">:message</label>')!!}
							</div>
						</div>
					</div>

					<div class="col-md-6">
						<div class="form-group{{ $errors->has('power_meter_operational_setting[warning_email_number]') ? ' has-error' : '' }}">
							{!! Form::label('power_meter_operational_setting[warning_email_number]', App\Language::trans('Number Of Warning Email To Be Sent'), ['class'=>'control-label col-md-12']) !!}
							<div class="col-md-8">
								{!! Form::number('power_meter_operational_setting[warning_email_number]', (isset($model->id) ? (isset($power_meter_operational_setting->warning_email_number) ? $power_meter_operational_setting->warning_email_number : '') : null), ['class'=>'form-control','min'=>'0','max'=>'99999','autofocus']) !!}
				                {!!$errors->first('power_meter_operational_setting[warning_email_number]', '<label for="power_meter_operational_setting[warning_email_number]" class="help-block error">:message</label>')!!}
							</div>
						</div>
					</div>
				</div>

			<h6 class="hk-sec-title mt-15">{{App\Language::trans('MailBox Setting')}}</h6> <hr>
		   		
				<div class="row">
					<div class="col-md-6">
						<div class="form-group{{ $errors->has('power_meter_mailbox_setting[mail_engine]') ? ' has-error' : '' }}">
							{!! Form::label('power_meter_mailbox_setting[mail_engine]', App\Language::trans('Mail Engine'), ['class'=>'control-label col-md-12']) !!}
							<div class="col-md-12">
								 <div class="row">	
								 	{!! Form::select('power_meter_mailbox_setting[mail_engine]', App\Setting::mail_engine(), null, ['class'=>'form-control','required']) !!}
									{!!$errors->first('power_meter_mailbox_setting[mail_engine]', '<label for="power_meter_mailbox_setting[mail_engine]" class="help-block error">:message</label>')!!}
							</div>
						</div>
					</div>
				</div>
			</div>


			<div class="row">
				<div class="col-md-6">
					<div class="form-group{{ $errors->has('power_meter_mailbox_setting[smtp_hostname]') ? ' has-error' : '' }}">
						{!! Form::label('power_meter_mailbox_setting[smtp_hostname]', App\Language::trans('SMTP Hostname'), ['class'=>'control-label col-md-12']) !!}
						<div class="col-md-8">
							{!! Form::text('power_meter_mailbox_setting[smtp_hostname]', (isset($model->id) ? (isset($power_meter_mailbox_setting->smtp_hostname) ? $power_meter_mailbox_setting->smtp_hostname : '') : null), ['class'=>'form-control']) !!}
	                        {!!$errors->first('power_meter_mailbox_setting[smtp_hostname]', '<label for="power_meter_mailbox_setting[smtp_hostname]" class="help-block error">:message</label>')!!}
						</div>
					</div>
				</div>

				<div class="col-md-6">
					<div class="form-group{{ $errors->has('power_meter_mailbox_setting[smtp_username]') ? ' has-error' : '' }}">
						{!! Form::label('power_meter_mailbox_setting[smtp_username]', App\Language::trans('SMTP Username'), ['class'=>'control-label col-md-12']) !!}
						<div class="col-md-8">
							{!! Form::text('power_meter_mailbox_setting[smtp_username]', (isset($model->id) ? (isset($power_meter_mailbox_setting->smtp_username) ? $power_meter_mailbox_setting->smtp_username : '') : null), ['class'=>'form-control']) !!}
	                        {!!$errors->first('power_meter_mailbox_setting[smtp_username]', '<label for="power_meter_mailbox_setting[smtp_username]" class="help-block error">:message</label>')!!}
						</div>
					</div>
				</div>
			</div>

			<div class="row">
				<div class="col-md-6">
					<div class="form-group{{ $errors->has('power_meter_mailbox_setting[smtp_password]') ? ' has-error' : '' }}">
						{!! Form::label('power_meter_mailbox_setting[smtp_password]', App\Language::trans('SMTP Password'), ['class'=>'control-label col-md-12']) !!}
						<div class="col-md-8">
							{!! Form::text('power_meter_mailbox_setting[smtp_password]', (isset($model->id) ? (isset($power_meter_mailbox_setting->smtp_password) ? $power_meter_mailbox_setting->smtp_password : '') : null), ['class'=>'form-control']) !!}
	                        {!!$errors->first('power_meter_mailbox_setting[smtp_password]', '<label for="power_meter_mailbox_setting[smtp_password]" class="help-block error">:message</label>')!!}
						</div>
					</div>
				</div>

				<div class="col-md-6">
					<div class="form-group{{ $errors->has('power_meter_mailbox_setting[smtp_port]') ? ' has-error' : '' }}">
						{!! Form::label('power_meter_mailbox_setting[smtp_port]', App\Language::trans('SMTP Port'), ['class'=>'control-label col-md-12']) !!}
						<div class="col-md-8">
							{!! Form::password('power_meter_mailbox_setting[smtp_port]', (isset($model->id) ? (isset($power_meter_mailbox_setting->smtp_port) ? $power_meter_mailbox_setting->smtp_port : null ) : null), ['class'=>'form-control']) !!}
	                        {!!$errors->first('power_meter_mailbox_setting[smtp_port]', '<label for="power_meter_mailbox_setting[smtp_password]" class="help-block error">:message</label>')!!}
						</div>
					</div>
				</div>

				
			</div>

			<div class="row">

				<div class="col-md-6">
					<div class="form-group{{ $errors->has('power_meter_mailbox_setting[smtp_timeout]') ? ' has-error' : '' }}">
						{!! Form::label('power_meter_mailbox_setting[smtp_timeout]', App\Language::trans('SMTP Timeout'), ['class'=>'control-label col-md-12']) !!}
						<div class="col-md-8">
							{!! Form::text('power_meter_mailbox_setting[smtp_timeout]', (isset($model->id) ? (isset($power_meter_mailbox_setting->smtp_timeout) ? $power_meter_mailbox_setting->smtp_timeout : '') : null), ['class'=>'form-control']) !!}
	                        {!!$errors->first('power_meter_mailbox_setting[smtp_timeout]', '<label for="power_meter_mailbox_setting[smtp_timeout]" class="help-block error">:message</label>')!!}
						</div>
					</div>
				</div>
				
			</div>

			<!-- End Tab Panel -->	       
		</div>

		<div role="tabpanel" class="tab-pane" id="club_house">
			<h6 class="hk-sec-title">{{App\Language::trans('Membership Setting')}}</h6> <hr>
			<div class="row">
			 <div class="col-md-6">
						<div class="form-group{{ $errors->has('is_period_item_to_monthly_break_down') ? ' has-error' : '' }}">
							{!! Form::label('is_period_item_to_monthly_break_down', App\Language::trans('Invoice Generated By Periodic Item'), ['class'=>'control-label col-md-12']) !!}			
							<div class="col-md-12">
								 <div class="row">	
								 	<div class="col-md-3">
									    <div class="custom-control custom-radio">
									        <input type="radio" id="is_period_item_to_monthly_break_downon" name="is_pay_by_accumulate" checked class="custom-control-input">
									        <label class="custom-control-label" for="is_period_item_to_monthly_break_downon">{{App\Language::trans('Per Item')}}</label>
									    </div>
									</div>
									<div class="col-md-3">
									    <div class="custom-control custom-radio">
									        <input type="radio" id="is_period_item_to_monthly_break_downoff" name="is_pay_by_accumulate"  class="custom-control-input">
									        <label class="custom-control-label" for="is_period_item_to_monthly_break_downoff">{{App\Language::trans('Monthly')}}</label>
									    </div>
									</div>
								 </div>
								 {!!$errors->first('is_inclusive', '<label for="is_period_item_to_monthly_break_down" class="help-block error">:message</label>')!!}
							</div>
						</div>
					</div>
			 </div>

	    	 <div class="row">
					<div class="col-md-6">
						<div class="form-group{{ $errors->has('is_direct_allow_to_payment') ? ' has-error' : '' }}">
							{!! Form::label('is_direct_allow_to_payment', App\Language::trans('Membership Verification Process?'), ['class'=>'control-label col-md-12']) !!}
							<div class="col-md-12">
								 <div class="row">	
								 	<div class="col-md-3">
									    <div class="custom-control custom-radio">
									        <input type="radio" id="is_direct_allow_to_payment_on" name="is_direct_allow_to_payment" checked class="custom-control-input">
									        <label class="custom-control-label" for="is_direct_allow_to_payment_on">{{App\Language::trans('Enabled')}}</label>
									    </div>
									</div>
									<div class="col-md-3">
									    <div class="custom-control custom-radio">
									        <input type="radio" id="is_direct_allow_to_payment_off" name="is_direct_allow_to_payment"  class="custom-control-input">
									        <label class="custom-control-label" for="is_direct_allow_to_payment_off">{{App\Language::trans('Disabled')}}</label>
									    </div>
									</div>
								 </div>
								 {!!$errors->first('is_direct_allow_to_payment', '<label for="is_direct_allow_to_payment" class="help-block error">:message</label>')!!}
							</div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group{{ $errors->has('membership_payment_allow_day') ? ' has-error' : '' }}">
							{!! Form::label('membership_payment_allow_day', App\Language::trans('Membership Renewal Period'), ['class'=>'control-label col-md-12']) !!}
							<div class="col-md-8">
								{!! Form::number('membership_payment_allow_day', null, ['class'=>'form-control','step'=>'.01','min'=>'0']) !!}
		                        {!!$errors->first('membership_payment_allow_day', '<label for="membership_payment_allow_day" class="help-block error">:message</label>')!!}
							</div>
						</div>
					</div>
				</div>
		

			<h6 class="hk-sec-title">{{App\Language::trans('Accounting System Integration')}}</h6> <hr>
			<div class="row">
				<div class="col-md-6">
					<div class="form-group{{ $errors->has('integrated_accounting_sytem') ? ' has-error' : '' }}">
						{!! Form::label('integrated_accounting_sytem', App\Language::trans('Integrate To'), ['class'=>'control-label col-md-12']) !!}
						<div class="col-md-8">
							{!! Form::select('integrated_accounting_sytem[]', App\Setting::integrated_accounting_system_combobox(), strlen($model->integrated_accounting_sytem) >  1 ? json_decode($model->integrated_accounting_sytem,true):null, ['class'=>'form-control select2 chosen-select integrated_accounting_sytem', "multiple"=>true, "id"=>"integrated_accounting_sytem",'onchange'=>'init_integrated_accounting_system_component(this)']) !!}
	                        {!!$errors->first('integrated_accounting_sytem', '<label for="country_id" class="help-block error">:message</label>')!!}
						</div>
					</div>
				</div>

				<div class="col-md-6">
					<div class="form-group{{ $errors->has('accounting_ncl_id') ? ' has-error' : '' }}">
						{!! Form::label('accounting_ncl_id', App\Language::trans('NCL ID'), ['class'=>'control-label col-md-12']) !!}
						<div class="col-md-8">
							{!! Form::text('accounting_ncl_id', null, ['class'=>'form-control']) !!}
	                        {!!$errors->first('accounting_ncl_id', '<label for="accounting_ncl_id" class="help-block error">:message</label>')!!}
						</div>
					</div>
				</div>
			</div>

			<div class="row">
				<div class="col-md-6">
					<div class="form-group{{ $errors->has('accounting_winz_id') ? ' has-error' : '' }}">
						{!! Form::label('accounting_winz_id', App\Language::trans('Winz Net ID'), ['class'=>'control-label col-md-12']) !!}
						<div class="col-md-8">
							{!! Form::text('accounting_winz_id', null, ['class'=>'form-control']) !!}
	                        {!!$errors->first('accounting_winz_id', '<label for="accounting_winz_id" class="help-block error">:message</label>')!!}
						</div>
					</div>
				</div>
			</div>

			<h6 class="hk-sec-title">{{App\Language::trans('Default Setting')}}</h6> <hr>
			<div class="row">
				<div class="col-md-6">
                		<div class="form-group{{ $errors->has('bank_account') ? ' has-error' : '' }}">
                            {!! Form::label('bank_account', App\Language::trans('Bank Account'), ['class'=>'control-label col-md-12']) !!}
                            <div class="col-md-8">
                            	{!! Form::select('bank_account', App\Setting::bank_or_cash_combobox(), null, ['class'=>'form-control','required']) !!}
                            	{!!$errors->first('bank_account', '<label for="bank_account" class="help-block error">:message</label>')!!}
                        	</div>
                        </div>
                </div>
            </div>

			<div class="row">
				<div class="col-md-6">					
						<div class="form-group{{ $errors->has('currency_id') ? ' has-error' : '' }}">
                            {!! Form::label('currency_id', App\Language::trans('Currency'), ['class'=>'control-label col-md-12','required']) !!}
                            <div class="col-md-8">
                            	{!! Form::select('currency_id', App\Currency::combobox(), null, ['class'=>'form-control','onchange'=>'init_currency_rate(this)']) !!}
                            	{!!$errors->first('currency_id', '<label for="currency_id" class="help-block error">:message</label>')!!}
                        	</div>
                        </div>
                </div>
                <!-- ,'required' -->

                <!-- ,'required' -->
                <div class="col-md-6">
                		<div class="form-group{{ $errors->has('payment_term_id') ? ' has-error' : '' }}">
                            {!! Form::label('payment_term_id', App\Language::trans('Payment Term'), ['class'=>'control-label col-md-12']) !!}
                            <div class="col-md-8">
                            	{!! Form::select('payment_term_id', App\PaymentTerm::combobox(), null, ['class'=>'form-control']) !!}
                            	{!!$errors->first('payment_term_id', '<label for="payment_term_id" class="help-block error">:message</label>')!!}
                        	</div>
                        </div>
                </div>
            </div>

			<!-- End Tab Panel -->	 	   
		</div>

		<div role="tabpanel" class="tab-pane" id="leaf_accounting">		
			<h6 class="hk-sec-title">{{App\Language::trans('API Setting')}}</h6> <hr>
	    	 <div class="row">
					<div class="col-md-6">
						<div class="form-group{{ $errors->has('is_min_credit') ? ' has-error' : '' }}">
							{!! Form::label('is_min_credit', App\Language::trans('Status'), ['class'=>'control-label col-md-12']) !!}
							<div class="col-md-12">
								 <div class="row">	
								 	<div class="col-md-3">
									    <div class="custom-control custom-radio">
									        <input type="radio" id="is_min_credit_on" name="is_min_credit" checked class="custom-control-input">
									        <label class="custom-control-label" for="is_min_credit_on">{{App\Language::trans('Enabled')}}</label>
									    </div>
									</div>
									<div class="col-md-3">
									    <div class="custom-control custom-radio">
									        <input type="radio" id="is_min_credit_off" name="is_min_credit"  class="custom-control-input">
									        <label class="custom-control-label" for="is_min_credit_off">{{App\Language::trans('Disabled')}}</label>
									    </div>
									</div>
								 </div>
								 {!!$errors->first('is_min_credit', '<label for="is_min_credit" class="help-block error">:message</label>')!!}
							</div>
						</div>
					</div>	
				</div>

				<div class="row">
					<div class="col-md-6">
						<div class="form-group{{ $errors->has('monthly_cut_off_day') ? ' has-error' : '' }}">
							{!! Form::label('monthly_cut_off_day', App\Language::trans('API Key'), ['class'=>'control-label col-md-12']) !!}
							<div class="col-md-8">
								{!! Form::text('monthly_cut_off_day', null, ['class'=>'form-control','min'=>'1','max'=>'31','autofocus']) !!}
				                {!!$errors->first('monthly_cut_off_day', '<label for="monthly_cut_off_day" class="help-block error">:message</label>')!!}
							</div>
						</div>
					</div>
				</div>
			<!-- End Tab Panel -->	       
		</div>

	</div>
</div>
</section>
@include('_version_02.commons.layouts.partials._form_floaring_footer_standard')


{!! Form::close() !!}
@stop
@section('script')
init_floating_footer();
//init_date_date_picker_new_ui_by_id("input[name=system_live_date]");
@if(!$model->is_min_credit)
	$("input[name=min_credit]").closest(".form-group").hide();
@endif
$("#is_min_credit_on").on("click", function(){
	$("input[name=min_credit]").closest(".form-group").show("slow");
})
$("#is_min_credit_off").on("click", function(){
	$("input[name=min_credit]").closest(".form-group").hide("slow");
})

@if(!$model->membership_payment_allow_day)
	$("input[name=membership_payment_allow_day]").closest(".form-group").hide();
@endif
$("#is_direct_allow_to_payment_off").on("click", function(){
	$("input[name=membership_payment_allow_day]").closest(".form-group").show("slow");
})
$("#is_direct_allow_to_payment_on").on("click", function(){
	$("input[name=membership_payment_allow_day]").closest(".form-group").hide("slow");
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

init_select2($("select[name=selected_module]"));
@stop