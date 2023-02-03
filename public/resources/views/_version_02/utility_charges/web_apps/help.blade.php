@extends('utility_charges.layouts.web_apps')
@section('content')
<center><h3>FAQ:-</h3></center>
<hr>
<ol>
@foreach($listing as $row)
	<li>
		<p class="text-danger">{{App\Language::trans('Question')}} : {{$row->question}}</p>
		<p class="text-info">{{App\Language::trans('Answer')}} : 
			{{nl2br($row->answers)}}
		</p>
	</li>
@endforeach
</ol>
@stop
@section('script')
@stop