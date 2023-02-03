@extends('commons.layouts.admin')
@section('content')
{!! Form::model($model, ['class'=>'form-horizontal']) !!}
@include('_version_02.commons.layouts.partials._alert')
<div class="box">
	<div class="box-header with-border">
		<h3 class="box-title">{{App\Language::trans('Detail Form')}}</h3>
		<div class="box-tools pull-right">
			<a href="{{action('UMeterRegistersController@getNew')}}" class="btn btn-block btn-info">
				<i class="fa fa-file"></i> {{App\Language::trans('New File')}}
			</a>
		</div>
	</div>
	<div class="box-body">
		<div class="row">
			<div class="col-md-6">
				<div class="form-group{{ $errors->has('leaf_house_id') ? ' has-error' : '' }}">
					{!! Form::label('leaf_house_id', App\Language::trans('House No'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						<p class="form-control-static">{{$model->convert_house_no($model->leaf_room_id, $rooms)}}</p>
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group{{ $errors->has('leaf_room_id') ? ' has-error' : '' }}">
					{!! Form::label('leaf_room_id', App\Language::trans('Room No'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						<p class="form-control-static">{{$model->convert_room_no($model->leaf_room_id, $rooms)}}</p>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-6">
				<div class="form-group{{ $errors->has('utility_charge_id') ? ' has-error' : '' }}">
					{!! Form::label('utility_charge_id', App\Language::trans('Usage Charge'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						<p class="form-control-static">{{$model->display_relationed('utility_charge_id', 'name')}}</p>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-6">
				<div class="form-group{{ $errors->has('account_no') ? ' has-error' : '' }}">
					{!! Form::label('account_no', App\Language::trans('Account No'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						<p class="form-control-static">{{$model->account_no}}</p>
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group{{ $errors->has('contract_no') ? ' has-error' : '' }}">
					{!! Form::label('contract_no', App\Language::trans('Contact No'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						<p class="form-control-static">{{$model->contract_no}}</p>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-6">
				<div class="form-group{{ $errors->has('deposit') ? ' has-error' : '' }}">
					{!! Form::label('deposit', App\Language::trans('Deposit'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						<p class="form-control-static">{{$model->getDouble($model->deposit)}}</p>
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group{{ $errors->has('meter_class') ? ' has-error' : '' }}">
					{!! Form::label('meter_class', App\Language::trans('Meter Class'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						<p class="form-control-static">{{$model->display_relationed('meter_class', 'name')}}</p>
					</div>
				</div>
			</div>
		</div>
		<hr>
		<div class="row">
			<div class="col-md-6">
				<div class="form-group{{ $errors->has('ip_address') ? ' has-error' : '' }}">
					{!! Form::label('ip_address', App\Language::trans('IP Address'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						<p class="form-control-static">{{$model->ip_address}}</p>
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group{{ $errors->has('meter_id') ? ' has-error' : '' }}">
					{!! Form::label('meter_id', App\Language::trans('Meter ID'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						<p class="form-control-static">{{$model->meter_id}}</p>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="box">
	<div class="box-header with-border">
		<h3 class="box-title">{{App\Language::trans('Utility Charge Table')}}</h3>
	</div>
	<div class="box-body">
		<div class="table-responsive">
			<table id="prices" class="table table-bordered">
				<thead>
					<tr>
						<th class="text-center col-md-1">#</th>
						<th class="text-center col-md-4">{{App\Language::trans('Unit Started')}}</th>
						<th class="text-center col-md-4">{{App\Language::trans('Unit Ended')}}</th>
						<th class="text-center col-md-1">{{App\Language::trans('GST')}}</th>
						<th class="text-center col-md-1">{{App\Language::trans('Unit Price')}}</th>
					</tr>
				</thead>
				<tbody>
					@foreach($model->display_relation_child('utility_charge_id','prices') as $row)
						<tr>
							<td class="text-center col-md-1">{{$i++}}</td>
							<td class="text-center col-md-4">{{$row->started}}</td>
							<td class="text-center col-md-4">{{$row->ended}}</td>
							<td class="text-center col-md-1">{{$row->is_gst ? App\Language::trans('Yes'):App\Language::trans('No')}}</td>
							<td class="text-center col-md-1">{{$settings->getDouble($row->unit_price)}}</td>
						</tr>
					@endforeach
				</tbody>
			</table>
		</div>
	</div>
</div>
<div class="box">
	<div class="box-header with-border">
		<h3 class="box-title">{{App\Language::trans('Address Detail')}}</h3>
	</div>
	<div class="box-body">
		<div class="row">
			<div class="col-md-6">
				<h4 class="text-center">{{App\Language::trans('Billing Information')}}</h4>
				<hr>
				<div class="form-group{{ $errors->has('billing_address1') ? ' has-error' : '' }}">
					{!! Form::label('billing_address1', App\Language::trans('Address'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						<p class="form-control-static">{{$model->billing_address1}}</p>
						<p class="form-control-static">{{$model->billing_address2}}</p>
                        {!!$errors->first('billing_address1', '<label for="billing_address1" class="help-block error">:message</label>')!!}
					</div>
				</div>
				<div class="form-group{{ $errors->has('billing_postcode') ? ' has-error' : '' }}">
					{!! Form::label('billing_postcode', App\Language::trans('Postcode'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						<p class="form-control-static">{{$model->billing_postcode}}</p>
					</div>
				</div>
				<div class="form-group{{ $errors->has('billing_country_id') ? ' has-error' : '' }}">
					{!! Form::label('billing_country_id', App\Language::trans('Country'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						<p class="form-control-static">{{$model->display_relationed('billing_country_id', 'name')}}</p>
					</div>
				</div>
				<div class="form-group{{ $errors->has('billing_state_id') ? ' has-error' : '' }}">
					{!! Form::label('billing_state_id', App\Language::trans('State'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						<p class="form-control-static">{{$model->display_relationed('billing_state_id', 'name')}}</p>
					</div>
				</div>
				<div class="form-group{{ $errors->has('billing_city_id') ? ' has-error' : '' }}">
					{!! Form::label('billing_city_id', App\Language::trans('City'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						<p class="form-control-static">{{$model->display_relationed('billing_city_id', 'name')}}</p>
					</div>
				</div>
			</div>
			<div class="col-md-6">
			</div>
		</div>
	</div>
</div>
<div class="box">
	<div class="box-header with-border">
		<h3 class="box-title">{{App\Language::trans('More Detail Form')}}</h3>
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
	</div>
	<div class="box-footer">
		<div class="row">
			<div class="col-md-offset-2 col-md-10">
				<a href="{{action('UMeterRegistersController@getIndex')}}" class="btn btn-danger"><i class="fa fa-ban fa-fw"></i>{{App\Language::trans('Close')}}</a>
			</div>
		</div>
	</div>
</div>
{!! Form::close() !!}
@endsection
@section('script')
@endsection