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
						{!! Form::text('billing_address1', null, ['class'=>'form-control']) !!}
                        {!!$errors->first('billing_address1', '<label for="billing_address1" class="help-block error">:message</label>')!!}
					</div>
				</div>
				<div class="form-group{{ $errors->has('billing_address2') ? ' has-error' : '' }}">
					<div class="col-md-offset-4 col-md-8">
						{!! Form::text('billing_address2', null, ['class'=>'form-control']) !!}
                        {!!$errors->first('billing_address2', '<label for="billing_address2" class="help-block error">:message</label>')!!}
					</div>
				</div>
				<div class="form-group{{ $errors->has('billing_postcode') ? ' has-error' : '' }}">
					{!! Form::label('billing_postcode', App\Language::trans('Postcode'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						{!! Form::text('billing_postcode', null, ['class'=>'form-control']) !!}
                        {!!$errors->first('billing_postcode', '<label for="billing_postcode" class="help-block error">:message</label>')!!}
					</div>
				</div>
				<div class="form-group{{ $errors->has('billing_country_id') ? ' has-error' : '' }}">
					{!! Form::label('billing_country_id', App\Language::trans('Country'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						{!! Form::select('billing_country_id', App\Country::combobox(), null, ['class'=>'form-control','onchange'=>'init_state_selectbox(this)']) !!}
                        {!!$errors->first('billing_country_id', '<label for="billing_country_id" class="help-block error">:message</label>')!!}
					</div>
				</div>
				<div class="form-group{{ $errors->has('billing_state_id') ? ' has-error' : '' }}">
					{!! Form::label('billing_state_id', App\Language::trans('State'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						{!! Form::select('billing_state_id', App\State::combobox($model->billing_country_id), null, ['class'=>'form-control','onchange'=>'init_city_selectbox(this)']) !!}
                        {!!$errors->first('billing_state_id', '<label for="billing_state_id" class="help-block error">:message</label>')!!}
					</div>
				</div>
				<div class="form-group{{ $errors->has('billing_city_id') ? ' has-error' : '' }}">
					{!! Form::label('billing_city_id', App\Language::trans('City'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						{!! Form::select('billing_city_id', App\City::combobox($model->billing_state_id), null, ['class'=>'form-control']) !!}
                        {!!$errors->first('billing_city_id', '<label for="billing_city_id" class="help-block error">:message</label>')!!}
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<h4 class="text-center">{{App\Language::trans('Delivery Address')}}</h4>
				<div class="form-group{{ $errors->has('delivery_address1') ? ' has-error' : '' }}">
					{!! Form::label('delivery_address1', App\Language::trans('Address'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						{!! Form::text('delivery_address1', null, ['class'=>'form-control']) !!}
                        {!!$errors->first('delivery_address1', '<label for="delivery_address1" class="help-block error">:message</label>')!!}
					</div>
				</div>
				<div class="form-group{{ $errors->has('delivery_address2') ? ' has-error' : '' }}">
					<div class="col-md-offset-4 col-md-8">
						{!! Form::text('delivery_address2', null, ['class'=>'form-control']) !!}
                        {!!$errors->first('delivery_address2', '<label for="delivery_address2" class="help-block error">:message</label>')!!}
					</div>
				</div>
				<div class="form-group{{ $errors->has('delivery_postcode') ? ' has-error' : '' }}">
					{!! Form::label('delivery_postcode', App\Language::trans('Postcode'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						{!! Form::text('delivery_postcode', null, ['class'=>'form-control']) !!}
                        {!!$errors->first('delivery_postcode', '<label for="delivery_postcode" class="help-block error">:message</label>')!!}
					</div>
				</div>
				<div class="form-group{{ $errors->has('delivery_country_id') ? ' has-error' : '' }}">
					{!! Form::label('delivery_country_id', App\Language::trans('Country'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						{!! Form::select('delivery_country_id', App\Country::combobox(), null, ['class'=>'form-control','onchange'=>'init_state_selectbox(this)']) !!}
                        {!!$errors->first('delivery_country_id', '<label for="delivery_country_id" class="help-block error">:message</label>')!!}
					</div>
				</div>
				<div class="form-group{{ $errors->has('delivery_state_id') ? ' has-error' : '' }}">
					{!! Form::label('delivery_state_id', App\Language::trans('State'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						{!! Form::select('delivery_state_id', App\State::combobox($model->delivery_country_id), null, ['class'=>'form-control','onchange'=>'init_city_selectbox(this)']) !!}
                        {!!$errors->first('delivery_state_id', '<label for="delivery_state_id" class="help-block error">:message</label>')!!}
					</div>
				</div>
				<div class="form-group{{ $errors->has('delivery_city_id') ? ' has-error' : '' }}">
					{!! Form::label('delivery_city_id', App\Language::trans('City'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						{!! Form::select('delivery_city_id', App\City::combobox($model->delivery_state_id), null, ['class'=>'form-control']) !!}
                        {!!$errors->first('delivery_city_id', '<label for="delivery_city_id" class="help-block error">:message</label>')!!}
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
