@extends('commons.layouts.admin')
@section('content')
{!! Form::model($model, ['class'=>'form-horizontal']) !!}
@include('commons.layouts.partials._alert')

{!!Form::hidden('is_from_meter_pairing', $is_from_meter_pairing, ['id'=>'is_from_meter_pairing' , 'value'=>'is_from_meter_pairing']) !!}

<div class="box">
	<div class="box-header with-border">
		<h3 class="box-title">{{App\Language::trans('Detail Form')}}</h3>
		<div class="box-tools pull-right">
			<a href="{{action('UMeterRegistersController@getNew')}}" class="btn btn-block btn-info">
				<i class="fa fa-file"></i> {{App\Language::trans('New File')}}
			</a>
		</div>
	</div>
	<div class="box-body">
		<div class="row">
			<div class="col-md-6">
				<div class="form-group{{ $errors->has('leaf_house_id') ? ' has-error' : '' }}">
					{!! Form::label('leaf_house_id', App\Language::trans('House No.'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						{!! Form::select('leaf_house_id', App\PowerMeterModel\MeterRegister::houses_combobox(), null, ['class'=>'form-control','autofocus','required','onchange'=>'init_room_combobox(this)']) !!}
                        {!!$errors->first('leaf_house_id', '<label for="leaf_house_id" class="help-block error">:message</label>')!!}
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group{{ $errors->has('leaf_room_id') ? ' has-error' : '' }}">
					{!! Form::label('leaf_room_id', App\Language::trans('Room No'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						{!! Form::select('leaf_room_id', App\PowerMeterModel\MeterRegister::rooms_combobox($model->leaf_house_id), null, ['class'=>'form-control','required']) !!}
                        {!!$errors->first('leaf_room_id', '<label for="leaf_room_id" class="help-block error">:message</label>')!!}
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-6">
				<div class="form-group{{ $errors->has('utility_charge_id') ? ' has-error' : '' }}">
					{!! Form::label('utility_charge_id', App\Language::trans('Usage Charge'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						{!! Form::select('utility_charge_id', App\UtilityCharge::combobox(), null, ['class'=>'form-control','required','onchange'=>'init_utility_charge(this, "prices")']) !!}
                        {!!$errors->first('utility_charge_id', '<label for="utility_charge_id" class="help-block error">:message</label>')!!}
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-6">
				<div class="form-group{{ $errors->has('account_no') ? ' has-error' : '' }}">
					{!! Form::label('account_no', App\Language::trans('Account No'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						{!! Form::text('account_no', null, ['class'=>'form-control']) !!}
                        {!!$errors->first('account_no', '<label for="account_no" class="help-block error">:message</label>')!!}
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group{{ $errors->has('contract_no') ? ' has-error' : '' }}">
					{!! Form::label('contract_no', App\Language::trans('Contact No'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						{!! Form::text('contract_no', null, ['class'=>'form-control']) !!}
                        {!!$errors->first('contract_no', '<label for="contract_no" class="help-block error">:message</label>')!!}
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-6">
				<div class="form-group{{ $errors->has('deposit') ? ' has-error' : '' }}">
					{!! Form::label('deposit', App\Language::trans('Deposit'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						{!! Form::number('deposit', null, ['class'=>'form-control','step'=>'0.01','min'=>'0']) !!}
                        {!!$errors->first('deposit', '<label for="deposit" class="help-block error">:message</label>')!!}
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group{{ $errors->has('meter_class_id') ? ' has-error' : '' }}">
					{!! Form::label('meter_class_id', App\Language::trans('Meter Class'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						{!! Form::select('meter_class_id', App\MeterClass::combobox(), null, ['class'=>'form-control','required']) !!}
                        {!!$errors->first('meter_class_id', '<label for="meter_class_id" class="help-block error">:message</label>')!!}
					</div>
				</div>
			</div>
		</div>
		<hr>
		<div class="row">
			<div class="col-md-6">
				<div class="form-group{{ $errors->has('ip_address') ? ' has-error' : '' }}">
					{!! Form::label('ip_address', App\Language::trans('IP Address'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						{!! Form::text('ip_address', null, ['class'=>'form-control','required']) !!}
                        {!!$errors->first('ip_address', '<label for="ip_address" class="help-block error">:message</label>')!!}
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group{{ $errors->has('meter_id') ? ' has-error' : '' }}">
					{!! Form::label('meter_id', App\Language::trans('Meter ID'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						{!! Form::text('meter_id', null, ['class'=>'form-control','required']) !!}
                        {!!$errors->first('meter_id', '<label for="meter_id" class="help-block error">:message</label>')!!}
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="box">
	<div class="box-header with-border">
		<h3 class="box-title">{{App\Language::trans('Utility Charge Table')}}</h3>
	</div>
	<div class="box-body">
		<div class="table-responsive">
			<table id="prices" class="table table-bordered">
				<thead>
					<tr>
						<th class="text-center col-md-1">#</th>
						<th class="text-center col-md-4">{{App\Language::trans('Unit Started')}}</th>
						<th class="text-center col-md-4">{{App\Language::trans('Unit Ended')}}</th>
						<th class="text-center col-md-1">{{App\Language::trans('GST')}}</th>
						<th class="text-center col-md-1">{{App\Language::trans('Unit Price')}}</th>
					</tr>
				</thead>
				<tbody>
					@foreach($model->display_relation_child('utility_charge_id','prices') as $row)
						<tr>
							<td class="text-center col-md-1">{{$i++}}</td>
							<td class="text-center col-md-4">{{$row->started}}</td>
							<td class="text-center col-md-4">{{$row->ended}}</td>
							<td class="text-center col-md-1">{{$row->is_gst ? App\Language::trans('Yes'):App\Language::trans('No')}}</td>
							<td class="text-center col-md-1">{{$settings->getDouble($row->unit_price)}}</td>
						</tr>
					@endforeach
				</tbody>
			</table>
		</div>
	</div>
</div>
<div class="box">
	<div class="box-header with-border">
		<h3 class="box-title">{{App\Language::trans('Address Detail')}}</h3>
	</div>
	<div class="box-body">
		<div class="row">
			<div class="col-md-6">
				<h4 class="text-center">{{App\Language::trans('Billing Information')}}</h4>
				<hr>
				<div class="form-group{{ $errors->has('billing_address1') ? ' has-error' : '' }}">
					{!! Form::label('billing_address1', App\Language::trans('Address'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						{!! Form::text('billing_address1', null, ['class'=>'form-control margin-bottom-15']) !!}
						{!! Form::text('billing_address2', null, ['class'=>'form-control']) !!}
                        {!!$errors->first('billing_address1', '<label for="billing_address1" class="help-block error">:message</label>')!!}
					</div>
				</div>
				<div class="form-group{{ $errors->has('billing_postcode') ? ' has-error' : '' }}">
					{!! Form::label('billing_postcode', App\Language::trans('Postcode'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						{!! Form::text('billing_postcode', null, ['class'=>'form-control']) !!}
                        {!!$errors->first('billing_postcode', '<label for="billing_postcode" class="help-block error">:message</label>')!!}
					</div>
				</div>
				<div class="form-group{{ $errors->has('billing_country_id') ? ' has-error' : '' }}">
					{!! Form::label('billing_country_id', App\Language::trans('Country'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						{!! Form::select('billing_country_id', App\Country::combobox(), null, ['class'=>'form-control','onchange'=>'init_state_selectbox(this)']) !!}
                        {!!$errors->first('billing_country_id', '<label for="billing_country_id" class="help-block error">:message</label>')!!}
					</div>
				</div>
				<div class="form-group{{ $errors->has('billing_state_id') ? ' has-error' : '' }}">
					{!! Form::label('billing_state_id', App\Language::trans('State'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						{!! Form::select('billing_state_id', App\State::combobox(old('billing_country_id') ? old('billing_country_id'):$model->billing_country_id), null, ['class'=>'form-control','onchange'=>'init_city_selectbox(this)']) !!}
                        {!!$errors->first('billing_state_id', '<label for="billing_state_id" class="help-block error">:message</label>')!!}
					</div>
				</div>
				<div class="form-group{{ $errors->has('billing_city_id') ? ' has-error' : '' }}">
					{!! Form::label('billing_city_id', App\Language::trans('City'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						{!! Form::select('billing_city_id', App\City::combobox(old('billing_state_id') ? old('billing_state_id'):$model->billing_state_id), null, ['class'=>'form-control']) !!}
                        {!!$errors->first('billing_city_id', '<label for="billing_city_id" class="help-block error">:message</label>')!!}
					</div>
				</div>
			</div>
			<div class="col-md-6">
			</div>
		</div>
	</div>
</div>
<div class="box">
	<div class="box-header with-border">
		<h3 class="box-title">{{App\Language::trans('More Detail Form')}}</h3>
	</div>
	<div class="box-body">
		<div class="row">
			<div class="col-md-6">
				<div class="form-group{{ $errors->has('status') ? ' has-error' : '' }}">
					{!! Form::label('status', App\Language::trans('Status'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						<label class="radio-inline">
							{!! Form::radio('status', 1, true) !!} {{App\Language::trans('Enabled')}}
						</label>
						<label class="radio-inline">
							{!! Form::radio('status', 0, false) !!} {{App\Language::trans('Disabled')}}
						</label>
                        {!!$errors->first('status', '<label for="status" class="help-block error">:message</label>')!!}
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="box-footer">
		<div class="row">
			<div class="col-md-offset-2 col-md-10">
				<button type="submit" class="btn btn-primary"><i class="fa fa-floppy-o fa-fw"></i>{{App\Language::trans('Save')}}</button>
				<a href="{{action('UMeterRegistersController@getIndex')}}" class="btn btn-danger"><i class="fa fa-ban fa-fw"></i>{{App\Language::trans('Close')}}</a>
			</div>
		</div>
	</div>
</div>
{!! Form::close() !!}
@endsection
@section('script')

$("[name='ip_address']").mask('099.099.099.099');

@endsection