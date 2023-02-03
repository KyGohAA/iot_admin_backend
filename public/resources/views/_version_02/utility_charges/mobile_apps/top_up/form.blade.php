@extends('_version_02.utility_charges.mobile_apps.layouts.main')
@section('content')
{!! Form::model($model, ['class'=>'form-horizontal',"files"=>true ,'style'=>'position: absolute; top: -30px;left:-5px;']) !!}

	<div id="div_step_1" class="fullscreen">
	  @include('_version_02.utility_charges.mobile_apps.top_up.partials.step_1')
	</div>

{!! Form::close() !!}
<br><br><br>
@endsection
@section('script')
@endsection
