@extends('_version_02.commons.layouts.admin')
@section('content')
@include('_version_02.commons.layouts.partials._alert')

<section class="hk-sec-wrapper">
    <section  class="hk-sec-wrapper">
    <h5 class="hk-sec-title">{{App\Language::trans('Modules')}}</h5><hr>
	    <div class="row">

	       @foreach($listing as $row)
		   		 <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12 mb-30">
		   		 	
				        <div class="card bg-light">
				            <div class="card-header"></div>
				            <div class="card-body">
				                <a href="http://localhost/cadviewer/html/{{$row}}" target="_blank"><h5 class="card-title">{{str_replace('.html', '' ,str_replace('_', ' ' ,$row))}}</h5></a>
				                <p class="card-text"></p>
				            </div>
				        </div>
			    	
			    </div>
	       @endforeach
			     
		</div>
	
	</section>
</section>


@endsection
@section('script')
@endsection