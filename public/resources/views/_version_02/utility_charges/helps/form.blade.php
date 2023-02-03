@extends('_version_02.commons.layouts.admin')
@section('content')
{!! Form::model($model, ['class'=>'form-horizontal', 'method' => 'post']) !!}
@include('_version_02.commons.layouts.partials._alert')

<section class="hk-sec-wrapper">
<div class="box">
	@php 
			$language_listing = $page_variables['language_listing'];
			$tab_status = ' active';
	@endphp
	 <!-- Nav tabs -->
	<ul class="nav nav-light nav-tabs bo" role="tablist">
	
		@foreach($language_listing as $language)

			<li role="presentation" class="nav-item">
				<a href="#{{ $language }}" aria-controls="{{ $language }}" class="d-flex h-60p align-items-center nav-link{{ $tab_status }}" role="tab" data-toggle="tab"><h5>{{App\Language::trans(ucfirst($language))}}</h5></a>
			</li>
			@php $tab_status =''; @endphp
		@endforeach


	</ul>
	<hr>

	<!-- Tab panes -->
	@php $tab_status = ' active'; @endphp
	<div class="tab-content">	
			@php
				if(isset($model->id)){

					$description = json_decode($model->description);
					$content = json_decode($model->content);
				}
			
			@endphp
			@foreach($language_listing as $language)
			    <div role="tabpanel" class="tab-pane{{ $tab_status }}" id="{{ $language }}">

			    	    
			    	 	<div class="col-md-12">
				        	<div class="form-group{{ $errors->has('description_'.$language) ? ' has-error' : '' }}">
								{!! Form::label('description['.$language.']', App\Language::trans(ucfirst($language).' Description'), ['class'=>'control-label col-md-2']) !!}
								<div class="col-md-10">
									{!! Form::text('description['.$language.']', (isset($model->id) ? $description->$language : null), ['id'=>'description['.$language.']', 'rows'=>'10' , 'cols'=>'80','class'=>'form-control']) !!}
				                    {!!$errors->first('description['.$language.']', '<label for="english_description" class="$model-block error">:message</label>')!!}
								</div>
							</div>
				       </div>

				        <div class="col-md-12">
				        	<div class="form-group{{ $errors->has('content['.$language.']') ? ' has-error' : '' }}">
								{!! Form::label('content['.$language.']', App\Language::trans(ucfirst($language).' Content'), ['class'=>'control-label col-md-2']) !!}
								<div class="col-md-10">
									{!! Form::textarea('content['.$language.']',  (isset($model->id) ? $content->$language : null), ['id'=>'content['.$language.']', 'rows'=>'10' , 'cols'=>'80','class'=>'form-control']) !!}
				                    {!!$errors->first('content['.$language.']', '<label for="english_content" class="$model-block error">:message</label>')!!}
								</div>
							</div>
				       </div>

					<!-- End Tab Panel -->	
				</div>
				@php $tab_status =''; @endphp
			@endforeach
		</div>
	</div>

	<hr>

	 <div class="row">
		<div class="col-md-6">
			<div class="form-group{{ $errors->has('status') ? ' has-error' : '' }}">	
				{!! Form::label('status', App\Language::trans('Status'), ['class'=>'control-label col-md-12']) !!}
				<div class="col-md-12">
					  <div class="row">	
					 	<div class="col-md-3">
						    <div class="custom-control custom-radio">
						        <input type="radio" id="status" name="status" value=1  class="custom-control-input" {{isset($model->status) == true ? ($model->status == true ? 'checked' : '') : 'checked'}}>
						        <label class="custom-control-label" for="status">{{App\Language::trans('Enabled')}}</label>
						    </div>
						</div>
						<div class="col-md-3">
						    <div class="custom-control custom-radio">
						         <input type="radio" id="status_off" name="status" value=0 class="custom-control-input" {{isset($model->status) == true ? ($model->status == false ? 'checked' : '') : ''}}>
						        <label class="custom-control-label" for="status_off">{{App\Language::trans('Disabled')}}</label>
						    </div>
						</div>
					 </div>
					 {!!$errors->first('status', '<label for="status" class="help-block error">:message</label>')!!}
				</div>
			</div>
		</div>	
	</div>

</section>

@include('_version_02.commons.layouts.partials._form_floaring_footer_standard')
{!! Form::close() !!}
@endsection
@section('script')
@endsection
