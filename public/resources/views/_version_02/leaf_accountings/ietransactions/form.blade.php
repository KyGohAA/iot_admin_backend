@extends('_version_02.commons.layouts.admin')
@section('content')
@include('_version_02.commons.layouts.partials._alert')
<div>
{!! Form::model($model, ['class'=>'form-horizontal', 'id'=> 'invoice-form']) !!}
      
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
                	    
                        <div class="form-group{{ $errors->has('status') ? ' has-error' : '' }}">
                            {!! Form::label('status', App\Language::trans('Document Status'), ['class'=>'control-label','required']) !!}
                            {!! Form::text('status', null, ['class'=>'form-control','required','readonly']) !!}
                            {!!$errors->first('status', '<label for="status" class="help-block error">:message</label>')!!}
                        </div>

                        <div class="form-group{{ $errors->has('document_no') ? ' has-error' : '' }}">
                            {!! Form::label('document_no', App\Language::trans('Document No.'), ['class'=>'control-label','required']) !!}
                            {!! Form::text('document_no', null, ['class'=>'form-control','required']) !!}
                            {!!$errors->first('document_no', '<label for="document_no" class="help-block error">:message</label>')!!}
                        </div>

                        <div class="form-group{{ $errors->has('document_date') ? ' has-error' : '' }}">
                            {!! Form::label('document_date', App\Language::trans('Document Date'), ['class'=>'control-label','required']) !!}
                            {!! Form::text('document_date', null, ['class'=>'form-control','required']) !!}
                            {!!$errors->first('document_date', '<label for="document_date" class="help-block error">:message</label>')!!}
                        </div>

                        <div class="form-group{{ $errors->has('tax_amount') ? ' has-error' : '' }}">
                            {!! Form::label('tax_amount', App\Language::trans('Tax Amount'), ['class'=>'control-label']) !!}
                            {!! Form::number('tax_amount', null, ['class'=>'form-control','onchange'=>'init_calculate_ietransaction(this)','required']) !!}
                            {!!$errors->first('tax_amount', '<label for="tax_amount" class="help-block error">:message</label>')!!}
                        </div>

                        <div class="form-group{{ $errors->has('amount') ? ' has-error' : '' }}">
                            {!! Form::label('amount', App\Language::trans('Amount'), ['class'=>'control-label']) !!}
                            {!! Form::number('amount', null, ['class'=>'form-control','onchange'=>'init_calculate_ietransaction(this)','required']) !!}
                            {!!$errors->first('amount', '<label for="Amount" class="help-block error">:message</label>')!!}
                        </div>

                        <div class="form-group{{ $errors->has('payment_term_id') ? ' has-error' : '' }}">
                            {!! Form::label('payment_term_id', App\Language::trans('Payment Term'), ['class'=>'control-label']) !!}
                            {!! Form::select('payment_term_id', App\PaymentTerm::combobox(), null, ['class'=>'form-control','required']) !!}
                            {!!$errors->first('payment_term_id', '<label for="payment_term_id" class="help-block error">:message</label>')!!}
                        </div>

                        <div class="form-group{{ $errors->has('currency_id') ? ' has-error' : '' }}">
                            {!! Form::label('currency_id', App\Language::trans('Currency'), ['class'=>'control-label','required']) !!}
                            {!! Form::select('currency_id', App\Currency::combobox(), null, ['class'=>'form-control','onchange'=>'init_currency_rate(this)','required']) !!}
                            {!!$errors->first('currency_id', '<label for="currency_id" class="help-block error">:message</label>')!!}
                        </div>

                        <div class="form-group{{ $errors->has('currency_rate') ? ' has-error' : '' }}">
                            {!! Form::label('currency_rate', App\Language::trans('Currency Rate'), ['class'=>'control-label','required']) !!}
                            {!! Form::text('currency_rate', null, ['class'=>'form-control','required']) !!}
                            {!!$errors->first('currency_rate', '<label for="currency_rate" class="help-block error">:message</label>')!!}
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
                                                <th class="text-dark text-uppercase" scope="row">Total</th>
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
                                        <th class="text-dark text-uppercase" scope="row">Summary</th>
                                        <th class="text-dark font-18" scope="row"><label id="lbl_summary_total"></label></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    <textarea class="form-control mt-35" rows="2" placeholder="Remarks"></textarea>
                </div>
                        
                <div class="col-xl-4 mb-20">
                    <div class="alert alert-info mb-20" role="alert">
                       Please check again , click finish button to submit the form.
                    </div>
                        <p class="mb-10">Finish</p>
                        <!-- <a class="d-block mb-25" href="#">How are shipping cost calculated?</a> -->
                        
                 
                       
                        <button class="btn btn-primary btn-block mb-10" type="submit">{{App\Language::trans('Submit')}}</button>


                        <small class="d-block text-center">As a member, you agree to our <a href="#">terms and conditions</a> to use.</small>
                    </div>
                </div>
        </section>
        {!! Form::close() !!}
