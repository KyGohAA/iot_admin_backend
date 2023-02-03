@extends('billings.layouts.admin')
@section('content')
{!! Form::model($model, ['class'=>'form-horizontal']) !!}
@include('billings.layouts.partials._alert')
<div class="box">
	<div class="box-header with-border">
		<h3 class="box-title">{{App\Language::trans('Detail Form')}}</h3>
		<div class="box-tools pull-right">
			<a href="{{action('UsersController@getNew')}}" class="btn btn-block btn-info">
				<i class="fa fa-file"></i> {{App\Language::trans('New File')}}
			</a>
		</div>
	</div>
	<div class="box-body">
		<div class="row">
			<div class="col-md-6">
				<div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
					{!! Form::label('email', App\Language::trans('Email'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						@if($model->id)
							<p class="form-control-static">{{$model->email}}</p>
						@else
							{!! Form::text('email', null, ['class'=>'form-control','required']) !!}
						@endif
                        {!!$errors->first('email', '<label for="email" class="help-block error">:message</label>')!!}
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-6">
				<div class="form-group{{ $errors->has('user_group_id') ? ' has-error' : '' }}">
					{!! Form::label('user_group_id', App\Language::trans('User Group'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						{!! Form::select('user_group_id', App\UserGroup::combobox(), null, ['class'=>'form-control']) !!}
                        {!!$errors->first('user_group_id', '<label for="user_group_id" class="help-block error">:message</label>')!!}
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
				<a href="{{action('UsersController@getIndex')}}" class="btn btn-danger"><i class="fa fa-ban fa-fw"></i>{{App\Language::trans('Close')}}</a>
			</div>
		</div>
	</div>
</div>
{!! Form::close() !!}
@endsection
@section('script')
@endsection