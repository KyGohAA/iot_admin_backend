@extends('_version_02.commons.layouts.admin')
@section('content')
{!!Form::model($model, array("url"=>null,"method"=>"post","class"=>"form-horizontal","files"=>true))!!}
@include('_version_02.commons.layouts.partials._alert')
<section class="hk-sec-wrapper">
    <h5 class="hk-sec-title">{{App\Language::trans('Product Detail')}}</h5><hr>
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


	<div class="row mb-20">
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
						{!! Form::number('lead_time', null, ['class'=>'form-control']) !!}
                        {!!$errors->first('lead_time', '<label for="lead_time" class="help-block error">:message</label>')!!}
					</div>
				</div>
			</div>
		</div>

    <h5 class="hk-sec-title">{{App\Language::trans('Tax And Account Mapping')}}</h5><hr>
    
    <div class="row mb-20">      

		 <div class="col-md-6">
 				<div class="form-group{{ $errors->has('deposit_to_account') ? ' has-error' : '' }}">
                    {!! Form::label('deposit_to_account', App\Language::trans('Deposit To'), ['class'=>'control-label col-md-4']) !!}
                    <div class="col-md-8">
                        {!! Form::select('deposit_to_account', App\Setting::gl_account_combobox(), null, ['class'=>'form-control','autofocus','required','onchange'=>'init_tax_info(this)']) !!} {!!$errors->first('deposit_to_account', '
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

    <h5 class="hk-sec-title">{{App\Language::trans('Price & Quantity Form')}}</h5><hr>
    <div class="row mb-20">
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
						{!! Form::number('selling_price', null, ['min'=>0,'step'=>'0.01','class'=>'form-control','onchange'=>'init_double(this)']) !!}
                        {!!$errors->first('selling_price', '<label for="selling_price" class="help-block error">:message</label>')!!}
					</div>
				</div>
				<div class="form-group{{ $errors->has('purchase_price') ? ' has-error' : '' }}">
					{!! Form::label('purchase_price', App\Language::trans('Purchase Price'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						{!! Form::number('purchase_price', null, ['min'=>0,'step'=>'0.01','class'=>'form-control','onchange'=>'init_double(this)']) !!}
                        {!!$errors->first('purchase_price', '<label for="purchase_price" class="help-block error">:message</label>')!!}
					</div>
				</div>
				<div class="form-group{{ $errors->has('standard_cost') ? ' has-error' : '' }}">
					{!! Form::label('standard_cost', App\Language::trans('Standard Cost'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						{!! Form::number('standard_cost', null, ['min'=>0,'step'=>'0.01','class'=>'form-control','onchange'=>'init_double(this)']) !!}
                        {!!$errors->first('standard_cost', '<label for="standard_cost" class="help-block error">:message</label>')!!}
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group{{ $errors->has('min_quantity') ? ' has-error' : '' }}">
					{!! Form::label('min_quantity', App\Language::trans('Min. Quantity'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						{!! Form::number('min_quantity', null, ['min'=>0,'step'=>'1','class'=>'form-control']) !!}
                        {!!$errors->first('min_quantity', '<label for="min_quantity" class="help-block error">:message</label>')!!}
					</div>
				</div>
				<div class="form-group{{ $errors->has('max_quantity') ? ' has-error' : '' }}">
					{!! Form::label('max_quantity', App\Language::trans('Max. Quantity'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						{!! Form::number('max_quantity', null, ['min'=>0,'step'=>'1','class'=>'form-control']) !!}
                        {!!$errors->first('max_quantity', '<label for="max_quantity" class="help-block error">:message</label>')!!}
					</div>
				</div>
				<div class="form-group{{ $errors->has('reorder_quantity') ? ' has-error' : '' }}">
					{!! Form::label('reorder_quantity', App\Language::trans('Reorder Quantity'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						{!! Form::number('reorder_quantity', null, ['min'=>0,'step'=>'1','class'=>'form-control']) !!}
                        {!!$errors->first('reorder_quantity', '<label for="reorder_quantity" class="help-block error">:message</label>')!!}
					</div>
				</div>
			</div>
		</div>

		@include('_version_02.billings.products.partials.__prices')
</section>


<section class="hk-sec-wrapper">
    <h5 class="hk-sec-title">{{App\Language::trans('Other Detail Form')}}</h5><hr>
    <div class="row">
		
			<div class="col-md-6">
				<div class="form-group{{ $errors->has('status') ? ' has-error' : '' }}">
					{!! Form::label('status', App\Language::trans('Status'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-12">
						 <div class="row">	
						 	<div class="col-md-3">
							    <div class="custom-control custom-radio">
							        <input type="radio" id="status_on" name="status" checked class="custom-control-input">
							        <label class="custom-control-label" for="status_on">{{App\ExtendModel::status_true_word()}}</label>
							    </div>
							</div>
							<div class="col-md-3">
							    <div class="custom-control custom-radio">
							        <input type="radio" id="status_off" name="status"  class="custom-control-input">
							        <label class="custom-control-label" for="status_off">{{App\ExtendModel::status_false_word()}}</label>
							    </div>
							</div>
						 </div>
						 {!!$errors->first('status', '<label for="status" class="help-block error">:message</label>')!!}
					</div>
				</div>
			</div>

			<div class="col-md-6">
				<div class="form-group{{ $errors->has('is_obsolete') ? ' has-error' : '' }}">
					{!! Form::label('is_obsolete', App\Language::trans('Is Obsolete'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-12">
						 <div class="row">	
						 	<div class="col-md-3">
							    <div class="custom-control custom-radio">
							        <input type="radio" id="is_obsolete_on" name="is_obsolete" checked class="custom-control-input">
							        <label class="custom-control-label" for="is_obsolete_on">{{App\ExtendModel::answer_true_word()}}</label>
							    </div>
							</div>
							<div class="col-md-3">
							    <div class="custom-control custom-radio">
							        <input type="radio" id="is_obsolete_off" name="is_obsolete"  class="custom-control-input">
							        <label class="custom-control-label" for="is_obsolete_off">{{App\ExtendModel::answer_false_word()}}</label>
							    </div>
							</div>
						 </div>
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
</section>
@include('_version_02.commons.layouts.partials._form_floaring_footer_standard')

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