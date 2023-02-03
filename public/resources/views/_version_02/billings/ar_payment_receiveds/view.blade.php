@extends('billings.layouts.admin')
@section('content')
{!! Form::model($model, ['class'=>'form-horizontal']) !!}
@include('_version_02.billings.layouts.partials._alert')


<div id="alert_msg_div" class="alert alert-danger alert-dismissible hide">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
    <i id="alert_msg" class="icon fa fa-danger"></i>
</div>

<!-- Horizontal Form -->
<div class="box box-info">
    <div class="box-header with-border">
        <h3 class="box-title">{{App\Language::trans('Received Payment')}}</h3>
        <div class="box-tools pull-right">
            <a href="{{action('ARPaymentReceivedsController@getNew')}}" class="btn btn-block btn-info">
                <i class="fa fa-file fa-fw"></i> {{App\Language::trans('New File')}}
            </a>
        </div>
    </div>
    <div class="box-body">
    	 <div class="col-md-12">
                    @include('_version_02.billings.layouts.partials._progress_stepper')
         </div>
                <br>
                <br>
                <br>

        <!-- START ROW -->
        <div class="row">
            <!--  START 6 COL -->
            <div class="col-md-6">
                <div class="hide" id="div_step_1">
                    <div class="form-group{{ $errors->has('customer_id') ? ' has-error' : '' }}">
                        {!! Form::label('customer_id', App\Language::trans('Customer Code'), ['class'=>'control-label col-md-4']) !!}
                        <div class="col-md-8">
                            {!!Form::hidden('is_tax_inclusive', null)!!} {!! Form::select('customer_id', App\Customer::combobox(), null, ['class'=>'form-control','onchange'=>'init_customer_info(this)']) !!} {!!$errors->first('customer_id', '
                            <label for="customer_id" class="help-block error">:message</label>')!!}
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                        {!! Form::label('name', App\Language::trans('Customer Name'), ['class'=>'control-label col-md-4']) !!}
                        <div class="col-md-8">
                            {!! Form::text('name', null, ['class'=>'form-control','required']) !!} {!!$errors->first('name', '
                            <label for="name" class="help-block error">:message</label>')!!}
                        </div>
                    </div>
                </div>

                <div class="hide" id="div_step_2">
                    <div class="form-group{{ $errors->has('type') ? ' has-error' : '' }}">
                        {!! Form::label('type', App\Language::trans('Type'), ['class'=>'control-label col-md-4']) !!}
                        <div class="col-md-8">
                            {!! Form::select('type', App\Setting::payment_received_type(), null, ['class'=>'form-control','autofocus','required','onchange'=>'init_payment_received_type_handle(this)']) !!} {!!$errors->first('type', '
                            <label for="type" class="help-block error">:message</label>')!!}
                        </div>
                    </div>
                </div>

                <div class="hide" id="div_step_3">
                    <div class="form-group{{ $errors->has('deposit_to_account') ? ' has-error' : '' }}">
                        {!! Form::label('deposit_to_account', App\Language::trans('Deposit To'), ['class'=>'control-label col-md-4']) !!}
                        <div class="col-md-8">
                            {!! Form::select('deposit_to_account', App\Setting::bank_or_cash_combobox(), null, ['class'=>'form-control','autofocus','required','onchange'=>'init_customer_info(this)']) !!} {!!$errors->first('deposit_to_account', '
                            <label for="customer_id" class="help-block error">:message</label>')!!}
                        </div>
                    </div>
                </div>

                 <div class="hide" id="div_step_4">
               
                <div class="form-group{{ $errors->has('payment_method') ? ' has-error' : '' }}">
                    {!! Form::label('payment_method', App\Language::trans('Payment Method'), ['class'=>'control-label col-md-4']) !!}
                    <div class="col-md-8">
                        {!! Form::select('payment_method', App\Setting::payment_method(), null, ['class'=>'form-control','autofocus','required' , 'onchange'=>'change_payment_label_text_by_payment_method(this)']) !!} {!!$errors->first('payment_method', '
                        <label for="payment_method" class="help-block error">:message</label>')!!}
                    </div>
                </div>

                <div class="form-group{{ $errors->has('reference_no') ? ' has-error' : '' }}">
                    {!! Form::label('doc_payment_no_ref_no', App\Language::trans('Reference No.'), ['id'=>'doc_payment_no_ref_no', 'class'=>'control-label col-md-4']) !!}
                    <div class="col-md-8">
                        {!! Form::text('reference_no', null, ['class'=>'form-control','autofocus','required']) !!} {!!$errors->first('reference_no', '
                        <label for="reference_no" class="help-block error">:message</label>')!!}
                    </div>
                </div>


             </div>

               
            </div>
            <!-- END 6 COL -->

            <!-- START 6 COL -->
         <div class="col-md-6">
          <div class="hide" id="div_step_6">

          		<div class="form-group{{ $errors->has('document_no') ? ' has-error' : '' }}">
                    {!! Form::label('document_no', App\Language::trans('Receipt No.'), ['class'=>'control-label col-md-4']) !!}
                    <div class="col-md-8">
                        {!! Form::text('document_no', null, ['class'=>'form-control','readonly']) !!} {!!$errors->first('document_no', '
                        <label for="document_no" class="help-block error">:message</label>')!!}
                    </div>
                </div>
                <div class="form-group{{ $errors->has('document_date') ? ' has-error' : '' }}">
                    {!! Form::label('document_date', App\Language::trans('Document Date'), ['class'=>'control-label col-md-4']) !!}
                    <div class="col-md-8">
                        {!! Form::text('document_date', null, ['class'=>'form-control','autofocus','required']) !!} {!!$errors->first('document_date', '
                        <label for="document_date" class="help-block error">:message</label>')!!}
                    </div>
                </div>
                
                 <div class="form-group{{ $errors->has('sales_person') ? ' has-error' : '' }}">
                    {!! Form::label('sales_person', App\Language::trans('Sales Person'), ['class'=>'control-label col-md-4']) !!}
                    <div class="col-md-8">
                        {!! Form::text('sales_person', null, ['class'=>'form-control','autofocus','required']) !!} {!!$errors->first('sales_person', '
                        <label for="sales_person" class="help-block error">:message</label>')!!}
                    </div>
                </div>



                    <div class="form-group{{ $errors->has('amount') ? ' has-error' : '' }}">
                        {!! Form::label('amount', App\Language::trans('Amount'), ['class'=>'control-label col-md-4']) !!}
                        <div class="col-md-8">
                            {!! Form::text('amount', null, ['class'=>'form-control','autofocus','required', 'onkeydown'=>'numeric_input_only(this)','readonly']) !!} {!!$errors->first('amount', '
                            <label for="amount" class="help-block error">:message</label>')!!}
                        </div>
                    </div>
                
                    <div class="form-group{{ $errors->has('currency_id') ? ' has-error' : '' }}">
                        {!! Form::label('currency_id', App\Language::trans('Currency'), ['class'=>'control-label col-md-4']) !!}
                        <div class="col-md-8">
                            {!! Form::select('currency_id', App\Currency::combobox(), null, ['class'=>'form-control','autofocus','required','onchange'=>'init_currency_rate(this)']) !!} {!!$errors->first('currency_id', '
                            <label for="currency_id" class="help-block error">:message</label>')!!}
                        </div>
                    </div>
                    <div class="form-group{{ $errors->has('currency_rate') ? ' has-error' : '' }}">
                        {!! Form::label('currency_rate', App\Language::trans('Currency Rate'), ['class'=>'control-label col-md-4']) !!}
                        <div class="col-md-8">
                            {!! Form::text('currency_rate', null, ['class'=>'form-control','autofocus','required']) !!} {!!$errors->first('currency_rate', '
                            <label for="currency_rate" class="help-block error">:message</label>')!!}
                        </div>
                    </div>
                </div>
            </div>
            <!-- END 6 COL -->
		</div>
			<!-- END ROW -->

             <div class="box-footer">
            <label onclick="go_to_step_with_previous_show(get_current_step(),'backward');" class="btn btn-default">Back</label>
            <label onclick="go_to_step_with_previous_show(get_current_step(),'forward');" class="btn btn-info pull-right">Next</label>
        </div>

			 <!-- START ROW -->
        <div class="row">
            <hr>
            <div class="col-md-12">
                <div id="security_deposit_remark">
                    <p class="text-muted well well-sm no-shadow" style="margin-top: 10px;">
                        <strong>{{App\Language::trans('Security Deposit')}} </strong>- {{App\Language::trans('money paid in advance to protect the provider of a product or service against damage or nonpayment by the customer')}}.
                        <br> {{App\Language::trans('Please specify the')}} <strong>{{App\Language::trans('bank account')}}</strong> {{App\Language::trans('where the money is received into and the')}} <strong>{{App\Language::trans('account')}}</strong> {{App\Language::trans('to post the security deposit. Any unused Security deposit will then show on your Customer Statement under')}} <strong>{{App\Language::trans('Deposit Available')}}</strong>.
                    </p>
                </div>
            </div>

            <div id="invoice_receipt_remark">
                <div id="invoice_receipt_remark" class="col-md-12">
                    <p class="text-muted well well-sm no-shadow" style="margin-top: 10px;">
                        {{App\Language::trans('Tick the check box next to the outstanding invoice to apply payment received.')}}
                    </p>
                </div>
            </div>
        </div>
        <!-- END ROW -->


        <div class="row" id="div_step_5">
		 <div class="col-md-12">
            <div id="invoice_receipt_item">
			    @include('_version_02.billings.ar_payment_receiveds.partials.__list')
			</div>
		  </div>
		</div>

            <div class="box-footer"  id="div_step_end">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group{{ $errors->has('remark') ? ' has-error' : '' }}">
                            {!! Form::label('remark', App\Language::trans('Remark'), ['class'=>'control-label col-md-2']) !!}
                            <div class="col-md-10">
                                {!! Form::textarea('remark', null, ['rows'=>7,'class'=>'form-control']) !!} {!!$errors->first('remark', '
                                <label for="remark" class="help-block error">:message</label>')!!}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-offset-2 col-md-10">
                    	<div id="div_step_end">
	                        <button type="submit" class="btn btn-primary"><i class="fa fa-floppy-o fa-fw"></i>{{App\Language::trans('Save')}}</button>
	                        <a href="{{action('ARInvoicesController@getIndex')}}" class="btn btn-danger"><i class="fa fa-ban fa-fw"></i>{{App\Language::trans('Close')}}</a>
	                    </div>
                    </div>
                </div>
            </div>


            

             <!-- /.col -->
                <div class="col-md-12">
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
                                    {!! Form::text('return_payment_date', null, ['class'=>'form-control']) !!} {!!$errors->first('return_payment_date', '
                                    <label for="return_payment_date" class="help-block error">:message</label>')!!}
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('reason') ? ' has-error' : '' }}">
                                <div class="form-group{{ $errors->has('reason') ? ' has-error' : '' }}">
                                    {!! Form::label('reason', App\Language::trans('Reason'), ['class'=>'control-label col-md-4']) !!}
                                    <div class="col-md-8">
                                        {!! Form::textarea('reason', null, ['rows'=>7,'class'=>'form-control']) !!} {!!$errors->first('reason', '
                                        <label for="reason" class="help-block error">:message</label>')!!}
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
        <!-- END ROW -->

      
</div>
<!-- /.box -->

       
      </div>
    <!-- END FORM BODY -->
    





{!! Form::close() !!}
@endsection
@section('script')


	$( "input[type='text']" ).change(function() {
	  progression_checker_by_step_no();
	});

	$("select").change(function() {
	  progression_checker_by_step_no();
  	});


	$('#amount').focusout(function() {
			$('#amount').val(init_decimal_point($('#amount').val()));
	});

	var customerInfoUrl = "{{action('CustomersController@getInfo')}}";	
	function init_customer_info(me) {

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
	
		getCustomerInvoiceById($(me).val(),'invoice_table');
	}
	

	function init_calculate_row(me, price_adjust) {
		var tr 					=	$(me).closest("tr");
		var is_tax_inclusive 	=	$("input[name*=is_tax_inclusive]");
		var quantity 			=	tr.find("input[name*=quantity]");
		var discount 			=	tr.find("input[name*=discount]");
		var amount 				=	tr.find("input[name*=amount]");
		var unit_price 			=	tr.find("input[name*=unit_price]");
		var tax_percent 		=	tr.find("input[name*=tax_percent]");
		var tax_txt 			=	tr.find("input[name*=tax_txt]");
		if (price_adjust) {
			if(is_tax_inclusive) {
				var new_unit_price	=	(unit_price.val() || 0)/(parseFloat(1)+parseFloat(tax_percent.val()));
				unit_price.val(init_decimal_point(new_unit_price));
			}
		}
		var tax_total			=	quantity.val() * unit_price.val() * tax_percent.val();
		tax_txt.val(init_decimal_point(tax_total));
		var total 				=	quantity.val() * unit_price.val() + tax_total;
		amount.val(init_decimal_point(total));
		init_calculate_product_table("product_table");
	}
	
	init_daterange("input[name=document_date]");
	init_daterange("input[name=return_payment_date]");
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
