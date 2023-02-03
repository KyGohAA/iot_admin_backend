@extends('_version_02.commons.layouts.admin')
@section('content')
{!! Form::model($model, ['class'=>'form-horizontal']) !!}
@include('_version_02.commons.layouts.partials._alert')
<div class="box">
	<div class="box-header with-border">
		<h3 class="box-title">{{App\Language::trans('Basic Detail Form')}}</h3>
		<div class="box-tools pull-right">
			<a href="{{action('ARInvoicesController@getNew')}}" class="btn btn-block btn-info">
				<i class="fa fa-file fa-fw"></i> {{App\Language::trans('New File')}}
			</a>
		</div>
	</div>
	<div class="box-body">
		<div class="row">
			<div class="col-md-6">
				<div class="form-group{{ $errors->has('customer_id') ? ' has-error' : '' }}">
					{!! Form::label('customer_id', App\Language::trans('Customer Code'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						<p class="form-control-static">{{$model->display_relationed('customer', 'code')}}</p>
					</div>
				</div>
				<div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
					{!! Form::label('name', App\Language::trans('Customer Name'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						<p class="form-control-static">{{$model->customer_name}}</p>
					</div>
				</div>
				<div class="form-group{{ $errors->has('phone_no') ? ' has-error' : '' }}">
					{!! Form::label('phone_no', App\Language::trans('Phone No'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						<p class="form-control-static">{{$model->phone_no}}</p>
					</div>
				</div>
				<div class="form-group{{ $errors->has('contact_person') ? ' has-error' : '' }}">
					{!! Form::label('contact_person', App\Language::trans('Contact Person'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						<p class="form-control-static">{{$model->contact_person}}</p>
					</div>
				</div>
				<div class="form-group{{ $errors->has('currency_id') ? ' has-error' : '' }}">
					{!! Form::label('currency_id', App\Language::trans('Currency'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						<p class="form-control-static">{{$model->display_relationed('currency', 'code')}}</p>
					</div>
				</div>
				<div class="form-group{{ $errors->has('currency_rate') ? ' has-error' : '' }}">
					{!! Form::label('currency_rate', App\Language::trans('Currency Rate'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						<p class="form-control-static">{{$model->currency_rate}}</p>
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group{{ $errors->has('document_no') ? ' has-error' : '' }}">
					{!! Form::label('document_no', App\Language::trans('Document No.'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						<p class="form-control-static">{{$model->document_no}}</p>
					</div>
				</div>
				<div class="form-group{{ $errors->has('document_date') ? ' has-error' : '' }}">
					{!! Form::label('document_date', App\Language::trans('Document Date'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						<p class="form-control-static">{{$model->document_date}}</p>
					</div>
				</div>
				<div class="form-group{{ $errors->has('po_no') ? ' has-error' : '' }}">
					{!! Form::label('po_no', App\Language::trans('P.O. No.'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						<p class="form-control-static">{{$model->po_no}}</p>
					</div>
				</div>
				<div class="form-group{{ $errors->has('payment_term_id') ? ' has-error' : '' }}">
					{!! Form::label('payment_term_id', App\Language::trans('Payment Term'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						<p class="form-control-static">{{$model->display_relationed('payment_term', 'code')}}</p>
					</div>
				</div>
				<div class="form-group{{ $errors->has('status') ? ' has-error' : '' }}">
					{!! Form::label('status', App\Language::trans('Document Status'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						<p class="form-control-static">{{$model->status}}</p>
					</div>
				</div>
				<div class="form-group{{ $errors->has('sales_person') ? ' has-error' : '' }}">
					{!! Form::label('sales_person', App\Language::trans('Sales Person'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						<p class="form-control-static">{{$model->sales_person}}</p>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@include('_version_02.commons.ar_invoices.partials.__address_view')
@include('_version_02.commons.ar_invoices.partials.__list_view')
<div class="box">
	<div class="box-header with-border">
		<h3 class="box-title">{{App\Language::trans('Action Bar')}}</h3>
	</div>
	<div class="box-footer">
		<div class="row">
			<div class="col-md-12">
				<div class="form-group{{ $errors->has('remark') ? ' has-error' : '' }}">
					{!! Form::label('remark', App\Language::trans('Remark'), ['class'=>'control-label col-md-2']) !!}
					<div class="col-md-10">
						<p class="form-control-static">{!!nl2br($model->remark)!!}</p>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-offset-2 col-md-10">
				<a href="{{action('ARInvoicesController@getIndex')}}" class="btn btn-danger"><i class="fa fa-ban fa-fw"></i>{{App\Language::trans('Close')}}</a>
			</div>
		</div>
	</div>
</div>
{!! Form::close() !!}
@endsection
@section('script')
@stop
