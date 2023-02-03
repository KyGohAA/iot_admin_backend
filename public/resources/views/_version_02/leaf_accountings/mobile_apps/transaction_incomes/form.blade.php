@extends('_version_02.leaf_accountings.mobile_apps.layouts.main')
@section('content')
{!! Form::model($model, ['class'=>'form-horizontal',"files"=>true]) !!}

	<div id="div_step_1">
	  @include('_version_02.leaf_accountings.mobile_apps.transaction_incomes.partials.step_1')
	</div>


{!! Form::close() !!}
<br><br><br>
@endsection
@section('script')
@endsection
