@extends('billings.layouts.admin')
@section('content')
{!! Form::model($model, ['class'=>'form-horizontal']) !!}
@include('billings.layouts.partials._alert')
<div class="box">
	<div class="box-header with-border">
		<h3 class="box-title">{{App\Language::trans('Basic Detail Form')}}</h3>
		<div class="box-tools pull-right">
			<a href="{{action('ARInvoicesController@getNew')}}" class="btn btn-block btn-info">
				<i class="fa fa-file fa-fw"></i> {{App\Language::trans('New File')}}
			</a>
		</div>
	</div>
	<div class="box-body">
		<div class="row">
			<div class="col-md-6">
				<div class="form-group{{ $errors->has('customer_id') ? ' has-error' : '' }}">
					{!! Form::label('customer_id', App\Language::trans('Customer Code'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						{!!Form::hidden('is_tax_inclusive', null)!!}
						{!! Form::select('customer_id', App\Customer::combobox(), null, ['class'=>'form-control','onchange'=>'init_customer_info(this)']) !!}
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
				<div class="form-group{{ $errors->has('phone_no') ? ' has-error' : '' }}">
					{!! Form::label('phone_no', App\Language::trans('Phone No'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						{!! Form::text('phone_no', null, ['class'=>'form-control']) !!}
                        {!!$errors->first('phone_no', '<label for="phone_no" class="help-block error">:message</label>')!!}
					</div>
				</div>
				<div class="form-group{{ $errors->has('contact_person') ? ' has-error' : '' }}">
					{!! Form::label('contact_person', App\Language::trans('Contact Person'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						{!! Form::text('contact_person', null, ['class'=>'form-control']) !!}
                        {!!$errors->first('contact_person', '<label for="contact_person" class="help-block error">:message</label>')!!}
					</div>
				</div>
				<div class="form-group{{ $errors->has('currency_id') ? ' has-error' : '' }}">
					{!! Form::label('currency_id', App\Language::trans('Currency'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						{!! Form::select('currency_id', App\Currency::combobox(), null, ['class'=>'form-control','onchange'=>'init_currency_rate(this)']) !!}
                        {!!$errors->first('currency_id', '<label for="currency_id" class="help-block error">:message</label>')!!}
					</div>
				</div>
				<div class="form-group{{ $errors->has('currency_rate') ? ' has-error' : '' }}">
					{!! Form::label('currency_rate', App\Language::trans('Currency Rate'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						{!! Form::text('currency_rate', null, ['class'=>'form-control']) !!}
                        {!!$errors->first('currency_rate', '<label for="currency_rate" class="help-block error">:message</label>')!!}
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group{{ $errors->has('document_no') ? ' has-error' : '' }}">
					{!! Form::label('document_no', App\Language::trans('Document No.'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						{!! Form::text('document_no', null, ['class'=>'form-control','readonly']) !!}
                        {!!$errors->first('document_no', '<label for="document_no" class="help-block error">:message</label>')!!}
					</div>
				</div>
				<div class="form-group{{ $errors->has('document_date') ? ' has-error' : '' }}">
					{!! Form::label('document_date', App\Language::trans('Document Date'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						{!! Form::text('document_date', null, ['class'=>'form-control']) !!}
                        {!!$errors->first('document_date', '<label for="document_date" class="help-block error">:message</label>')!!}
					</div>
				</div>
				<div class="form-group{{ $errors->has('po_no') ? ' has-error' : '' }}">
					{!! Form::label('po_no', App\Language::trans('P.O. No.'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						{!! Form::text('po_no', null, ['class'=>'form-control']) !!}
                        {!!$errors->first('po_no', '<label for="po_no" class="help-block error">:message</label>')!!}
					</div>
				</div>
				<div class="form-group{{ $errors->has('payment_term_id') ? ' has-error' : '' }}">
					{!! Form::label('payment_term_id', App\Language::trans('Payment Term'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						{!! Form::select('payment_term_id', App\PaymentTerm::combobox(), null, ['class'=>'form-control']) !!}
                        {!!$errors->first('payment_term_id', '<label for="payment_term_id" class="help-block error">:message</label>')!!}
					</div>
				</div>
				<div class="form-group{{ $errors->has('status') ? ' has-error' : '' }}">
					{!! Form::label('status', App\Language::trans('Document Status'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						{!! Form::text('status', null, ['class'=>'form-control','readonly']) !!}
                        {!!$errors->first('status', '<label for="status" class="help-block error">:message</label>')!!}
					</div>
				</div>
				<div class="form-group{{ $errors->has('sales_person') ? ' has-error' : '' }}">
					{!! Form::label('sales_person', App\Language::trans('Sales Person'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						{!! Form::text('sales_person', null, ['class'=>'form-control']) !!}
                        {!!$errors->first('sales_person', '<label for="sales_person" class="help-block error">:message</label>')!!}
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@include('billings.ar_invoices.partials.__address')
@include('billings.ar_invoices.partials.__list')
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
					<a href="{{action('ARInvoicesController@getIndex')}}" class="btn btn-danger"><i class="fa fa-ban fa-fw"></i>{{App\Language::trans('Close')}}</a>
				</div>
			</div>
		</div>
	</div>
{!! Form::close() !!}
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