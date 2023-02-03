@extends('commons.layouts.admin')
@section('content')
{!! Form::model($model, ['class'=>'form-horizontal']) !!}
@include('commons.layouts.partials._alert')
<div class="box">
	<div class="box-header with-border">
		<h3 class="box-title">{{App\Language::trans('Detail Form')}}</h3>
		<div class="box-tools pull-right">
			<a href="{{action('HelpsController@getNew')}}" class="btn btn-block btn-info">
				<i class="fa fa-file"></i> {{App\Language::trans('New File')}}
			</a>
		</div>
	</div>

	<!-- Nav tabs -->
<ul class="nav nav-tabs margin-bottom-15" role="tablist">
	<li role="presentation" class="active">
		<a href="#english_setting" aria-controls="english_setting" role="tab" data-toggle="tab">{{App\Language::trans('English')}}</a>
	</li>

	<li role="presentation">
		<a href="#malay_setting" aria-controls="malay_setting" role="tab" data-toggle="tab">{{App\Language::trans('Malay')}}</a>
	</li>

	<li role="presentation">
		<a href="#chinese_setting" aria-controls="chinese_setting" role="tab" data-toggle="tab">{{App\Language::trans('Chinese')}}</a>
	</li>	
</ul>




	<!-- Tab panes -->
<div class="tab-content">
    <div role="tabpanel" class="tab-pane active" id="english_setting">
     		<div class="col-md-12">
	        	<div class="form-group{{ $errors->has('english_description') ? ' has-error' : '' }}">
					{!! Form::label('english_description', App\Language::trans('English Description'), ['class'=>'control-label col-md-2']) !!}
					<div class="col-md-10">
						{!! Form::text('english_description', null, ['id'=>'english_description', 'rows'=>'10' , 'cols'=>'80','class'=>'form-control']) !!}
	                    {!!$errors->first('english_description', '<label for="english_description" class="help-block error">:message</label>')!!}
					</div>
				</div>
	       </div>

	        <div class="col-md-12">
	        	<div class="form-group{{ $errors->has('english_content') ? ' has-error' : '' }}">
					{!! Form::label('english_content', App\Language::trans('English Content'), ['class'=>'control-label col-md-2']) !!}
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
					{!! Form::label('malay_description', App\Language::trans('Malay Description'), ['class'=>'control-label col-md-2']) !!}
					<div class="col-md-10">
						{!! Form::text('malay_description', null, ['id'=>'malay_description', 'rows'=>'10' , 'cols'=>'80','class'=>'form-control']) !!}
	                    {!!$errors->first('malay_description', '<label for="malay_description" class="help-block error">:message</label>')!!}
					</div>
				</div>
	       </div>

	        <div class="col-md-12">
	        	<div class="form-group{{ $errors->has('malay_content') ? ' has-error' : '' }}">
					{!! Form::label('malay_content', App\Language::trans('Malay Content'), ['class'=>'control-label col-md-2']) !!}
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
					{!! Form::label('chinese_description', App\Language::trans('Chinese Description'), ['class'=>'control-label col-md-2']) !!}
					<div class="col-md-10">
						{!! Form::text('chinese_description', null, ['id'=>'chinese_description', 'rows'=>'10' , 'cols'=>'80','class'=>'form-control']) !!}
	                    {!!$errors->first('chinese_description', '<label for="chinese_description" class="help-block error">:message</label>')!!}
					</div>
				</div>
	       </div>

	        <div class="col-md-12">
	        	<div class="form-group{{ $errors->has('chinese_content') ? ' has-error' : '' }}">
					{!! Form::label('chinese_content', App\Language::trans('Chinese Content'), ['class'=>'control-label col-md-2']) !!}
					<div class="col-md-10">
						{!! Form::textarea('chinese_content', null, ['id'=>'chinese_content', 'rows'=>'10' , 'cols'=>'80','class'=>'form-control']) !!}
	                    {!!$errors->first('chinese_content', '<label for="chinese_content" class="help-block error">:message</label>')!!}
					</div>
				</div>
	       </div>
      </div>
 

  </div>

      	
	<div class="row">
		<div class="col-md-12">
			<div class="form-group{{ $errors->has('status') ? ' has-error' : '' }}">
				{!! Form::label('status', App\Language::trans('Status'), ['class'=>'control-label col-md-2']) !!}
				<div class="col-md-10">
					<label class="radio-inline">
						{!! Form::radio('status', 1, true) !!} {{App\Language::trans('Enabled')}}
					</label>
					<label class="radio-inline">
						{!! Form::radio('status', 0, false) !!} {{App\Language::trans('Disabled')}}
					</label>
		            {!!$errors->first('status', '<label for="status" class="help-block error">:message</label>')!!}
				</div>
			</div>
		</div>
	</div>

	<div class="box-footer">
		<div class="row">
			<div class="col-md-offset-2 col-md-10">
				<button type="submit" class="btn btn-primary"><i class="fa fa-floppy-o fa-fw"></i>{{App\Language::trans('Save')}}</button>
				<a href="{{action('HelpsController@getIndex')}}" class="btn btn-danger"><i class="fa fa-ban fa-fw"></i>{{App\Language::trans('Close')}}</a>
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
    CKEDITOR.replace('english_content')
    //bootstrap WYSIHTML5 - text editor
    $('.textarea').wysihtml5()

     CKEDITOR.replace('malay_content')
    //bootstrap WYSIHTML5 - text editor
    $('.textarea').wysihtml5()

     CKEDITOR.replace('chinese_content')
    //bootstrap WYSIHTML5 - text editor
    $('.textarea').wysihtml5()
  })
@endsection