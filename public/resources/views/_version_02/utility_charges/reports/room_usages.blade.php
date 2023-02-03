@extends('_version_02.commons.layouts.admin')
@section('content')
{!! Form::model($model, ['class'=>'form-horizontal','method'=>'get']) !!}
<section class="hk-sec-wrapper">
    <h5 class="hk-sec-title">{{App\Language::trans('Filter By')}}</h5><hr>

		<div class="row">
			<div class="col-md-12">
				<div class="form-group{{ $errors->has('leaf_house_id') ? ' has-error' : '' }}">
					{!! Form::label('leaf_house_id', App\Language::trans('Date Range'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-12">
						<input class="form-control" type="text" name="daterange"/>
                        {!!$errors->first('leaf_house_id', '<label for="daterange" class="help-block error">:message</label>')!!}
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
							        <input type="radio" id="export_by_pdf" name="export_by" checked class="custom-control-input">
							        <label class="custom-control-label" for="export_by_pdf">{{App\Language::trans('HTML')}}</label>
							    </div>
							</div>
							<div class="col-md-3">
							    <div class="custom-control custom-radio">
							        <input type="radio" id="export_by_html" name="export_by"  class="custom-control-input">
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
@if(count($listing) > 0)
	<section class="hk-sec-wrapper">
   		 <h5 class="hk-sec-title">{{App\Language::trans('Result')}}</h5><hr>
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
	</section>
@elseif(count($listing) == 0 && $is_search_result == true)
	@include('_version_02.commons.report_modules.no_data_found')
@endif
@stop
@section('script')
@stop