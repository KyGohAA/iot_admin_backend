@extends('_version_02.commons.layouts.admin')
@section('content')
<style type="text/css">
	.fa-150x {
		font-size: 150px;
	}
</style>

{!! Form::model($model, ['class'=>'form-horizontal']) !!}
	<section class="hk-sec-wrapper">

		<h3 class="hk-sec-title">{{App\Language::trans('Houses Listing')}}</h3><hr>
			<div class="row" id='house_listing_div'>
					@include('_version_02.commons.layouts.plugins.power_meter_loading_bar')
			</div>
	</section>
{!! Form::close() !!}


<input type="hidden" id="modal_target_div_id" name="modal_target_div_id" value='meter_house_room_detail_div'>
<input type="hidden" id="modal_id" name="modal_id" value='power_meter_house_room_detail_modal'>
@include('_version_02.utility_charges.meter_registers.partials.power_meter_house_room_detail_modal')

@endsection
@section('script')

    $(function() {
        $(".dial").knob({
        	readOnly:true,
	    });
    });
@endsection