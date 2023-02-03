@extends('billings.layouts.admin')
@section('content')
{!! Form::model($model, ['class'=>'form-horizontal']) !!}
@include('billings.layouts.partials._alert')
<div class="box">
	<div class="box-header with-border">
		<h3 class="box-title">{{App\Language::trans('Detail Form')}}</h3>
		<div class="box-tools pull-right">
			<a href="{{action('CustomersController@getNew')}}" class="btn btn-block btn-info">
				<i class="fa fa-file"></i> {{App\Language::trans('New File')}}
			</a>
		</div>
	</div>
	<div class="box-body">
		<div class="row">
			<div class="col-md-6">
				<div class="form-group{{ $errors->has('code') ? ' has-error' : '' }}">
					{!! Form::label('code', App\Language::trans('Code'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						{!! Form::text('code', null, ['class'=>'form-control','autofocus','required']) !!}
                        {!!$errors->first('code', '<label for="code" class="help-block error">:message</label>')!!}
					</div>
				</div>
				<div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
					{!! Form::label('name', App\Language::trans('Name'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						{!! Form::text('name', null, ['class'=>'form-control']) !!}
                        {!!$errors->first('name', '<label for="name" class="help-block error">:message</label>')!!}
					</div>
				</div>
				<div class="form-group{{ $errors->has('registration_no') ? ' has-error' : '' }}">
					{!! Form::label('registration_no', App\Language::trans('Registration No'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						{!! Form::text('registration_no', null, ['class'=>'form-control','autofocus']) !!}
                        {!!$errors->first('registration_no', '<label for="registration_no" class="help-block error">:message</label>')!!}
					</div>
				</div>
				<div class="form-group{{ $errors->has('gst_no') ? ' has-error' : '' }}">
					{!! Form::label('gst_no', App\Language::trans('GST No'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						{!! Form::text('gst_no', null, ['class'=>'form-control']) !!}
                        {!!$errors->first('gst_no', '<label for="gst_no" class="help-block error">:message</label>')!!}
					</div>
				</div>
				<div class="form-group{{ $errors->has('currency_id') ? ' has-error' : '' }}">
					{!! Form::label('currency_id', App\Language::trans('Currency'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						{!! Form::select('currency_id', App\Currency::combobox(), null, ['class'=>'form-control','autofocus']) !!}
                        {!!$errors->first('currency_id', '<label for="currency_id" class="help-block error">:message</label>')!!}
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group{{ $errors->has('customer_group_id') ? ' has-error' : '' }}">
					{!! Form::label('customer_group_id', App\Language::trans('Group'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						{!! Form::select('customer_group_id', App\CustomerGroup::combobox(), null, ['class'=>'form-control']) !!}
                        {!!$errors->first('customer_group_id', '<label for="customer_group_id" class="help-block error">:message</label>')!!}
					</div>
				</div>
				<div class="form-group{{ $errors->has('payment_term_id') ? ' has-error' : '' }}">
					{!! Form::label('payment_term_id', App\Language::trans('Payment Term'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						{!! Form::select('payment_term_id', App\PaymentTerm::combobox(), null, ['class'=>'form-control','autofocus']) !!}
                        {!!$errors->first('payment_term_id', '<label for="payment_term_id" class="help-block error">:message</label>')!!}
					</div>
				</div>
				<div class="form-group{{ $errors->has('sales_person') ? ' has-error' : '' }}">
					{!! Form::label('sales_person', App\Language::trans('Sales Person'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						{!! Form::text('sales_person', null, ['class'=>'form-control']) !!}
                        {!!$errors->first('sales_person', '<label for="sales_person" class="help-block error">:message</label>')!!}
					</div>
				</div>
				<div class="form-group{{ $errors->has('credit_limit') ? ' has-error' : '' }}">
					{!! Form::label('credit_limit', App\Language::trans('Credit Limit'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						{!! Form::text('credit_limit', null, ['class'=>'form-control','onchange'=>'init_double(this)']) !!}
                        {!!$errors->first('credit_limit', '<label for="credit_limit" class="help-block error">:message</label>')!!}
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="box">
	<div class="box-header with-border">
		<h3 class="box-title">{{App\Language::trans('Contact Detail Form')}}</h3>
		<div class="box-tools pull-right">
			<a href="{{action('CustomersController@getNew')}}" class="btn btn-block btn-info">
				<i class="fa fa-file"></i> {{App\Language::trans('New File')}}
			</a>
		</div>
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
				<div class="form-group{{ $errors->has('phone_no_1') ? ' has-error' : '' }}">
					{!! Form::label('phone_no_1', App\Language::trans('Phone No. (1)'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						{!! Form::text('phone_no_1', null, ['class'=>'form-control']) !!}
                        {!!$errors->first('phone_no_1', '<label for="phone_no_1" class="help-block error">:message</label>')!!}
					</div>
				</div>
				<div class="form-group{{ $errors->has('phone_no_2') ? ' has-error' : '' }}">
					{!! Form::label('phone_no_2', App\Language::trans('Phone No. (2)'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						{!! Form::text('phone_no_2', null, ['class'=>'form-control']) !!}
                        {!!$errors->first('phone_no_2', '<label for="phone_no_2" class="help-block error">:message</label>')!!}
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group{{ $errors->has('fax_no') ? ' has-error' : '' }}">
					{!! Form::label('fax_no', App\Language::trans('Fax No.'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						{!! Form::text('fax_no', null, ['class'=>'form-control']) !!}
                        {!!$errors->first('fax_no', '<label for="fax_no" class="help-block error">:message</label>')!!}
					</div>
				</div>
				<div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
					{!! Form::label('email', App\Language::trans('Email'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						{!! Form::text('email', null, ['class'=>'form-control']) !!}
                        {!!$errors->first('email', '<label for="email" class="help-block error">:message</label>')!!}
					</div>
				</div>
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
</div>
<div class="box">
	<div class="box-header with-border">
		<h3 class="box-title">{{App\Language::trans('Addresses Detail Form')}}</h3>
		<div class="box-tools pull-right">
			<a href="{{action('CustomersController@getNew')}}" class="btn btn-block btn-info">
				<i class="fa fa-file"></i> {{App\Language::trans('New File')}}
			</a>
		</div>
	</div>
	<div class="box-body">
		<div class="row">
			<div class="col-md-6">
				<h4 class="text-center">{{App\Language::trans('Billing Address')}}</h4>
				<div class="form-group{{ $errors->has('billing_address1') ? ' has-error' : '' }}">
					{!! Form::label('billing_address1', App\Language::trans('Address'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						{!! Form::text('billing_address1', null, ['class'=>'form-control']) !!}
                        {!!$errors->first('billing_address1', '<label for="billing_address1" class="help-block error">:message</label>')!!}
					</div>
				</div>
				<div class="form-group{{ $errors->has('billing_address2') ? ' has-error' : '' }}">
					<div class="col-md-offset-4 col-md-8">
						{!! Form::text('billing_address2', null, ['class'=>'form-control']) !!}
                        {!!$errors->first('billing_address2', '<label for="billing_address2" class="help-block error">:message</label>')!!}
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
						{!! Form::select('billing_state_id', App\State::combobox($model->billing_country_id), null, ['class'=>'form-control','onchange'=>'init_city_selectbox(this)']) !!}
                        {!!$errors->first('billing_state_id', '<label for="billing_state_id" class="help-block error">:message</label>')!!}
					</div>
				</div>
				<div class="form-group{{ $errors->has('billing_city_id') ? ' has-error' : '' }}">
					{!! Form::label('billing_city_id', App\Language::trans('City'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						{!! Form::select('billing_city_id', App\City::combobox($model->billing_state_id), null, ['class'=>'form-control']) !!}
                        {!!$errors->first('billing_city_id', '<label for="billing_city_id" class="help-block error">:message</label>')!!}
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<h4 class="text-center">{{App\Language::trans('Delivery Address')}}</h4>
				<div class="form-group{{ $errors->has('delivery_address1') ? ' has-error' : '' }}">
					{!! Form::label('delivery_address1', App\Language::trans('Address'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						{!! Form::text('delivery_address1', null, ['class'=>'form-control']) !!}
                        {!!$errors->first('delivery_address1', '<label for="delivery_address1" class="help-block error">:message</label>')!!}
					</div>
				</div>
				<div class="form-group{{ $errors->has('delivery_address2') ? ' has-error' : '' }}">
					<div class="col-md-offset-4 col-md-8">
						{!! Form::text('delivery_address2', null, ['class'=>'form-control']) !!}
                        {!!$errors->first('delivery_address2', '<label for="delivery_address2" class="help-block error">:message</label>')!!}
					</div>
				</div>
				<div class="form-group{{ $errors->has('delivery_postcode') ? ' has-error' : '' }}">
					{!! Form::label('delivery_postcode', App\Language::trans('Postcode'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						{!! Form::text('delivery_postcode', null, ['class'=>'form-control']) !!}
                        {!!$errors->first('delivery_postcode', '<label for="delivery_postcode" class="help-block error">:message</label>')!!}
					</div>
				</div>
				<div class="form-group{{ $errors->has('delivery_country_id') ? ' has-error' : '' }}">
					{!! Form::label('delivery_country_id', App\Language::trans('Country'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						{!! Form::select('delivery_country_id', App\Country::combobox(), null, ['class'=>'form-control','onchange'=>'init_state_selectbox(this)']) !!}
                        {!!$errors->first('delivery_country_id', '<label for="delivery_country_id" class="help-block error">:message</label>')!!}
					</div>
				</div>
				<div class="form-group{{ $errors->has('delivery_state_id') ? ' has-error' : '' }}">
					{!! Form::label('delivery_state_id', App\Language::trans('State'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						{!! Form::select('delivery_state_id', App\State::combobox($model->delivery_country_id), null, ['class'=>'form-control','onchange'=>'init_city_selectbox(this)']) !!}
                        {!!$errors->first('delivery_state_id', '<label for="delivery_state_id" class="help-block error">:message</label>')!!}
					</div>
				</div>
				<div class="form-group{{ $errors->has('delivery_city_id') ? ' has-error' : '' }}">
					{!! Form::label('delivery_city_id', App\Language::trans('City'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						{!! Form::select('delivery_city_id', App\City::combobox($model->delivery_state_id), null, ['class'=>'form-control']) !!}
                        {!!$errors->first('delivery_city_id', '<label for="delivery_city_id" class="help-block error">:message</label>')!!}
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="box">
	<div class="box-header with-border">
		<h3 class="box-title">{{App\Language::trans('Other Detail Form')}}</h3>
		<div class="box-tools pull-right">
			<a href="{{action('CustomersController@getNew')}}" class="btn btn-block btn-info">
				<i class="fa fa-file"></i> {{App\Language::trans('New File')}}
			</a>
		</div>
	</div>
	<div class="box-body">
		<div class="row">
			<div class="col-md-6">
				<div class="form-group{{ $errors->has('status') ? ' has-error' : '' }}">
					{!! Form::label('status', App\Language::trans('Status'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						<label class="radio-inline">
							{!! Form::radio('status', 1, true) !!} {{App\ExtendModel::status_true_word()}}
						</label>
						<label class="radio-inline">
							{!! Form::radio('status', 0, false) !!} {{App\ExtendModel::status_false_word()}}
						</label>
                        {!!$errors->first('status', '<label for="status" class="help-block error">:message</label>')!!}
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group{{ $errors->has('is_suspend') ? ' has-error' : '' }}">
					{!! Form::label('is_suspend', App\Language::trans('Account Suspend'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						<label class="radio-inline">
							{!! Form::radio('is_suspend', 1, false) !!} {{App\ExtendModel::answer_true_word()}}
						</label>
						<label class="radio-inline">
							{!! Form::radio('is_suspend', 0, true) !!} {{App\ExtendModel::answer_false_word()}}
						</label>
                        {!!$errors->first('is_suspend', '<label for="is_suspend" class="help-block error">:message</label>')!!}
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
	</div>
	<div class="box-footer">
		<div class="row">
			<div class="col-md-offset-2 col-md-10">
				<button type="submit" class="btn btn-primary"><i class="fa fa-floppy-o fa-fw"></i>{{App\Language::trans('Save')}}</button>
				<a href="{{action('CustomersController@getIndex')}}" class="btn btn-danger"><i class="fa fa-ban fa-fw"></i>{{App\Language::trans('Close')}}</a>
			</div>
		</div>
	</div>
</div>
{!! Form::close() !!}
@endsection
@section('script')
@endsection