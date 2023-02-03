@extends('_version_02.commons.layouts.admin')
@section('content')
{!! Form::model($model, ['class'=>'form-horizontal']) !!}
@include('_version_02.commons.layouts.partials._alert')
<div id="alert_msg_div" class="alert alert-danger alert-dismissible hide">
	<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
	<i id="alert_msg" class="icon fa fa-danger"></i>
</div>

<div class="box">
	<div class="box-header with-border">
		<h3 class="box-title">{{App\Language::trans('Refunds')}}</h3>
		<div class="box-tools pull-right">
			<a href="{{action('ARRefundsController@getNew')}}" class="btn btn-block btn-info">
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

				<div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
					{!! Form::label('name', App\Language::trans('Customer Name'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						{!! Form::text('name', null, ['class'=>'form-control','required']) !!}
                        {!!$errors->first('name', '<label for="name" class="help-block error">:message</label>')!!}
					</div>
				</div>

				<div class="form-group{{ $errors->has('payment_from_account') ? ' has-error' : '' }}">
					{!! Form::label('payment_from_account', App\Language::trans('Payment From'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						{!! Form::select('payment_from_account', App\Setting::bank_or_cash_combobox(), null, ['class'=>'form-control','autofocus','required','onchange'=>'init_customer_info(this)']) !!}
                        {!!$errors->first('payment_from_account', '<label for="customer_id" class="help-block error">:message</label>')!!}
					</div>
				</div>

				<div class="form-group{{ $errors->has('amount') ? ' has-error' : '' }}">
					{!! Form::label('amount', App\Language::trans('Amount'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						{!! Form::text('amount', null, ['class'=>'form-control','autofocus','required', 'onkeydown'=>'numeric_input_only(this)']) !!}
                        {!!$errors->first('amount', '<label for="amount" class="help-block error">:message</label>')!!}
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

			</div>

			<div class="col-md-6">

				<div class="form-group{{ $errors->has('document_no') ? ' has-error' : '' }}">
					{!! Form::label('document_no', App\Language::trans('Payment No.'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						{!! Form::text('document_no', null, ['class'=>'form-control','autofocus','required','readonly']) !!}
                        {!!$errors->first('document_no', '<label for="document_no" class="help-block error">:message</label>')!!}
					</div>
				</div>


				<div class="form-group{{ $errors->has('document_date') ? ' has-error' : '' }}">
					{!! Form::label('document_date', App\Language::trans('Document Date'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						{!! Form::text('document_date', null, ['class'=>'form-control','autofocus','required']) !!}
                        {!!$errors->first('document_date', '<label for="document_date" class="help-block error">:message</label>')!!}
					</div>
				</div>


				<div class="form-group{{ $errors->has('payment_method') ? ' has-error' : '' }}">
					{!! Form::label('payment_method', App\Language::trans('Payment Method'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						{!! Form::select('payment_method', App\Setting::payment_method(), null, ['class'=>'form-control','autofocus','required' , 'onchange'=>'change_payment_label_text_by_payment_method(this)']) !!}
                        {!!$errors->first('payment_method', '<label for="payment_term_id" class="help-block error">:message</label>')!!}
					</div>
				</div>
	

				<div class="form-group{{ $errors->has('po_no') ? ' has-error' : '' }}">
					{!! Form::label('doc_payment_no_ref_no', App\Language::trans('Cheque No.'), ['id'=>'doc_payment_no_ref_no', 'class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						{!! Form::text('reference_no', null, ['class'=>'form-control','autofocus','required']) !!}
                        {!!$errors->first('reference_no', '<label for="po_no" class="help-block error">:message</label>')!!}
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

         <!-- /.col -->
			        <div  class="col-md-12">
			         <!-- .box -->
			          <div id="return_payment_div" class="box box-info">
			          		<!-- .box-header -->
					            <div class="box-header with-border">
					              <h3 class="box-title">
					               {{ Form::checkbox('return_payment', 1, null, ['id'=>'return_payment', 'class' => 'minimal' , 'onchange'=>"init_return_payment_div('return_payment')"]) }}
					                Return Payment</h3>
					            </div>
				            <!-- /.box-header -->
				          	  <!-- .box-body -->
					            <div class="box-body">
										<div class="form-group{{ $errors->has('return_payment_date') ? ' has-error' : '' }}">
											{!! Form::label('return_payment_date', App\Language::trans('Date'), ['class'=>'control-label col-md-4']) !!}
											<div class="col-md-8">
												{!! Form::text('return_payment_date', null, ['class'=>'form-control']) !!}
						                        {!!$errors->first('return_payment_date', '<label for="return_payment_date" class="help-block error">:message</label>')!!}
											</div>
										</div>

										<div class="form-group{{ $errors->has('reason') ? ' has-error' : '' }}">
											<div class="form-group{{ $errors->has('reason') ? ' has-error' : '' }}">
												{!! Form::label('reason', App\Language::trans('Reason'), ['class'=>'control-label col-md-4']) !!}
												<div class="col-md-8">
													{!! Form::textarea('reason', null, ['rows'=>7,'class'=>'form-control']) !!}
							                        {!!$errors->first('reason', '<label for="reason" class="help-block error">:message</label>')!!}
												</div>
											</div>
										</div>
					            </div>
				          	  <!-- /.box-body -->
			          </div>
			          <!-- /.box -->
			        </div>
			        <!-- /.col -->


			</div>
			
	</div>

</div>
</div>

<div>
	@include('_version_02.billings.ar_refunds.partials.__list')
</div>

<div class="box">
	<div class="box-header with-border">
		<h3 class="box-title">{{App\Language::trans('Action Bar')}}</h3>
	</div>
	<div class="box-footer">
		<div class="row">
			<div class="col-md-12">
				<div class="form-group{{ $errors->has('remark') ? ' has-error' : '' }}">
					{!! Form::label('remark', App\Language::trans('Remark'), ['class'=>'control-label col-md-2']) !!}
					<div class="col-md-10">
						{!! Form::textarea('remark', null, ['rows'=>7,'class'=>'form-control']) !!}
                        {!!$errors->first('remark', '<label for="remark" class="help-block error">:message</label>')!!}
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-offset-2 col-md-10">
				<button type="submit" class="btn btn-primary"><i class="fa fa-floppy-o fa-fw"></i>{{App\Language::trans('Save')}}</button>
				<a href="{{action('ARRefundsController@getIndex')}}" class="btn btn-danger"><i class="fa fa-ban fa-fw"></i>{{App\Language::trans('Close')}}</a>
			</div>
		</div>
	</div>
</div>

{!! Form::close() !!}
@endsection
@section('script')
	var customerInfoUrl = "{{action('CustomersController@getInfo')}}";	
	function init_customer_info(me) {
		console.log("s");
		$.get(customerInfoUrl, {customer_id:$(me).val()}, function(fdata){
			for (var key in fdata.data) {
				if(key != "status") {
					$("input[name="+key+"]").val(fdata.data[key]);
				
					if(key == "currency_label") {
						$(".currency_label").html(fdata.data[key]);
					}
					if(key.match(/_id/g)) {
						$("select[name="+key+"]").val(fdata.data[key]).trigger("change");
					}
				}
			}	
		},"json");
	
		//getByCustomerId
		getCustomerDocumentByCustomerIdAndType($(me).val(),'AR_PAYMENT_RECEIVED','payment_received_table',$('#type').val());
	}

	init_select2($("select[name=customer_id]"));
	init_select2($("select[name=currency_id]"));
	init_select2($("select[name=payment_term_id]"));
	init_select2($("select[name=billing_country_id]"));
	init_select2($("select[name=billing_state_id]"));
	init_select2($("select[name=billing_city_id]"));
	init_select2($("select[name=delivery_country_id]"));
	init_select2($("select[name=delivery_state_id]"));
	init_select2($("select[name=delivery_city_id]"));
	$("table").find("tr").each(function(){
		init_select2($(this).find("select"));
	});

@endsection