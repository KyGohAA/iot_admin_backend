@extends('commons.layouts.admin')
@section('content')
{!! Form::model($model, ['class'=>'form-horizontal']) !!}
@include('commons.layouts.partials._alert')
<div class="box">
	<div class="box-header with-border">
		<h3 class="box-title">{{App\Language::trans('Detail Form')}}</h3>
		<div class="box-tools pull-right">
			<a href="{{action('UMeterInvoiceController@getNew')}}" class="btn btn-block btn-info">
				<i class="fa fa-file"></i> {{App\Language::trans('New File')}}
			</a>
		</div>
	</div>
	<div class="box-body">
		<div class="row">
			<div class="col-md-6">
				<div class="form-group{{ $errors->has('leaf_house_id') ? ' has-error' : '' }}">
					{!! Form::label('leaf_house_id', App\Language::trans('House No.'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						{!! Form::select('leaf_house_id', App\PowerMeterModel\MeterRegister::houses_combobox(), $model->id ? $model->display_relationed('meter_register', 'leaf_house_id'):null, ['class'=>'form-control','autofocus','required','onchange'=>'init_room_combobox(this)']) !!}
                        {!!$errors->first('leaf_house_id', '<label for="leaf_house_id" class="help-block error">:message</label>')!!}
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group{{ $errors->has('document_no') ? ' has-error' : '' }}">
					{!! Form::label('document_no', App\Language::trans('Document No'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						{!! Form::text('document_no', null, ['class'=>'form-control','readonly'=>true]) !!}
                        {!!$errors->first('document_no', '<label for="document_no" class="help-block error">:message</label>')!!}
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-6">
				<div class="form-group{{ $errors->has('leaf_room_id') ? ' has-error' : '' }}">
					{!! Form::label('leaf_room_id', App\Language::trans('Room No.'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						{!! Form::select('leaf_room_id', App\PowerMeterModel\MeterRegister::rooms_combobox((old('leaf_house_id') ? old('leaf_house_id'):$model->display_relationed('meter_register', 'leaf_house_id'))), $model->id ? $model->display_relationed('meter_register', 'leaf_room_id'):null, ['class'=>'form-control','required','onchange'=>'init_room_status(this)']) !!}
                        {!!$errors->first('leaf_room_id', '<label for="leaf_room_id" class="help-block error">:message</label>')!!}
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group{{ $errors->has('over_due_amount') ? ' has-error' : '' }}">
					{!! Form::label('over_due_amount', App\Language::trans('Over Due Amount'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						{!! Form::text('over_due_amount', null, ['class'=>'form-control','readonly'=>true]) !!}
                        {!!$errors->first('over_due_amount', '<label for="over_due_amount" class="help-block error">:message</label>')!!}
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-6">
				<div class="form-group{{ $errors->has('current_meter_reading') ? ' has-error' : '' }}">
					{!! Form::label('current_meter_reading', App\Language::trans('Current Meter'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						{!! Form::text('current_meter_reading', null, ['class'=>'form-control','required','onchange'=>'init_billing_summary(this)']) !!}
                        {!!$errors->first('current_meter_reading', '<label for="current_meter_reading" class="help-block error">:message</label>')!!}
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group{{ $errors->has('last_meter_reading') ? ' has-error' : '' }}">
					{!! Form::label('last_meter_reading', App\Language::trans('Last Meter Reading'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						{!! Form::text('last_meter_reading', null, ['class'=>'form-control','readonly'=>true]) !!}
                        {!!$errors->first('last_meter_reading', '<label for="last_meter_reading" class="help-block error">:message</label>')!!}
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="box">
	<div class="box-header with-border">
		<h3 class="box-title">{{App\Language::trans('Charge Information')}}</h3>
	</div>
	<div class="box-body">
		<div class="row">
			<div class="col-md-offset-2 col-md-10">
				<div class="table-responsive">
					<table id="price_list" class="table table-bordered table-hover">
						<thead>
							<tr>
								<th class="col-md-4 text-center">{{App\Language::trans('Meter Block')}}</th>
								<th class="col-md-4 text-center">{{App\Language::trans('Meter Usage')}}</th>
								<th class="col-md-2 text-center">{{App\Language::trans('Unit Price')}}</th>
								<th class="col-md-2 text-center">{{App\Language::trans('Total')}}</th>
							</tr>
						</thead>
						<tbody>
							@php $total=0; @endphp
							@php $meter_gst=0; @endphp
							@php $meter_without_gst=0; @endphp
							@php $amount_gst=0; @endphp
							@php $amount_without_gst=0; @endphp
							@foreach($model->items as $row)
								<tr>
									<td class="text-center">{{$row->meter_block}}</td>
									<td class="text-center">{{$row->meter_usage}}</td>
									<td class="text-center">{{$model->getDouble($row->unit_price)}}</td>
									<td class="text-center">{{$model->getDouble($row->total_price)}}</td>
								</tr>
								@if ($row->is_gst)
									@php $meter_gst+=$row->meter_usage; @endphp
									@php $amount_gst+=$row->total_price; @endphp
								@else
									@php $meter_without_gst+=$row->meter_usage; @endphp
									@php $amount_without_gst+=$row->total_price; @endphp
								@endif
								@php $total+=$row->total_price; @endphp
							@endforeach
						</tbody>
						<tfoot>
							<tr>
								<td colspan="3" class="text-right">{{App\Language::trans('Total')}} : </td>
								<td class="total text-center">{{$model->getDouble($total)}}</td>
							</tr>
						</tfoot>
					</table>
					<hr>
					<table id="detail_prices" class="table table-bordered table-hover">
						<thead>
							<tr>
								<th class="col-md-6 text-center">{{App\Language::trans('Description')}}</th>
								<th class="col-md-2 text-center">{{App\Language::trans('With GST')}}</th>
								<th class="col-md-2 text-center">{{App\Language::trans('Without GST')}}</th>
								<th class="col-md-2 text-center">{{App\Language::trans('Total')}}</th>
							</tr>
						</thead>
						<tbody>
							<tr class="usage_kwh">
								<td class="col-md-6 text-center">{{App\Language::trans('Usage')}}<span class="pull-right col-md-1">kwH</span></td>
								<td class="col-md-2 text-center">{{$model->getDouble($meter_gst)}}</td>
								<td class="col-md-2 text-center">{{$model->getDouble($meter_without_gst)}}</td>
								<td class="col-md-2 text-center">{{$model->getDouble($meter_gst+$meter_without_gst)}}</td>
							</tr>
							<tr class="usage_rm">
								<td class="col-md-6 text-center">{{App\Language::trans('Usage')}}<span class="pull-right col-md-1">RM</span></td>
								<td class="col-md-2 text-center">{{$model->getDouble($amount_gst)}}</td>
								<td class="col-md-2 text-center">{{$model->getDouble($amount_without_gst)}}</td>
								<td class="col-md-2 text-center">{{$model->getDouble($amount_gst+$amount_without_gst)}}</td>
							</tr>
							<tr class="icpt">
								<td class="col-md-6 text-center">{{App\Language::trans('ICPT')}} (RM<span class="icpt_charge">0.0152</span>-)<span class="pull-right col-md-1">RM</span></td>
								<td class="col-md-2 text-center"><span class="text">{{$model->getDouble($meter_gst*0.0152)}}</span>-</td>
								<td class="col-md-2 text-center"><span class="text">{{$model->getDouble($meter_without_gst*0.0152)}}</span>-</td>
								<td class="col-md-2 text-center"><span class="text">{{$model->getDouble(($meter_gst*0.0152)+($meter_without_gst*0.0152))}}</span>-</td>
							</tr>
							<tr class="current_month_usage">
								<td class="col-md-6 text-center">{{App\Language::trans('Current Month Usage')}}<span class="pull-right col-md-1">RM</span></td>
								<td class="col-md-2 text-center"></td>
								<td class="col-md-2 text-center"></td>
								<td class="col-md-2 text-center">{{$model->getDouble($model->current_month_amount)}}</td>
							</tr>
							<tr class="gst">
								<td class="col-md-6 text-center">6% GST<span class="pull-right col-md-1">RM</span></td>
								<td class="col-md-2 text-center"></td>
								<td class="col-md-2 text-center"></td>
								<td class="col-md-2 text-center">{{$model->getDouble($model->gst_amount)}}</td>
							</tr>
							<tr class="kwtbb">
								<td class="col-md-6 text-center">KWTBB (1.6%)<span class="pull-right col-md-1">RM</span></td>
								<td class="col-md-2 text-center"></td>
								<td class="col-md-2 text-center"></td>
								<td class="col-md-2 text-center">{{$model->getDouble($model->kwtbb_amount)}}</td>
							</tr>
							<tr class="late_payment_charge">
								<td class="col-md-6 text-center">Late Payment Charge<span class="pull-right col-md-1">RM</span></td>
								<td class="col-md-2 text-center"></td>
								<td class="col-md-2 text-center"></td>
								<td class="col-md-2 text-center">{{$model->getDouble($model->late_charge)}}</td>
							</tr>
							<tr class="current_charge">
								<td class="col-md-6 text-center">Current Charge<span class="pull-right col-md-1">RM</span></td>
								<td class="col-md-2 text-center"></td>
								<td class="col-md-2 text-center"></td>
								<td class="col-md-2 text-center">{{$model->getDouble($model->total_amount)}}</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
	<div class="box-footer">
		<div class="row">
			<div class="col-md-offset-2 col-md-10 text-right">
				<button type="submit" class="btn btn-primary"><i class="fa fa-floppy-o fa-fw"></i>{{App\Language::trans('Save')}}</button>
				<a href="{{action('UMeterInvoiceController@getIndex')}}" class="btn btn-danger"><i class="fa fa-ban fa-fw"></i>{{App\Language::trans('Close')}}</a>
			</div>
		</div>
	</div>
</div>
{!! Form::close() !!}
@endsection
@section('script')
@endsection