@extends('commons.layouts.admin')
@section('content')
{!! Form::model($model, ['class'=>'form-horizontal']) !!}
@include('_version_02.commons.layouts.partials._alert')
<div class="box">
	<div class="box-header with-border">
		<h3 class="box-title">{{App\Language::trans('Detail Form')}}</h3>
		<div class="box-tools pull-right">
			<a href="{{action('PaymentTermsController@getNew')}}" class="btn btn-block btn-info">
				<i class="fa fa-file"></i> {{App\Language::trans('New File')}}
			</a>
		</div>
	</div>
	<div class="box-body">
		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					{!! Form::label('code', App\Language::trans('Code'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						<p class="form-control-static">{{$model->code}}</p>
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					{!! Form::label('days', App\Language::trans('Days'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						<p class="form-control-static">{{$model->days.' '.App\Language::trans('Days')}}</p>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					{!! Form::label('status', App\Language::trans('Status'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						<p class="form-control-static">{{$model->display_status_string('status')}}</p>
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					{!! Form::label('is_default', App\Language::trans('Is Default'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						<p class="form-control-static">{{$model->display_answer_string('is_default')}}</p>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="form-group">
					{!! Form::label('remark', App\Language::trans('Remark'), ['class'=>'control-label col-md-2']) !!}
					<div class="col-md-10">
						<p class="form-control-static">{{nl2br($model->remark)}}</p>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="box-footer">
		<div class="row">
			<div class="col-md-offset-2 col-md-10">
				<a href="{{action('PaymentTermsController@getIndex')}}" class="btn btn-danger"><i class="fa fa-ban fa-fw"></i>{{App\Language::trans('Close')}}</a>
			</div>
		</div>
	</div>
</div>
{!! Form::close() !!}
@endsection
@section('script')
@endsection