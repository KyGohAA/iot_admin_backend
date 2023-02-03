@extends('commons.layouts.admin')
@section('content')
{!! Form::model($model, ['class'=>'form-horizontal']) !!}
@include('commons.layouts.partials._alert')


<div class="box" id="step_1">
	<div class="box-header with-border">
		<h3 class="box-title">{{App\Language::trans('Product Detail')}}</h3>
		
	</div>

	<!-- Nav tabs -->
<ul class="nav nav-tabs margin-bottom-15" role="tablist">
	<li role="presentation" class="active">
		<a href="#english_setting" aria-controls="english_setting" role="tab" data-toggle="tab">{{App\Language::trans('English')}}</a>
	</li>

	<li role="presentation">
		<a href="#malay_setting" aria-controls="malay_setting" role="tab" data-toggle="tab">{{App\Language::trans('Chinese Simplified')}}</a>
	</li>

	<li role="presentation">
		<a href="#chinese_setting" aria-controls="chinese_setting" role="tab" data-toggle="tab">{{App\Language::trans('Chinese Traditional')}}</a>
	</li>	
</ul>

	<!-- Tab panes -->
<div class="tab-content">
    <div role="tabpanel" class="tab-pane active" id="english_setting">
     		<div class="col-md-12">
	        	<div class="form-group{{ $errors->has('english_description') ? ' has-error' : '' }}">
					{!! Form::label('english_description', App\Language::trans('Name'), ['class'=>'control-label col-md-2']) !!}
					<div class="col-md-10">
						{!! Form::text('english_description', null, ['id'=>'english_description', 'rows'=>'10' , 'cols'=>'80','class'=>'form-control']) !!}
	                    {!!$errors->first('english_description', '<label for="english_description" class="help-block error">:message</label>')!!}
					</div>
				</div>
	       </div>

	        <div class="col-md-12">
	        	<div class="form-group{{ $errors->has('english_content') ? ' has-error' : '' }}">
					{!! Form::label('english_content', App\Language::trans('Content'), ['class'=>'control-label col-md-2']) !!}
					<div class="col-md-10">
						{!! Form::textarea('english_content', null, ['id'=>'english_content', 'rows'=>'10' , 'cols'=>'80','class'=>'form-control']) !!}
	                    {!!$errors->first('english_content', '<label for="english_content" class="help-block error">:message</label>')!!}
					</div>
				</div>
	       </div>
      </div>
   
   <div role="tabpanel" class="tab-pane" id="malay_setting">
   
     		<div class="col-md-12">
	        	<div class="form-group{{ $errors->has('malay_description') ? ' has-error' : '' }}">
					{!! Form::label('malay_description', App\Language::trans('Name'), ['class'=>'control-label col-md-2']) !!}
					<div class="col-md-10">
						{!! Form::text('malay_description', null, ['id'=>'malay_description', 'rows'=>'10' , 'cols'=>'80','class'=>'form-control']) !!}
	                    {!!$errors->first('malay_description', '<label for="malay_description" class="help-block error">:message</label>')!!}
					</div>
				</div>
	       </div>

	        <div class="col-md-12">
	        	<div class="form-group{{ $errors->has('malay_content') ? ' has-error' : '' }}">
					{!! Form::label('malay_content', App\Language::trans('Content'), ['class'=>'control-label col-md-2']) !!}
					<div class="col-md-10">
						{!! Form::textarea('malay_content', null, ['id'=>'malay_content', 'rows'=>'10' , 'cols'=>'80','class'=>'form-control']) !!}
	                    {!!$errors->first('malay_content', '<label for="malay_content" class="help-block error">:message</label>')!!}
					</div>
				</div>
	       </div>
      </div>
  

   <div role="tabpanel" class="tab-pane" id="chinese_setting">
      		<div class="col-md-12">
	        	<div class="form-group{{ $errors->has('chinese_description') ? ' has-error' : '' }}">
					{!! Form::label('chinese_description', App\Language::trans('Name'), ['class'=>'control-label col-md-2']) !!}
					<div class="col-md-10">
						{!! Form::text('chinese_description', null, ['id'=>'chinese_description', 'rows'=>'10' , 'cols'=>'80','class'=>'form-control']) !!}
	                    {!!$errors->first('chinese_description', '<label for="chinese_description" class="help-block error">:message</label>')!!}
					</div>
				</div>
	       </div>

	        <div class="col-md-12">
	        	<div class="form-group{{ $errors->has('chinese_content') ? ' has-error' : '' }}">
					{!! Form::label('chinese_content', App\Language::trans('Content'), ['class'=>'control-label col-md-2']) !!}
					<div class="col-md-10">
						{!! Form::textarea('chinese_content', null, ['id'=>'chinese_content', 'rows'=>'10' , 'cols'=>'80','class'=>'form-control']) !!}
	                    {!!$errors->first('chinese_content', '<label for="chinese_content" class="help-block error">:message</label>')!!}
					</div>
				</div>
	       </div>
      </div>
 

  </div>

	<div class="box-footer">
		<div class="row">
			<div class="col-md-offset-2 col-md-10">
				<label class="btn btn-success pull-right" onclick="hide_step_by_step_no_shopping(1);">{{App\Language::trans('Next')}}</label>
			</div>
		</div>
	</div>
