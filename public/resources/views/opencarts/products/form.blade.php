@extends('commons.layouts.admin')
@section('content')
{!! Form::model($model, ['class'=>'form-horizontal']) !!}
@include('commons.layouts.partials._alert')


{!!Form::hidden('product_id', null , ['id'=>'product_id']) !!}
<div class="box">
	<div class="box-header with-border">
		<h3 class="box-title">{{App\Language::trans('Product Detail')}}</h3>
	</div>

	<?php 
		$product_description_listing = $model->product_description($model->id);
		$product_image_listing = $model->product_image($model->id);
		$is_first = true;
		$image_counter
	?>

	<div class="box-body">
	  <div class="row">
	 	 <div class="col-md-12">
    	 			<h4 class="page-header">{{App\Language::trans('Cover Photo')}}</h4>
    	 </div>
	 	 <div class="col-md-12">
			 	 @if($model->image)
	               <div class="col-md-4">
	                  <img class="img-responsive" width="150" height ="150" src="{{asset($model->image)}}">
	                  <!--    <div class="text-center checkbox-custom checkbox-danger mb5">
	                     {!!Form::checkbox("company_logo_del", $model->id_company, false, array("id"=>"company_logo_del"))!!}
	                     <label for="company_logo_del">{{App\Language::trans('Remove file')}}</label>
	                     </div> -->
	               </div>
	               @endif
	      </div>
	   </div>

	   <div class="row">
	   	 <div class="col-md-12">
    	 			<h4 class="page-header">{{App\Language::trans('Product Photo Set')}}</h4>
    	 </div>
	 	 <div class="col-md-12">
	               @if(count($product_image_listing) > 0)
	               	@foreach($product_image_listing as $row)
		               <div class="col-md-2">
		                  <img class="img-responsive" width="150" height ="150" src="{{asset($row->image)}}">            
		                  <!--    <div class="text-center checkbox-custom checkbox-danger mb5">
		                     {!!Form::checkbox("company_logo_del", $model->id_company, false, array("id"=>"company_logo_del"))!!}
		                     <label for="company_logo_del">{{App\Language::trans('Remove file')}}</label>
		                     </div> -->
		               </div>
		            @endforeach
	               @endif
	      </div>
	   </div>
	</div>

<!-- Nav tabs -->
<ul class="nav nav-tabs margin-bottom-15" role="tablist">
	@foreach($product_description_listing as $row)
		<li role="presentation" class="{{$is_first == true ? 'active' : ''}}">
			<a href="#{{$row['language_id']}}" aria-controls="{{$row['language_id']}}" role="tab" data-toggle="tab">{{App\Language::trans($row['language_id'])}}</a>
		</li>
		<?php 
			$is_first = false;
		?>
	@endforeach
</ul>



<?php 
	$is_first = true;
?>
	<!-- Tab panes -->
