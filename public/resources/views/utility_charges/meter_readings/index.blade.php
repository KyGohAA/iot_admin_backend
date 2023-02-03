@extends('commons.layouts.admin')
@section('content')
@include('commons.layouts.partials._alert')


<!-- Default box -->
<div class="box">
    <div class="box-header with-border">
        <h3 class="box-title">{{App\Language::trans('Listing Information')}}</h3>
        <div class="box-tools pull-right">
            <a href="{{action('UMeterRefundsController@getNew')}}" class="btn btn-block btn-info">
                <i class="fa fa-file"></i> {{App\Language::trans('New File')}}
            </a>
        </div>
    </div>
    <div class="box-body">
        <div class="table-responsive">
            <table id="leaf_data_table" class="table">
                <thead>
                     <tr>
                     
                        <!-- <th class="text-center">#</th> -->
                        @foreach($cols as $col)
                         @if(is_integer($col) == true)
                          <th class="text-center">{{App\Language::trans(date('h A', strtotime('- '.($interval-intval($col)).' hours', strtotime('now'))))}}</th>
                         @else
                           <th class="text-center">{{App\Language::trans(ucwords(str_replace('_', ' ', $col)))}}</th>
                         @endif
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $row)
                        <tr>
                            @php $is_gap = 0; @endphp
                            @foreach($cols as $col)
                                @php $is_pass = false; @endphp
                                @if(isset($row[$col]) == true)
                                        @if(is_integer($col) == true)
                                            @if(date('h',strtotime(isset($row[$col]) ? $row[$col] : "0")) - $is_gap == date('h', strtotime('- '.($interval-intval($col)).' hours', strtotime('now'))))
                                                <td class="text-center">{{$row[$col]."=".date('h',strtotime(isset($row[$col]) ? $row[$col] : "0"))."-".date('h', strtotime('- '.($interval-intval($col)).' hours', strtotime('now')))}}</td>
                                            @else
                                                @php $is_gap ++; @endphp
                                                <td class="text-center">Emtpy</td>
                                                <td class="text-center">{{$row[$col]."=".date('h',strtotime(isset($row[$col]) ? $row[$col] : "0"))."-".date('h', strtotime('- '.($interval-intval($col)).' hours', strtotime('now')))}}</td>
                                            @endif
                                         @else
                                           <td class="text-center">{{isset($row[$col]) ? $row[$col]: '-'}}</td>
                                         @endif
                                @else
                                    
                                @endif
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <!-- /.box-body -->
    <div class="box-footer text-center">

    </div>
    <!-- /.box-footer-->
</div>
<!-- /.box -->

@endsection
@section('script')
@endsection