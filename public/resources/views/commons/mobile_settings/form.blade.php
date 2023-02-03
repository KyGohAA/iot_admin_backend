@extends('commons.layouts.admin')
@include('commons.mobile_settings.partials._hidden_variable')
@section('content')
{!!Form::model($model, array("url"=>null,"method"=>"post","class"=>"form-horizontal","files"=>true))!!}
<div class="box">
<!-- Nav tabs -->
<ul class="nav nav-tabs margin-bottom-15" role="tablist">
 <?php $mobile_module_counter = 1;?>
 @foreach($mobile_module_listing as $key => $value)
	<li role="presentation" class="{{($mobile_module_counter == 1 ? 'active' : '')}}">
		<a href="#{{$key}}" aria-controls="{{$key}}" role="tab" data-toggle="tab"><h4 class="box-title">{{App\Language::trans($value)}}</h4></a>
	</li>
	<?php $mobile_module_counter ++;?>
 @endforeach
</ul>

<!-- Tab panes -->
<div class="tab-content">
<?php $module_tab_counter = 1;?>
 @foreach($mobile_module_listing as $key => $value)
 
	<div role="tabpanel" class="tab-pane {{($module_tab_counter == 1 ? 'active' : '')}}" id="{{$key}}">
		<ul class="nav nav-tabs margin-bottom-15" role="tablist">
		 <?php $language_tab_counter = 1;?>
		 @foreach($language_listing as $language_key => $language_value)
			<li role="presentation" class="{{($language_tab_counter == 1 ? 'active' : '')}}">
				<a href="#{{$key.'_'.$language_key}}" aria-controls="{{$key.'_'.$language_key}}" role="tab" data-toggle="tab">{{App\Language::trans($language_value)}}</a>
			</li>
			 <?php $language_tab_counter ++;?>
		 @endforeach
		</ul>

		<div class="tab-content">
		 <?php $language_tab_counter = 1;?>
		 @foreach($language_listing as $sub_language_key => $sub_language_value)
			<div role="tabpanel" class="tab-pane {{($language_tab_counter == 1 ? 'active' : '')}}" id="{{$key.'_'.$sub_language_key}}">
			
				@include('commons.layouts.partials._alert')
					
					<div class="box-body">
					  <div class="col-md-12">
				        	<div class="form-group{{ $errors->has('content_'.$sub_language_key) ? ' has-error' : '' }}">
								{!! Form::label('content_'.$sub_language_key, App\Language::trans($sub_language_value.' '.' Content'), ['class'=>'control-label col-md-2']) !!}
								<div class="col-md-10">
									{!! Form::textarea('content_'.$sub_language_key.'_'.$key, null, ['id'=>'content_'.$sub_language_key.'_'.$key, 'rows'=>'10' , 'cols'=>'80','class'=>'form-control']) !!}
				                    {!!$errors->first('content_'.$sub_language_key, '<label for="content_".{{$sub_language_key}} class="help-block error">:message</label>')!!}
								</div>
							</div>
				       </div>
					</div>
		
				
			</div>
			 <?php $language_tab_counter ++;?>
		  @endforeach
		</div>
	</div>	
  <?php $module_tab_counter ++;?>
  @endforeach
</div>

	<div class="box-footer">
			<div class="row">
				<div class="col-md-offset-2 col-md-10">
					<button type="submit" class="btn btn-primary"><i class="fa fa-floppy-o fa-fw"></i>{{App\Language::trans('Save')}}</button>
					<a href="{{action('TaxesController@getIndex')}}" class="btn btn-danger"><i class="fa fa-ban fa-fw"></i>{{App\Language::trans('Close')}}</a>
				</div>
			</div>
	</div>
</div>
{!! Form::close() !!}
@stop
@section('script')

mobile_module_listing = JSON.parse($('#mobile_module_listing_str').val());
language_listing = JSON.parse($('#language_listing_str').val());

 for (var key in mobile_module_listing) {
	 for (var language_key in language_listing) {
	    console.log('content_'+language_key+'_'+key);
		CKEDITOR.replace('content_'+language_key+'_'+key);
	}
}

@stop