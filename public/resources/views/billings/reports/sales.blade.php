@extends('billings.layouts.admin')
@section('content')
{!! Form::model($model, ['class'=>'form-horizontal','method'=>'get']) !!}
<div class="box">
	<div class="box-header with-border">
		<h3 class="box-title">{{App\Language::trans('Filter By')}}</h3>
	</div>
	<div class="box-body">
		<div class="row">
			<div class="input-daterange">
				<div class="col-md-6">
					<div class="form-group{{ $errors->has('date_started') ? ' has-error' : '' }}">
						{!! Form::label('date_started', App\Language::trans('Date Started'), ['class'=>'control-label col-md-4']) !!}
						<div class="col-md-8">
							{!! Form::text('date_started', null, ['class'=>'form-control']) !!}
	                        {!!$errors->first('date_started', '<label for="date_started" class="help-block error">:message</label>')!!}
						</div>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group{{ $errors->has('date_ended') ? ' has-error' : '' }}">
						{!! Form::label('date_ended', App\Language::trans('Date Ended'), ['class'=>'control-label col-md-4']) !!}
						<div class="col-md-8">
							{!! Form::text('date_ended', null, ['class'=>'form-control']) !!}
	                        {!!$errors->first('date_ended', '<label for="date_ended" class="help-block error">:message</label>')!!}
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-6">
				<div class="form-group{{ $errors->has('from_customer_id') ? ' has-error' : '' }}">
					{!! Form::label('from_customer_id', App\Language::trans('From Customer'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						{!! Form::select('from_customer_id', App\Customer::by_name_combobox(), null, ['class'=>'form-control']) !!}
                        {!!$errors->first('from_customer_id', '<label for="from_customer_id" class="help-block error">:message</label>')!!}
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group{{ $errors->has('to_customer_id') ? ' has-error' : '' }}">
					{!! Form::label('to_customer_id', App\Language::trans('To Customer'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						{!! Form::select('to_customer_id', App\Customer::by_name_combobox(), null, ['class'=>'form-control']) !!}
                        {!!$errors->first('to_customer_id', '<label for="to_customer_id" class="help-block error">:message</label>')!!}
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-6">
				<div class="form-group{{ $errors->has('from_ar_invoice_id') ? ' has-error' : '' }}">
					{!! Form::label('from_ar_invoice_id', App\Language::trans('From Invoice'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						{!! Form::select('from_ar_invoice_id', App\MembershipModel\ARInvoice::combobox(), null, ['class'=>'form-control']) !!}
                        {!!$errors->first('from_ar_invoice_id', '<label for="from_ar_invoice_id" class="help-block error">:message</label>')!!}
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group{{ $errors->has('to_ar_invoice_id') ? ' has-error' : '' }}">
					{!! Form::label('to_ar_invoice_id', App\Language::trans('To Invoice'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						{!! Form::select('to_ar_invoice_id', App\MembershipModel\ARInvoice::combobox(), null, ['class'=>'form-control']) !!}
                        {!!$errors->first('to_ar_invoice_id', '<label for="to_ar_invoice_id" class="help-block error">:message</label>')!!}
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-6">
				<div class="form-group{{ $errors->has('export_by') ? ' has-error' : '' }}">
					{!! Form::label('export_by', App\Language::trans('Export By'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						<label class="radio-inline">
							{!! Form::radio('export_by', 'html', true, ['id'=>'export_by_pdf']) !!} {{App\Language::trans('HTML')}}
						</label>
						<label class="radio-inline">
							{!! Form::radio('export_by', 'pdf', false, ['id'=>'export_by_html']) !!} {{App\Language::trans('PDF')}}
						</label>
                        {!!$errors->first('export_by', '<label for="export_by" class="help-block error">:message</label>')!!}
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="box-footer">
		<div class="row">
			<div class="col-md-offset-2 col-md-10">
				<button type="submit" class="btn btn-primary"><i class="fa fa-search fa-fw"></i>{{App\Language::trans('Search')}}</button>
			</div>
		</div>
	</div>
</div>
{!! Form::close() !!}
@if(count($listing))
	<div class="box">
		<div class="box-header with-border">
			<h3 class="box-title">{{App\Language::trans('Listing Information')}}</h3>
		</div>
		<div class="box-body">
			<div class="table-responsive">
				<table class="table table-bordered table-hover">
					<thead>
						<tr>
							<th class="text-center">#</th>
							<th class="text-center">{{App\Language::trans('Document No.')}}</th>
							<th class="text-center">{{App\Language::trans('Document Date')}}</th>
							<th class="text-center">{{App\Language::trans('Customer')}}</th>
							<th class="text-center">{{App\Language::trans('Payment Term')}}</th>
							<th class="text-center">{{App\Language::trans('Due Date')}}</th>
							<th class="text-center">{{App\Language::trans('Status')}}</th>
							<th class="text-center">{{App\Language::trans('Amount')}}</th>
						</tr>
					</thead>
					<tbody>
						@foreach($listing as $index => $row)
							<tr>
								<td class="text-center">{{$index+1}}</td>
								<td class="text-center">{{$row->document_no}}</td>
								<td class="text-center">{{$row->getDate($row->document_date)}}</td>
								<td class="text-center">{{$row->customer_name}}</td>
								<td class="text-center">{{$row->payment_term_days ? ($row->payment_term_days.' '.App\Language::trans('Days')):App\Language::trans('Cash')}}</td>
								<td class="text-center">{{$row->getDate($row->due_date)}}</td>
								<td class="text-center">{{$row->status}}</td>
								<td class="text-center">{{$row->getDouble($row->total_amount)}}</td>
							</tr>
							@php  $total += $row->total_amount; @endphp
						@endforeach
					</tbody>
					<tfoot>
						<tr>
							<td class="text-right" colspan="7">{{App\Language::trans('Total')}}:</td>
							<td class="text-center">{{number_format($total, 2)}}</td>
						</tr>
					</tfoot>
				</table>
			</div>
		</div>
		<div class="box-footer">
		</div>
	</div>
@endif
@stop
@section('script')
	init_select2($("select[name=from_customer_id]"));
	init_select2($("select[name=to_customer_id]"));
	init_select2($("select[name=from_ar_invoice_id]"));
	init_select2($("select[name=to_ar_invoice_id]"));
@stop