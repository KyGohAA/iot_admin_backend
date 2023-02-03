<section id="advance_search" class="hk-sec-wrapper {{$advance_search_status ? '':'collapsed-box'}}">
    <h5 class="hk-sec-title">{{App\Language::trans('Advance Search')}}</h5><hr>
    
        {!!Form::model($model, ['class'=>'form-horizontal','method'=>'get'])!!}
                <div class="form-group{{ $errors->has('item_to_list_from') ? ' has-error' : '' }} row">
                    {!! Form::label('item_to_list_from', App\Language::trans('From'), ['class'=>'col-sm-2 col-form-label']) !!}
                    <div class="col-sm-3">
                        {!! Form::number('item_to_list_from', 0, ['id'=>'item_to_list_from', 'rows'=>'10' , 'cols'=>'80','class'=>'form-control']) !!}
                        {!!$errors->first('item_to_list_from', '<label for="item_to_list_from" class="help-block error">:message</label>')!!}
                    </div>
                    {!! Form::label('item_to_list_to', App\Language::trans('From'), ['class'=>'col-sm-2 col-form-label']) !!}
                    <div class="col-sm-3">
                        {!! Form::number('item_to_list_to', count(App\OcTranslationsWord::all()), ['id'=>'item_to_list_to', 'rows'=>'10' , 'cols'=>'80','class'=>'form-control']) !!}
                        {!!$errors->first('item_to_list_to', '<label for="item_to_list_to" class="help-block error">:message</label>')!!}
                    </div>
                </div>
                <h6 class="hk-sec-title">{{App\Language::trans('Preferrable do not process over 2000 records at once. ')}}</h6><hr>
                <button type="submit" class="btn btn-info mr-10"><i class="fa fa-search fa-fw"></i>{{App\Language::trans('Search')}}</button>

        {!!Form::close()!!}

</section>