<div class="tab-content">
	@foreach($product_description_listing as $row)
	    <div role="tabpanel" class="tab-pane {{$is_first == true ? 'active' : ''}}" id="{{$row['language_id']}}">
	     		<div class="col-md-12">
		        	<div class="form-group{{ $errors->has('model') ? ' has-error' : '' }}">
						{!! Form::label('model', App\Language::trans('Name'), ['class'=>'control-label col-md-2']) !!}
						<div class="col-md-10">
							{!! Form::text('model', null, ['id'=>'model', 'rows'=>'10' , 'cols'=>'80','class'=>'form-control']) !!}
		                    {!!$errors->first('model', '<label for="model" class="help-block error">:message</label>')!!}
						</div>
					</div>
		       </div>

		        <div class="col-md-12">
		        	<div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
						{!! Form::label('description', App\Language::trans('Content'), ['class'=>'control-label col-md-2']) !!}
						<div class="col-md-10">
							{!! Form::textarea('description', $row['description'] != '' ? $row['description'] : null , ['id'=>"description_".$row['language_id'], 'rows'=>'10' , 'cols'=>'80','class'=>'form-control']) !!}
		                    {!!$errors->first('description', '<label for="description" class="help-block error">:message</label>')!!}
						</div>
					</div>
		       </div>

		       <div class="col-md-12">
		        	<div class="form-group{{ $errors->has('meta_keyword') ? ' has-error' : '' }}">
						{!! Form::label('meta_keyword', App\Language::trans('Meta Keyword'), ['class'=>'control-label col-md-2']) !!}
						<div class="col-md-10">
							{!! Form::text('meta_keyword', $row['meta_keyword'] != '' ? $row['meta_keyword']  : null , ['id'=>'meta_keyword', 'rows'=>'10' , 'cols'=>'80','class'=>'form-control']) !!}
		                    {!!$errors->first('meta_keyword', '<label for="meta_keyword" class="help-block error">:message</label>')!!}
						</div>
					</div>
		       </div>

		       <div class="col-md-12">
		        	<div class="form-group{{ $errors->has('tag') ? ' has-error' : '' }}">
						{!! Form::label('tag', App\Language::trans('Tag'), ['class'=>'control-label col-md-2']) !!}
						<div class="col-md-10">
							{!! Form::text('tag', $row['tag'] != '' ? $row['tag'] : null  , ['id'=>'tag', 'rows'=>'10' , 'cols'=>'80','class'=>'form-control']) !!}
		                    {!!$errors->first('tag', '<label for="tag" class="help-block error">:message</label>')!!}
						</div>
					</div>
		       </div>

				<div class="col-md-12">
		        	<div class="form-group{{ $errors->has('meta_title') ? ' has-error' : '' }}">
						{!! Form::label('meta_title', App\Language::trans('Meta Title'), ['class'=>'control-label col-md-2']) !!}
						<div class="col-md-10">
							{!! Form::text('meta_title', $row['meta_title'] != '' ? $row['meta_title'] : null  , ['id'=>'meta_title', 'rows'=>'10' , 'cols'=>'80','class'=>'form-control']) !!}
		                    {!!$errors->first('meta_title', '<label for="meta_title" class="help-block error">:message</label>')!!}
						</div>
					</div>
		       </div>

		       <div class="col-md-12">
		        	<div class="form-group{{ $errors->has('meta_description') ? ' has-error' : '' }}">
						{!! Form::label('meta_description', App\Language::trans('Meta Description'), ['class'=>'control-label col-md-2']) !!}
						<div class="col-md-10">
							{!! Form::text('meta_description', $row['meta_description'] != '' ? $row['meta_description'] : null  , ['id'=>'meta_description', 'rows'=>'10' , 'cols'=>'80','class'=>'form-control']) !!}
		                    {!!$errors->first('meta_description', '<label for="meta_description" class="help-block error">:message</label>')!!}
						</div>
					</div>
		       </div>
		</div>

		<?php 
			$is_first = false;
		?>
	@endforeach
  </div>


	<div class="box-footer">
	</div>
</div>


