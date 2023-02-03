@extends('_version_02.commons.layouts.admin')
@section('content')
{!! Form::model($model, ['class'=>'form-horizontal','method'=>'get']) !!}
<section class="hk-sec-wrapper">
    <h5 class="hk-sec-title">{{App\Language::trans('Filter By')}}</h5><hr>
		

		<div class="form-group{{ $errors->has('export_by') ? ' has-error' : '' }} row">
			{!! Form::label('export_by', App\Language::trans('Export By'), ['class'=>'control-label col-md-2']) !!}
			<div class="col-md-10">
				 <div class="row">	
				 	<div class="col-md-3">
					    <div class="custom-control custom-radio">
					        <input type="radio" id="html" name="export_by" checked class="custom-control-input">
					        <label class="custom-control-label" for="html">{{App\Language::trans('HTML')}}</label>
					    </div>
					</div>
					<div class="col-md-3">
					    <div class="custom-control custom-radio">
					        <input type="radio" id="pdf" name="export_by"  class="custom-control-input">
					        <label class="custom-control-label" for="pdf">{{App\Language::trans('PDF')}}</label>
					    </div>
					</div>
				 </div>
				 {!!$errors->first('status', '<label for="status" class="help-block error">:message</label>')!!}
			</div>
		</div>

		<div class="form-group{{ $errors->has('from_customer_id') ? ' has-error' : '' }} row">
			{!! Form::label('from_customer_id', App\Language::trans('Customer'), ['class'=>'col-sm-2 col-form-label']) !!}
			<div class="col-sm-10">
				{!! Form::select('from_customer_id', App\Customer::by_name_combobox(), null, ['class'=>'form-control']) !!}
                {!!$errors->first('from_customer_id', '<label for="from_customer_id" class="help-block error">:message</label>')!!}
			</div>
		</div>

		<div class="form-group{{ $errors->has('item_to_list') ? ' has-error' : '' }} row">
            {!! Form::label('created_date_range', App\Language::trans('Created Date Range'), ['class'=>'col-sm-2 col-form-label']) !!}
            <div class="col-sm-10">
                  <input class="form-control" type="text" name="daterange"/>
            </div>
        </div>
        @include('_version_02.commons.layouts.partials._form_floating_footer_report')
		
</section>

{!! Form::close() !!}
@if(count($listing))
	<section class="hk-sec-wrapper">
    <h5 class="hk-sec-title">{{App\Language::trans('Result')}}</h5><hr>

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
			
	</section>

@endif
@stop
@section('script')
	init_select2($("select[name=from_customer_id]"));
	init_select2($("select[name=to_customer_id]"));
	init_select2($("select[name=from_ar_invoice_id]"));
	init_select2($("select[name=to_ar_invoice_id]"));
@stop