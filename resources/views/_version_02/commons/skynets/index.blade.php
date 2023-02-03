@extends('_version_02.commons.layouts.admin')
@section('content')
@include('_version_02.commons.layouts.partials._alert')

<!-- <section class="hk-sec-wrapper">
    <section class="hk-sec-wrapper">
    <h5 class="hk-sec-title">{{App\Language::trans('Import Delivery Pricing Data')}}</h5><hr>
    
         <form action="{{ route('import') }}" method="POST" enctype="multipart/form-data" middleware = 'csrf'>
       			
                <input type="file" name="file" class="form-control">
                <br>
                <button class="btn btn-success">{{App\Language::trans('Import')}}</button>

            </form>
	</section>
</section> -->

<section class="hk-sec-wrapper">
    <section class="hk-sec-wrapper">
    <h5 class="hk-sec-title">{{App\Language::trans('Check by tracking number')}}</h5><hr>
    
    {!!Form::model($model, ['class'=>'form-horizontal','method'=>'post'])!!}
                <div class="form-group{{ $errors->has('url') ? ' has-error' : '' }}">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <input name="Check" value="Track" class="btn btn-success loading-label" type="submit">
                        </div>
                        {!! Form::text('tracking_number', null, ['id'=>'url', 'rows'=>'1000' , 'cols'=>'80','class'=>'form-control','placeholder'=>App\Language::trans('Tracking No. , If more than one item want to check, please separate using "," , e.g. 238289437072,238289437073,238289437072') , 'aria-describedby'=>'basic-addon1' ,'aria-label'=>'' ]) !!}
                        {!!$errors->first('tracking_number', '<label for="url" class="help-block error">:message</label>')!!}
                    </div>
                </div>
                <p>{{App\Language::trans('Testing AWBNo : 238289437072.')}} <br>
                </p> <br> <br>
    {!!Form::close()!!}

    @if(isset($result))

  		    <hr><h5 class="hk-sec-title">{{App\Language::trans('Result')}}</h5><hr>
		    <div class="row">
		        <div class="col-sm">
		            <div class="table-wrap">
		                <table id="leaf_data_table" class="table tablesaw table-bordered table-hover mb-0 w-100 pb-30" data-tablesaw-minimap>
		                    <thead>
		                        <tr>
		                            @php $priority_counter = 1 ;
		                            	 $columns = array();
		                            @endphp
		                            	<th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="0">#</th>
		                            @foreach ($result as $row) 
		                            	 @foreach ($row as $key => $value) 
		                            	 	@if($key == 'AWBNumber')
		                            	 		<th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="{{$priority_counter}}">{{App\Language::trans('AWB Number')}}</th>
		                            	 	@else
		                            	 		@php
		                            	 			 $word = '';
		                            	 			 $word_array = preg_split('/.(?=[A-Z])/',lcfirst($key));
		                            	 			 foreach($word_array as $row){
		                            	 			 	$word = $word.' '.$row;
		                            	 			 }
		                            	 		@endphp

		                            	 		<th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="{{$priority_counter}}">{{App\Language::trans(trim($word))}}</th>
		                            	 	@endif
			                            	@php $priority_counter ++ ; @endphp
			                             @endforeach
										@php  break; @endphp
									@endforeach
		                        </tr>
		                    </thead>
		                    <tbody>
		                        @php $priority_counter = 1 ;
		                        	 $index=1;
		                        @endphp
		                        @foreach (array_reverse($result) as $row)

		                        	
		                        	@if($row['Description'] == 'Data not found')
		                        		<tr>
			                        		<td class="text-center">{{$index}}</td>
			                        		<td class="text-center">{{$row['AWBNumber']}}</td>
											<td class="text-center" colspan="5">{{$row['Description']}}</td>
										</tr>
		                        	@else
		                        		 <tr>
			                        		<td class="text-center">{{$index}}</td>
											 @foreach ($row as $key => $value) 
												<td class="text-center">{{$value}}</td>
											 @endforeach
										</tr>
		                        	@endif
			                       
									  @php $index++; @endphp
								@endforeach

		                    </tbody>
		                </table>
		            </div>
		        </div>
		    </div>
		@endif
	</section>
</section>


