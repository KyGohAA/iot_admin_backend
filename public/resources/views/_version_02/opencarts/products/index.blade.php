@extends('_version_02.commons.layouts.admin')
@section('content')
@include('_version_02.commons.layouts.partials._alert')
@include('_version_02.opencarts.products.partials.save_by_url')
@include('_version_02.opencarts.products.partials.search_bar')

<div id="alert_msg_div" class="alert alert-success alert-dismissible hide">
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
		<i id="alert_msg" class="icon fa fa-check"></i>
</div>

<!-- Default box -->
@if(count($model) > 0)
      <section class="hk-sec-wrapper">
                            <div class="row">
                                <div class="col-sm">
                                    <div class="table-wrap">
                                        <table id="leaf_data_table" class="table tablesaw table-bordered table-hover mb-0 w-100 pb-30" data-tablesaw-mode="swipe"  data-tablesaw-minimap data-tablesaw-mode-switch>
                                            <thead>
                                                <tr>
                                                    @php $priority_counter = 1 ; @endphp
                                                    @foreach($cols as $col)
                                                        @if($col == 'id')
                                                            <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="0">#</th>
                                                            <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="0">{{App\Language::trans('Image')}}</th>
                                                        @elseif($col == 'product_url' || $col == 'status')
                                                        @else
                                                            <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="{{$priority_counter}}">{{App\Language::trans(ucwords(str_replace('_', ' ', $col)))}}</th>
                                                        @endif
                                                        @php $priority_counter ++ ; @endphp
                                                    @endforeach
                                                    <th class="text-center">{{App\Language::trans('Action')}}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                 @php $priority_counter = 1 ; @endphp
                                                 @foreach($model as $index => $row)
                                                    <tr id="{{$row['product_id']}}">   
                                                        <td class="text-center"><p style="color:{{$row->status == true ? 'green':'red'}};">{{$index+1}}</p></td>
                                                        <td class="text-center"><img class="img-responsive" width="50" height="50" src=""></td>
                                                        <td class="text-center"><a href="http://{{$row->product_url}}">{{App\Language::trans($row->model)}}</a></td>
                                                        @foreach($cols as $col)
                                                            @if($col == 'date_started' || $col == 'date_started')
                                                                <td class="text-center">{{$row[$col]}}</td>
                                                            @elseif($col == 'cost' || $col == 'price' || $col == 'selling_price')
                                                                <td class="text-center">
                                                                    {!! Form::number($col, null, ['id'=>$col.'_'.$row->id, 'rows'=>'10' , 'cols'=>'80','class'=>'form-control width-100','placeholder'=>$row->setDouble($row[$col])]) !!}
                                                                </td>
                                                            @elseif($col != 'id' && $col != 'model' && $col != 'product_url' && $col != 'status')
                                                                <td class="text-center">{{$row[$col]}}</td>
                                                            @endif
                                                        @endforeach
                                                        <td class="text-center">
                                                            <a class='btn' onclick="return confirm(confirmMsg)" class="loading-label" href="{{action('OCProductsController@getEdit', [$row->id])}}">{{App\Language::trans('Edit')}}</a> | <a class='btn' onclick='update_selected_product({{$row->id}});'>{{App\Language::trans('Update')}}</a>
                                                             
                                                             @if($page_key != 'is_verified')
                                                               |  <a class='btn' onclick='updated_selected_product_detail({{$row->id}},"is_verified");'>{{App\Language::trans('Verified')}}</a> 
                                                             @endif
                                                             @if($page_key != 'is_removed')
                                                              | <a class='btn' onclick='updated_selected_product_detail({{$row->id}},"is_removed");'>{{App\Language::trans('Removed')}}</a>
                                                             @endif
                                                             
                                                            <!-- <a href="{{action('HelpsController@getView', [$row->id])}}">{{App\Language::trans('View')}}</a> | 
                                                            <a onclick="return confirm(confirmMsg)" class="loading-label" href="{{action('HelpsController@getDelete', [$row->id])}}">{{App\Language::trans('Del')}}</a> -->
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </section>
@endif

@endsection
@section('script')
@endsection