@extends('_version_02.utility_charges.mobile_apps_light.layouts.main')
@section('content')
{!! Form::model($model, ['class'=>'form-horizontal',"files"=>true]) !!}

      <!-- Row -->
                <div class="row">
                    <div class="col-xl-12 pa-0">
                       
                        <div class="faq-content container-fluid">
                            <div class="hk-row">
                               
                                <div class="col-xl-8">
                                    <div class="card card-lg">
                                        <h5 class="card-header border-bottom-0">
                                            History
                                        </h5>
                                        <div class="accordion accordion-type-2 accordion-flush" id="accordion_2">
                                            
                                            @php
                                                  $state = ' activestate';
                                                  $is_show = ' show';
                                            @endphp

                                            @foreach($transaction_listing as $transaction_item)
                                               
                                                @php
                                                  $accordion_id =  strtolower( str_replace( ' ' , '_',$transaction_item['title']));
                                                @endphp

                                                <div class="card">
                                                    <div class="card-header d-flex justify-content-between">
                                                        <a class="collapsed" role="button" data-toggle="collapse" href="#{{ $accordion_id }}" aria-expanded="false"> {{ $transaction_item['title'] }}</a>
                                                    </div>
                                                    <div id="{{ $accordion_id }}" class="collapse" data-parent="#accordion_2">
                                                     
                                                           <div class="card-body pa-15">
                                                               <div class="row">
                                                                    <div class="col-sm">
                                                                        <div class="table-wrap">
                                                                           <!-- @if(isset($transaction_item['data']))
                                                                            <table id="datable_2" class="table table-hover w-100 display">
                                                                                <thead>
                                                                                    <tr>
                                                                                    @if(isset($transaction_item['data_headers']))
                                                                                      @foreach ($transaction_item['data_headers'] as $header)
                                                                                        <th>{{ $header }}</th>
                                                                                      @endforeach 
                                                                                    @endif                                                  
                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                     
                                                                                     @if(isset($transaction_item['data']))
                                                                                         @php $counter =0 ; @endphp
                                                                                         @foreach ($transaction_item['data'] as $transaction_data)
                                                                                            <tr>
                                                                                                <td>{{ $transaction_data['document_data'] }}</td>
                                                                                                <td>{{ $transaction_data['document_no'] }}</td>
                                                                                                <td>{{ $transaction_data['amount'] }}</td>  
                                                                                            </tr>

                                                                                         @endforeach
                                                                                     @endif

                                                                                   
                                                                                </tbody>
                                                                            </table>
                                                                            @endif -->
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                          </div>
                                                    </div>
                                                </div>

                                            @endforeach

                                          
                                        
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /Row -->

{!! Form::close() !!}
<br><br><br>
@endsection
@section('script')
@endsection
