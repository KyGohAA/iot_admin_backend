@extends('umrah.layouts.admin')
@section('content')
{!! Form::model($model, ['class'=>'form-horizontal']) !!}
@include('_version_02.umrah.layouts.partials._alert')
<div class="box">
	<div class="box-header with-border">
		<h3 class="box-title">{{App\Language::trans('Select Users')}}</h3>
		<div class="box-tools pull-right">
			<a href="{{action('CitiesController@getNew')}}" class="btn btn-block btn-info">
				<i class="fa fa-file"></i> {{App\Language::trans('New File')}}
			</a>
		</div>
	</div>
	<div class="box-body">
		<div class="row">
			<div class="col-md-6">
				<div class="form-group{{ $errors->has('from_user') ? ' has-error' : '' }}">
					{!! Form::label('from_user', App\Language::trans('From User'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						{!! Form::select('from_user', App\User::combobox(), null, ['class'=>'form-control','autofocus','required']) !!}
                        {!!$errors->first('from_user', '<label for="from_user" class="help-block error">:message</label>')!!}
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group{{ $errors->has('to_user') ? ' has-error' : '' }}">
					{!! Form::label('to_user', App\Language::trans('To User'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						{!! Form::select('to_user', App\User::combobox(), null, ['class'=>'form-control','required']) !!}
                        {!!$errors->first('to_user', '<label for="to_user" class="help-block error">:message</label>')!!}
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="box">
	<div class="box-header with-border">
		<h3 class="box-title">{{App\Language::trans('Select Packages')}}</h3>
	</div>
	<div class="box-body">
		<div class="table-responsive">
			<table class="table">
				<thead>
					<tr>
						<th>#</th>
						<th class="col-md-11">Package Name</th>
						<th class="col-md-1">Quantity</th>
					</tr>
				</thead>
				<tbody>
					@foreach($vouchers as $index => $row)
						<tr>
							<td>{{$index+1}}</td>
							<td>{{$row->name}}</td>
							<td>
								{!! Form::number('package['.$row->id.'][quantity]', 0, ['class'=>'form-control']) !!}
							</td>
						</tr>
					@endforeach
				</tbody>
			</table>
		</div>
	</div>
	<div class="box-footer">
		<div class="row">
			<div class="col-md-12 text-right">
				<button type="submit" class="btn btn-primary"><i class="fa fa-floppy-o fa-fw"></i>{{App\Language::trans('Save')}}</button>
				<a href="{{action('VoucherAssignsController@getIndex')}}" class="btn btn-danger"><i class="fa fa-ban fa-fw"></i>{{App\Language::trans('Close')}}</a>
			</div>
		</div>
	</div>
</div>
{!! Form::close() !!}
@endsection
@section('script')
@endsection