@extends('commons.layouts.admin')
@section('content')

{!!Form::model($model, array("url"=>null,"method"=>"post","class"=>"form-horizontal","files"=>true))!!}
@include('commons.layouts.partials._alert')

<div class="box">
	<div class="box-header with-border">
		<h3 class="box-title">{{App\Language::trans('Product Pictures')}}</h3>
		<div class="box-tools pull-right">
			<a href="{{action('ProductsController@getNew')}}" class="btn btn-block btn-info">
				<i class="fa fa-file"></i> {{App\Language::trans('New File')}}
			</a>
		</div>
	</div>
	<div class="box-body">

	  <div class="row">
	 	 <div class="col-md-6">
			 	 @if($model->cover_photo_path)
	               <div class="col-md-4">
	                  <img class="img-responsive" width="150" src="{{asset($model->cover_photo_path)}}">
	                  <label for="product_cover_photo">{{App\Language::trans('Cover Photo')}}</label>
	                  <!--    <div class="text-center checkbox-custom checkbox-danger mb5">
	                     {!!Form::checkbox("company_logo_del", $model->id_company, false, array("id"=>"company_logo_del"))!!}
	                     <label for="company_logo_del">{{App\Language::trans('Remove file')}}</label>
	                     </div> -->
	               </div>
	               @endif
          </div>
	   </div>
	</div>
</div>


