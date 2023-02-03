@extends('_version_02.commons.layouts.admin')
@section('content')
@include('_version_02.commons.layouts.partials._alert')
@include('_version_02.billings.ar_payment_receiveds.partials._hidden_variable')
<div>
    {!! Form::model($model, ['class'=>'form-horizontal','id'=>'ar-payment-received-form']) !!}
         <h3>
            <span class="wizard-icon-wrap"><i class="ion ion-md-airplane"></i></span>
            <span class="wizard-head-text-wrap">
                <span class="step-head">Member Detail</span>
            </span> 
        </h3>
        <section>
            <h3 class="display-4 mb-40">Select a Member</h3>
            <div class="row">
                <div class="col-xl-4 mb-20">
                    <div class="card mt-30">
                        <div class="card-body bg-light">
                            <h5 class="card-title">{!!Form::select('customer_id', App\Customer::combobox(), null, ['class'=>'form-control','onchange'=>'init_customer_info_arpa(this)']) !!}</h5>
                            <p class="card-text"><label id="lbl_customer_detail"></label></p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-8 mb-20">
                
                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            {!! Form::label('name', App\Language::trans('Name'), ['class'=>'control-label']) !!}
                            {!! Form::text('name', null, ['class'=>'form-control','required']) !!}
                            {!!$errors->first('name', '<label for="name" class="help-block error">:message</label>')!!}
                        </div>

                        <div class="form-group{{ $errors->has('contact_person') ? ' has-error' : '' }}">
                            {!! Form::label('contact_person', App\Language::trans('Contact Person'), ['class'=>'control-label','required']) !!}
                            {!! Form::text('contact_person', null, ['class'=>'form-control']) !!}
                            {!!$errors->first('contact_person', '<label for="contact_person" class="help-block error">:message</label>')!!}
                        </div>
    
                        <h5 class="hk-sec-title mt-40">{{App\Language::trans('Billing Information')}}</h5><hr>

                        <div class="form-group">
                            <label for="email">Email</label>
                            {!! Form::email('email', null, ['class'=>'form-control','required']) !!}
                            {!!$errors->first('email', '<label for="email" class="help-block error">:message</label>')!!}
                        </div>

                        <div class="form-group">
                            <label for="address">Address</label>
                            {!! Form::text('billing_address1', null, ['class'=>'form-control','required']) !!}
                            {!!$errors->first('billing_address1', '<label for="billing_address1" class="help-block error">:message</label>')!!}
                        </div>

                        <div class="form-group">
                            <label for="address">Address 2 (Optional)</label>
                            {!! Form::text('billing_address2', null, ['class'=>'form-control','autofocus']) !!}
                            {!!$errors->first('billing_address2', '<label for="billing_address2" class="help-block error">:message</label>')!!}
                        </div>

                        <div class="row">
                            <div class="col-md-5 mb-10">
                                <label for="country">Country</label>
                                {!! Form::select('billing_country_id', App\Country::combobox(), null, ['class'=>'form-control','onchange'=>'init_state_selectbox(this)']) !!}
                                {!!$errors->first('billing_country_id', '<label for="billing_country_id" class="help-block error">:message</label>')!!}
                            </div>
                            <div class="col-md-4 mb-10">
                                <label for="state">State</label>
                                {!! Form::select('billing_state_id', App\State::combobox($model->billing_country_id), null, ['class'=>'form-control','onchange'=>'init_city_selectbox(this)']) !!}
                                {!!$errors->first('billing_state_id', '<label for="billing_state_id" class="help-block error">:message</label>')!!}
                            </div>
                            <div class="col-md-3 mb-10">
                                <label for="zip">PostCode</label>
                                {!! Form::text('billing_postcode', null, ['class'=>'form-control']) !!}
                                {!!$errors->first('billing_postcode', '<label for="billing_postcode" class="help-block error">:message</label>')!!}
                            </div>
                        </div>
                    
                </div>
            </div>
        </section>

        <h3>
            <span class="wizard-icon-wrap"><i class="ion ion-md-basket"></i></span>
            <span class="wizard-head-text-wrap">
                <span class="step-head">Select Item(s)</span>
            </span> 
        </h3>
        <section>
            <h3 class="display-4 mb-20">Select Item(s)</h3>
            <div class="row">
                <div class="col-xl-12 mb-20">
                     @include('_version_02.billings.ar_payment_receiveds.partials._list_by_product_to_invoice')
                </div>
            </div>
        </section>
      
        <h3>
            <span class="wizard-icon-wrap"><i class="ion ion-md-card"></i></span>
            <span class="wizard-head-text-wrap">
                <span class="step-head">Trasaction Detail</span>
            </span> 
        </h3>
        <section>
            <h3 class="display-4 mb-40">Transaction Detail</h3>
            <div class="row">
                <div class="col-xl-8 mb-20">

                        <div class="form-group{{ $errors->has('document_date') ? ' has-error' : '' }}">
                            {!! Form::label('document_date', App\Language::trans('Document Date'), ['class'=>'control-label','required']) !!}
                            <div class="col-md-12">
                                {!! Form::text('document_date', null, ['class'=>'form-control','required']) !!}
                                {!!$errors->first('document_date', '<label for="document_date" class="help-block error">:message</label>')!!}
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('document_no') ? ' has-error' : '' }}">
                            {!! Form::label('document_no', App\Language::trans('Document No.'), ['class'=>'control-label','required']) !!}
                            <div class="col-md-12">
                                {!! Form::text('document_no', null, ['class'=>'form-control','required']) !!}
                                {!!$errors->first('document_no', '<label for="document_no" class="help-block error">:message</label>')!!}
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('deposit_to_account') ? ' has-error' : '' }}">
                            {!! Form::label('deposit_to_account', App\Language::trans('Deposit To'), ['class'=>'control-label col-md-4']) !!}
                            <div class="col-md-12">
                                {!! Form::select('deposit_to_account', App\Setting::bank_or_cash_combobox(), null, ['class'=>'form-control','autofocus','required']) !!} 
                                {!!$errors->first('deposit_to_account', '<label for="customer_id" class="help-block error">:message</label>')!!}
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('payment_method') ? ' has-error' : '' }}">
                            {!! Form::label('payment_method', App\Language::trans('Payment Method'), ['class'=>'control-label col-md-4']) !!}
                            <div class="col-md-12">
                                {!! Form::select('payment_method', App\Setting::payment_method(), null, ['class'=>'form-control','autofocus','required' , 'onchange'=>'change_payment_label_text_by_payment_method(this)']) !!} {!!$errors->first('payment_method', '
                                <label for="payment_method" class="help-block error">:message</label>')!!}
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('reference_no') ? ' has-error' : '' }}">
                            {!! Form::label('doc_payment_no_ref_no', App\Language::trans('Reference No.'), ['id'=>'doc_payment_no_ref_no', 'class'=>'control-label col-md-4']) !!}
                            <div class="col-md-12">
                                {!!$errors->first('reference_no', '<label for="reference_no" class="help-block error">:message</label>')!!}   
                                 {!! Form::text('reference_no', null, ['class'=>'form-control','autofocus','required','onblur' => 'reference_no_format_checker(this)']) !!} 
                            </div>
                        </div>
                </div>

                <div class="col-xl-4 mb-20">
                    <div class="card">
                        <h6 class="card-header border-0">
                            <i class="ion ion-md-clipboard font-21 mr-10"></i>Summary
                        </h6>
                        <div class="card-body pa-0">
                            <div class="table-wrap">
                                <div class="table-responsive">
                                    <table class="table table-sm mb-0">
                                        <tbody>
                                            <tr>
                                                <th class="w-70" scope="row">Sub Total</th>
                                                <th class="w-30" scope="row"><label id="lbl_trans_detail_subtotal"></label></th>
                                            </tr>
                      
                          
                                            <tr>
                                                <td class="w-70">Tax</td>
                                                <td class="w-30"><label id="lbl_trans_detail_tax"></label></td>
                                            </tr>
                            
                                        </tbody>
                                        <tfoot>
                                            <tr class="bg-light">
                                                <th class="text-dark text-uppercase" scope="row">To Pay</th>
                                                <th class="text-dark font-18" scope="row"><label id="lbl_trans_detail_total"></label></th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <h3>
            <span class="wizard-icon-wrap"><i class="ion ion-md-checkmark-circle-outline"></i></span>
            <span class="wizard-head-text-wrap">
                <span class="step-head">Summary</span>
            </span> 
        </h3>
        <section>
            <h3 class="display-4 mb-40">Summary</h3>
            <div class="row">
                <div class="col-xl-8 mb-20">
                    <div class="table-wrap">
                        <div class="table-responsive">
                            <table class="table table-sm mb-0">
                                <tbody>
                                    <tr>
                                        <th class="w-70" scope="row">Sub Total</th>
                                        <th class="w-30" scope="row"><label id="lbl_summary_subtotal"></label></th>
                                    </tr>
                                    <tr>
                                        <td class="w-70">Tax</td>
                                        <td class="w-30"><label id="lbl_summary_tax"></label></td>
                                    </tr>
                                    
                                </tbody>
                                <tfoot>
                                    <tr class="bg-light">
                                        <th class="text-dark text-uppercase" scope="row">To Pay</th>
                                        <th class="text-dark font-18" scope="row"><label id="lbl_summary_total"></label></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    <textarea id="remark" name="remark" class="form-control mt-35" rows="2" placeholder="Remarks"></textarea>
                </div>
                
                <div class="col-xl-4 mb-20">
                    <div class="alert alert-info mb-20" role="alert">
                       Please check again , click finish button to submit the form.
                    </div>
                        <p class="mb-10">Invoice correspond to the payment received will be auto-generate , please after submit . <br> Please go AR Invoice module to have the invoice</p>
                        <input type="hidden" id="amount" name="amount">
                        <!-- <a class="d-block mb-25" href="#">How are shipping cost calculated?</a> -->
                        <button class="btn btn-primary btn-block mb-10" type="submit">Submit</button>
                    </div>
                </div>
        </section>
    {!! Form::close() !!}
