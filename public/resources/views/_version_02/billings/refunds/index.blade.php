@extends('billings.layouts.admin')
@section('content')
@include('_version_02.billings.layouts.partials._alert')

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
                    <div class="form-group{{ $errors->has('customer_id') ? ' has-error' : '' }}">
                        {!! Form::label('customer_id', App\Language::trans('Customer'), ['class'=>'control-label col-md-2']) !!}
                        <div class="col-md-10">
                            {!! Form::select('customer_id', App\Customer::combobox(), null, ['class'=>'form-control']) !!}
                            {!!$errors->first('customer_id', '<label for="customer_id" class="help-block error">:message</label>')!!}
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
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group{{ $errors->has('sort_by') ? ' has-error' : '' }}">
                        {!! Form::label('sort_by', App\Language::trans('Sort By'), ['class'=>'control-label col-md-6']) !!}
                        <div class="col-md-6">
                            {!! Form::select('sort_by', App\Refund::sort_by_combobox(), null, ['class'=>'form-control']) !!}
                            {!!$errors->first('sort_by', '<label for="sort_by" class="help-block error">:message</label>')!!}
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="checkbox">
                        <label>
                            {!!Form::checkbox('is_desc', 1, false)!!} {{('in descending order')}}
                        </label>
                    </div>
                </div>
            </div>
    </div>
    <!-- /.box-body -->
    <div class="box-footer">
        <div class="row">
            <div class="col-md-offset-2 col-md-10">
                <button type="submit" class="btn btn-info"><i class="fa fa-search fa-fw"></i>{{App\Language::trans('Search')}}</button>
                <a href="{{action('ARInvoicesController@getIndex')}}" class="btn btn-danger"><i class="fa fa-ban fa-fw"></i>{{App\Language::trans('Close')}}</a>
            </div>
        </div>
        {!!Form::close()!!}
    </div>
    <!-- /.box-footer-->
</div>



<!-- Default box -->
<div class="box">
    <div class="box-header with-border">
        <h3 class="box-title">{{App\Language::trans('Listing Information')}}</h3>
        <div class="box-tools pull-right">
            <a href="{{action('ARInvoicesController@getNew')}}" class="btn btn-block btn-info">
                <i class="fa fa-file"></i> {{App\Language::trans('New File')}}
            </a>
        </div>
    </div>
    <div class="box-body">
        <div class="table-responsive">
            <table id="leaf_data_table" class="table">
                <thead>
                    <tr>
                        @foreach($cols as $col) @if($col == 'id')
                        <th class="text-center">#</th>
                        @else
                        <th class="text-center">{{App\Language::trans(ucwords(str_replace('_', ' ', $col)))}}</th>
                        @endif @endforeach
                        <th class="text-center">{{App\Language::trans('Action')}}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($model as $index => $row)
                    <tr>
                        <td class="text-center">{{$index+1}}</td>
                        @foreach($row->toArray() as $key => $value) @if($key == 'status')
                        <td class="text-center">{{$row->display_status_string($key)}}</td>
                        @elseif($key != 'id')
                        <td class="text-center">{{$value}}</td>
                        @endif @endforeach
                        <td class="text-center">
                            <a onclick="return confirm(confirmMsg)" class="loading-label" href="{{action('ARInvoicesController@getEdit', [$row->id])}}">{{App\Language::trans('Edit')}}</a> |
                            <a class="loading-label" href="{{action('ARInvoicesController@getView', [$row->id])}}">{{App\Language::trans('View')}}</a> |
                            <a onclick="return confirm(confirmMsg)" class="loading-label" href="{{action('ARInvoicesController@getDelete', [$row->id])}}">{{App\Language::trans('Del')}}</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <!-- /.box-body -->
    <div class="box-footer text-center">
        {{$model->links()}}
    </div>
    <!-- /.box-footer-->
</div>
<!-- /.box -->

@endsection
@section('script')
@endsection