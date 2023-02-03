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
							{!! Form::text('date_started', null, ['class'=>'form-control','required']) !!}
	                        {!!$errors->first('date_started', '<label for="date_started" class="help-block error">:message</label>')!!}
						</div>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group{{ $errors->has('date_ended') ? ' has-error' : '' }}">
						{!! Form::label('date_ended', App\Language::trans('Date Ended'), ['class'=>'control-label col-md-4']) !!}
						<div class="col-md-8">
							{!! Form::text('date_ended', null, ['class'=>'form-control','required']) !!}
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
						{!! Form::select('leaf_house_id', App\PowerMeterModel\MeterRegister::houses_combobox(), null, ['class'=>'form-control','required','onchange'=>'init_room_combobox(this)']) !!}
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
                        <!-- ,'required' -->
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
				<button type="submit" class="btn btn-primary" target="_blank" id='submit_report_button'><i class="fa fa-search fa-fw"></i>{{App\Language::trans('Search')}}</button>
			</div>
		</div>
	</div>
</div>
{!! Form::close() !!}
@if(count($listing) > 0)
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
							<th class="text-center">{{App\Language::trans('Date')}}</th>
							<th class="text-center">{{App\Language::trans('From Time')}}</th>
							<th class="text-center">{{App\Language::trans('To Time')}}</th>
							<th class="text-center">{{App\Language::trans('Last Meter Reading')}}</th>
							<th class="text-center">{{App\Language::trans('Current Meter Reading')}}</th>
							<th class="text-center">{{App\Language::trans('Current Usage')}}</th>
						</tr>
					</thead>
					<tbody>
						    @php  
						    	  ini_set('max_execution_time', 0);
						    	  $total = 0;
						    	  $grand_total = 0;
						    	  $total_payable_amount = 0 ;
						    	  $setting = new App\Setting();
						    @endphp
		
					        @foreach($houses_detail as $house)
					            @php  $total = 0; @endphp
					            <tr style="background-color: #ddd;">
									<td class="text-left" colspan="7">{{App\Language::trans('House')}} : {{$house['house_unit']}}</td>
								</tr>
							
					            
					            @foreach($house['house_rooms'] as $room)

					                @if($leaf_room_id !=0)
					                    @if($room['id_house_room'] != $leaf_room_id)
					                    	@php
					                     	    continue;
					                        @endphp
					                    @endif
					                @endif

					                @php
						                $isMeterRegister   = false;
						                $isFirstRoomHeader = true;
						                $rowNo             = 0;
						                $index             = 0;
						            @endphp

					                @foreach($listing as $row) 
					                    
					                    @if($row->meter_register_id == $room['meter']['id']) 
					                        @if($isFirstRoomHeader == true) 
					                            <tr>
						                            <td class="text-center">{{App\Language::trans('Room') . ' : ' . $room['house_room_name']}}</td>
						                            <td class="text-center">{{App\Language::trans('Meter Id').' : '.$room['meter']['id']}}</td>
					                            </tr>
					                         
					                        @endif
					                        
					                        @php
						                        $payable_amount    = App\Setting::calculate_utility_fee($row->total_usage);
						                        $isFirstRoomHeader = false;
						                        $isMeterRegister   = true;
						                        $total_payable_amount += $payable_amount;
						                    @endphp
					                        
					                        <!-- table of the listing -->
					                        <!-- header of the table -->
					                        <tr>
												 <td class="text-center">{{($index + 1)}}</td>
									             <td class="text-center">{{$setting->convert_encoding($setting->getDate($row->current_date))}}</td>
									             <td class="text-center">{{$setting->convert_encoding($row->time_started)}}</td>
									             <td class="text-center">{{$setting->convert_encoding($row->time_ended)}}</td>
									             <td class="text-right">{{$setting->convert_encoding($row->last_meter_reading)}}</td>
									             <td class="text-right">{{$setting->convert_encoding($row->current_meter_reading)}}</td>
									             <td class="text-right">{{$setting->convert_encoding($row->current_usage)}}</td>
									        </tr>

					                        @php
					                        	$total += $row->current_usage;
					                        	$grand_total +=  $row->current_usage;
					                        	$index++;
					                        @endphp

					                    @endif
					                    
					                @endforeach
					                
					                @if($isMeterRegister == false) 
					                    <tr>
					                    	<td class="text-center">{{App\Language::trans('Room') . " " . $room['house_room_name']}}</td>
					                    	<td class="text-center">{{App\Language::trans(App\Setting::SUNWAY_NO_METER_FOUND_LABEL)}}</td>
					                    </tr>         
					                @else 
					                	<tr>
					                    	<td colspan="6" class="text-right">{{$setting->convert_encoding(App\Language::trans('Sub-total')) . ' : '}}</td>
					                   		<td class="text-right">{{$setting->convert_encoding($setting->getDouble($total))}}</td>
					                    </tr>
					                    <tr><tr>
					                @endif
					    
				                	@php
				                		$isMeterRegister = false;
				                	@endphp
					        @endforeach
					    @endforeach


					</tbody>
					<tfoot>
						<hr>
						<br>
						<tr>
							<td class="text-right" colspan="6">{{App\Language::trans('Total')}}:</td>
							<td class="text-right">{{number_format($grand_total, 2)}}</td>
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