<section class="hk-sec-wrapper">
    <section class="hk-sec-wrapper">
    <h5 class="hk-sec-title">{{App\Language::trans('Domestic Rate Check')}}</h5><hr>
    
    @if(isset($price_result))
	<div class="alert alert-success alert-dismissible">
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
		<i id="alert_msg" class="icon fa fa-{{session(App\Setting::session_alert_icon)}}"></i>
		{{$price_result}}
	</div>
	@endif


    {!!Form::model($model, ['class'=>'form-horizontal','method'=>'post'])!!}

    	<div class="row">
			<div class="col-md-12">
				<div class="form-group{{ $errors->has('type') ? ' has-error' : '' }}">
					{!! Form::label('type', App\Language::trans('Document Type'), ['class'=>'control-label col-md-2']) !!}
					<div class="col-md-12">
						 <div class="row">	
						 	<div class="col-md-3">
							    <div class="custom-control custom-radio">
							        <input type="radio" id="type_on" name="type" value='document'  class="custom-control-input" checked>
							        <label class="custom-control-label" for="type_on">{{App\Language::trans('Document')}}</label>
							    </div>
							</div>
							<div class="col-md-3">
							    <div class="custom-control custom-radio">
							         <input type="radio" id="type_off" name="type" value='parcel' class="custom-control-input">
							        <label class="custom-control-label" for="type_off">{{App\Language::trans('Parcel')}}</label>
							    </div>
							</div>
						 </div>
						 {!!$errors->first('type', '<label for="type" class="help-block error">:message</label>')!!}
					</div>
				</div>
			</div>
		</div>

    	<div class="row">
			<div class="col-md-6">
				<div class="form-group{{ $errors->has('deliver_pricing_table_id') ? ' has-error' : '' }}">
					{!! Form::label('deliver_pricing_table_id', App\Language::trans('State'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						{!! Form::select('deliver_pricing_table_id', App\DeliveryPricingTable::combobox(), null, ['class'=>'form-control','required']) !!}
                        {!!$errors->first('deliver_pricing_table_id', '<label for="deliver_pricing_table_id" class="help-block error">:message</label>')!!}
					</div>
				</div>
			</div>

			<div class="col-md-6">
				<div class="form-group{{ $errors->has('code') ? ' has-error' : '' }}">
					{!! Form::label('code', App\Language::trans('Code'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						{!! Form::number('code', null, ['class'=>'form-control','required']) !!}
                        {!!$errors->first('code', '<label for="code" class="help-block error">:message</label>')!!}
					</div>
				</div>
			</div>
		</div>

    	
		<h6 class="hk-sec-title">{{App\Language::trans('Item detail')}}</h6><hr>
			<div class="row">
				<div class="col-md-12">
					{!! Form::label('dimension', App\Language::trans('Dimension in cm (L x W x H)'), ['class'=>'control-label col-md-12']) !!}
     		  	</div>
     		  	<div class="col-md-12">
     		  		<div class="form-group{{ $errors->has('dimension') ? ' has-error' : '' }}">
		     		  	<div class="col-md-3">
							{!! Form::number('length', null, ['id'=>'length', 'rows'=>'10' , 'cols'=>'80','class'=>'form-control','placeholder'=>'Length' , 'autocomplete'=>'off' , 'onblur'=>'init_calculate_volumetric_weight();']) !!}
		                    {!!$errors->first('length', '<label for="length" class="help-block error">:message</label>')!!}
						</div>
						<div class="col-md-3">
							{!! Form::number('width', null, ['id'=>'width', 'rows'=>'10' , 'cols'=>'80','class'=>'form-control','placeholder'=>'Width' , 'autocomplete'=>'off' , 'onblur'=>'init_calculate_volumetric_weight();']) !!}
		                    {!!$errors->first('width', '<label for="width" class="help-block error">:message</label>')!!}
						</div>
						<div class="col-md-3">
							{!! Form::number('height', null, ['id'=>'height', 'rows'=>'10' , 'cols'=>'80','class'=>'form-control','placeholder'=>'Height' , 'autocomplete'=>'off' , 'onblur'=>'init_calculate_volumetric_weight();']) !!}
		                    {!!$errors->first('height', '<label for="height" class="help-block error">:message</label>')!!}
						</div>
					</div> 
				</div>
			</div>

            <div class="form-group{{ $errors->has('volumetric_weight') ? ' has-error' : '' }}">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <input name="Check" value="Submit" class="btn btn-success" type="submit">
                      
                    </div>
                    {!! Form::text('volumetric_weight', null, ['id'=>'volumetric_weight', 'rows'=>'1000' , 'readonly', 'cols'=>'80','class'=>'form-control','placeholder'=>App\Language::trans('Volumetric Weight in KG') , 'aria-describedby'=>'basic-addon1' ,'aria-label'=>'' ]) !!}
                    {!!$errors->first('volumetric_weight', '<label for="volumetric_weight" class="help-block error">:message</label>')!!}
                </div>
            </div>
    {!!Form::close()!!}
	</section>
</section>



@endsection
@section('script')
@endsection