@extends('_version_02.iot.layouts.admin')
@section('content')
@include('_version_02.iot.layouts.partials._alert')
<div class="row clearfix">
                <div class="col-lg-12">
                    <div class="card">
                        @include('_version_02.iot.layouts.partials._index_header')
                        <div class="body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                                    <thead>
                                        <tr>
				                            @foreach($cols as $col)   
				                            		@php
						                            	if($col == 'device_profile_id'){
						                            		continue;
						                            	}      
				                            		@endphp

													@if($col == 'id')
														<th>#</th>
													@elseif($col == 'dev_eui')
														<th>#</th>
														<th>Device Eui</th>
														<th>Name</th>
													@elseif(str_contains($col, '_id'))
														<th>{{App\Language::trans(ucwords(str_replace('_id', '', $col)))}}</th>
													@else
														<th>{{App\Language::trans(ucwords(str_replace('_', ' ', $col)))}}</th>
													@endif
										
											@endforeach
											<th class="text-center">{{App\Language::trans('Action')}}</th>
                                        </tr>
                                    </thead>

                            
							
                                    <tbody>
  											@php
  												$counter = 0;
  											@endphp
					                        @foreach($model as $index => $row)
					                        	@php

					                        		$included = ['x24e124136c225107','x24e124141c147463','x24e124141c141557','x24e124148b495286','x24e124535b316056','x24e124538c019556','x24e124600c124993'];

					                        		if(isset($row['dev_eui']))
					                        		{
					                        			if(!in_array($row['dev_eui'],$included)){continue;}
					                        		}
												
													

												@endphp

					                         	<tr id="{{$row['id']}}">
													<td class="text-center">{{$counter+1}}</td>

													@foreach($row->toArray() as $key => $value)

														@php

															if(!in_array($key,$cols))
															{
																continue;
															}
														
							                            	if($key == 'device_profile_id'){
							                            		continue;
							                            	}      
				                            		

														@endphp
														@if($key == 'dev_eui')
															@php
																$dp = App\Iot\DeviceProfile::getById($row['device_profile_id']);
															@endphp
															<td class="text-center"> {{ substr($value,1,strlen($value))}}</td>
															<td class="text-center"> {{ $dp['name'] }}</td>
											

														@elseif($key == 'photo')
															<!-- <td class="text-center"><img class="img-responsive" width="50" height="50" src="{{$row->profile_jpg()}}"></td> -->
														@elseif($key == 'status')
															<td class="text-center">{{$row->display_status_string($key)}}</td>
														@elseif($key != 'user_id' && $key != 'id')
															<td class="text-center">{{$value}}</td>
														@endif
													@endforeach
													<td class="text-center">
														<a onclick="return confirm(confirmMsg)" class="loading-label" href="{{action('IOTUniversalsController@getDeviceInfo', [$row['dev_eui']])}}">{{App\Language::trans('View')}}</a>
													</td>
												</tr>
												@php
  													$counter ++;
  												@endphp
					                        @endforeach
                                           
                                                                           
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
</div>



<div class="iq-counter-icon">
	 <div class="elementor-widget-container">
		<div class="iq-counter text-left iq-counter-style-3">

		
			<div class="counter-content">
				<p class="iq-counter-info">
					<label id='iot_temperature'>Loading ...</label>
				</p>
				<h6 class="counter-title-text">Temperature</h6>
			</div>

		</div>

	</div>
</div>

<div class="iq-counter-icon">
	 <div class="elementor-widget-container">
		<div class="iq-counter text-left iq-counter-style-3">

		
			<div class="counter-content">
				<p class="iq-counter-info">
					<label id='iot_humidity'>Loading ...</label>
				</p>
				<h6 class="counter-title-text">Humidity</h6>
			</div>

		</div>

	</div>
</div>

<div class="iq-counter-icon">
	 <div class="elementor-widget-container">
		<div class="iq-counter text-left iq-counter-style-3">

			
			<div class="counter-content">
				<p class="iq-counter-info">
					<label id='iot_brightness'>Loading ...</label>
				</p>
				<h6 class="counter-title-text">Brightness</h6>
			</div>

		</div>

	</div>
</div>

<div class="iq-counter-icon">
	 <div class="elementor-widget-container">
		<div class="iq-counter text-left iq-counter-style-3">

		
			<div class="counter-content">
				<p class="iq-counter-info">
					<label id='iot_on_off_count'>Loading ...</label>
				</p>
				<h6 class="counter-title-text">Open & Closed</h6>
			</div>

		</div>

	</div>
</div>

<div class="elementor-widget-container">
		<h4 class="elementor-heading-title elementor-size-default" id='data_date_range'>Loading ...</h4>	
</div>


	

	<div class="elementor-widget-container">
			<h4 class="elementor-heading-title elementor-size-default" id='data_date_range'>Loading ...</h4>	
	</div>
				

@endsection
@section('script')
@endsection