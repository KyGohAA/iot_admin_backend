@extends('_version_02.commons.layouts.admin')
@section('content')
{!! Form::model(null, ['class'=>'form-horizontal']) !!}
@include('_version_02.commons.layouts.partials._alert')
@php 
	$language_array = array();
@endphp
<section class="hk-sec-wrapper">
    <h5 class="hk-sec-title">{{App\Language::trans('Text Detail')}}</h5><hr>
   		<div class="table-responsive">
					<table id="invoice_table" class="table table-bordered table-hover" style="min-width: 1000px;">
						<thead>
							<tr>
								<th>#</th>
								<th class="text-center">
									{{App\Language::trans('Name')}}
								</th>
								<th class="text-center">
									{{App\Language::trans('Referral Value')}}<br>					
								</th>
								@foreach ($file_to_edit_array as $row) 
									@php 
										
										$language = App\OpencartLanguageTranslator::get_current_oc_language_by_file_path($row);
										if($language == 'en-gb' || $language == 'english')
										{
											continue;
										}

										$translation_listing[$language] = array();
										array_push($language_array,$language);

									@endphp
									<th class="text-center">
										{{$language}}				
									</th>
								@endforeach
							</tr>
						</thead>
						<tbody>
								@php $i = 1; @endphp
								@foreach ($referral_listing as $row) 
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

													<!-- //get variable name -->
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
														<td>{{$key_element}}</td>

													<!-- //get item to translate -->
													@else
														@php 
															$element = App\OpencartLanguageTranslator::get_value($row); 
														@endphp
														<td>{{$element}}</td>

														@foreach($language_array as $language)
															@php
																$translation_listing[$language][$key_element] = '';
																$ori_temp = App\OpencartLanguageTranslator::split_parameter_to_raw($language_to_translate_listing[$language][0][$i-1]);
																$ori_key = trim(App\OpencartLanguageTranslator::get_key($ori_temp[0]));

																if(strcmp($ori_key,$key_element) == 0){
																		$ori_value = App\OpencartLanguageTranslator::get_value($ori_temp[1]); 
																}

															@endphp		
															<td>{!! Form::text('translation_listing['.$language.'][0]['.$key_element.']',trim($ori_value), ['id'=>'translation_listing['.$language.'][0]['.$key_element.']','class'=>'form-control','autofocus','required']) !!}</td>

														@endforeach
														
														
													@endif
													
												

											@endforeach


									</tr>
									@php $i++; @endphp
								@endforeach			
						</tbody>
					</table>
				</div>
		
</section>

@include('_version_02.commons.layouts.partials._form_floaring_footer_standard')
{!! Form::close() !!}
@endsection
@section('script')
@endsection