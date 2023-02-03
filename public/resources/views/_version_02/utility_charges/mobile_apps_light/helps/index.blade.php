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
                                            FAQ
                                        </h5>
                                        <div class="accordion accordion-type-2 accordion-flush" id="accordion_2">
                                            @php
                                                  $counter = 0;
                                                  $state = ' activestate';
                                                  $is_show = ' show';
                                            @endphp
                                            @foreach($faq_listing as $faq)
                                                    <div class="card">
                                                        <div class="card-header d-flex justify-content-between">
                                                            <a class="collapsed" role="button" data-toggle="collapse" href="#collapse_{{$counter}}i" aria-expanded="false">{{ $faq['title'] }}</a>
                                                        </div>
                                                        <div id="collapse_{{$counter}}i" class="collapse" data-parent="#accordion_2">
                                                            <div class="card-body pa-15">{{ $faq['content'] }}</div>
                                                        </div>
                                                    </div>

                                                    @php
                                                          $counter ++;
                                                    @endphp
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

@endsection
@section('script')
@endsection