</div>
<!-- END FORM BODY -->

@endsection
@section('script')
    var productInfoUrl = "{{action('ProductsController@getInfo')}}";
    var productInfoByLeafProductIdUrl = "{{action('ProductsController@getInfoByLeafProductId')}}";

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
	function init_customer_membership_info(me) {
		$.get(customerInfoUrl, {customer_id:$(me).val()}, function(fdata){
            init_loading_overlay();
            membership_period = "";
            membership_detail = fdata.data.membership_detail;
            console.log("first");
            console.log(JSON.stringify(membership_detail));
            membership_detail_personal_info = fdata.data.membership_detail_personal_info;
            console.log("second");
            console.log(JSON.stringify(membership_detail_personal_info));
            for (var key in  membership_detail_personal_info ) { 
              if(key == 'membership_period' ||  key == 'membership_extend_to_date'){
                 $("#" + key).html(membership_detail_personal_info[key]);
              } 
            }
           console.log("-----------------Start -------------");
            for (var key in  membership_detail) {
            console.log(key);
                if(key == "address") {
                    for(var key_item in membership_detail[key]){
                    console.log("hit-" + membership_detail[key][key_item]);
                      if(key_item == "unit_no"){
                        $("#"+key_item).html(membership_detail[key][key_item]);
                         $("#unit_no").html(membership_detail[key][key_item]);
                      }
                    }
                 }else if(key == "house_fee_items")
                 {
                    console.log("yes it is");
                    console.log(membership_detail[key]);
                    for (var key_item in  membership_detail[key]){
                           for (var key_item_chiled in  membership_detail[key][key_item]){
                            
                             if(key_item == "id_fee_type"){
                                 $('#payable_item_id').val(membership_detail[key]);
                                 alert($('#payable_item_id').val());
                             }
                          }
                    }
                    
                 }
            }

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
                 init_hide_loading_overlay();
		},"json");
	
		getCustomerInvoiceById($(me).val(),'invoice_table');
	}
	

    


@endsection
