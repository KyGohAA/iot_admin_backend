@extends('utility_charges.layouts.web_apps')
@section('content')
{!! Form::model($model, ['class'=>'form-horizontal','method'=>'get']) !!}
<div class="panel panel-default">
	<div class="panel-heading">
		<h4 class="panel-title">{{App\Language::trans('Filter By')}}</h4>
	</div>
	<div class="panel-body">
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
				<div class="form-group{{ $errors->has('room_id') ? ' has-error' : '' }}">
					{!! Form::label('room_id', App\Language::trans('Room No.'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						{!!Form::select('room_id', App\LeafAPI::get_self_houses(), null, ['class'=>'input-room'])!!}
                        {!!$errors->first('room_id', '<label for="room_id" class="help-block error">:message</label>')!!}
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
                        {!!$errors->first('export_by', '<label for="export_by" class="help-block error">:message</label>')!!}
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="panel-footer">
		<div class="row">
			<div class="col-md-offset-2 col-md-10">
				<button type="submit" class="btn btn-primary"><i class="fa fa-search fa-fw"></i>{{App\Language::trans('Search')}}</button>
			</div>
		</div>
	</div>
</div>
{!! Form::close() !!}
@if(count($listing))
	<div class="panel panel-default">
		<div class="panel-heading">
			<h4 class="panel-title">{{App\Language::trans('Listing Information')}}</h4>
		</div>
		<div class="panel-body">
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
								<td class="text-center">{{strtotime($row->due_date) <= strtotime('now') ? App\Language::trans('Outstanding'):App\Language::trans('Unpaid')}}</td>
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
		<div class="panel-footer">
		</div>
	</div>
@endif
@stop
@section('script')
@stop