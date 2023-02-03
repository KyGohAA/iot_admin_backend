@extends('_version_02.commons.layouts.admin')
@section('content')
{!! Form::model($model, ['class'=>'form-horizontal']) !!}
@include('_version_02.commons.layouts.partials._alert')
<section class="hk-sec-wrapper">
    <h5 class="hk-sec-title">{{App\Language::trans('Ticket Detail')}}</h5><hr>
    <div class="row">
			<div class="col-md-6">
				<div class="form-group{{ $errors->has('document_no') ? ' has-error' : '' }}">
					{!! Form::label('document_no', App\Language::trans('Document No'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						{!! Form::text('document_no', null, ['class'=>'form-control','readonly']) !!}
                        {!!$errors->first('document_no', '<label for="document_no" class="help-block error">:message</label>')!!}
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group{{ $errors->has('document_date') ? ' has-error' : '' }}">
					{!! Form::label('document_date', App\Language::trans('Document Date'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						{!! Form::text('document_date', $model->getDate('now'), ['class'=>'form-control document_date']) !!}
                        {!!$errors->first('document_date', '<label for="document_date" class="help-block error">:message</label>')!!}
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-6">
				<div class="form-group{{ $errors->has('customer_name') ? ' has-error' : '' }}">
					{!! Form::label('customer_name', App\Language::trans('Customer Name'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						{!! Form::text('customer_name', null, ['class'=>'form-control','readonly']) !!}
                        {!!$errors->first('customer_name', '<label for="customer_name" class="help-block error">:message</label>')!!}
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group{{ $errors->has('complaint_date') ? ' has-error' : '' }}">
					{!! Form::label('complaint_date', App\Language::trans('Complaint Date'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						{!! Form::text('complaint_date', $model->getDate('now'), ['class'=>'form-control complaint_date']) !!}
                        {!!$errors->first('complaint_date', '<label for="complaint_date" class="help-block error">:message</label>')!!}
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="form-group{{ $errors->has('complaint') ? ' has-error' : '' }}">
					{!! Form::label('complaint', App\Language::trans('Description'), ['class'=>'control-label col-md-2']) !!}
					<div class="col-md-10">
						{!! Form::textarea('complaint', null, ['rows'=>'5','class'=>'form-control']) !!}
                        {!!$errors->first('complaint', '<label for="complaint" class="help-block error">:message</label>')!!}
					</div>
				</div>
			</div>
		</div>
</section>

@include('_version_02.commons.layouts.partials._form_floaring_footer_standard')
{!! Form::close() !!}
@endsection
@section('script')
init_datepicker($(".document_date"));
init_datepicker($(".complaint_date"));
@endsection