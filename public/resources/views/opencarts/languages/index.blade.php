@extends('billings.layouts.admin')
@section('content')
@include('billings.layouts.partials._alert')
<!-- Default box -->
<div class="box">
	<div class="box-header with-border">
		<h3 class="box-title">{{App\Language::trans('Listing Information')}} &nbsp; <medium class="label pull-right bg-green">{{App\OpencartLanguageTranslator::get_current_oc_language_by_file_path($current_directory)}}</medium> </h3>
		<div class="box-tools pull-right">
			<a href="{{action('OpencartLanguageTranslatorsController@getIndex')}}" class="btn btn-block btn-info">
					<i class="fa fa-file fa-fw"></i> {{App\Language::trans('Main page')}}
			</a>
		</div>
	</div>


	<div class="box-body">
		<div class="table-responsive">
			<table id="leaf_data_table" class="table">
					<thead>
	                    <tr>
	                        @foreach($header_cols as $col)               
	                                <th class="text-center">{{App\Language::trans(ucwords(str_replace('_', ' ', $col)))}}</th>
	                        @endforeach
	                    </tr>
	                </thead>
				<tbody>
					@foreach($mirror_listing as $row)
					@php $is_exist = false;
					     $row_to_copy = "" ; 
					@endphp
					<tr>	

					 @if(strpos( $row, 'php' ) !== false)	
							<td>
								 <a class="btn btn-app" target="_blank" href="{{action('OpencartLanguageTranslatorsController@getEdit', [$row])}}">
		               				 <i class="fa fa-file-o"></i> {{$row}}
		             			 </a>
		             		</td>   
					  @else
							<td>
								 <a class="btn btn-app" href="{{action('OpencartLanguageTranslatorsController@getNext', [$row])}}">
		               				 <i class="fa fa-envelope-o"></i> {{$row}}
		             			 </a>
		             		</td>   
					  @endif

					  @foreach($listing as $temp_row)
						   @if(strcmp($temp_row,$row) == 0)
						   		 @php $is_exist = true; 	 
						   		 @endphp
							  	 @if(strpos( $row, 'php' ) !== false)	
									<td>
										 <a class="btn btn-app" target="_blank" href="{{action('OpencartLanguageTranslatorsController@getEdit', [$row])}}">
				               				 <i class="fa fa-file-o"></i> {{$row}}
				             			 </a>
				             		</td>   
								  @else
										<td>
											 <a class="btn btn-app" href="{{action('OpencartLanguageTranslatorsController@getNext', [$row])}}">
					               				 <i class="fa fa-envelope-o"></i> {{$row}}
					             			 </a>
					             		</td>   
								  @endif
							@endif
					  @endforeach
					  
					  @if($is_exist == false)
					  	<td>
					  		<a type="submit" href="{{action('OpencartLanguageTranslatorsController@getCopy', [$row])}}" class="btn btn-primary"><i class="fa fa-floppy-o fa-fw"></i>{{App\Language::trans('Copy')}}</a>
	             		</td>   
					  @endif


					   @if(strpos( $row, 'php' ) !== false)	
					   		 @php $last_up_at = App\OpencartLanguageTranslator::get_file_translation_progress($current_directory.'\\'.$row); 
					   			 	 
						   	 @endphp
							<td>
								 {{$last_up_at}}
		             			 
		             		</td>   
					 
					  @endif


					  </tr>
					@endforeach
				</tbody>
			</table>
		</div>
	</div>
	<!-- /.box-body -->
	<div class="box-footer text-center">
		
	</div>
	<!-- /.box-footer-->
</div>
<!-- /.box -->

@endsection
@section('script')
@endsection