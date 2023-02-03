@extends('_version_02.commons.layouts.admin')
@section('content')
{!! Form::model($model, ['class'=>'form-horizontal']) !!}
@include('_version_02.commons.layouts.partials._alert')
<section class="hk-sec-wrapper">
    <h5 class="hk-sec-title">{{App\Language::trans('House Detail')}}</h5><hr>
   		

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

		<div class="row">
			<div class="col-md-6">
				<div class="form-group{{ $errors->has('house_unit') ? ' has-error' : '' }}">
					{!! Form::label('house_unit', App\Language::trans('Unit No'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						{!! Form::text('house_unit', null, ['class'=>'form-control','required']) !!}
                        {!!$errors->first('house_unit', '<label for="house_unit" class="help-block error">:message</label>')!!}
					</div>
				</div>
			</div>

			<div class="col-md-6">
				<div class="form-group{{ $errors->has('house_floor') ? ' has-error' : '' }}">
					{!! Form::label('house_floor', App\Language::trans('Floor'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						{!! Form::text('house_floor', null, ['class'=>'form-control','required']) !!}
                        {!!$errors->first('house_floor', '<label for="house_floor" class="help-block error">:message</label>')!!}
					</div>
				</div>
			</div>
		</div>


		<div class="row">
			<div class="col-md-6">
				<div class="form-group{{ $errors->has('country_id') ? ' has-error' : '' }}">
					{!! Form::label('country_id', App\Language::trans('Country'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						{!! Form::select('country_id', App\Country::combobox(), null, ['class'=>'form-control','autofocus','onchange'=>'init_state_selectbox(this)']) !!}
                        {!!$errors->first('country_id', '<label for="country_id" class="help-block error">:message</label>')!!}
					</div>
				</div>
			</div>
		
			<div class="col-md-6">
				<div class="form-group{{ $errors->has('state_id') ? ' has-error' : '' }}">
					{!! Form::label('state_id', App\Language::trans('State'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						{!! Form::select('state_id', App\State::combobox($model->country_id), null, ['class'=>'form-control']) !!}
                        {!!$errors->first('state_id', '<label for="state_id" class="help-block error">:message</label>')!!}
					</div>
				</div>
			</div>
		</div>


	@php
			if(isset($model->id)){
				$landlord_information =  json_decode($model->landlord_information);
				$house_other_information =  json_decode($model->house_other_information);

			}
	@endphp

	<h5 class="hk-sec-title mt-30">{{App\Language::trans('Landlord Information')}}</h5><hr>

	    <div class="row">
			 <div class="col-md-6">
		        	<div class="form-group{{ $errors->has('landlord_information') ? ' has-error' : '' }}">
						{!! Form::label('landlord_information', App\Language::trans('Name'), ['class'=>'control-label col-md-4']) !!}
						<div class="col-md-8">
							{!! Form::text('landlord_information[name]', (isset($landlord_information->name) ? $landlord_information->name : null), ['class'=>'form-control','placeholder'=>'Name']) !!}
							{!!$errors->first('landlord_information[name]', '<label for="landlord_information[name]" class="help-block error">:message</label>')!!}
						</div>
					</div>
		       </div>

		       <div class="col-md-6">
		        	<div class="form-group{{ $errors->has('landlord_information') ? ' has-error' : '' }}">
						{!! Form::label('landlord_information', App\Language::trans('Contact No.'), ['class'=>'control-label col-md-4']) !!}
						<div class="col-md-8">
							{!! Form::text('landlord_information[contact_no]', (isset($landlord_information->contact_no) ? $landlord_information->contact_no : null), ['class'=>'form-control','placeholder'=>'Contact No.']) !!}
							{!!$errors->first('landlord_information[contact_no]', '<label for="landlord_information[contact_no]" class="help-block error">:message</label>')!!}
						</div>
					</div>
		       </div>
	   </div>


	    <div class="row">
			 <div class="col-md-6">
		        	<div class="form-group{{ $errors->has('landlord_information') ? ' has-error' : '' }}">
						{!! Form::label('landlord_information', App\Language::trans('Ic No.'), ['class'=>'control-label col-md-4']) !!}
						<div class="col-md-8">
							{!! Form::text('landlord_information[ic_no]', (isset($landlord_information->ic_no) ? $landlord_information->ic_no : null), ['class'=>'form-control','placeholder'=>'Ic No.']) !!}
							{!!$errors->first('landlord_information[ic_no]', '<label for="landlord_information[ic_no]" class="help-block error">:message</label>')!!}
						</div>
					</div>
		       </div>

		       <div class="col-md-6">
		        	<div class="form-group{{ $errors->has('landlord_information') ? ' has-error' : '' }}">
						{!! Form::label('landlord_information', App\Language::trans('House Address'), ['class'=>'control-label col-md-4']) !!}
						<div class="col-md-8">
							{!! Form::text('landlord_information[house_address]', (isset($landlord_information->house_address) ? $landlord_information->house_address : null), ['class'=>'form-control','placeholder'=>'House Address']) !!}
							{!!$errors->first('landlord_information[house_address]', '<label for="landlord_information[house_address]" class="help-block error">:message</label>')!!}
						</div>
					</div>
		       </div>
	   </div>


	   <!-- <div class="row">
			 <div class="col-md-6">
		        	<div class="form-group{{ $errors->has('landlord_information') ? ' has-error' : '' }}">
						{!! Form::label('landlord_information', App\Language::trans('Name'), ['class'=>'control-label col-md-4']) !!}
						<div class="col-md-8">
							{!! Form::text('landlord_information[name]', (isset($landlord_information->name) ? $landlord_information->name : null), ['class'=>'form-control','placeholder'=>'Name']) !!}
							{!!$errors->first('landlord_information[name]', '<label for="landlord_information[name]" class="help-block error">:message</label>')!!}
						</div>
					</div>
		       </div>

		       <div class="col-md-6">
		        	<div class="form-group{{ $errors->has('landlord_information') ? ' has-error' : '' }}">
						{!! Form::label('landlord_information', App\Language::trans('Name'), ['class'=>'control-label col-md-4']) !!}
						<div class="col-md-8">
							{!! Form::text('landlord_information[name]', (isset($landlord_information->name) ? $landlord_information->name : null), ['class'=>'form-control','placeholder'=>'Name']) !!}
							{!!$errors->first('landlord_information[name]', '<label for="landlord_information[name]" class="help-block error">:message</label>')!!}
						</div>
					</div>
		       </div>
	   </div> -->

	  
	   <!-- <div class="row">
			 <div class="col-md-6">
		        	<div class="form-group{{ $errors->has('landlord_information') ? ' has-error' : '' }}">
						{!! Form::label('landlord_information', App\Language::trans('Name'), ['class'=>'control-label col-md-4']) !!}
						<div class="col-md-8">
							{!! Form::text('landlord_information[name]', (isset($landlord_information->name) ? $landlord_information->name : null), ['class'=>'form-control','placeholder'=>'Name']) !!}
							{!!$errors->first('landlord_information[name]', '<label for="landlord_information[name]" class="help-block error">:message</label>')!!}
						</div>
					</div>
		       </div>

		       <div class="col-md-6">
		        	<div class="form-group{{ $errors->has('landlord_information') ? ' has-error' : '' }}">
						{!! Form::label('landlord_information', App\Language::trans('Name'), ['class'=>'control-label col-md-4']) !!}
						<div class="col-md-8">
							{!! Form::text('landlord_information[name]', (isset($landlord_information->name) ? $landlord_information->name : null), ['class'=>'form-control','placeholder'=>'Name']) !!}
							{!!$errors->first('landlord_information[name]', '<label for="landlord_information[name]" class="help-block error">:message</label>')!!}
						</div>
					</div>
		       </div>
	   </div> -->


	<h5 class="hk-sec-title">{{App\Language::trans('Misc.')}}</h5><hr>

	<div class="row">
			 <div class="col-md-6">
		        	<div class="form-group{{ $errors->has('house_other_information') ? ' has-error' : '' }}">
						{!! Form::label('house_other_information', App\Language::trans('Property Type'), ['class'=>'control-label col-md-4']) !!}
						<div class="col-md-8">
							{!! Form::text('house_other_information[property_type]', (isset($house_other_information->property_type) ? $house_other_information->property_type : null), ['class'=>'form-control','placeholder'=>'Property Type']) !!}
							{!!$errors->first('house_other_information[property_type]', '<label for="house_other_information[property_type]" class="help-block error">:message</label>')!!}
						</div>
					</div>
		       </div>

		        <div class="col-md-6">
		        	<div class="form-group{{ $errors->has('house_other_information') ? ' has-error' : '' }}">
						{!! Form::label('house_other_information', App\Language::trans('Rental'), ['class'=>'control-label col-md-4']) !!}
						<div class="col-md-8">
							{!! Form::text('house_other_information[rental]', (isset($house_other_information->rental) ? $house_other_information->rental : null), ['class'=>'form-control','placeholder'=>'Rental']) !!}
							{!!$errors->first('house_other_information[rental]', '<label for="house_other_information[rental]" class="help-block error">:message</label>')!!}
						</div>
					</div>
		       </div>

		       
	   </div>

	   <div class="row">
			 
			 <div class="col-md-6">
				<div class="form-group{{ $errors->has('house_other_information[is_aircond_unit]') ? ' has-error' : '' }}">
					{!! Form::label('house_other_information[is_aircond_unit]', App\Language::trans('Is Air Cond Unit'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						{!! Form::select('house_other_information[is_aircond_unit]', App\Setting::status_combobox(), (isset($house_other_information->is_aircond_unit) ? $house_other_information->is_aircond_unit : null), ['class'=>'form-control','autofocus','onchange'=>'init_state_selectbox(this)']) !!}
                        {!!$errors->first('house_other_information[is_aircond_unit]', '<label for="house_other_information[is_aircond_unit]" class="help-block error">:message</label>')!!}
					</div>
				</div>
			</div>


		        <div class="col-md-6">
		        	<div class="form-group{{ $errors->has('house_other_information') ? ' has-error' : '' }}">
						{!! Form::label('house_other_information', App\Language::trans('Fix Air Cond Electric Charges'), ['class'=>'control-label col-md-4']) !!}
						<div class="col-md-8">
							{!! Form::number('house_other_information[fix_aircond_electric_fee_charges]', (isset($house_other_information->fix_aircond_electric_fee_charges) ? $house_other_information->fix_aircond_electric_fee_charges : null), ['class'=>'form-control','placeholder'=>'Fix Charges']) !!}
							{!!$errors->first('house_other_information[fix_aircond_electric_fee_charges]', '<label for="house_other_information[fix_aircond_electric_fee_charges]" class="help-block error">:message</label>')!!}
						</div>
					</div>
		       </div>



		      <!-- <div class="col-md-6">
		        	<div class="form-group{{ $errors->has('house_other_information') ? ' has-error' : '' }}">
						{!! Form::label('house_other_information', App\Language::trans('Wifi'), ['class'=>'control-label col-md-4']) !!}
						<div class="col-md-8">
							{!! Form::text('house_other_information[rental]', (isset($house_other_information->rental) ? $house_other_information->rental : null), ['class'=>'form-control','placeholder'=>'Rental']) !!}
							{!!$errors->first('house_other_information[rental]', '<label for="house_other_information[rental]" class="help-block error">:message</label>')!!}
						</div>
					</div>
		       </div>  -->

		       
	   </div>



	 <div class="row">
			

		       <div class="col-md-6">
		        	<div class="form-group{{ $errors->has('house_other_information') ? ' has-error' : '' }}">
						{!! Form::label('house_other_information', App\Language::trans('Parking Lot No.'), ['class'=>'control-label col-md-4']) !!}
						<div class="col-md-8">
							{!! Form::text('house_other_information[parking_lot_no]', (isset($house_other_information->parking_lot_no) ? $house_other_information->parking_lot_no : null), ['class'=>'form-control','placeholder'=>'Parking Lot No.']) !!}
							{!!$errors->first('house_other_information[parking_lot_no]', '<label for="house_other_information[parking_lot_no]" class="help-block error">:message</label>')!!}
						</div>
					</div>
		       </div>

		       <div class="col-md-6">
		        	<div class="form-group{{ $errors->has('house_other_information') ? ' has-error' : '' }}">
						{!! Form::label('house_other_information', App\Language::trans('House Lot'), ['class'=>'control-label col-md-4']) !!}
						<div class="col-md-8">
							{!! Form::text('house_other_information[house_lot]', (isset($house_other_information->house_lot) ? $house_other_information->house_lot : null), ['class'=>'form-control','placeholder'=>'House Lot']) !!}
							{!!$errors->first('house_other_information[house_lot]', '<label for="house_other_information[contact_no]" class="help-block error">:message</label>')!!}
						</div>
					</div>
		       </div>
	   </div>

	<div class="panel panel-primary">
      <div class="panel-heading">
        <span class="panel-title">{{App\Language::trans('Utility List')}}</span>
      </div>
      <div class="panel-body p25">
        <div class="form-group {!!$errors->first('code') ? 'has-error' : ''!!}">
          
          <div class="col-sm-10" id="house_fee_items">
           {!!Form::select("house_fee_items[]", App\UtilityKy\House::utility_combobox(), strlen($model->house_fee_items) >  1 ? json_decode($model->house_fee_items,true):null, array("style"=>"width: 100%;","class"=>"form-control 3col active","id"=>"house_fee_items","multiple"=>"multiple"))!!}
          </div>
       
        </div>
         
    
       </div>
    </div>  

    @include('_version_02.commons.utilityKy.houses.partials._utility_fee')



</section>
@include('_version_02.commons.layouts.partials._form_floaring_footer_standard')
{!! Form::close() !!}
@endsection
@section('script')
@endsection