@extends('commons.layouts.admin')
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
				<div class="form-group{{ $errors->has('leaf_house_id') ? ' has-error' : '' }}">
					{!! Form::label('leaf_house_id', App\Language::trans('House No.'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						{!! Form::select('leaf_house_id', App\PowerMeterModel\MeterRegister::houses_combobox(), null, ['class'=>'form-control','autofocus','onchange'=>'init_room_combobox(this)']) !!}
                        {!!$errors->first('leaf_house_id', '<label for="leaf_house_id" class="help-block error">:message</label>')!!}
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-6">
				<div class="form-group{{ $errors->has('leaf_room_id') ? ' has-error' : '' }}">
					{!! Form::label('leaf_room_id', App\Language::trans('Room No.'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						{!! Form::select('leaf_room_id', App\PowerMeterModel\MeterRegister::rooms_combobox((old('leaf_house_id') ? old('leaf_house_id'):$model->leaf_house_id)), null, ['class'=>'form-control','onchange'=>'init_room_status(this)']) !!}
                        {!!$errors->first('leaf_room_id', '<label for="leaf_room_id" class="help-block error">:message</label>')!!}
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-6">
				<div class="form-group{{ $errors->has('is_paid') ? ' has-error' : '' }}">
					{!! Form::label('is_paid', App\Language::trans('Payment Status'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						<label class="radio-inline">
							{!! Form::radio('is_paid', 'all', $model->is_paid == 'all' ? true:false) !!} {{App\Language::trans('All')}}
						</label>
						<label class="radio-inline">
							{!! Form::radio('is_paid', '0', $model->is_paid == 0 ? true:false) !!} {{App\Language::trans('Outstanding')}}
						</label>
						<label class="radio-inline">
							{!! Form::radio('is_paid', '1', $model->is_paid == 1 ? true:false) !!} {{App\Language::trans('Paid')}}
						</label>
                        {!!$errors->first('is_paid', '<label for="is_paid" class="help-block error">:message</label>')!!}
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
						<label class="radio-inline">
							{!! Form::radio('export_by', 'excel', false, ['id'=>'export_by_excel']) !!} {{App\Language::trans('Excel')}}
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
				<button type="submit" class="btn btn-primary" id='submit_report_button'><i class="fa fa-search fa-fw"></i>{{App\Language::trans('Search')}}</button>
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
							<th class="text-center">{{App\Language::trans('Room No')}}</th>
							<th class="text-center">{{App\Language::trans('Last Meter Reading')}}</th>
							<th class="text-center">{{App\Language::trans('Current Meter Reading')}}</th>
							<th class="text-center">{{App\Language::trans('Payment Status')}}</th>
							<th class="text-center">{{App\Language::trans('Amount')}}</th>
						</tr>
					</thead>
					<tbody>
						@foreach($listing as $index => $row)
							<tr>
								<td class="text-center">{{$index+1}}</td>
								<td class="text-center">{{$row->document_no}}</td>
								<td class="text-center">{{$setting->convert_room_no($model->leaf_room_id, $rooms)}}</td>
								<td class="text-center">{{$row->last_meter_reading}}</td>
								<td class="text-center">{{$row->current_meter_reading}}</td>
								<td class="text-center">{{$row->is_paid ? App\Language::trans('Paid'):App\Language::trans('Outstanding')}}</td>
								<td class="text-center">{{$row->total_amount}}</td>
							</tr>
							@php  $total += $row->total_amount; @endphp
						@endforeach
					</tbody>
					<tfoot>
						<tr>
							<td class="text-right" colspan="6">{{App\Language::trans('Total')}}:</td>
							<td class="text-center">{{number_format($total, 2)}}</td>
						</tr>
					</tfoot>
				</table>
			</div>
		</div>
		<div class="box-footer">
		</div>
	</div>
@elseif(count($listing) == 0 && $is_search_result == true)
	@include('commons.report_modules.no_data_found')
@endif
@stop
@section('script')
@stop