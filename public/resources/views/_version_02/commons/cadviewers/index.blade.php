@extends('_version_02.commons.layouts.admin')
@section('content')
@include('_version_02.commons.layouts.partials._alert')
<section class="hk-sec-wrapper">
    <section  class="hk-sec-wrapper">
    <h5 class="hk-sec-title">{{App\Language::trans('Modules')}}</h5><hr>
	    

	    <div class="row">
			<div class="col-md-6">
				<div class="form-group{{ $errors->has('state_id') ? ' has-error' : '' }}">
					{!! Form::label('state_id', App\Language::trans('Modules'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						{!! Form::select('state_id', $listing, null, ['class'=>'form-control','required' , 'onchange' => 'load_cadviewer(this);']) !!}
                        {!!$errors->first('state_id', '<label for="state_id" class="help-block error">:message</label>')!!}
					</div>
				</div>
			</div>
		</div>
	
	
	</section>
</section>

<div id="cadviewer_content_div" class="row" style="width: 100%;height: 2000px;"></div>


@endsection
@section('script')
@endsection