<div class="box">
	<div class="box-header with-border">
		<h3 class="box-title">{{App\Language::trans('Detail Form')}}</h3>
		<div class="box-tools pull-right">
		<!-- PREVIOUS NEW BUTTON-->
		</div>
	</div>
	<div class="box-body">
		<div class="row">
			   <div class="col-md-6">
			 	<div class="form-group {!!$errors->first('cover_photo_path') ? 'has-error' : ''!!}">
                  <label for="cover_photo_path" class="control-label col-sm-4">{{App\Language::trans('Product Cover Photo')}}</label>
                  <div class="col-sm-8">
                     {!!Form::file("cover_photo_path", array("id"=>"cover_photo_path","class"=>"form-control"))!!}
                     {!!$errors->first('cover_photo_path', '<span for="cover_photo_path" class="help-block error">:message</span>')!!}
                  </div>
               </div>
               
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
						{!! Form::text('name', null, ['class'=>'form-control','required']) !!}
                        {!!$errors->first('name', '<label for="name" class="help-block error">:message</label>')!!}
					</div>
				</div>
				<div class="form-group{{ $errors->has('barcode') ? ' has-error' : '' }}">
					{!! Form::label('barcode', App\Language::trans('Barcode'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						{!! Form::text('barcode', null, ['class'=>'form-control']) !!}
                        {!!$errors->first('barcode', '<label for="barcode" class="help-block error">:message</label>')!!}
					</div>
				</div>
				
			</div>
			<div class="col-md-6">
				<div class="form-group{{ $errors->has('product_category_id') ? ' has-error' : '' }}">
					{!! Form::label('product_category_id', App\Language::trans('Category'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						{!! Form::select('product_category_id', App\ProductCategory::combobox(), null, ['class'=>'form-control']) !!}
                        {!!$errors->first('product_category_id', '<label for="product_category_id" class="help-block error">:message</label>')!!}
					</div>
				</div>
				
				<div class="form-group{{ $errors->has('cost_method') ? ' has-error' : '' }}">
					{!! Form::label('cost_method', App\Language::trans('Cost Method'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						{!! Form::select('cost_method', App\Setting::costing_method(), null, ['class'=>'form-control']) !!}
                        {!!$errors->first('cost_method', '<label for="cost_method" class="help-block error">:message</label>')!!}
					</div>
				</div>

				<div class="form-group{{ $errors->has('lead_time') ? ' has-error' : '' }}">
					{!! Form::label('lead_time', App\Language::trans('Lead Time'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						{!! Form::text('lead_time', null, ['class'=>'form-control']) !!}
                        {!!$errors->first('lead_time', '<label for="lead_time" class="help-block error">:message</label>')!!}
					</div>
				</div>
			</div>
		</div>
	</div>
</div>


<!-- START BOX -->
<div class="box">
	<div class="box-header with-border">
		<h3 class="box-title">{{App\Language::trans('Tax And Account Mapping')}}</h3>
		<div class="box-tools pull-right">
			<!-- PREVIOUS NEW BUTTON-->
		</div>
	</div>

<div class="box-body">
		<div class="row">      

		 <div class="col-md-6">
 				<div class="form-group{{ $errors->has('deposit_to_account') ? ' has-error' : '' }}">
                    {!! Form::label('deposit_to_account', App\Language::trans('Deposit To'), ['class'=>'control-label col-md-4']) !!}
                    <div class="col-md-8">
                        {!! Form::select('deposit_to_account', App\Setting::bank_or_cash_combobox(), null, ['class'=>'form-control','autofocus','required','onchange'=>'init_tax_info(this)']) !!} {!!$errors->first('deposit_to_account', '
                        <label for="customer_id" class="help-block error">:message</label>')!!}
                    </div>
                </div>

		 		<div class="form-group{{ $errors->has('sale_tax_id') ? ' has-error' : '' }}">
					{!! Form::label('sale_tax_id', App\Language::trans('Sale Tax'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						{!! Form::select('sale_tax_id', App\Tax::combobox(App\Tax::sale_tag), null, ['class'=>'form-control','required','onchange'=>'init_tax_info(this)']) !!}
                        {!!$errors->first('sale_tax_id', '<label for="sale_tax_id" class="help-block error">:message</label>')!!}
					</div>
				</div>

				<div class="form-group{{ $errors->has('purchase_tax_id') ? ' has-error' : '' }}">
					{!! Form::label('purchase_tax_id', App\Language::trans('Purchase Tax'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						{!! Form::select('purchase_tax_id', App\Tax::combobox(App\Tax::purchase_tag), null, ['class'=>'form-control','required','onchange'=>'init_tax_info(this)']) !!}
                        {!!$errors->first('purchase_tax_id', '<label for="purchase_tax_id" class="help-block error">:message</label>')!!}
					</div>
				</div>
            </div>

            <div class="col-md-6">
				<div class="form-group{{ $errors->has('payment_term_id') ? ' has-error' : '' }}">
					{!! Form::label('payment_term_id', App\Language::trans('Payment Term'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						{!! Form::select('payment_term_id', App\PaymentTerm::get_common_combobox(), null, ['class'=>'form-control']) !!}
                        {!!$errors->first('payment_term_id', '<label for="payment_term_id" class="help-block error">:message</label>')!!}
					</div>
				 </div>

      		    <div class="form-group">
                    {!! Form::label('sale_tax_amount', App\Language::trans('Sales Tax Percent (%)'), ['class'=>'control-label col-md-4']) !!}   
                    <div class="col-md-8">                      
                     {!! Form::label('sale_tax_amount', App\Language::trans('-'), ['id'=> 'sale_tax_amount', 'class'=>'control-label']) !!}
                    </div>
                </div>

                <div class="form-group">
                    {!! Form::label('purchase_tax_amount', App\Language::trans('Purchase Tax Percent (%)'), ['class'=>'control-label col-md-4']) !!}   
                    <div class="col-md-8">                      
                     {!! Form::label('purchase_tax_amount', App\Language::trans('-'), ['id'=> 'purchase_tax_amount', 'class'=>'control-label']) !!}
                    </div>
                </div>
			</div>
		</div>
	</div>
</div>
<!-- END BOX -->

<div class="box">
	<div class="box-header with-border">
		<h3 class="box-title">{{App\Language::trans('Price & Quantity Form')}}</h3>
		<div class="box-tools pull-right">
			<!-- PREVIOUS NEW BUTTON-->
		</div>
	</div>
	<div class="box-body">
		<div class="row">
			<div class="col-md-6">
				<div class="form-group{{ $errors->has('uom_id') ? ' has-error' : '' }}">
					{!! Form::label('uom_id', App\Language::trans('Unit Of Measurement'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						{!! Form::select('uom_id', App\Uom::combobox(), null, ['class'=>'form-control']) !!}
                        {!!$errors->first('uom_id', '<label for="uom_id" class="help-block error">:message</label>')!!}
					</div>
				</div>
				<div class="form-group{{ $errors->has('selling_price') ? ' has-error' : '' }}">
					{!! Form::label('selling_price', App\Language::trans('Selling Price'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						{!! Form::text('selling_price', null, ['class'=>'form-control','onchange'=>'init_double(this)']) !!}
                        {!!$errors->first('selling_price', '<label for="selling_price" class="help-block error">:message</label>')!!}
					</div>
				</div>
				<div class="form-group{{ $errors->has('purchase_price') ? ' has-error' : '' }}">
					{!! Form::label('purchase_price', App\Language::trans('Purchase Price'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						{!! Form::text('purchase_price', null, ['class'=>'form-control','onchange'=>'init_double(this)']) !!}
                        {!!$errors->first('purchase_price', '<label for="purchase_price" class="help-block error">:message</label>')!!}
					</div>
				</div>
				<div class="form-group{{ $errors->has('standard_cost') ? ' has-error' : '' }}">
					{!! Form::label('standard_cost', App\Language::trans('Standard Cost'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						{!! Form::text('standard_cost', null, ['class'=>'form-control','onchange'=>'init_double(this)']) !!}
                        {!!$errors->first('standard_cost', '<label for="standard_cost" class="help-block error">:message</label>')!!}
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group{{ $errors->has('min_quantity') ? ' has-error' : '' }}">
					{!! Form::label('min_quantity', App\Language::trans('Min. Quantity'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						{!! Form::number('min_quantity', null, ['min'=>0,'step'=>'0.01','class'=>'form-control']) !!}
                        {!!$errors->first('min_quantity', '<label for="min_quantity" class="help-block error">:message</label>')!!}
					</div>
				</div>
				<div class="form-group{{ $errors->has('max_quantity') ? ' has-error' : '' }}">
					{!! Form::label('max_quantity', App\Language::trans('Max. Quantity'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						{!! Form::number('max_quantity', null, ['min'=>0,'step'=>'0.01','class'=>'form-control']) !!}
                        {!!$errors->first('max_quantity', '<label for="max_quantity" class="help-block error">:message</label>')!!}
					</div>
				</div>
				<div class="form-group{{ $errors->has('reorder_quantity') ? ' has-error' : '' }}">
					{!! Form::label('reorder_quantity', App\Language::trans('Reorder Quantity'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						{!! Form::number('reorder_quantity', null, ['min'=>0,'step'=>'0.01','class'=>'form-control']) !!}
                        {!!$errors->first('reorder_quantity', '<label for="reorder_quantity" class="help-block error">:message</label>')!!}
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@include('billings.products.partials.__prices')
<div class="box">
	<div class="box-header with-border">
		<h3 class="box-title">{{App\Language::trans('Other Detail Form')}}</h3>
		<div class="box-tools pull-right">
			<!-- PREVIOUS NEW BUTTON-->
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
				<div class="form-group{{ $errors->has('is_obsolete') ? ' has-error' : '' }}">
					{!! Form::label('is_obsolete', App\Language::trans('Is Obsolete'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						<label class="radio-inline">
							{!! Form::radio('is_obsolete', 1, false) !!} {{App\ExtendModel::answer_true_word()}}
						</label>
						<label class="radio-inline">
							{!! Form::radio('is_obsolete', 0, true) !!} {{App\ExtendModel::answer_false_word()}}
						</label>
                        {!!$errors->first('is_obsolete', '<label for="is_obsolete" class="help-block error">:message</label>')!!}
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
				<a href="{{action('ProductsController@getIndex')}}" class="btn btn-danger"><i class="fa fa-ban fa-fw"></i>{{App\Language::trans('Close')}}</a>
			</div>
		</div>
	</div>
</div>
{!! Form::close() !!}
@endsection
@section('script')
	var taxInfoUrl = "{{action('TaxesController@getInfo')}}";	
	function init_tax_info(me) {
	 id  = $(me).attr('id').replace("_id", "_amount")
	 console.log(id);
		$.get(taxInfoUrl, {tax_id:$(me).val()}, function(fdata){
       
		for (var key in fdata.data) {
				if(key == "rate") {
					$('#'+id).html(fdata.data[key]);			
				}
			}	
		},"json");
	}
@endsection