@extends('commons.layouts.admin')
@section('content')
{!! Form::model($model, ['class'=>'form-horizontal']) !!}
@include('commons.layouts.partials._alert')
<div class="box">
	<div class="box-header with-border">
		<h3 class="box-title">{{App\Language::trans('Detail Form')}}</h3>
		<div class="box-tools pull-right">
			<a href="{{action('UMeterClassController@getNew')}}" class="btn btn-block btn-info">
				<i class="fa fa-file"></i> {{App\Language::trans('New File')}}
			</a>
		</div>
	</div>
	<div class="box-body">
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
					{!! Form::label('is_bonus', App\Language::trans('Bonus ?'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						<label class="radio-inline">
							{!! Form::radio('is_bonus', 1, false, ['id'=>'is_bonus_on']) !!} {{App\Language::trans('Yes')}}
						</label>
						<label class="radio-inline">
							{!! Form::radio('is_bonus', 0, true, ['id'=>'is_bonus_off']) !!} {{App\Language::trans('No')}}
						</label>
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
					{!! Form::label('status', App\Language::trans('Status'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
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
	</div>
	<div class="box-footer">
		<div class="row">
			<div class="col-md-offset-2 col-md-10">
				<button type="submit" class="btn btn-primary"><i class="fa fa-floppy-o fa-fw"></i>{{App\Language::trans('Save')}}</button>
				<a href="{{action('UMeterClassController@getIndex')}}" class="btn btn-danger"><i class="fa fa-ban fa-fw"></i>{{App\Language::trans('Close')}}</a>
			</div>
		</div>
	</div>
</div>
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