</div>
<!-- END FORM BODY -->

@endsection
@section('script')
	var customerInfoUrl = "{{action('CustomersController@getInfo')}}";
	var productInfoUrl = "{{action('ProductsController@getInfo')}}";
	function init_customer_info(me) {
		$.get(customerInfoUrl, {customer_id:$(me).val()}, function(fdata){
			for (var key in fdata.data) {
				if(key != "status") {
					$("input[name="+key+"]").val(fdata.data[key]);
					if(key == "phone_no_1") {
						$("input[name=phone_no]").val(fdata.data[key]);
					}
					if(key == "currency_label") {
						$(".currency_label").html(fdata.data[key]);
					}
					if(key.match(/_id/g)) {
						$("select[name="+key+"]").val(fdata.data[key]).trigger("change");
					}
				}
			}
			setTimeout(function(){
			    var billingStateSelectbox = $("select[name*=billing_state_id]");
			    billingStateSelectbox.empty();
			    var billing_state = $.get(statesComboboxUrl, {country_id:fdata.data["billing_country_id"]}, function(data){
			        for (var i = 0; i < data.length; i++) {
			             billingStateSelectbox.append($("<option>",
			             {
			                value: data[i].id,
			                text : data[i].text 
			            }));
			        }
			        billingStateSelectbox.val(fdata.data["billing_state_id"]).trigger("change");
			    }, "json");
			    $.when(billing_state).done(function(){
				    var billingCitySelectbox = $("select[name*=billing_city_id]");
				    billingCitySelectbox.empty();
				    $.get(citiesComboboxUrl, {state_id:fdata.data["billing_state_id"]}, function(data){
				        for (var i = 0; i < data.length; i++) {
				             billingCitySelectbox.append($("<option>",
				             {
				                value: data[i].id,
				                text : data[i].text 
				            }));
				        }
				        billingCitySelectbox.val(fdata.data["billing_city_id"]).trigger("change");
				    }, "json");
				});
			    var deliveryStateSelectbox = $("select[name*=delivery_state_id]");
			    deliveryStateSelectbox.empty();
			    var delivery_state = $.get(statesComboboxUrl, {country_id:fdata.data["delivery_country_id"]}, function(data){
			        for (var i = 0; i < data.length; i++) {
			             deliveryStateSelectbox.append($("<option>",
			             {
			                value: data[i].id,
			                text : data[i].text 
			            }));
			        }
			        deliveryStateSelectbox.val(fdata.data["delivery_state_id"]).trigger("change");
			    }, "json");
			    $.when(delivery_state).done(function(){
				    var deliveryCitySelectbox = $("select[name*=delivery_city_id]");
				    deliveryCitySelectbox.empty();
				    $.get(citiesComboboxUrl, {state_id:fdata.data["delivery_state_id"]}, function(data){
				        for (var i = 0; i < data.length; i++) {
				             deliveryCitySelectbox.append($("<option>",
				             {
				                value: data[i].id,
				                text : data[i].text 
				            }));
				        }
				        deliveryCitySelectbox.val(fdata.data["delivery_city_id"]).trigger("change");
				    }, "json");
				});
			},1000);
		},"json");
	}

	function init_product_info(me, type) {

		$.get(productInfoUrl, {product_id:$(me).val()}, function(fdata){

			var tr = $(me).closest("tr");
			for (var key in fdata.data) {
				
				console.log(key);
				tr.find("input[name*="+key+"]").val(fdata.data[key]);
		
				if(key == "description") {
					tr.find("textarea[name*=description]").val(fdata.data[key]);
				}
				if(key.match(/_id/g)) {
					tr.find("select[name*="+key+"]").val(fdata.data[key]).trigger("change");;
				}
	
			}
			{{-- recalculate the amount --}}
			init_calculate_product_table("product_table");
		},"json");
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

	
	function init_calculate_product_table(tableID) {

		var table = $("#"+tableID);
		var tbody = table.find("tbody");
		var index = table.find("tr").length;
		var gst_total = 0;
		var sub_total = 0;
		tbody.find("tr").each(function(){
			if(!$(this).hasClass("hidden")) {
				gst_total += parseFloat($(this).find("input[name*=tax_txt]").val()) || 0;
				sub_total += parseFloat($(this).find("input[name*=quantity]").val() * $(this).find("input[name*=unit_price]").val());
			}
		});
		table.find(".sub_total").html(init_decimal_point(sub_total))
		table.find(".gst_total").html(init_decimal_point(gst_total))
		table.find(".grand_total").html(init_decimal_point(sub_total + gst_total))
	}
	
	$("table").find("tr").each(function(){
		//init_select2($(this).find("select"));
	});
@endsection