<div class="box collapsed-box">
	<div class="box-header with-border">
		<h3 class="box-title">{{App\Language::trans('Address Form')}}</h3>
		<div class="box-tools pull-right">
			<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus fa-fw"></i>
			</button>
		</div>
	</div>
	<div class="box-body" style="display: none;">
		<div class="row">
			<div class="col-md-6">
				<h4 class="text-center">{{App\Language::trans('Billing Address')}}</h4>
				<div class="form-group{{ $errors->has('billing_address1') ? ' has-error' : '' }}">
					{!! Form::label('billing_address1', App\Language::trans('Address'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						<p class="form-control-static">{{$model->billing_address1}}</p>
					</div>
				</div>
				<div class="form-group{{ $errors->has('billing_address2') ? ' has-error' : '' }}">
					<div class="col-md-offset-4 col-md-8">
						<p class="form-control-static">{{$model->billing_address2}}</p>
					</div>
				</div>
				<div class="form-group{{ $errors->has('billing_postcode') ? ' has-error' : '' }}">
					{!! Form::label('billing_postcode', App\Language::trans('Postcode'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						<p class="form-control-static">{{$model->billing_postcode}}</p>
					</div>
				</div>
				<div class="form-group{{ $errors->has('billing_country_id') ? ' has-error' : '' }}">
					{!! Form::label('billing_country_id', App\Language::trans('Country'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						<p class="form-control-static">{{$model->display_relationed('billing_country', 'name')}}</p>
					</div>
				</div>
				<div class="form-group{{ $errors->has('billing_state_id') ? ' has-error' : '' }}">
					{!! Form::label('billing_state_id', App\Language::trans('State'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						<p class="form-control-static">{{$model->display_relationed('billing_state', 'name')}}</p>
					</div>
				</div>
				<div class="form-group{{ $errors->has('billing_city_id') ? ' has-error' : '' }}">
					{!! Form::label('billing_city_id', App\Language::trans('City'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						<p class="form-control-static">{{$model->display_relationed('billing_city', 'name')}}</p>
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<h4 class="text-center">{{App\Language::trans('Delivery Address')}}</h4>
				<div class="form-group{{ $errors->has('delivery_address1') ? ' has-error' : '' }}">
					{!! Form::label('delivery_address1', App\Language::trans('Address'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						<p class="form-control-static">{{$model->delivery_address1}}</p>
					</div>
				</div>
				<div class="form-group{{ $errors->has('delivery_address2') ? ' has-error' : '' }}">
					<div class="col-md-offset-4 col-md-8">
						<p class="form-control-static">{{$model->delivery_address2}}</p>
					</div>
				</div>
				<div class="form-group{{ $errors->has('delivery_postcode') ? ' has-error' : '' }}">
					{!! Form::label('delivery_postcode', App\Language::trans('Postcode'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						<p class="form-control-static">{{$model->delivery_postcode}}</p>
					</div>
				</div>
				<div class="form-group{{ $errors->has('delivery_country_id') ? ' has-error' : '' }}">
					{!! Form::label('delivery_country_id', App\Language::trans('Country'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						<p class="form-control-static">{{$model->display_relationed('delivery_country', 'name')}}</p>
					</div>
				</div>
				<div class="form-group{{ $errors->has('delivery_state_id') ? ' has-error' : '' }}">
					{!! Form::label('delivery_state_id', App\Language::trans('State'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						<p class="form-control-static">{{$model->display_relationed('delivery_state', 'name')}}</p>
					</div>
				</div>
				<div class="form-group{{ $errors->has('delivery_city_id') ? ' has-error' : '' }}">
					{!! Form::label('delivery_city_id', App\Language::trans('City'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						<p class="form-control-static">{{$model->display_relationed('delivery_city', 'name')}}</p>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
