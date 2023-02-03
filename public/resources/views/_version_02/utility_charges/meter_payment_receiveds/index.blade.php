@extends('utility_charges.layouts.admin')
@section('content')
@include('_version_02.utility_charges.layouts.partials._alert')


<div class="box">
    <div class="box-header with-border">
        <h3 class="box-title">{{App\Language::trans('Listing Information')}}</h3>
        <div class="box-tools pull-right">
            <a href="{{action('UMeterPaymentReceivedsController@getNew')}}" class="btn btn-block btn-info">
                <i class="fa fa-file"></i> {{App\Language::trans('New File')}}
            </a>
        </div>
    </div>
    <div class="box-body">
        <div class="table-responsive">
            <table id="leaf_data_table" class="table">
                <thead>
                    <tr>
                        @foreach($cols as $col)
                            @if($col == 'id')
                                <th class="text-center">#</th>
                            @else
                                <th class="text-center">{{App\Language::trans(ucwords(str_replace('_', ' ', $col)))}}</th>
                            @endif
                        @endforeach
                        <th class="text-center">{{App\Language::trans('Action')}}</th>
                    </tr>
                </thead>
                <tbody>
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
                            <td class="text-center">
                                <a onclick="return confirm(confirmMsg)" class="loading-label" href="{{action('UMeterPaymentReceivedsController@getEdit', [$row->id])}}">{{App\Language::trans('Edit')}}</a> | 
                                <a class="loading-label" href="{{action('UMeterPaymentReceivedsController@getView', [$row->id])}}">{{App\Language::trans('View')}}</a> | 
                                <a target="_blank" href="{{action('UMeterPaymentReceivedsController@getPrint', [$row->id])}}">{{App\Language::trans('Print')}}</a> | 
                                <a onclick="return confirm(confirmMsg)" class="loading-label" href="{{action('UMeterPaymentReceivedsController@getDelete', [$row->id])}}">{{App\Language::trans('Del')}}</a>
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
var customerInfoUrl = "{{action('CustomersController@getInfo')}}";
    function init_customer_info(me) {
        $.get(customerInfoUrl, {customer_id:$(me).val()}, function(fdata){
            for (var key in fdata.data) {
                console.log("key " + key + " has value " + fdata.data[key]);
            }
        },"json");
    }
    
    init_select2($("select[name=customer_id]"));
    init_select2($("select[name=sort_by]"));
@endsection