<div class="box">
	<div class="box-header with-border">
		<h3 class="box-title">{{App\Language::trans('Product Information')}}</h3>
	</div>

	
  

    <div class="panel-body p25">

    	 <div class="row">
    	 		<div class="col-md-12">
    	 			<h4 class="page-header">{{App\Language::trans('Price and Cost')}}</h4>
    	 		</div>

	      		<div class="col-md-6">
	      		
		        	<div class="form-group{{ $errors->has('cost') ? ' has-error' : '' }}">
						{!! Form::label('cost', App\Language::trans('Cost'), ['class'=>'control-label col-md-4']) !!}
						<div class="col-md-8">
							{!! Form::number('cost', null, ['id'=>'cost', 'rows'=>'10' , 'cols'=>'80','class'=>'form-control']) !!}
		                    {!!$errors->first('cost', '<label for="cost" class="help-block error">:message</label>')!!}
						</div>
					</div>
		       </div>

		       <div class="col-md-6">
		        	<div class="form-group{{ $errors->has('price') ? ' has-error' : '' }}">
						{!! Form::label('price', App\Language::trans('Price'), ['class'=>'control-label col-md-4']) !!}
						<div class="col-md-8">
							{!! Form::number('price', null, ['id'=>'price', 'rows'=>'10' , 'cols'=>'80','class'=>'form-control']) !!}
		                    {!!$errors->first('price', '<label for="price" class="help-block error">:message</label>')!!}
						</div>
					</div>
		       </div>
	       </div>

	       <div class="row">
	      		<div class="col-md-6">
		        	<div class="form-group{{ $errors->has('selling_price') ? ' has-error' : '' }}">
						{!! Form::label('selling_price', App\Language::trans('Selling Price'), ['class'=>'control-label col-md-4']) !!}
						<div class="col-md-8">
							{!! Form::number('selling_price', null, ['id'=>'selling_price', 'rows'=>'10' , 'cols'=>'80','class'=>'form-control']) !!}
		                    {!!$errors->first('selling_price', '<label for="selling_price" class="help-block error">:message</label>')!!}
						</div>
					</div>
		       </div>
	       </div>


	      
    		<div class="row">

	    		 <div class="col-md-12">
	    	 			<h4 class="page-header">{{App\Language::trans('Stock')}}</h4>
	    	 	</div>

	    	 	<div class="col-md-6">
		    	 	<div class="form-group{{ $errors->has('date_available') ? ' has-error' : '' }}">
                        {!! Form::label('date_available', App\Language::trans('Available Date'), ['class'=>'control-label col-md-4']) !!}
                        <div class="col-md-8">
                            {!! Form::text('date_available', null, ['class'=>'form-control','required']) !!} {!!$errors->first('date_available', '
                            <label for="date_available" class="help-block error">:message</label>')!!}
                        </div>
	                 </div>
	             </div>

	      		<div class="col-md-6">
		        	<div class="form-group{{ $errors->has('quantity') ? ' has-error' : '' }}">
						{!! Form::label('quantity', App\Language::trans('Quantity'), ['class'=>'control-label col-md-4']) !!}
						<div class="col-md-8">
							{!! Form::number('quantity', null, ['id'=>'quantity', 'rows'=>'10' , 'cols'=>'80','class'=>'form-control']) !!}
		                    {!!$errors->first('quantity', '<label for="quantity" class="help-block error">:message</label>')!!}
						</div>
					</div>
		       </div>
	      
	      		<div class="col-md-6">
		        	<div class="form-group{{ $errors->has('minimum') ? ' has-error' : '' }}">
						{!! Form::label('minimum', App\Language::trans('Minimum Quantity'), ['class'=>'control-label col-md-4']) !!}
						<div class="col-md-8">
							{!! Form::number('minimum', null, ['id'=>'minimum', 'rows'=>'10' , 'cols'=>'80','class'=>'form-control']) !!}
		                    {!!$errors->first('minimum', '<label for="minimum" class="help-block error">:message</label>')!!}
						</div>
					</div>
		       </div>
	       </div>

	       <div class="row">
	      		<div class="col-md-6">
		        	<div class="form-group{{ $errors->has('stock_status_id') ? ' has-error' : '' }}">
						{!! Form::label('stock_status_id', App\Language::trans('Out Of Stock Status'), ['class'=>'control-label col-md-4']) !!}
						<div class="col-md-8">
							{!! Form::number('stock_status_id', null, ['id'=>'stock_status_id', 'rows'=>'10' , 'cols'=>'80','class'=>'form-control']) !!}
		                    {!!$errors->first('stock_status_id', '<label for="stock_status_id" class="help-block error">:message</label>')!!}
						</div>
					</div>
		       </div>	
	       </div>  


	       <div class="row">
	       		<div class="col-md-12">
    	 			<h4 class="page-header">{{App\Language::trans('Product Attributes')}}</h4>
    	 		</div>

	      		<div class="col-md-6">
		        	<div class="form-group{{ $errors->has('weight_class_id') ? ' has-error' : '' }}">
						{!! Form::label('weight_class_id', App\Language::trans('Weight Class'), ['class'=>'control-label col-md-4']) !!}
						<div class="col-md-8">
							{!! Form::number('weight_class_id', null, ['id'=>'weight_class_id', 'rows'=>'10' , 'cols'=>'80','class'=>'form-control']) !!}
		                    {!!$errors->first('weight_class_id', '<label for="weight_class_id" class="help-block error">:message</label>')!!}
						</div>
					</div>
		       </div>	

		       <div class="col-md-6">
		        	<div class="form-group{{ $errors->has('length_class_id') ? ' has-error' : '' }}">
						{!! Form::label('length_class_id', App\Language::trans('Length Class'), ['class'=>'control-label col-md-4']) !!}
						<div class="col-md-8">
							{!! Form::number('length_class_id', null, ['id'=>'length_class_id', 'rows'=>'10' , 'cols'=>'80','class'=>'form-control']) !!}
		                    {!!$errors->first('length_class_id', '<label for="length_class_id" class="help-block error">:message</label>')!!}
						</div>
					</div>
		       </div>	

	       </div> 

	       <div class="row">
	      		<div class="col-md-12">
		        	<div class="form-group{{ $errors->has('dimension') ? ' has-error' : '' }}">
						{!! Form::label('dimension', App\Language::trans('Dimension (L x W x H)'), ['class'=>'control-label col-md-2']) !!}
						<div class="col-md-3">
							{!! Form::number('length', null, ['id'=>'length', 'rows'=>'10' , 'cols'=>'80','class'=>'form-control','placeholder'=>'Length']) !!}
		                    {!!$errors->first('length', '<label for="length" class="help-block error">:message</label>')!!}
						</div>
						<div class="col-md-3">
							{!! Form::number('width', null, ['id'=>'width', 'rows'=>'10' , 'cols'=>'80','class'=>'form-control','placeholder'=>'Width']) !!}
		                    {!!$errors->first('width', '<label for="width" class="help-block error">:message</label>')!!}
						</div>
						<div class="col-md-3">
							{!! Form::number('height', null, ['id'=>'height', 'rows'=>'10' , 'cols'=>'80','class'=>'form-control','placeholder'=>'Height']) !!}
		                    {!!$errors->first('height', '<label for="height" class="help-block error">:message</label>')!!}
						</div>
					</div>
		       </div>
	       </div>


	       <div class="row">
	       		<div class="col-md-12">
    	 			<h4 class="page-header">{{App\Language::trans('Other Attributes')}}</h4>
    	 		</div>

	      		<div class="col-md-6">
		        	<div class="form-group{{ $errors->has('model') ? ' has-error' : '' }}">
						{!! Form::label('model', App\Language::trans('Model'), ['class'=>'control-label col-md-4']) !!}
						<div class="col-md-8">
							{!! Form::text('model', null, ['id'=>'model', 'rows'=>'10' , 'cols'=>'80','class'=>'form-control']) !!}
		                    {!!$errors->first('model', '<label for="model" class="help-block error">:message</label>')!!}
						</div>
					</div>
		       </div>
	       </div> 

			<div class="row">
			   	<div class="col-md-6">
			        	<div class="form-group{{ $errors->has('sku') ? ' has-error' : '' }}">
							{!! Form::label('sku', App\Language::trans('SKU'), ['class'=>'control-label col-md-4']) !!}
							<div class="col-md-8">
								{!! Form::text('sku', null, ['id'=>'sku', 'rows'=>'10' , 'cols'=>'80','class'=>'form-control']) !!}
			                    {!!$errors->first('sku', '<label for="sku" class="help-block error">:message</label>')!!}
							</div>
						</div>
			       </div>	


			       <div class="col-md-6">
			        	<div class="form-group{{ $errors->has('upc') ? ' has-error' : '' }}">
							{!! Form::label('upc', App\Language::trans('UPC'), ['class'=>'control-label col-md-4']) !!}
							<div class="col-md-8">
								{!! Form::text('upc', null, ['id'=>'upc', 'rows'=>'10' , 'cols'=>'80','class'=>'form-control']) !!}
			                    {!!$errors->first('upc', '<label for="upc" class="help-block error">:message</label>')!!}
							</div>
						</div>
			       </div>	
			</div>


			<div class="row">
			       <div class="col-md-6">
			        	<div class="form-group{{ $errors->has('ean') ? ' has-error' : '' }}">
							{!! Form::label('ean', App\Language::trans('EAN'), ['class'=>'control-label col-md-4']) !!}
							<div class="col-md-8">
								{!! Form::text('ean', null, ['id'=>'ean', 'rows'=>'10' , 'cols'=>'80','class'=>'form-control']) !!}
			                    {!!$errors->first('ean', '<label for="ean" class="help-block error">:message</label>')!!}
							</div>
						</div>
			       </div>	


			       <div class="col-md-6">
			        	<div class="form-group{{ $errors->has('jan') ? ' has-error' : '' }}">
							{!! Form::label('jan', App\Language::trans('JAN'), ['class'=>'control-label col-md-4']) !!}
							<div class="col-md-8">
								{!! Form::text('jan', null, ['id'=>'jan', 'rows'=>'10' , 'cols'=>'80','class'=>'form-control']) !!}
			                    {!!$errors->first('jan', '<label for="jan" class="help-block error">:message</label>')!!}
							</div>
						</div>
			       </div>
			</div>


			<div class="row">
			        <div class="col-md-6">
			        	<div class="form-group{{ $errors->has('isbn') ? ' has-error' : '' }}">
							{!! Form::label('isbn', App\Language::trans('ISBN'), ['class'=>'control-label col-md-4']) !!}
							<div class="col-md-8">
								{!! Form::text('isbn', null, ['id'=>'isbn', 'rows'=>'10' , 'cols'=>'80','class'=>'form-control']) !!}
			                    {!!$errors->first('isbn', '<label for="isbn" class="help-block error">:message</label>')!!}
							</div>
						</div>
			       </div>	


			       <div class="col-md-6">
			        	<div class="form-group{{ $errors->has('mpn') ? ' has-error' : '' }}">
							{!! Form::label('mpn', App\Language::trans('MPN'), ['class'=>'control-label col-md-4']) !!}
							<div class="col-md-8">
								{!! Form::text('mpn', null, ['id'=>'mpn', 'rows'=>'10' , 'cols'=>'80','class'=>'form-control']) !!}
			                    {!!$errors->first('mpn', '<label for="mpn" class="help-block error">:message</label>')!!}
							</div>
						</div>
			       </div>
			</div>
	       
	</div>


	<div class="box-footer">
		<div class="row">
			<div class="col-md-offset-2 col-md-10">
				<button type="submit" class="btn btn-success pull-right">{{App\Language::trans('Save')}}</button>
				
			</div>
		</div>
	</div>
</div>

{!! Form::close() !!}
@endsection
@section('script')
  init_daterange("input[name=date_available]");
  $(function () {
	$.get(getOCProductDetailUrl, {product_id:$('#product_id').val()}, function(fdata){
		product_description_listing_arr = fdata.data['product_description'];
		product_description_listing_arr.forEach(function(description) {
		    for(var key in description){
				if(key == "language_id") {
					//bootstrap WYSIHTML5 - text editor
					CKEDITOR.replace("description_"+description[key])
					$('.textarea').wysihtml5()
				}
			}
		});
	},"json");
  })
@endsection