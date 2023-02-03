@extends('billings.layouts.admin')
@section('content')
{!! Form::model($model, ['class'=>'form-horizontal']) !!}
@include('_version_02.billings.layouts.partials._alert')
@include('_version_02.billings.ar_payment_receiveds.partials._hidden_variable')

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
         <br><br><br>

      <!-- START ROW -->
        <div class="row">

        <!--  START 6 COL -->
            <div class="col-md-6">
                <div class="hide" id="div_step_1">    

                    <h4 class="page-header">{{App\Language::trans('Payee Info And Payment Method')}}</h4>
                    <div class="form-group{{ $errors->has('customer_id') ? ' has-error' : '' }}">
                        {!! Form::label('unit_no', App\Language::trans('Unit No.'), ['class'=>'control-label col-md-4']) !!}   
                        <div class="col-md-8">                      
                         {!! Form::label('unit_no', App\Language::trans('-'), ['id'=> 'unit_no', 'class'=>'control-label']) !!}
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('membership_period') ? ' has-error' : '' }}">
                        {!! Form::label('membership_period', App\Language::trans('Current Membership Period.'), ['class'=>'control-label col-md-4']) !!}   
                        <div class="col-md-8">                      
                         {!! Form::label('membership_period', App\Language::trans('-'), ['id'=> 'membership_period', 'class'=>'control-label']) !!}
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                        {!! Form::label('name', App\Language::trans('Payee Name'), ['class'=>'control-label col-md-4']) !!}
                        <div class="col-md-8">
                            {!! Form::text('customer_name', null, ['class'=>'form-control','required']) !!} {!!$errors->first('name', '
                            <label for="name" class="help-block error">:message</label>')!!}
                        </div>
                    </div>

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

            <!--  START 6 COL -->
            <div class="col-md-6">
              <div class="hide" id="div_step_2">
                    <h4 class="page-header">{{App\Language::trans('Payment Detail')}}</h4>
                    <div class="form-group{{ $errors->has('membership_extend_to_date') ? ' has-error' : '' }}">
                        {!! Form::label('ex', App\Language::trans('New Membership Period.'), ['class'=>'control-label col-md-4']) !!}   
                        <div class="col-md-8">                      
                         {!! Form::label('membership_extend_to_date', App\Language::trans('-'), ['id'=> 'membership_extend_to_date', 'class'=>'control-label']) !!}
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('document_date') ? ' has-error' : '' }}">
                            {!! Form::label('document_date', App\Language::trans('Document Date'), ['class'=>'control-label col-md-4']) !!}
                            <div class="col-md-8">
                                {!! Form::text('document_date', null, ['class'=>'form-control','required']) !!} {!!$errors->first('document_date', '
                                <label for="document_date" class="help-block error">:message</label>')!!}
                            </div>
                    </div>
                    
                   <div class="form-group{{ $errors->has('document_no') ? ' has-error' : '' }}">
                        {!! Form::label('document_no', App\Language::trans('Receipt No.'), ['class'=>'control-label col-md-4']) !!}
                        <div class="col-md-8">
                            {!! Form::text('document_no', null, ['class'=>'form-control','readonly']) !!} {!!$errors->first('document_no', '
                            <label for="document_no" class="help-block error">:message</label>')!!}
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            {!! Form::label('name', App\Language::trans('Product Name'), ['class'=>'control-label col-md-4']) !!}
                            <div class="col-md-8">
                                {!! Form::text('name', null, ['class'=>'form-control','required','readonly']) !!} {!!$errors->first('name', '
                                <label for="name" class="help-block error">:message</label>')!!}
                            </div>
                    </div>

                    <div class="form-group{{ $errors->has('amount') ? ' has-error' : '' }}">
                        {!! Form::label('amount', App\Language::trans('Amount'), ['class'=>'control-label col-md-4']) !!}
                        <div class="col-md-8">
                            {!! Form::text('amount', null, ['class'=>'form-control','autofocus','required', 'onkeydown'=>'numeric_input_only(this)','readonly']) !!} {!!$errors->first('amount', '
                            <label for="amount" class="help-block error">:message</label>')!!}
                        </div>
                    </div>
                 </div>
             </div>
            <!-- END 6 COL -->

         </div>
        <!-- END ROW -->

        <!-- START ROW -->
        <div class="row">
            <!--  START 6 COL -->
                <div class="col-md-6">
                    <div class="hide" id="div_step_3">        
                         <h4 class="page-header">{{App\Language::trans('Transaction Detail')}}</h4>

                         <div class="form-group{{ $errors->has('deposit_to_account') ? ' has-error' : '' }}">
                            {!! Form::label('deposit_to_account', App\Language::trans('Deposit To'), ['class'=>'control-label col-md-4']) !!}
                            <div class="col-md-8">
                                {!! Form::select('deposit_to_account', App\Setting::bank_or_cash_combobox(), null, ['class'=>'form-control','autofocus','required']) !!} 
                                {!!$errors->first('deposit_to_account', '<label for="customer_id" class="help-block error">:message</label>')!!}
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


            <!--  START 6 COL -->
                <div class="col-md-6">
                    <div class="hide" id="div_step_end">        
                         <h4 class="page-header">{{App\Language::trans('Remark And Confirmation')}}</h4>

                    <div class="form-group{{ $errors->has('remark') ? ' has-error' : '' }}">
                        {!! Form::label('remark', App\Language::trans('Remark'), ['class'=>'control-label col-md-2']) !!}
                        <div class="col-md-10">
                            {!! Form::textarea('remark', null, ['rows'=>7,'class'=>'form-control']) !!} {!!$errors->first('remark', '
                            <label for="remark" class="help-block error">:message</label>')!!}
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-offset-2 col-md-10">
                            <div>
                                <button type="submit" class="btn btn-primary"><i class="fa fa-floppy-o fa-fw"></i>{{App\Language::trans('Save')}}</button>
                                <a href="{{action('ARInvoicesController@getIndex')}}" class="btn btn-danger"><i class="fa fa-ban fa-fw"></i>{{App\Language::trans('Close')}}</a>
                            </div>
                        </div>
                    </div>

                 </div>
                </div>
            <!-- END 6 COL -->



         </div>
          <!-- END ROW -->
		</div>
			<!-- END ROW -->

        <div class="box-footer">
            <!-- <label onclick="go_to_step_with_previous_show(get_current_step(),'backward');" class="btn btn-default">Back</label> -->
            <label onclick="go_to_step_with_previous_show(get_current_step(),'forward');" class="btn btn-info pull-right">Next</label>
        </div>

        <!-- return payment include -->
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

        customer_id = typeof(me) == "undefined" ? document.getElementById('customer_id').value  : $(me).val();
		$.get(customerInfoUrl, {customer_id:customer_id}, function(fdata){
            console.log(JSON.stringify(fdata));
            membership_period = "";
            membership_detail = fdata.data.membership_detail;
            membership_detail_personal_info = fdata.data.membership_detail_personal_info;
            for (var key in  membership_detail_personal_info ) { 
              if(key == 'membership_period' ||  key == 'membership_extend_to_date'){
                 $("#" + key).html(membership_detail_personal_info[key]);
              } 
            }
           
            for (var key in  membership_detail) {
                if(key == "address") {
                    for(var key_item in membership_detail[key]){
                    console.log("hit-" + membership_detail[key][key_item]);
                      if(key_item == "unit_no"){
                        $("#"+key_item).html(membership_detail[key][key_item]);
                         $("#unit_no").html(membership_detail[key][key_item]);
                      }
                    }
                 }
            }

			for (var key in fdata.data) {
                if(key == "name") {
                    console.log("here - " + fdata.data[key]);
				    $("input[name=customer_"+key+"]").val(fdata.data[key]);

                }else if(key != "status") {
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
	
		//getCustomerInvoiceById($(me).val(),'invoice_table');
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