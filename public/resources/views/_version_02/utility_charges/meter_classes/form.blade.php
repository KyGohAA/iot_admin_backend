@extends('_version_02.commons.layouts.admin')
@section('content')
{!! Form::model($model, ['class'=>'form-horizontal']) !!}
@include('_version_02.commons.layouts.partials._alert')
<section class="hk-sec-wrapper">
    <h5 class="hk-sec-title">{{App\Language::trans($page_variables['page_title'])}}</h5><hr>
		<div class="row">
				<div class="col-md-6">
					<div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
						{!! Form::label('name', App\Language::trans('Name'), ['class'=>'control-label col-md-4']) !!}
						<div class="col-md-8">
							{!! Form::text('name', null, ['class'=>'form-control','required']) !!}
	                        {!!$errors->first('name', '<label for="name" class="help-block error">:message</label>')!!}
						</div>
					</div>
				</div>
			
			
		</div>	

		<div class="row">

			<div class="col-md-6">
				<div class="form-group{{ $errors->has('is_bonus') ? ' has-error' : '' }}">	
					{!! Form::label('is_bonus', App\Language::trans('Bonus ?'), ['class'=>'control-label col-md-12']) !!}
					<div class="col-md-12">
						  <div class="row">	
						 	<div class="col-md-3">
							    <div class="custom-control custom-radio">
							        <input type="radio" id="is_bonus" name="is_bonus" value=1  class="custom-control-input" {{isset($model->is_bonus) == true ? ($model->is_bonus == true ? 'checked' : '') : 'checked'}}>
							        <label class="custom-control-label" for="is_bonus">{{App\Language::trans('Enabled')}}</label>
							    </div>
							</div>
							<div class="col-md-3">
							    <div class="custom-control custom-radio">
							         <input type="radio" id="is_bonus_off" name="is_bonus" value=0 class="custom-control-input" {{isset($model->is_bonus) == true ? ($model->is_bonus == false ? 'checked' : '') : ''}}>
							        <label class="custom-control-label" for="is_bonus_off">{{App\Language::trans('Disabled')}}</label>
							    </div>
							</div>
						 </div>
						 {!!$errors->first('is_bonus', '<label for="is_bonus" class="help-block error">:message</label>')!!}
					</div>
				</div>
			</div>	

			<div class="col-md-6">
				<div class="form-group{{ $errors->has('bonus_amount') ? ' has-error' : '' }}">
					{!! Form::label('bonus_amount', App\Language::trans('Bonus Amount'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						{!! Form::number('bonus_amount', $model->id ? $model->bonus_amount:0, ['class'=>'form-control','step'=>'0.01','min'=>'0']) !!}
                        {!!$errors->first('bonus_amount', '<label for="bonus_amount" class="help-block error">:message</label>')!!}
					</div>
				</div>
			</div>
		</div>


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

		@if(!$model->is_bonus)
			$("input[name=bonus_amount]").closest(".form-group").hide();
		@endif
		$("#is_bonus_on").on("click", function(){
			$("input[name=bonus_amount]").closest(".form-group").show("slow");
		})
		$("#is_bonus_off").on("click", function(){
			$("input[name=bonus_amount]").closest(".form-group").hide("slow");
		})

@endsection

