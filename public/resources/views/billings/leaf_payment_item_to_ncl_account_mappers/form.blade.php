@extends('billings.layouts.admin')
@section('content')
{!! Form::model($model, ['class'=>'form-horizontal']) !!}
@include('billings.layouts.partials._alert')
<div class="box">
    <div class="box-header with-border">
        <h3 class="box-title">{{App\Language::trans('Detail Form')}}</h3>
        <div class="box-tools pull-right">
            <a href="{{action('LeafPaymentItemToNCLAccountMappersController@getNew')}}" class="btn btn-block btn-info">
                <i class="fa fa-file"></i> {{App\Language::trans('New File')}}
            </a>
        </div>
    </div>
    <div class="box-body">

        <div class="row">
            <div class="col-md-6">
                <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                    {!! Form::label('name', App\Language::trans('Payment Item'), ['class'=>'control-label col-md-4']) !!}
                    <div class="col-md-8">
                        {!! Form::select('name',App\Product::leaf_all_payable_item_combobox(), null, ['class'=>'form-control','required']) !!} {!!$errors->first('name', '
                        <label for="name" class="help-block error">:message</label>')!!}
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group{{ $errors->has('deposit_to_account') ? ' has-error' : '' }}">
                    {!! Form::label('deposit_to_account', App\Language::trans('Deposit To'), ['class'=>'control-label col-md-4']) !!}
                    <div class="col-md-8">
                        {!! Form::select('deposit_to_account', App\Setting::bank_or_cash_combobox(), null, ['class'=>'form-control','autofocus','required']) !!} {!!$errors->first('deposit_to_account', '
                        <label for="customer_id" class="help-block error">:message</label>')!!}
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
       			 <div class="form-group{{ $errors->has('payment_term_id') ? ' has-error' : '' }}">
					{!! Form::label('payment_term_id', App\Language::trans('Payment Term'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						{!! Form::select('payment_term_id', App\PaymentTerm::get_common_combobox(), null, ['class'=>'form-control']) !!}
                        {!!$errors->first('payment_term_id', '<label for="payment_term_id" class="help-block error">:message</label>')!!}
					</div>
		
				 </div>
			</div>
		</div>


        <div class="row">
            <div class="col-md-6">
                <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                    {!! Form::label('name', App\Language::trans('Tax'), ['class'=>'control-label col-md-4']) !!}
                    <div class="col-md-8">
                        {!! Form::select('name',App\Tax::combobox(), null, ['class'=>'form-control','required']) !!} {!!$errors->first('name', '
                        <label for="name" class="help-block error">:message</label>')!!}
                    </div>
                </div>
            </div>
        </div>

        
        <div class="row">
            <div class="col-md-6">
                <div class="form-group{{ $errors->has('status') ? ' has-error' : '' }}">
                    {!! Form::label('status', App\Language::trans('Status'), ['class'=>'control-label col-md-4']) !!}
                    <div class="col-md-8">
                        <label class="radio-inline">
                            {!! Form::radio('status', 1, true) !!} {{App\ExtendModel::status_true_word()}}
                        </label>
                        <label class="radio-inline">
                            {!! Form::radio('status', 0, false) !!} {{App\ExtendModel::status_false_word()}}
                        </label>
                        {!!$errors->first('status', '
                        <label for="status" class="help-block error">:message</label>')!!}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="box-footer">
        <div class="row">
            <div class="col-md-offset-2 col-md-10">
                <button type="submit" class="btn btn-primary"><i class="fa fa-floppy-o fa-fw"></i>{{App\Language::trans('Save')}}</button>
                <a href="{{action('LeafPaymentItemToNCLAccountMappersController@getIndex')}}" class="btn btn-danger"><i class="fa fa-ban fa-fw"></i>{{App\Language::trans('Close')}}</a>
            </div>
        </div>
    </div>
</div>
{!! Form::close() !!}
@endsection
@section('script')
@endsection