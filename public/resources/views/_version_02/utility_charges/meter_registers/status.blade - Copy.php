@extends('_version_02.commons.layouts.admin')
@section('content')
<style type="text/css">
	.fa-150x {
		font-size: 150px;
	}
</style>

@if(isset($room_list) && count($room_list))
	<section class="hk-sec-wrapper">
		<h3 class="hk-sec-title">{{$house_unit}}</h3><!-- <hr> -->
	    <div class="row">
				@if(isset($main_meter) && count($main_meter))
					@php $graph = number_format(($main_meter['monthly_usage'] > 0 ? ($main_meter['monthly_usage']/$main_meter['total_usage']):$main_meter['monthly_usage'])*100,2,'.',''); @endphp
					<div class="col-md-3 text-center margin-bottom-15">			
							<p class="text-center">{{$main_meter["total_usage"]}} Kwh</p>
							<input type="text" value="@php echo $graph; @endphp" class="dial">
							<hr>
							<h4 class="text-center">{{App\Language::trans('Total Usage')}}</h4>
							<hr>
						<hr>
					</div>
					

					<div class="col-md-3 text-center margin-bottom-15">
							
							<p class="text-center">{{$main_meter["monthly_usage"]}} Kwh</p>
							<input type="text" value="@php echo $graph; @endphp" class="dial">
							<hr>
							<h4 class="text-center">{{App\Language::trans('Monthly Usage')}}</h4>
							<hr>
						<hr>
					</div>
				@endif
		</div>

	</section>
		<!-- <div class="alert alert-success alert-dismissible">
		    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
		   {{App\Language::trans('Last update since')}} {{$last_reading_update}}
		 </div> -->
@elseif(count(App\PowerMeterModel\MeterRegister::houses_array()) == 0)
	<div class="alert alert-info alert-dismissible">
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
		<i class="icon fa fa-{{session(App\Setting::session_alert_icon)}}"></i>
		{{App\Language::trans('Unit is not register with power register model.')}}
	</div>
@endif


{!! Form::model($model, ['class'=>'form-horizontal']) !!}
<section class="hk-sec-wrapper">

    @if(isset($room_list) && count($room_list))
    	<h3 class="hk-sec-title">{{App\Language::trans('Rooms Listing')}}</h3><hr>
	@else
		<h3 class="hk-sec-title">{{App\Language::trans('Houses Listing')}}</h3><hr>
	@endif

	<div class="row">
	@if(isset($room_list) && count($room_list))
		@foreach($room_list as $room)
			@php $i = 0; @endphp
			@php $graph = number_format(($room['monthly_usage'] > 0 ? ($room['monthly_usage']/$room['total_usage']):$room['monthly_usage'])*100,2,'.',''); @endphp
			<div class="col-md-3 text-center margin-bottom-15">			 	
				
				<hr>
				<p class="text-center">{{$room['total_usage']}} Kwh</p> 
				<input type="text" value="@php echo $graph; @endphp" class="dial"><br>
				<hr>
				<h4 class="text-center">Room No. {{$room['room_name']}}</h4>
				<span class='label label-success'> {{App\Language::trans('Last update sicne ')}} {{$room['last_update']}}</span>
				<hr>
							
			</div>
			@php $i++; @endphp
		@endforeach
	@else
		@foreach(App\PowerMeterModel\MeterRegister::houses_array() as $house)
			<a  class="loading-label" href="{{action('UMeterRegistersController@getStatus', ['leaf_house_id'=>$house['id_house'],'house_unit'=>$house['house_unit']])}}">
				<div class="col-md-12 text-center margin-bottom-15">
					<i class="fa fa-home fa-fw fa-8x"></i>
					<p>{{App\Language::trans('House No.')}} {{$house['house_unit']}}</p>
					<hr>
				</div>
			</a>
		@endforeach
	@endif
	</div>

	<div class="box-footer text-right">
		@if(isset($room_list) && count($room_list))
			<a class="btn btn-danger loading-label" href="{{action('UMeterRegistersController@getStatus')}}"><i class="fa fa-arrow-left fa-fw"></i> {{App\Language::trans('Back')}}</a>
		@endif
	</div>

</section>
{!! Form::close() !!}
@endsection
@section('script')
    $(function() {
        $(".dial").knob({
        	readOnly:true,
	    });
    });
@endsection