</div>

<div class="box hide" id="step_2">
	<div class="box-header with-border">
		<h3 class="box-title">{{App\Language::trans('Product Information')}}</h3>
	</div>

    <div class="panel-body p25">

    	 <div class="row">
	      		<div class="col-md-12">
		        	<div class="form-group{{ $errors->has('chinese_description') ? ' has-error' : '' }}">
						{!! Form::label('chinese_description', App\Language::trans('Price'), ['class'=>'control-label col-md-2']) !!}
						<div class="col-md-10">
							{!! Form::number('chinese_description', null, ['id'=>'chinese_description', 'rows'=>'10' , 'cols'=>'80','class'=>'form-control']) !!}
		                    {!!$errors->first('chinese_description', '<label for="chinese_description" class="help-block error">:message</label>')!!}
						</div>
					</div>
		       </div>
	       </div>

    		<div class="row">
	      		<div class="col-md-12">
		        	<div class="form-group{{ $errors->has('chinese_description') ? ' has-error' : '' }}">
						{!! Form::label('chinese_description', App\Language::trans('Quantity'), ['class'=>'control-label col-md-2']) !!}
						<div class="col-md-10">
							{!! Form::number('chinese_description', null, ['id'=>'chinese_description', 'rows'=>'10' , 'cols'=>'80','class'=>'form-control']) !!}
		                    {!!$errors->first('chinese_description', '<label for="chinese_description" class="help-block error">:message</label>')!!}
						</div>
					</div>
		       </div>
	       </div>

	       <div class="row">
	      		<div class="col-md-12">
		        	<div class="form-group{{ $errors->has('chinese_description') ? ' has-error' : '' }}">
						{!! Form::label('chinese_description', App\Language::trans('Minimum Quantity'), ['class'=>'control-label col-md-2']) !!}
						<div class="col-md-10">
							{!! Form::number('chinese_description', null, ['id'=>'chinese_description', 'rows'=>'10' , 'cols'=>'80','class'=>'form-control']) !!}
		                    {!!$errors->first('chinese_description', '<label for="chinese_description" class="help-block error">:message</label>')!!}
						</div>
					</div>
		       </div>
	       </div>

	       <div class="row">
	      		<div class="col-md-12">
		        	<div class="form-group{{ $errors->has('chinese_description') ? ' has-error' : '' }}">
						{!! Form::label('chinese_description', App\Language::trans('Out Of Stock Status'), ['class'=>'control-label col-md-2']) !!}
						<div class="col-md-10">
							{!! Form::number('chinese_description', null, ['id'=>'chinese_description', 'rows'=>'10' , 'cols'=>'80','class'=>'form-control']) !!}
		                    {!!$errors->first('chinese_description', '<label for="chinese_description" class="help-block error">:message</label>')!!}
						</div>
					</div>
		       </div>
	       </div>

			<div class="col-md-12">
		        	<div class="form-group{{ $errors->has('lenght_class_id') ? ' has-error' : '' }}">
						{!! Form::label('lenght_class_id', App\Language::trans('Length Class'), ['class'=>'control-label col-md-2']) !!}
						<div class="col-md-10">
							{!! Form::select('lenght_class_id', App\Opencart\LengthClassDescription::combobox(1), null, ['class'=>'form-control','onchange'=>'init_currency_rate(this)']) !!}
		                    {!!$errors->first('lenght_class_id', '<label for="lenght_class_id" class="help-block error">:message</label>')!!}
						</div>
					</div>
		        </div>

      		<div class="row">
	      		<div class="col-md-12">
		        	<div class="form-group{{ $errors->has('chinese_description') ? ' has-error' : '' }}">
						{!! Form::label('chinese_description', App\Language::trans('Dimension (L x W x H)'), ['class'=>'control-label col-md-3']) !!}
						<div class="col-md-3">
							{!! Form::number('chinese_description', null, ['id'=>'chinese_description', 'rows'=>'10' , 'cols'=>'80','class'=>'form-control','placeholder'=>'Length']) !!}
		                    {!!$errors->first('chinese_description', '<label for="chinese_description" class="help-block error">:message</label>')!!}
						</div>
						<div class="col-md-3">
							{!! Form::number('chinese_description', null, ['id'=>'chinese_description', 'rows'=>'10' , 'cols'=>'80','class'=>'form-control','placeholder'=>'Width']) !!}
		                    {!!$errors->first('chinese_description', '<label for="chinese_description" class="help-block error">:message</label>')!!}
						</div>
						<div class="col-md-3">
							{!! Form::number('chinese_description', null, ['id'=>'chinese_description', 'rows'=>'10' , 'cols'=>'80','class'=>'form-control','placeholder'=>'Height']) !!}
		                    {!!$errors->first('chinese_description', '<label for="chinese_description" class="help-block error">:message</label>')!!}
						</div>
					</div>
		       </div>
	       </div>

			<div class="col-md-12">
		        	<div class="form-group{{ $errors->has('weight_class_id') ? ' has-error' : '' }}">
						{!! Form::label('weight_class_id', App\Language::trans('Weight Class'), ['class'=>'control-label col-md-2']) !!}
						<div class="col-md-10">
							{!! Form::select('weight_class_id', App\Opencart\WeightClassDescription::combobox(1), null, ['class'=>'form-control','onchange'=>'init_currency_rate(this)']) !!}
		                    {!!$errors->first('weight_class_id', '<label for="weight_class_id" class="help-block error">:message</label>')!!}
						</div>
					</div>
		        </div>
	       <div class="row">
	      		<div class="col-md-12">
		        	<div class="form-group{{ $errors->has('chinese_description') ? ' has-error' : '' }}">
						{!! Form::label('chinese_description', App\Language::trans('Weight'), ['class'=>'control-label col-md-2']) !!}
						<div class="col-md-10">
							{!! Form::number('chinese_description', null, ['id'=>'chinese_description', 'rows'=>'10' , 'cols'=>'80','class'=>'form-control']) !!}
		                    {!!$errors->first('chinese_description', '<label for="chinese_description" class="help-block error">:message</label>')!!}
						</div>
					</div>
		       </div>
	       </div>

	</div>

	<div class="box-footer">
		<div class="row">
			<div class="col-md-offset-2 col-md-10">
				<label class="btn btn-success pull-right" onclick="hide_step_by_step_no_shopping(2);">{{App\Language::trans('Next')}}</label>
			</div>
		</div>
	</div>
