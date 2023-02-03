@extends('_version_02.commons.layouts.admin')
@section('content')
{!! Form::model($model, ['class'=>'form-horizontal','method'=>'get']) !!}
<section class="hk-sec-wrapper">
    <h5 class="hk-sec-title">{{App\Language::trans('Filter By')}}</h5><hr>
   		

   		<div class="row">
			<div class="col-md-6">
				<div class="form-group{{ $errors->has('month_started') ? ' has-error' : '' }}">
					{!! Form::label('month_started', App\Language::trans('From Month'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						{!! Form::select('month_started', App\PowerMeterModel\MeterInvoice::previous_one_year_combobox(), (old('month_started') ? old('month_started'):$model->three_month_pass()), ['class'=>'form-control','autofocus']) !!}
                        {!!$errors->first('month_started', '<label for="month_started" class="help-block error">:message</label>')!!}
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group{{ $errors->has('month_ended') ? ' has-error' : '' }}">
					{!! Form::label('month_ended', App\Language::trans('To Month'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						{!! Form::select('month_ended', App\PowerMeterModel\MeterInvoice::previous_one_year_combobox(), (old('month_ended') ? old('month_ended'):$model->last_month()), ['class'=>'form-control']) !!}
                        {!!$errors->first('month_ended', '<label for="month_ended" class="help-block error">:message</label>')!!}
					</div>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-md-12">
				<div class="form-group{{ $errors->has('leaf_house_id') ? ' has-error' : '' }}">
					{!! Form::label('leaf_house_id', App\Language::trans('House No.'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-12">
						{!! Form::select('leaf_house_id', App\PowerMeterModel\MeterRegister::houses_combobox(), null, ['class'=>'form-control','required','onchange'=>'init_room_combobox(this)']) !!}
                        {!!$errors->first('leaf_house_id', '<label for="leaf_house_id" class="help-block error">:message</label>')!!}
					</div>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-md-12">
				<div class="form-group{{ $errors->has('leaf_room_id') ? ' has-error' : '' }}">
					{!! Form::label('leaf_room_id', App\Language::trans('Room No.'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-12">
						{!! Form::select('leaf_room_id', App\PowerMeterModel\MeterRegister::rooms_combobox((old('leaf_house_id') ? old('leaf_house_id'):$model->leaf_house_id)), null, ['class'=>'form-control','onchange'=>'init_room_status(this)']) !!}
                        {!!$errors->first('leaf_room_id', '<label for="leaf_room_id" class="help-block error">:message</label>')!!}
                        <!-- ,'required' -->
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-6">
				<div class="form-group{{ $errors->has('export_by') ? ' has-error' : '' }}">
					{!! Form::label('export_by', App\Language::trans('Exported By'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-12">
						 <div class="row">	
						 	<div class="col-md-3">
							    <div class="custom-control custom-radio">
							        <input type="radio" id="export_by_pdf" name="html" checked class="custom-control-input">
							        <label class="custom-control-label" for="export_by_pdf">{{App\Language::trans('HTML')}}</label>
							    </div>
							</div>
							<div class="col-md-3">
							    <div class="custom-control custom-radio">
							        <input type="radio" id="export_by_html" name="pdf"  class="custom-control-input">
							        <label class="custom-control-label" for="export_by_html">{{App\Language::trans('PDF')}}</label>
							    </div>
							</div>
							<div class="col-md-3">
							    <div class="custom-control custom-radio">
							        <input type="radio" id="export_by_excel" name="export_by"  class="custom-control-input">
							        <label class="custom-control-label" for="export_by_excel">{{App\Language::trans('Excel')}}</label>
							    </div>
							</div>

						 </div>
						 {!!$errors->first('export_by', '<label for="export_by" class="help-block error">:message</label>')!!}
					</div>
				</div>
			</div>
		</div>

</section>
@include('_version_02.commons.layouts.partials._form_floating_footer_report')
{!! Form::close() !!}
@if(count($listing))
	<section class="hk-sec-wrapper">
    <h5 class="hk-sec-title">{{App\Language::trans('Result')}}</h5><hr>

			<div class="table-responsive">
				<table class="table table-bordered table-hover">
					<thead>
						<tr>
							<th class="text-center">{{App\Language::trans('Month')}}</th>
							<th class="text-center">{{App\Language::trans('Total Hours')}}</th>
							<th class="text-center">{{App\Language::trans('Avg. kW')}}</th>
							<th class="text-center">{{App\Language::trans('Max. kW')}}</th>
							<th class="text-center">{{App\Language::trans('Min. kW')}}</th>
							<th class="text-center">{{App\Language::trans('Total kWh')}}</th>
							<th class="text-center">{{App\Language::trans('Total Charges (RM)')}}</th>
						</tr>
					</thead>
					<tbody>
					@php  
						$x=0;
						$total_payable_amount = 0;
					@endphp
	
					@foreach($houses_detail as $house)
						<tr style="background-color: #ddd;">
							<td class="text-left" colspan="7">{{App\Language::trans('House')}} : {{$house['house_unit']}}</td>
						</tr>
						@foreach($house['house_rooms'] as $room)

							@php  
								$isMeterRegister = false;
								$isFirstRoomHeader = true;
								$rowNo =0;
								$room_subtotal =0;
								$total_payable_amount = 0;
								$room_subtotal_total_usage  =0;
							@endphp
							
							@foreach($listing as $row)
								@if(!isset($room['meter']['id']))

										<tr>
											<td class="text-left" colspan="1">{{App\Language::trans('Room')}} : {{$room['house_room_name']}}  </td>
											<td class="text-left" colspan="6"><span class='label label-danger'> {{App\Language::trans(App\Setting::SUNWAY_NO_METER_FOUND_LABEL)}} </span></td>
										</tr>
										@php   break;	@endphp

								@elseif($row->meter_register_id == $room['meter']['id'])
									@if($isFirstRoomHeader == true)
										<tr style="background-color: #FFFEFE;">
											<td class="text-left" colspan="1">{{App\Language::trans('Room')}} : {{$room['house_room_name']}}  </td>
											<td  class="text-left"  colspan="6" >{{App\Language::trans('Meter ID')}} : <span class='label label-success'> {{$listing[$rowNo]->meter_register_id}} </span></td>
										</tr>
									@endif
									
									@php  				
										$payable_amount = App\Setting::calculate_utility_fee($row->total_usage);
										$isFirstRoomHeader = false;			
										$isMeterRegister = true;
										$total += $row->total_usage; 
										$room_subtotal += $payable_amount;
										$room_subtotal_total_usage += $row->total_usage;

									@endphp		
										<tr>
											<td class="text-center">{{date('m-Y', strtotime($row->current_date))}}</td>
											<td class="text-center">{{$row->total_hours}}</td>
											<td class="text-center">{{number_format($row->average_usage,9)}}</td>
											<td class="text-center">{{number_format($row->max_usage,9)}}</td>
											<td class="text-center">{{number_format($row->min_usage,9)}}</td>
											<td class="text-center">{{number_format($row->total_usage,9)}}</td>
											<td class="text-right">{{number_format($payable_amount,2)}}</td>
										</tr>
										
								@endif
				
								@php  
								$rowNo++;
								@endphp
								
								
							@endforeach
							
							@if(isset($room['meter']['id']))
								<tr>
									<td class="text-right" colspan="5">{{App\Language::trans('Room Subtotal')}}</td>
									<td class="text-center">{{number_format($room_subtotal_total_usage, 9)}}</td>
									<td class="text-right">{{number_format($room_subtotal, 2)}}</td>
								</tr>
							@endif
									
							@php  
								$x = $x + $room_subtotal;
								$isMeterRegister = false;
							@endphp
							
						@endforeach
					@endforeach

				
						
					</tbody>
					<tfoot>
						<tr style="background-color: #ddd;">
							<td class="text-right" colspan="5">{{App\Language::trans('Total')}}:</td>
							<td class="text-center">{{number_format($total, 9)}}</td>
							<td class="text-right">{{number_format($x,2)}}</td>
						</tr>
					</tfoot>
				</table>
			</div>
			
	</section>

@elseif(count($listing) == 0 && $is_search_result == true)
	@include('_version_02.commons.report_modules.no_data_found')
@endif
@stop
@section('script')
$(".input-daterange").datepicker({
	format: "dd-mm-yyyy",
});
@stop