@extends('_version_02.commons.layouts.admin')
@section('content')
{!! Form::model($model, ['class'=>'form-horizontal']) !!}
@include('_version_02.commons.layouts.partials._alert')

<section class="hk-sec-wrapper">
    <h5 class="hk-sec-title">{{App\Language::trans('User Group Information')}}</h5><hr>
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
		</div>

		<div class="row">
			<div class="col-md-6">
				<div class="form-group{{ $errors->has('is_admin') ? ' has-error' : '' }}">
					{!! Form::label('is_admin', App\Language::trans('Is Admin'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-12">
						 <div class="row">	
						 	<div class="col-md-3">
							    <div class="custom-control custom-radio">
							        <input type="radio" id="is_admin_on" name="is_admin" checked class="custom-control-input">
							        <label class="custom-control-label" for="is_admin_on">{{App\ExtendModel::answer_true_word()}}</label>
							    </div>
							</div>
							<div class="col-md-3">
							    <div class="custom-control custom-radio">
							        <input type="radio" id="is_admin_off" name="is_admin"  class="custom-control-input">
							        <label class="custom-control-label" for="is_admin_off">{{App\ExtendModel::answer_false_word()}}</label>
							    </div>
							</div>
						 </div>
						 {!!$errors->first('is_admin', '<label for="is_admin" class="help-block error">:message</label>')!!}
					</div>
				</div>
			</div>
		</div>

    <div class="row">
			<div class="col-md-12">
				<div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
					{!! Form::label('name', App\Language::trans('Name'), ['class'=>'control-label col-md-2']) !!}
					<div class="col-md-10">
						{!! Form::text('name', null, ['class'=>'form-control','autofocus','required']) !!}
                        {!!$errors->first('name', '<label for="name" class="help-block error">:message</label>')!!}
					</div>
				</div>
			</div>
		</div>



	<br><h5 class="hk-sec-title">{{App\Language::trans('Access Right')}}</h5>
    <hr>
    		  	@foreach($permissions as $controller => $resources)
				<h6 class="hk-sec-title">{{$controller}}</h6><hr>
				<div class="row">
					@php $checked = true; @endphp
					@foreach($resources as $row)
						@if(!$model->get_permissions($row->resource_controller, $row->resource_action))
							@php $checked = false; @endphp
						@endif
						<div class="col-md-3">
							<div class="checkbox">
								<label>
									{!!Form::checkbox('permissions['.$row->resource_controller.'][]', $row->resource_action, ($model->id ? $model->get_permissions($row->resource_controller, $row->resource_action):false))!!} {{$row->resource_label}}
								</label>
							</div>
						</div>
					@endforeach
					@if(count($resources) > 1)
						<div class="col-md-3">
							<div class="checkbox">
								<label>
									{!!Form::checkbox('select_all', null, $checked, ['class'=>'select_all'])!!} {{App\Language::trans('All')}}
								</label>
							</div>
						</div>
					@endif
				</div>
				<br>
			@endforeach

	<div class="box-header with-border">
		 <h5 class="hk-sec-title">{{App\Language::trans('Remarks')}}</h5><hr>
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
$("input").not(".select_all").on("click", function(){
	var row = $(this).closest(".row");
	var checked = true;
	row.find("input").not(".select_all").each(function(){
		if(!$(this).prop("checked")) {
			checked = false;
		}
	})
	row.find(".select_all").prop("checked", checked);
});
$(".select_all").on("click", function(){
	var row = $(this).closest(".row");
	var checked = $(this).prop("checked");
	row.find("input").each(function(){
		$(this).prop("checked", checked);
	});
});
@endsection