</div>


<div class="box hide" id="step_3">
	<div class="box-header with-border">
		<h3 class="box-title">{{App\Language::trans('Product Photos')}}</h3>
	</div>

    <div class="panel-body p25">

    	 <div class="row">

    			<div class="col-md-12">
		        	<div class="form-group{{ $errors->has('category_id') ? ' has-error' : '' }}">
						{!! Form::label('category_id', App\Language::trans('Category'), ['class'=>'control-label col-md-2']) !!}
						<div class="col-md-10">
							{!! Form::select('category_id', App\Opencart\CategoryDescription::combobox(1), null, ['class'=>'form-control','onchange'=>'init_currency_rate(this)']) !!}
		                    {!!$errors->first('category_id', '<label for="category_id" class="help-block error">:message</label>')!!}
						</div>
					</div>
		        </div>
	    </div>

    		<div class="row">
	      		<div class="col-md-12">	      
		        	<div class="form-group{{ $errors->has('product_images') ? ' has-error' : '' }}">
						{!! Form::label('product_images', App\Language::trans('Photo'), ['class'=>'control-label col-md-2']) !!}					
						<div class="col-md-10">
							  <div class="row">
					      		<div class="col-md-12">
							        <div id="wrapper">
										 <form action="upload_file.php" method="post" enctype="multipart/form-data">
										  <input placeholder="Upload" type="file" id="upload_file" name="upload_file[]" onchange="preview_image();" multiple/>		 
										 </form>

									     <div class="row">
									     	 <br>
											 <div class="col-md-12" id="image_preview"></div>
										 </div>
									</div>
									<div class="help-block TMargin10"><small>{{App\Language::trans('Click to select product cover photo.')}}</small></div>
								</div>
					       </div>
						</div>
					</div>
		       </div>
	       </div>   
	</div>

	<div class="box-footer">
		<div class="row">
			<div class="col-md-offset-2 col-md-10">
				<button type="submit" class="btn btn-success pull-right">{{App\Language::trans('Done')}}</button>
				
			</div>
		</div>
	</div>
</div>






{!! Form::close() !!}
@endsection
@section('script')
  $(function () {
    // Replace the <textarea id="content"> with a CKEditor
    // instance, using default configuration.

    //bootstrap WYSIHTML5 - text editor
    $('#malay_content').wysihtml5()
     $('#english_content').wysihtml5()
      $('#chinese_content').wysihtml5()

  })
@endsection