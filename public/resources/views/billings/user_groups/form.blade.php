@extends('billings.layouts.admin')
@section('content')
{!! Form::model($model, ['class'=>'form-horizontal']) !!}
@include('billings.layouts.partials._alert')
<div class="box">
	<div class="box-header with-border">
		<h3 class="box-title">{{App\Language::trans('Detail Form')}}</h3>
		<div class="box-tools pull-right">
			<a href="{{action('UserGroupsController@getNew')}}" class="btn btn-block btn-info">
				<i class="fa fa-file"></i> {{App\Language::trans('New File')}}
			</a>
		</div>
	</div>
	<div class="box-body">
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
	</div>
</div>
<div class="box">
	<div class="box-header with-border">
		<h3 class="box-title">{{App\Language::trans('Description Form')}}</h3>
		<div class="box-tools pull-right">
			<a href="{{action('UserGroupsController@getNew')}}" class="btn btn-block btn-info">
				<i class="fa fa-file"></i> {{App\Language::trans('New File')}}
			</a>
		</div>
	</div>
	<div class="box-body">
		@foreach($permissions as $controller => $resources)
			<h3 class="box-title">{{$controller}}</h3>
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
		@endforeach
	</div>
</div>
<div class="box">
	<div class="box-header with-border">
		<h3 class="box-title">{{App\Language::trans('Other Form')}}</h3>
		<div class="box-tools pull-right">
			<a href="{{action('UserGroupsController@getNew')}}" class="btn btn-block btn-info">
				<i class="fa fa-file"></i> {{App\Language::trans('New File')}}
			</a>
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
				<a href="{{action('UserGroupsController@getIndex')}}" class="btn btn-danger"><i class="fa fa-ban fa-fw"></i>{{App\Language::trans('Close')}}</a>
			</div>
		</div>
	</div>
</div>
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