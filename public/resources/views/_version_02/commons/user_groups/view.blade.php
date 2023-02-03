@extends('_version_02.commons.layouts.admin')
@section('content')
{!! Form::model($model, ['class'=>'form-horizontal']) !!}
@include('_version_02.commons.layouts.partials._alert')
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
						<p class="form-control-static">{{$model->name}}</p>
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
								{!!Form::checkbox('permissions['.$row->resource_controller.'][]', $row->resource_action, ($model->id ? $model->get_permissions($row->resource_controller, $row->resource_action):false), ['disabled'])!!} {{$row->resource_label}}
							</label>
						</div>
					</div>
				@endforeach
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
				<div class="form-group">
					{!! Form::label('status', App\Language::trans('Status'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						<p class="form-control-static">{{$model->display_status_string('status')}}</p>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					{!! Form::label('remark', App\Language::trans('Remark'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						<p class="form-control-static">{{nl2br($model->remark)}}</p>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="box-footer">
		<div class="row">
			<div class="col-md-offset-2 col-md-10">
				<a href="{{action('UserGroupsController@getIndex')}}" class="btn btn-danger"><i class="fa fa-ban fa-fw"></i>{{App\Language::trans('Close')}}</a>
			</div>
		</div>
	</div>
</div>
{!! Form::close() !!}
@endsection
@section('script')
@endsection