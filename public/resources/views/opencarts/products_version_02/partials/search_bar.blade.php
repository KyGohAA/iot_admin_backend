<section class="hk-sec-wrapper {{$advance_search_status ? '':'collapsed-box'}}">
    <h5 class="hk-sec-title mb-40">{{App\Language::trans('Advance Search')}}</h5>
    <div class="row" {!!$advance_search_status ? '':'style="display: none;"'!!}>
        <div class="col-sm">
            {!!Form::model($model, ['class'=>'form-horizontal','method'=>'get'])!!}
                <div class="form-group{{ $errors->has('item_to_list') ? ' has-error' : '' }} row">
                    {!! Form::label('item_to_list', App\Language::trans('Record To Show'), ['class'=>'col-sm-2 col-form-label']) !!}
                    <div class="col-sm-10">
                        {!! Form::text('item_to_list', 100, ['id'=>'item_to_list', 'rows'=>'10' , 'cols'=>'80','class'=>'form-control']) !!}
                        {!!$errors->first('item_to_list', '<label for="item_to_list" class="help-block error">:message</label>')!!}
                    </div>
                </div>

                <div class="form-group{{ $errors->has('e_store_name') ? ' has-error' : '' }} row">
                    {!! Form::label('e_store_name', App\Language::trans('E-Store'), ['class'=>'col-sm-2 col-form-label']) !!}
                    <div class="col-sm-10">
                        {!! Form::select('e_store_name', App\Opencart\Setting::estore_combobox(), null, ['class'=>'form-control']) !!}
                        {!!$errors->first('e_store_name', '<label for="e_store_name" class="help-block error">:message</label>')!!}
                    </div>
                </div>

                <div class="form-group{{ $errors->has('item_to_list') ? ' has-error' : '' }} row">
                    {!! Form::label('created_date_range', App\Language::trans('Created Date Range'), ['class'=>'col-sm-2 col-form-label']) !!}
                    <div class="col-sm-10">
                          <input class="form-control" type="text" name="daterange" value="01/01/2018 - 01/15/2018" />
                    </div>
                </div>
          

                <button type="submit" class="btn btn-info mr-10"><i class="fa fa-search fa-fw"></i>{{App\Language::trans('Search')}}</button>
                <a href="{{action('ARPaymentReceivedsController@getIndex')}}" class="btn btn-danger"><i class="fa fa-ban fa-fw"></i>{{App\Language::trans('Close')}}</a>

            {!!Form::close()!!}
        </div>
    </div>
</section>