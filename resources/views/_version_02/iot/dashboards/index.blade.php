@extends('_version_02.iot.layouts.admin')
@section('content')
      
            <div class="row clearfix g-3 mb-3">
                <div class="col-md-8 col-sm-12">
                    <div class="card">
                        <div class="header">
                            <h2>Weekly Daylight Report</h2>
                            <ul class="header-dropdown m-r--5">
                                <li class="dropdown"> <a href="javascript:void(0);" class="dropdown-toggle" data-bs-toggle="dropdown"
                                        role="button" aria-haspopup="true" aria-expanded="false"> <i
                                            class="zmdi zmdi-more-vert"></i> </a>
                                    <ul class="dropdown-menu pull-right">
                                        <li><a href="javascript:void(0);">All On</a></li>
                                        <li><a href="javascript:void(0);">All Off</a></li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                        <div class="body">
                            <div id="bar_chart" class="graph"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-sm-12">
                    <div class="card">
                        <div class="header">
                            <h2>Daylight Report</h2>
                            <ul class="header-dropdown m-r--5">
                                <li class="dropdown"> <a href="javascript:void(0);" class="dropdown-toggle" data-bs-toggle="dropdown"
                                        role="button" aria-haspopup="true" aria-expanded="false"> <i
                                            class="zmdi zmdi-more-vert"></i> </a>
                                    <ul class="dropdown-menu pull-right">
                                        <li><a href="javascript:void(0);">All On</a></li>
                                        <li><a href="javascript:void(0);">All Off</a></li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                        <div class="body">
                            <div id="donut_chart" class="graph"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row clearfix g-3 mb-3">
                @php //dd($graph_info); @endphp
            @if(count($graph_info) > 0)
                @foreach($graph_info as $info)
                    @if($info['status_code'] == 1)
                            @php 
                                $graph_info_data = $info['data']['graph_info'];
                                $graph_keys = $info['data']['graph_keys'];

                                //dd($info);
                            @endphp
                            @foreach( $graph_info_data as $g_index => $g_data)


                                 @php 
                                    //dd( $g_key);
                                    //dd($info);
                                    //dd($info['data']['data'][$graph_keys[$index]]);

                                    //dd($graph_keys[$index]);

                                    $graph_data = isset($info['data']['data'][$graph_keys[$index]]) ? $info['data']['data'][$graph_keys[$index]] : false;
                                   
                                    $date_range = isset($info['data']['date_range']) ? $info['data']['date_range']: false;


                                    $avg = isset($info['data']['graph_average']) ? $info['data']['graph_average']: false;

                                    $symbol = isset($info['data']['symbols'][$graph_keys[$g_index]]) ? $info['data']['symbols'][$graph_keys[$g_index]]: '-';
                                    

                                    $avg_val = isset($avg[$graph_keys[$g_index]]) ? $avg[$graph_keys[$g_index]] : '-';
                                    if($graph_keys[$g_index] == 'temperature' || $graph_keys[$g_index] == 'humidity' ){
                                        $avg_val = number_format((float)$avg_val, 2, '.', '');
                                    }

                                    //dd($avg);
                                    //dd($avg[$graph_keys[$index]]);
                                 @endphp
                                <div class="col-lg-4 col-md-4 col-sm-12">
                                    <div class="card overflowhidden number-chart">
                                        <div class="body pb-0">
                                            <div class="number">
                                              
                                                <h6>{{ $g_data['title'] }}</h6>
                                                <span> {{ $avg_val }}  {{ $symbol }}</span>
                                            </div>
                                            <small class="text-muted"> {{ $date_range['start'] }} to {{ $date_range['end'] }}</small>
                                        </div>
                                        <div id="{{ $g_data['graph_id'] }}" class="text-center"></div>
                                    </div>
                                </div>
                            @endforeach
                    @endif
                @endforeach  
            @endif
            </div>

            <div class="row clearfix g-3 mb-3">
               
                <div class="col-lg-6 col-md-6 col-md-12">
                    <div class="card">
                        <div class="body">
                            <div class="clearfix">
                                <div class="float-start">
                                    <h6 class="mb-0">Main Gate - {{ $sensor['door']['name'] }}</h6>
                                </div>
                                <div class="float-end">                   
                                    <button class="btn btn-outline-success" type="button">Status : {{ ucfirst($sensor['door']['state']) }}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row clearfix g-3 mb-3">
                <div class="col-lg-4 col-md-4 col-sm-12">
                    <div class="card appliances-grp ng-star-inserted">
                        <div class="body clearfix">
                            <div class="icon"><img alt="" src="assets/images/air-conditioner.png"></div>
                            <div class="content">
                                <h6>Entrance Detector  <span class="text-success"> {{ $sensor['entrance']['name'] }}</span></h6>
                                <p class="ng-star-inserted">In  :<span style='padding-left:15px;' class="text-warning">{{ $sensor['entrance']['in'] }}</span></p>
                                <p class="ng-star-inserted">Out :<span style='padding-left:15px;' class="text-warning">{{ $sensor['entrance']['out'] }}</span></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-12">
                    <div class="card appliances-grp ng-star-inserted">
                        <div class="body clearfix">
                            <div class="icon"><img alt="" src="assets/images/fridge.png"></div>
                            <div class="content">
                                <h6 _ngcontent-c23="">Room Environment <span class="text-success"> {{ $sensor['environment']['name'] }} </span></h6>
                                <p class="ng-star-inserted">Temprature <span class="text-primary"> {{ $sensor['environment']['temperature'] }}  ° C</span></p>
                                <p class="ng-star-inserted">Humidity <span class="text-success"> {{ $sensor['environment']['humidity'] }} </span></p>
                            </div>
                        </div>
                    </div>
                </div>
                 <div class="col-lg-4 col-md-4 col-sm-12">
                    <div class="card appliances-grp ng-star-inserted">
                        <div class="body clearfix">
                            <div class="icon"><img alt="" src="assets/images/fridge.png"></div>
                            <div class="content">
                                <h6 _ngcontent-c23="">Light Sensor<span class="text-success"> {{ $sensor['environment']['name'] }} </span></h6>
                                <p class="ng-star-inserted">Day Light <span class="text-primary"> {{ $sensor['environment']['daylight'] }}</span></p>
                                <p class="ng-star-inserted">PIR <span class="text-success"> {{ $sensor['environment']['pir'] }} </span></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            

            <div class="row clearfix g-3 mb-3">

@endsection
@section('script')


@endsection