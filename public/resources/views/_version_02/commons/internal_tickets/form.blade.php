@extends('_version_02.commons.layouts.admin')
@section('content')
{!! Form::model($model, ['class'=>'form-horizontal']) !!}
@include('_version_02.commons.layouts.partials._alert')
<section class="hk-sec-wrapper">
    <h5 class="hk-sec-title">{{App\Language::trans('Ticket Detail')}}</h5><hr>
	
	<div class="row">
		<div class="col-md-6">
			<div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
				{!! Form::label('name', App\Language::trans('Name'), ['class'=>'control-label col-md-4']) !!}
				<div class="col-md-8">
					{!! Form::text('name', null, ['class'=>'form-control','readonly']) !!}
                    {!!$errors->first('name', '<label for="name" class="help-block error">:message</label>')!!}
				</div>
			</div>
		</div>
	</div>
		

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
			<div class="col-md-12">
				<div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
					{!! Form::label('title', App\Language::trans('Title'), ['class'=>'control-label col-md-2']) !!}
					<div class="col-md-10">
						{!! Form::text('title', null, ['class'=>'form-control']) !!}
                        {!!$errors->first('title', '<label for="title" class="help-block error">:message</label>')!!}
					</div>
				</div>
			</div>
		</div>
			

		<div class="row">
			<div class="col-md-12">
				<div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
					{!! Form::label('description', App\Language::trans('Description'), ['class'=>'control-label col-md-2']) !!}
					<div class="col-md-10">
						{!! Form::textarea('description', null, ['rows'=>'5','class'=>'form-control']) !!}
                        {!!$errors->first('description', '<label for="description" class="help-block error">:message</label>')!!}
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