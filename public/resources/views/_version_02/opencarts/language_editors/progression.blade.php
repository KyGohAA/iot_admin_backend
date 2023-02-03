@extends('_version_02.commons.layouts.admin')
@section('content')
@include('_version_02.commons.layouts.partials._alert')
<!-- Default box -->
<div class="box">
	<div class="box-header with-border">
		<h3 class="box-title">{{App\Language::trans('Listing Information')}}</h3>
		<div class="box-tools pull-right">
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
					@foreach($listing as $row)				
					  <tr>	
					  	<td>
							 {{$row['language']}}
	             		</td> 
	             		<td>
							 {{$row['file_name']}}
	             		</td>   
	             		<td>
							 {{$row['last_update_at']}}
	             		</td> 
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