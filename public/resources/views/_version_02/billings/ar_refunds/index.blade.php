@extends('_version_02.commons.layouts.admin')
@section('content')
@include('_version_02.commons.layouts.partials._alert')
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
                        {!! Form::label('date_started', App\Language::trans('Document Date From'), ['class'=>'control-label col-md-2']) !!}
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
                            {!! Form::select('sort_by', App\MembershipModel\ARRefund::sort_by_combobox(), null, ['class'=>'form-control']) !!}
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
                <a href="{{action('ARRefundsController@getIndex')}}" class="btn btn-danger"><i class="fa fa-ban fa-fw"></i>{{App\Language::trans('Close')}}</a>
            </div>
        </div>
        {!!Form::close()!!}
    </div>
    <!-- /.box-footer-->
</div>

<section class="hk-sec-wrapper">
    <div class="row">
        <div class="col-sm">
            <div class="table-wrap">
                <table id="leaf_data_table" class="table tablesaw table-bordered table-hover mb-0 w-100 pb-30" data-tablesaw-mode="swipe"  data-tablesaw-minimap data-tablesaw-mode-switch>
                    <thead>
                        <tr>
                            @php $priority_counter = 1 ; @endphp
                            @foreach($cols as $col)
                                @if($col != 'store_id')
                                    @if($col == 'id')
                                        <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="0">#</th>
                                    @elseif(str_contains($col, '_id'))
                                        <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="{{$priority_counter}}">{{App\Language::trans(ucwords(str_replace('_id', '', $col)))}}</th>
                                    @else
                                        <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="{{$priority_counter}}">{{App\Language::trans(ucwords(str_replace('_', ' ', $col)))}}</th>
                                    @endif
                                @endif
                                @php $priority_counter ++ ; @endphp
                            @endforeach
                            <th class="text-center">{{App\Language::trans('Action')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $priority_counter = 1 ; @endphp
                        @foreach($model as $index => $row)
                        <tr>
                            <td class="text-center">{{$index+1}}</td>
                            @foreach($row->toArray() as $key => $value)
                                 @if($key == 'status')
                                     <td class="text-center">{{$row->display_status_string($key)}}</td>
                                 @elseif($key != 'id')
                                     <td class="text-center">{{$value}}</td>
                                 @endif
                            @endforeach
                            @include('_version_02.commons.layouts.partials._table_action_column')
                        </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
@endsection
@section('script')
@endsection