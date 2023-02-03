Skip to content
 
Search or jump to…

Pull requests
Issues
Marketplace
Explore
 @kyGohX
Sign out
 Watch 2
 Star 0  Fork 0 LeafSmart/leaf_webview Private
 Code  Issues 0  Pull requests 0  Projects 1  Wiki  Insights
Tree: 5f0358b191 Find file Copy path leaf_webview/resources/views/utility_charges/reports/monthly_usages.blade.php
5f0358b  on Aug 1
@kyGohX kyGohX Sunway power meter update
1 contributor
RawBlameHistory      
202 lines (173 sloc)  7.57 KB
@extends('billings.layouts.admin')
@section('content')
{!! Form::model($model, ['class'=>'form-horizontal','method'=>'get']) !!}
<div class="box">
    <div class="box-header with-border">
        <h3 class="box-title">{{App\Language::trans('Filter By')}}</h3>
    </div>
    <div class="box-body">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group{{ $errors->has('month_started') ? ' has-error' : '' }}">
                    {!! Form::label('month_started', App\Language::trans('From Month'), ['class'=>'control-label col-md-4']) !!}
                    <div class="col-md-8">
                        {!! Form::select('month_started', App\MeterInvoice::previous_one_year_combobox(), (old('month_started') ? old('month_started'):$model->three_month_pass()), ['class'=>'form-control','autofocus']) !!}
                        {!!$errors->first('month_started', '<label for="month_started" class="help-block error">:message</label>')!!}
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group{{ $errors->has('month_ended') ? ' has-error' : '' }}">
                    {!! Form::label('month_ended', App\Language::trans('To Month'), ['class'=>'control-label col-md-4']) !!}
                    <div class="col-md-8">
                        {!! Form::select('month_ended', App\MeterInvoice::previous_one_year_combobox(), (old('month_ended') ? old('month_ended'):$model->last_month()), ['class'=>'form-control']) !!}
                        {!!$errors->first('month_ended', '<label for="month_ended" class="help-block error">:message</label>')!!}
                    </div>
                </div>
            </div>
        </div>

            <div class="row">
            <div class="col-md-6">
                <div class="form-group{{ $errors->has('leaf_house_id') ? ' has-error' : '' }}">
                    {!! Form::label('leaf_house_id', App\Language::trans('House No.'), ['class'=>'control-label col-md-4']) !!}
                    <div class="col-md-8">
                        {!! Form::select('leaf_house_id', App\PowerMeterModel\MeterRegister::houses_combobox(), null, ['class'=>'form-control','autofocus','onchange'=>'init_room_combobox(this)']) !!}
                        {!!$errors->first('leaf_house_id', '<label for="leaf_house_id" class="help-block error">:message</label>')!!}
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group{{ $errors->has('leaf_room_id') ? ' has-error' : '' }}">
                    {!! Form::label('leaf_room_id', App\Language::trans('Room No.'), ['class'=>'control-label col-md-4']) !!}
                    <div class="col-md-8">
                        {!! Form::select('leaf_room_id', App\PowerMeterModel\MeterRegister::rooms_combobox((old('leaf_house_id') ? old('leaf_house_id'):$model->leaf_house_id)), null, ['class'=>'form-control','onchange'=>'init_room_status(this)']) !!}
                        {!!$errors->first('leaf_room_id', '<label for="leaf_room_id" class="help-block error">:message</label>')!!}
                    </div>
                </div>
            </div>
        </div>


        <div class="row">
            <div class="col-md-6">
                <div class="form-group{{ $errors->has('export_by') ? ' has-error' : '' }}">
                    {!! Form::label('export_by', App\Language::trans('Export By'), ['class'=>'control-label col-md-4']) !!}
                    <div class="col-md-8">
                        <label class="radio-inline">
                            {!! Form::radio('export_by', 'html', true, ['id'=>'export_by_pdf']) !!} {{App\Language::trans('HTML')}}
                        </label>
                        <label class="radio-inline">
                            {!! Form::radio('export_by', 'pdf', false, ['id'=>'export_by_html']) !!} {{App\Language::trans('PDF')}}
                        </label>
                        <label class="radio-inline">
                            {!! Form::radio('export_by', 'excel', false, ['id'=>'export_by_excel']) !!} {{App\Language::trans('Excel')}}
                        </label>
                        {!!$errors->first('export_by', '<label for="export_by" class="help-block error">:message</label>')!!}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="box-footer">
        <div class="row">
            <div class="col-md-offset-2 col-md-10">
                <button type="submit" class="btn btn-primary"><i class="fa fa-search fa-fw"></i>{{App\Language::trans('Search')}}</button>
            </div>
        </div>
    </div>
