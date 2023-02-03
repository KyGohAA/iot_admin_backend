@extends('billings.layouts.admin')
@section('content')
{!! Form::model($model, ['class'=>'form-horizontal']) !!}
@include('_version_02.billings.layouts.partials._alert')
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
						<p class="form-control-static">{{$model->code}}</p>
					</div>
				</div>
				<div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
					{!! Form::label('name', App\Language::trans('Name'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						<p class="form-control-static">{{$model->name}}</p>
					</div>
				</div>
				<div class="form-group{{ $errors->has('registration_no') ? ' has-error' : '' }}">
					{!! Form::label('registration_no', App\Language::trans('Registration No'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						<p class="form-control-static">{{$model->registration_no}}</p>
					</div>
				</div>
				<div class="form-group{{ $errors->has('gst_no') ? ' has-error' : '' }}">
					{!! Form::label('gst_no', App\Language::trans('GST No'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						<p class="form-control-static">{{$model->gst_no}}</p>
					</div>
				</div>
				<div class="form-group{{ $errors->has('currency_id') ? ' has-error' : '' }}">
					{!! Form::label('currency_id', App\Language::trans('Currency'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						<p class="form-control-static">{{$model->display_relationed('currency', 'code')}}</p>
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group{{ $errors->has('customer_group_id') ? ' has-error' : '' }}">
					{!! Form::label('customer_group_id', App\Language::trans('Group'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						<p class="form-control-static">{{$model->display_relationed('customer_group', 'name')}}</p>
					</div>
				</div>
				<div class="form-group{{ $errors->has('payment_term_id') ? ' has-error' : '' }}">
					{!! Form::label('payment_term_id', App\Language::trans('Payment Term'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						<p class="form-control-static">{{$model->display_relationed('payment_term', 'code')}}</p>
					</div>
				</div>
				<div class="form-group{{ $errors->has('sales_person') ? ' has-error' : '' }}">
					{!! Form::label('sales_person', App\Language::trans('Sales Person'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						<p class="form-control-static">{{$model->sales_person}}</p>
					</div>
				</div>
				<div class="form-group{{ $errors->has('credit_limit') ? ' has-error' : '' }}">
					{!! Form::label('credit_limit', App\Language::trans('Credit Limit'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						<p class="form-control-static">{{$model->credit_limit}}</p>
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
						<p class="form-control-static">{{$model->contact_person}}</p>
					</div>
				</div>
				<div class="form-group{{ $errors->has('phone_no_1') ? ' has-error' : '' }}">
					{!! Form::label('phone_no_1', App\Language::trans('Phone No. (1)'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						<p class="form-control-static">{{$model->phone_no_1}}</p>
					</div>
				</div>
				<div class="form-group{{ $errors->has('phone_no_2') ? ' has-error' : '' }}">
					{!! Form::label('phone_no_2', App\Language::trans('Phone No. (2)'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						<p class="form-control-static">{{$model->phone_no_2}}</p>
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group{{ $errors->has('fax_no') ? ' has-error' : '' }}">
					{!! Form::label('fax_no', App\Language::trans('Fax No.'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						<p class="form-control-static">{{$model->fax_no}}</p>
					</div>
				</div>
				<div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
					{!! Form::label('email', App\Language::trans('Email'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						<p class="form-control-static">{{$model->email}}</p>
					</div>
				</div>
				<div class="form-group{{ $errors->has('website') ? ' has-error' : '' }}">
					{!! Form::label('website', App\Language::trans('Website'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						<p class="form-control-static">{{$model->website}}</p>
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
						<p class="form-control-static">{{$model->billing_address1}}</p>
					</div>
				</div>
				<div class="form-group{{ $errors->has('billing_address2') ? ' has-error' : '' }}">
					<div class="col-md-offset-4 col-md-8">
						<p class="form-control-static">{{$model->billing_address2}}</p>
					</div>
				</div>
				<div class="form-group{{ $errors->has('billing_postcode') ? ' has-error' : '' }}">
					{!! Form::label('billing_postcode', App\Language::trans('Postcode'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						<p class="form-control-static">{{$model->billing_postcode}}</p>
					</div>
				</div>
				<div class="form-group{{ $errors->has('billing_country_id') ? ' has-error' : '' }}">
					{!! Form::label('billing_country_id', App\Language::trans('Country'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						<p class="form-control-static">{{$model->display_relationed('billing_country', 'name')}}</p>
					</div>
				</div>
				<div class="form-group{{ $errors->has('billing_state_id') ? ' has-error' : '' }}">
					{!! Form::label('billing_state_id', App\Language::trans('State'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						<p class="form-control-static">{{$model->display_relationed('billing_state', 'name')}}</p>
					</div>
				</div>
				<div class="form-group{{ $errors->has('billing_city_id') ? ' has-error' : '' }}">
					{!! Form::label('billing_city_id', App\Language::trans('City'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						<p class="form-control-static">{{$model->display_relationed('billing_city', 'name')}}</p>
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<h4 class="text-center">{{App\Language::trans('Delivery Address')}}</h4>
				<div class="form-group{{ $errors->has('delivery_address1') ? ' has-error' : '' }}">
					{!! Form::label('delivery_address1', App\Language::trans('Address'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						<p class="form-control-static">{{$model->delivery_address1}}</p>
					</div>
				</div>
				<div class="form-group{{ $errors->has('delivery_address2') ? ' has-error' : '' }}">
					<div class="col-md-offset-4 col-md-8">
						<p class="form-control-static">{{$model->delivery_address2}}</p>
					</div>
				</div>
				<div class="form-group{{ $errors->has('delivery_postcode') ? ' has-error' : '' }}">
					{!! Form::label('delivery_postcode', App\Language::trans('Postcode'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						<p class="form-control-static">{{$model->delivery_postcode}}</p>
					</div>
				</div>
				<div class="form-group{{ $errors->has('delivery_country_id') ? ' has-error' : '' }}">
					{!! Form::label('delivery_country_id', App\Language::trans('Country'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						<p class="form-control-static">{{$model->display_relationed('delivery_country', 'name')}}</p>
					</div>
				</div>
				<div class="form-group{{ $errors->has('delivery_state_id') ? ' has-error' : '' }}">
					{!! Form::label('delivery_state_id', App\Language::trans('State'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						<p class="form-control-static">{{$model->display_relationed('delivery_state', 'name')}}</p>
					</div>
				</div>
				<div class="form-group{{ $errors->has('delivery_city_id') ? ' has-error' : '' }}">
					{!! Form::label('delivery_city_id', App\Language::trans('City'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						<p class="form-control-static">{{$model->display_relationed('delivery_city', 'name')}}</p>
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
						<p class="form-control-static">{{$model->display_status_string('status')}}</p>
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group{{ $errors->has('is_suspend') ? ' has-error' : '' }}">
					{!! Form::label('is_suspend', App\Language::trans('Account Suspend'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						<p class="form-control-static">{{$model->display_answer_string('is_suspend')}}</p>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="form-group{{ $errors->has('remark') ? ' has-error' : '' }}">
					{!! Form::label('remark', App\Language::trans('Remark'), ['class'=>'control-label col-md-2']) !!}
					<div class="col-md-10">
						<p class="form-control-static">{{nl2br($model->remark)}}</p>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="box-footer">
		<div class="row">
			<div class="col-md-offset-2 col-md-10">
				<a href="{{action('CustomersController@getIndex')}}" class="btn btn-danger"><i class="fa fa-ban fa-fw"></i>{{App\Language::trans('Close')}}</a>
			</div>
		</div>
	</div>
</div>
{!! Form::close() !!}
@endsection
@section('script')
@endsection