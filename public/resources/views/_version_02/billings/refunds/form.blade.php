@extends('billings.layouts.admin')
@section('content')
{!! Form::model($model, ['class'=>'form-horizontal']) !!}
@include('_version_02.billings.layouts.partials._alert')
<div class="box">
	<div class="box-header with-border">
		<h3 class="box-title">{{App\Language::trans('Refunds')}}</h3>
		<div class="box-tools pull-right">
			<a href="{{action('CountriesController@getNew')}}" class="btn btn-block btn-info">
				<i class="fa fa-file fa-fw"></i> {{App\Language::trans('New File')}}
			</a>
		</div>
	</div>
	<div class="box-body">
		<div class="row">
			<div class="col-md-6">

			<div class="form-group{{ $errors->has('type') ? ' has-error' : '' }}">
					{!! Form::label('type', App\Language::trans('Type'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						{!! Form::select('type', App\Setting::payment_received_type(), null, ['class'=>'form-control','autofocus','required','onchange'=>'init_payment_received_type_handle(this)']) !!}
                        {!!$errors->first('type', '<label for="type" class="help-block error">:message</label>')!!}
					</div>
				</div>

				<div class="form-group{{ $errors->has('customer_id') ? ' has-error' : '' }}">
					{!! Form::label('customer_id', App\Language::trans('Customer'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						{!! Form::select('customer_id', App\Customer::combobox(), null, ['class'=>'form-control','autofocus','required','onchange'=>'init_customer_info(this)']) !!}
                        {!!$errors->first('customer_id', '<label for="customer_id" class="help-block error">:message</label>')!!}
					</div>
				</div>

                    <div class="form-group{{ $errors->has('payment_from_account_id') ? ' has-error' : '' }}">
					{!! Form::label('payment_from_account_id', App\Language::trans('Payment From'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						{!! Form::select('payment_from_account_id', App\Customer::combobox(), null, ['class'=>'form-control','autofocus','required','onchange'=>'init_customer_info(this)']) !!}
                        {!!$errors->first('payment_from_account_id', '<label for="customer_id" class="help-block error">:message</label>')!!}
					</div>
				</div>

				<div class="form-group{{ $errors->has('phone_no') ? ' has-error' : '' }}">
					{!! Form::label('phone_no', App\Language::trans('Amount'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						{!! Form::text('phone_no', null, ['class'=>'form-control','autofocus','required']) !!}
                        {!!$errors->first('phone_no', '<label for="phone_no" class="help-block error">:message</label>')!!}
					</div>
				</div>

				<div class="form-group{{ $errors->has('currency_id') ? ' has-error' : '' }}">
					{!! Form::label('currency_id', App\Language::trans('Currency'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						{!! Form::select('currency_id', App\Currency::combobox(), null, ['class'=>'form-control','autofocus','required']) !!}
                        {!!$errors->first('currency_id', '<label for="currency_id" class="help-block error">:message</label>')!!}
					</div>
				</div>
				<div class="form-group{{ $errors->has('currency_rate') ? ' has-error' : '' }}">
					{!! Form::label('currency_rate', App\Language::trans('Currency Rate'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						{!! Form::text('currency_rate', null, ['class'=>'form-control','autofocus','required']) !!}
                        {!!$errors->first('currency_rate', '<label for="currency_rate" class="help-block error">:message</label>')!!}
					</div>
				</div>

				<div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
					{!! Form::label('name', App\Language::trans('Account'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						{!! Form::text('name', null, ['class'=>'form-control','required']) !!}
                        {!!$errors->first('name', '<label for="name" class="help-block error">:message</label>')!!}
					</div>
				</div>

			</div>

			<div class="col-md-6">
				<div class="form-group{{ $errors->has('payment_no') ? ' has-error' : '' }}">
					{!! Form::label('payment_no', App\Language::trans('Payment No.'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						{!! Form::text('payment_no', null, ['class'=>'form-control','autofocus','required']) !!}
                        {!!$errors->first('payment_no', '<label for="payment_no" class="help-block error">:message</label>')!!}
					</div>
				</div>
				<div class="form-group{{ $errors->has('document_date') ? ' has-error' : '' }}">
					{!! Form::label('document_date', App\Language::trans('Document Date'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						{!! Form::text('document_date', null, ['class'=>'form-control','autofocus','required']) !!}
                        {!!$errors->first('document_date', '<label for="document_date" class="help-block error">:message</label>')!!}
					</div>
				</div>


				<div class="form-group{{ $errors->has('payment_term_id') ? ' has-error' : '' }}">
					{!! Form::label('payment_term_id', App\Language::trans('Payment Method'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						{!! Form::select('payment_term_id', App\Setting::payment_method(), null, ['class'=>'form-control','autofocus','required' , 'onchange'=>'change_payment_label_text_by_payment_method(this)']) !!}
                        {!!$errors->first('payment_term_id', '<label for="payment_term_id" class="help-block error">:message</label>')!!}
					</div>
				</div>
	

				<div class="form-group{{ $errors->has('po_no') ? ' has-error' : '' }}">
					{!! Form::label('doc_payment_no_ref_no', App\Language::trans('Cheque No.'), ['id'=>'doc_payment_no_ref_no', 'class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						{!! Form::text('po_no', null, ['class'=>'form-control','autofocus','required']) !!}
                        {!!$errors->first('po_no', '<label for="po_no" class="help-block error">:message</label>')!!}
					</div>
				</div>


				

				<div class="form-group{{ $errors->has('sales_person') ? ' has-error' : '' }}">
					{!! Form::label('sales_person', App\Language::trans('Sales Person'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						{!! Form::text('sales_person', null, ['class'=>'form-control','autofocus','required']) !!}
                        {!!$errors->first('sales_person', '<label for="sales_person" class="help-block error">:message</label>')!!}
					</div>
				</div>

			
         
    
        <!-- /.col -->



				  


			</div>
			
	</div>

</div>
</div>

<div>

	@include('_version_02.billings.refunds.partials.__list')
</div>
{!! Form::close() !!}
@endsection
@section('script')
	var customerInfoUrl = "{{action('CustomersController@getInfo')}}";
	function init_customer_info(me) {
		$.get(customerInfoUrl, {customer_id:$(me).val()}, function(fdata){
			for (var key in fdata.data) {
				console.log("key " + key + " has value " + fdata.data[key]);
			}
		},"json");
	}

	 $('#daterange-btn').daterangepicker(
      {
        ranges   : {
          'Today'       : [moment(), moment()],
          'Yesterday'   : [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
          'Last 7 Days' : [moment().subtract(6, 'days'), moment()],
          'Last 30 Days': [moment().subtract(29, 'days'), moment()],
          'This Month'  : [moment().startOf('month'), moment().endOf('month')],
          'Last Month'  : [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        },
        startDate: moment().subtract(29, 'days'),
        endDate  : moment()
      },
      function (start, end) {
        $('#daterange-btn span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'))
      }
    )
@endsection