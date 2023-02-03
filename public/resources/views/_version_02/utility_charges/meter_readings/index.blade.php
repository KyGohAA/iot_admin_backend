@extends('_version_02.commons.layouts.admin')
@section('content')
@include('_version_02.commons.layouts.partials._alert')
<section class="hk-sec-wrapper">
    @if($cols == '')
        @include('_version_02.commons.layouts.partials._no_data_msg')
    @else
        <div class="row">
            <div class="col-sm">
                <div class="table-wrap">
                    <table id="leaf_data_table" class="table tablesaw table-bordered table-hover mb-0 w-100 pb-30" data-tablesaw-mode="swipe"  data-tablesaw-minimap data-tablesaw-mode-switch>
                        <thead>
                            <tr>
                                @php $priority_counter = 1 ; @endphp
                                @foreach($cols as $col)
                                     @if(is_integer($col) == true)
                                        <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="{{$priority_counter}}">{{App\Language::trans(date('h A', strtotime('- '.($interval-intval($col)).' hours', strtotime('now'))))}}</th>
                                     @else
                                        <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="{{$priority_counter}}">{{App\Language::trans(ucwords(str_replace('_', ' ', $col)))}}</th>
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
        </div>
    @endif
</section>
@endsection
@section('script')
@endsection