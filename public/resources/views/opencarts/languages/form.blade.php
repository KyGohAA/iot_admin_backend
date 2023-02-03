@extends('billings.layouts.admin')
@section('content')
{!! Form::model(null, ['class'=>'form-horizontal']) !!}
@include('billings.layouts.partials._alert')
<div class="box">
	<div class="box-header with-border">
		<h3 class="box-title">{{App\Language::trans('Detail Form')}} &nbsp; <medium class="label pull-right bg-green">{{App\OpencartLanguageTranslator::get_current_oc_language_by_file_path($current_directory)}}</medium> </h3>
		<div class="box-tools pull-right">
			<a href="{{action('OpencartLanguageTranslatorsController@getIndex')}}" class="btn btn-block btn-info">
				<i class="fa fa-file fa-fw"></i> {{App\Language::trans('Main Page')}}
			</a>
		</div>
	</div>
	<div class="box-body">
		<div class="table-responsive">
					<table id="invoice_table" class="table table-bordered table-hover" style="min-width: 1000px;">
						<thead>
							<tr>
								<th class="col-md-1">#</th>
								<th class="col-md-2 text-center">
									{{App\Language::trans('Name')}}
								</th>
								<th class="col-md-2 text-center">
									{{App\Language::trans('Referral Value')}}<br>					
								</th>

								<th class="col-md-4 text-center">
									{{App\Language::trans('Value')}}<br>					
								</th>
							</tr>
						</thead>
						<tbody>
							@php $i = 1; @endphp

								@foreach ($mirror_listing as $row) 
								<tr>
								<td>{{$i}}</td>
									@php $temp = App\OpencartLanguageTranslator::split_parameter_to_raw($row);
										 $ori_value = "";
										 $key_element = "";
									 @endphp
									@foreach ($temp as $row) 
										@php 
											$key = "" ;
											$needle = '$_[';										
										@endphp

										@if(strpos($row, $needle) !== false)
											@php 
												$key_element =trim(App\OpencartLanguageTranslator::get_key($row));
												$key = $temp;
												foreach($listing as $row){
													if(strpos($row,trim($key_element)) !== false){
														$ori_temp = App\OpencartLanguageTranslator::split_parameter_to_raw($row);

														$ori_key = trim(App\OpencartLanguageTranslator::get_key($ori_temp[0]));

														if(strcmp($ori_key,$key_element) == 0){
																$ori_value = App\OpencartLanguageTranslator::get_value($ori_temp[1]); 
														}
													}
												}	
											@endphp
											<td class="col-md-2">{{$key_element}}</td>
											
										@else
											@php 
												$element = App\OpencartLanguageTranslator::get_value($row); 
											@endphp
											<td class="col-md-2">{{$element}}</td>
											<td class="col-md-4">{!! Form::text($key_element,trim($ori_value), ['id'=>$key_element,'class'=>'form-control','autofocus','required']) !!}</td>
											
										@endif
										
										

									@endforeach


								</tr>
								@php $i++; @endphp
							@endforeach			
						</tbody>
					</table>
				</div>
	</div>
	<div class="box-footer">
		<div class="row">
			<div class="col-md-offset-2 col-md-10">
				<button type="submit" class="btn btn-primary"><i class="fa fa-floppy-o fa-fw"></i>{{App\Language::trans('Save')}}</button>
				<a href="{{action('OpencartLanguageTranslatorsController@getIndex')}}" class="btn btn-danger"><i class="fa fa-ban fa-fw"></i>{{App\Language::trans('Close')}}</a>
			</div>
		</div>
	</div>
</div>
{!! Form::close() !!}
@endsection
@section('script')
@endsection