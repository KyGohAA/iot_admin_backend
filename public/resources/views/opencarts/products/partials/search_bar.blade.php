<!-- Default box -->
<div class="box {{$advance_search_status ? '':'collapsed-box'}}">
    <div class="box-header with-border">
        <h3 class="box-title">{{App\Language::trans('Advance Search')}}</h3>
        <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus fa-fw"></i>
            </button>
        </div>
    </div>
    <div class="box-body" {!!$advance_search_status ? '':'style="display: none;"'!!}>
        {!!Form::model($model, ['class'=>'form-horizontal','method'=>'get'])!!}
    
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group{{ $errors->has('item_to_list') ? ' has-error' : '' }}">
                        {!! Form::label('item_to_list', App\Language::trans('Record to show'), ['class'=>'control-label col-md-2']) !!}
                        <div class="col-md-2">
                            {!! Form::text('item_to_list', 100, ['id'=>'item_to_list', 'rows'=>'10' , 'cols'=>'80','class'=>'form-control']) !!}
                            {!!$errors->first('item_to_list', '<label for="item_to_list" class="help-block error">:message</label>')!!}
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group{{ $errors->has('customer_id') ? ' has-error' : '' }}">
                        {!! Form::label('customer_id', App\Language::trans('E-Store'), ['class'=>'control-label col-md-2']) !!}
                        <div class="col-md-2">
                            {!! Form::select('e_store_name', App\Opencart\Setting::estore_combobox(), null, ['class'=>'form-control']) !!}
                            {!!$errors->first('e_store_name', '<label for="e_store_name" class="help-block error">:message</label>')!!}
                        </div>
                    </div>
                </div>
            </div>
<!-- 
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
            </div>
 -->
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group{{ $errors->has('ar_invoice_id') ? ' has-error' : '' }}">
                        {!! Form::label('ar_invoice_id', App\Language::trans('Creating Date From'), ['class'=>'control-label col-md-2']) !!}
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
    <div class="box-footer">
        <div class="row">
            <div class="col-md-offset-2 col-md-10">
                <button type="submit" class="btn btn-info"><i class="fa fa-search fa-fw"></i>{{App\Language::trans('Search')}}</button>
                <a href="{{action('ARPaymentReceivedsController@getIndex')}}" class="btn btn-danger"><i class="fa fa-ban fa-fw"></i>{{App\Language::trans('Close')}}</a>
            </div>
        </div>
        {!!Form::close()!!}
    </div>
    <!-- /.box-footer-->
</div>
