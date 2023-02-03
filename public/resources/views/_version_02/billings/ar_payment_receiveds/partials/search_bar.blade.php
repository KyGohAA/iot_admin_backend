<div class="row">
                <div class="col-sm">
                    <div class="accordion" id="accordion_1">
                        <div class="card" class="">
                            <div class="card-header d-flex justify-content-between activestate">  
                            </div>
                           
                            <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <a class="collapsed" role="button" data-toggle="collapse" href="#collapse_4" aria-expanded="false">{{App\Language::trans('Advance Search')}}</a>
                            </div>
                            <div id="collapse_4" class="collapse" data-parent="#accordion_1">
                                <div class="card-body pa-15">
                                {!!Form::model($model, ['class'=>'form-horizontal','method'=>'get'])!!}
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group{{ $errors->has('customer_id') ? ' has-error' : '' }}">
                        {!! Form::label('customer_id', App\Language::trans('Customer'), ['class'=>'control-label col-md-2']) !!}
                        <div class="col-md-10">
                            {!! Form::select('customer_id', App\Customer::combobox(), null, ['class'=>'form-control']) !!}
                            {!!$errors->first('customer_id', '<label for="customer_id" class="help-block error">:message</label>')!!}
                        </div>
                    </div>
                </div>
            </div>

            <!-- <div class="row">
                <div class="col-md-6">
                    <div class="form-group{{ $errors->has('type') ? ' has-error' : '' }}">
                        {!! Form::label('type', App\Language::trans('Type'), ['class'=>'control-label col-md-4']) !!}
                        <div class="col-md-8">
                            {!! Form::select('type', App\Setting::payment_received_type(), null, ['class'=>'form-control','autofocus','required','onchange'=>'init_payment_received_type_handle(this)']) !!}
                            {!!$errors->first('type', '<label for="type" class="help-block error">:message</label>')!!}
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group{{ $errors->has('payment_method') ? ' has-error' : '' }}">
                        {!! Form::label('payment_method', App\Language::trans('Payment Method'), ['class'=>'control-label col-md-4']) !!}
                        <div class="col-md-8">
                            {!! Form::select('payment_method', App\Setting::payment_method(), null, ['class'=>'form-control','autofocus','required']) !!}
                            {!!$errors->first('payment_method', '<label for="payment_term_id" class="help-block error">:message</label>')!!}
                        </div>
                    </div>
                </div>
            </div> -->

             <div class="row">
                <div class="col-md-4">
                    <div class="form-group{{ $errors->has('sort_by') ? ' has-error' : '' }}">
                        {!! Form::label('sort_by', App\Language::trans('Sort By'), ['class'=>'control-label col-md-6']) !!}
                        <div class="col-md-6">
                            {!! Form::select('sort_by', App\MembershipModel\ARPaymentReceived::sort_by_combobox(), null, ['class'=>'form-control']) !!}
                            {!!$errors->first('sort_by', '<label for="sort_by" class="help-block error">:message</label>')!!}
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="checkbox">
                        <label>
                            {!!Form::checkbox('is_desc', 1, false)!!} {{('in descending order')}}
                        </label>
                    </div>
                </div>

                 <div class="col-md-6">
                    <div class="form-group{{ $errors->has('payment_method') ? ' has-error' : '' }}">
                        {!! Form::label('payment_method', App\Language::trans('Payment Method'), ['class'=>'control-label col-md-4']) !!}
                        <div class="col-md-8">
                            {!! Form::select('payment_method', App\Setting::payment_method(), null, ['class'=>'form-control','autofocus','required']) !!}
                            {!!$errors->first('payment_method', '<label for="payment_term_id" class="help-block error">:message</label>')!!}
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group{{ $errors->has('ar_invoice_id') ? ' has-error' : '' }}">
                        {!! Form::label('ar_invoice_id', App\Language::trans('Document Date From'), ['class'=>'control-label col-md-2']) !!}
                          <div class="input-daterange">
                            <div class="col-md-3">
                                <div class="form-group{{ $errors->has('date_started') ? ' has-error' : '' }}">  
                                    <div class="col-md-8">
                                        {!! Form::text('date_started', null, ['class'=>'form-control']) !!}
                                        {!!$errors->first('date_started', '<label for="date_started" class="help-block error">:message</label>')!!}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group{{ $errors->has('date_ended') ? ' has-error' : '' }}">
                                    {!! Form::label('date_ended', App\Language::trans('To'), ['class'=>'control-label col-md-4']) !!}
                                    <div class="col-md-8">
                                        {!! Form::text('date_ended', null, ['class'=>'form-control']) !!}
                                        {!!$errors->first('date_ended', '<label for="date_ended" class="help-block error">:message</label>')!!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </div>
        <!-- /.box-body -->
        <div class="box-footer mt-10">
            <div class="row">
                <div class="col-md-offset-2 col-md-10">
                    <button type="submit" class="btn btn-info"><i class="fa fa-search fa-fw"></i>{{App\Language::trans('Search')}}</button>
                    <a href="{{action('ARPaymentReceivedsController@getIndex')}}" class="btn btn-danger"><i class="fa fa-ban fa-fw"></i>{{App\Language::trans('Close')}}</a>
                </div>
            </div>
            {!!Form::close()!!}
        </div>
        </div>
    </div>
    </div>
   

</div>
</div>
</div>