</div>
{!! Form::close() !!}
@if(count($listing))
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">{{App\Language::trans('Listing Information')}}</h3>
        </div>
        <div class="box-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th class="text-center">{{App\Language::trans('Month')}}</th>
                            <th class="text-center">{{App\Language::trans('Total Hours')}}</th>
                            <th class="text-center">{{App\Language::trans('Avg. kW')}}</th>
                            <th class="text-center">{{App\Language::trans('Max. kW')}}</th>
                            <th class="text-center">{{App\Language::trans('Min. kW')}}</th>
                            <th class="text-center">{{App\Language::trans('Total kWh')}}</th>
                            <th class="text-center">{{App\Language::trans('Total Charges (RM)')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                    @php  
                        $total_payable_amount = 0;
                    @endphp
    
                    @foreach($houses_detail as $house)
                        <tr style="background-color: #ddd;">
                            <td class="text-left" colspan="7">{{App\Language::trans('House')}} : {{$house['house_unit']}}</td>
                        </tr>
                            
                    
                        @foreach($house['house_rooms'] as $room)

                            @php  
                                $isMeterRegister = false;
                                $isFirstRoomHeader = true;
                                $rowNo =0 ;
                            @endphp
                            
                            @foreach($listing as $row)
                                    
                            
                                @if($row->meter_register_id == $room['meter']['id'])
                                    @if($isFirstRoomHeader == true)
                                        <tr style="background-color: #FFFEFE;">
                                            <td class="text-left" colspan="1">{{App\Language::trans('Room')}} : {{$room['house_room_name']}}  </td>
                                            <td  class="text-left"  colspan="6" >{{App\Language::trans('Meter Status')}} : <span class='label label-success'> {{$listing[$rowNo]->meter_register_id}} </span></td>
                                        </tr>
                                    @endif
                                    
                                    @php                
                                        $payable_amount = App\Setting::calculate_utility_fee($row->total_usage);
                                        $isFirstRoomHeader = false;         
                                        $isMeterRegister = true;
                                        $total += $row->total_usage; 
                                        $total_payable_amount += $payable_amount;
                                    @endphp     
                                        <tr>
                                            <td class="text-center">{{date('m-Y', strtotime($row->current_date))}}</td>
                                            <td class="text-center">{{$row->total_hours}}</td>
                                            <td class="text-center">{{$row->average_usage}}</td>
                                            <td class="text-center">{{$row->max_usage}}</td>
                                            <td class="text-center">{{$row->min_usage}}</td>
                                            <td class="text-center">{{$row->total_usage}}</td>
                                            <td class="text-center">{{$payable_amount}}</td>
                                        </tr>
                                        
                                @endif
                                    
                                
                                @php  
                                $rowNo++;
                                @endphp
                                
                                
                            @endforeach
                            
                            @if($isMeterRegister == false)
                                    
                                <tr>
                                    <td class="text-left" colspan="1">{{App\Language::trans('Room')}} : {{$room['house_room_name']}}  </td>
                                    <td class="text-left" colspan="6"><span class='label label-danger'> {{App\Language::trans('Meter is no yet registered in database or room')}} </span></td>
                                </tr>

                                @endif
                            
                            
                            
                            
                            
                            
                            @php  
                                $isMeterRegister = false;
                            @endphp
                            
                        @endforeach
                    @endforeach

                
                        
                    </tbody>
                    <tfoot>
                        <tr>
                            <td class="text-right" colspan="5">{{App\Language::trans('Total')}}:</td>
                            <td class="text-center">{{number_format($total, 2)}}</td>
                            <td class="text-center">{{number_format($total_payable_amount, 2)}}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        <div class="box-footer">
        </div>
    </div>
@endif
@stop
@section('script')
$(".input-daterange").datepicker({
    format: "dd-mm-yyyy",
});
@stop
© 2018 GitHub, Inc.
Terms
Privacy
Security
Status
Help
Contact GitHub
Pricing
API
Training
Blog
About
Press h to open a hovercard with more details.