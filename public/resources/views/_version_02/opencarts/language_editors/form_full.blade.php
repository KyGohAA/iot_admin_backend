@extends('_version_02.commons.layouts.admin')
@section('content')


@include('_version_02.commons.layouts.partials._alert')
@include('_version_02.opencarts.languages.partials.search_bar')

{!! Form::model(null, ['class'=>'form-horizontal']) !!}
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
								<th class="text-left" style="word-wrap: break-word;max-width: 150px;">
									{{App\Language::trans('Name')}}
								</th>
								<th class="text-left" style="word-wrap: break-word;max-width: 150px;">
									{{App\Language::trans('Referral Value')}}<br>					
								</th>
								@php $language_listing  = App\OpencartLanguageTranslator::get_oc_language_list(); @endphp
								@foreach ($language_listing as $key => $language) 
									
									<th class="text-left" >
										{{$language}}				
									</th>
								@endforeach
							</tr>
						</thead>
						<tbody>
								@php $i = 1; @endphp
								@foreach ($listing as $row) 
									@php $translation_value = (array)json_decode($row['translation_value']); 
									//dd($translation_value);
									@endphp
									<tr>
										<td class="text-left">{{$i}}</td>
										<td class="text-left" style="word-wrap: break-word;max-width: 150px;"><lable style='width:50px;'>{{$row['translation_key']}}</lable></td>
										<td class="text-left" style="word-wrap: break-word;max-width: 150px;"><lable style='width:50px;'>{{(isset($translation_value['en-gb']) ? $translation_value['en-gb'] : '')}}</lable></td>
										<div class='row'>
											@foreach ($language_listing as $key => $language) 
											
												<td>
													<div class="col-md-3">

														{!! Form::textarea('translation_listing['.$row['id'].']['.$row['translation_key'].']['.$language.']',trim((isset($translation_value[$language]) ? $translation_value[$language] : '')), ['id'=>'translation_listing['.$row['id'].']['.$row['translation_key'].']['.$language.']','class'=>'form-control','autofocus' ,'style' => 'width:250px;']) !!}
													</div>
												</td>

											
											@endforeach
										</div>
									</tr>
									@php $i++; @endphp
								@endforeach			
						</tbody>
					</table>
				</div>
			
		
</section>

@include('_version_02.commons.layouts.partials._oc_translate_form_floaring_footer_standard')
{!! Form::close() !!}
@endsection
@section('script')
@endsection