@extends('_version_02.commons.layouts.admin')
@section('content')
{!! Form::model($model, ['class'=>'form-horizontal']) !!}
@include('_version_02.commons.layouts.partials._alert')
<section class="hk-sec-wrapper">
    <h5 class="hk-sec-title">{{App\Language::trans('Information')}}</h5><hr>
   
    <div class="row">
			<div class="col-md-6">
				<div class="form-group{{ $errors->has('code') ? ' has-error' : '' }}">
					{!! Form::label('code', App\Language::trans('Code'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						{!! Form::text('code', null, ['class'=>'form-control']) !!}
                        {!!$errors->first('code', '<label for="code" class="help-block error">:message</label>')!!}
					</div>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-md-6">
				<div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
					{!! Form::label('text', App\Language::trans('Name'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						{!! Form::text('name', null, ['class'=>'form-control']) !!}
                        {!!$errors->first('name', '<label for="name" class="help-block error">:message</label>')!!}
					</div>
				</div>
			</div>
		</div>


		<div class="row">
			<div class="col-md-6">
				<div class="form-group{{ $errors->has('amount') ? ' has-error' : '' }}">
					{!! Form::label('amount', App\Language::trans('Amount'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						{!! Form::number('amount', null, ['min'=>1,'max'=>9999,'step'=>'0.01','class'=>'form-control']) !!}
                        {!!$errors->first('amount', '<label for="amount" class="help-block error">:message</label>')!!}
					</div>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-md-6">
				<div class="form-group{{ $errors->has('implementation_date') ? ' has-error' : '' }}">
					{!! Form::label('implementation_date', App\Language::trans('Implementation Date'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						{!! Form::select('implementation_date', App\Setting::select_days_combobox(),null, ['class'=>'form-control']) !!}
                        {!!$errors->first('implementation_date', '<label for="month_ended" class="help-block error">:message</label>')!!}
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
	        <!-- .form-group -->
		</div>

		<div class="row">
			<div class="col-md-6">
				<div class="form-group{{ $errors->has('starting_date') ? ' has-error' : '' }}">
					{!! Form::label('starting_date', App\Language::trans('From Month'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						{!! Form::select('starting_date', App\PowerMeterModel\MeterInvoice::previous_one_year_combobox(), null, ['class'=>'form-control','autofocus']) !!}
                        {!!$errors->first('starting_date', '<label for="starting_date" class="help-block error">:message</label>')!!}
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group{{ $errors->has('month_ended') ? ' has-error' : '' }}">
					{!! Form::label('ending_date', App\Language::trans('To Month'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						{!! Form::select('ending_date', App\PowerMeterModel\MeterInvoice::next_one_year_combobox(), null, ['class'=>'form-control']) !!}
                        {!!$errors->first('ending_date', '<label for="ending_date" class="help-block error">:message</label>')!!}
					</div>
				</div>
			</div>
		</div>

		
		
		<h5 class="hk-sec-title mt-20">{{App\Language::trans('Beneficial Selection')}}</h5><hr>

		<div class="row">
			<div class="col-md-6">
				<div class="form-group{{ $errors->has('room_type') ? ' has-error' : '' }}">
					{!! Form::label('room_type', App\Language::trans('Room Type'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						{!! Form::select('room_type', App\Setting::room_type_combobox(), null, ['class'=>'form-control','autofocus','onchange'=>'init_room_type_subsidize_handle(this)']) !!}
                        {!!$errors->first('room_type', '<label for="room_type" class="help-block error">:message</label>')!!}
					</div>
				</div>
			</div>	
		</div>

		    <!-- Plugin: Dual Select List -->
    <div class="panel panel-primary">
	      <div class="panel-heading">
	        <span class="panel-title">{{App\Language::trans('Subsidize Tenant List')}}</span>
	      </div>

	      <div class="panel-body p25">
		        <div class="form-group {!!$errors->first('code') ? 'has-error' : ''!!}">
		          <label for="code" class="control-label col-sm-2">{{App\Language::trans('Subsidize Tenant List')}}</label>
			          <div class="col-sm-12" id="single_room_div">
			           {!!Form::select("subsidize_tenant_id[]", App\Customer::combobox_from_leaf_by_room_type_member_id('single'), strlen($model->subsidize_tenant_id) >  1 ? json_decode($model->subsidize_tenant_id,true):null, array("style"=>"width: 100%;","class"=>"form-control 3col active","id"=>"subsidize_tenant_id","multiple"=>"multiple"))!!}
			          </div>

			           <div class="col-sm-12 hide" id="twin_room_div">
			           {!!Form::select("subsidize_tenant_id[]", App\Customer::combobox_from_leaf_by_room_type_member_id('twin'), strlen($model->subsidize_tenant_id) >  1 ? json_decode($model->subsidize_tenant_id,true):null, 			array("style"=>"width: 100%;","class"=>"form-control 3col active","id"=>"subsidize_tenant_id","multiple"=>"multiple"))!!}
			           </div>
		        </div>
	       </div>
    </div>  

	<div class="row">
		<div class="col-md-12">
			<div class="form-group{{ $errors->has('remark') ? ' has-error' : '' }}">
				{!! Form::label('remark', App\Language::trans('Remark'), ['class'=>'control-label col-md-4']) !!}
				<div class="col-md-12">
					{!! Form::textarea('remark', null, ['class'=>'form-control']) !!}
                    {!!$errors->first('remark', '<label for="remark" class="help-block error">:message</label>')!!}
				</div>
			</div>
		</div>
	</div>


</section>
@include('_version_02.commons.layouts.partials._form_floaring_footer_standard')
{!! Form::close() !!}
@stop
@section('script')


$(".input-daterange").datepicker({
	format: "dd-mm-yyyy",
});
@stop