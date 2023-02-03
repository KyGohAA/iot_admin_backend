@extends('commons.layouts.admin')
@section('content')
@include('commons.layouts.partials._alert')
@include('utility_charges.layouts.partials._meter_css')

<!-- Default box -->
<div class="box">
    <div class="box-header with-border">
        <h3 class="box-title">{{App\Language::trans('Listing Information')}}</h3>
        <div class="box-tools pull-right">
            <!--  <a href="{{action('UMeterRegistersController@getNew')}}" class="btn btn-block btn-info">
                <i class="fa fa-file"></i> {{App\Language::trans('New File')}}
            </a> -->
        </div>
    </div>
    <div class="box-body">
        <div class="table-responsive">
            <table id="leaf_data_table" class="table">
                <thead>
                    <tr>
                        <th class="text-center">{{App\Language::trans('House')}}</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $house_counter = 1;?>
                        @foreach($houses as $house)
                        <?php $room_counter = 1;?>
                            <tr>
                                <td>
                                    <hr>
                                    <div class="info-box-content">
                                        <span class="info-box-number"><span class="label label-primary pull-left">{{$house_counter}}</span> &nbsp {{$house['house_unit']}} <small class="label pull-right bg-green">id : {{$house['id_house']}} </small></span>
                                    </div>
                                    <hr> @foreach($house['house_rooms'] as $room)
                                    <?php  $meter = $room['meter'];?>
                                        <br>

                                        <button onclick="get_meter_reading_detail({{$room['id_house_room']}});" id="{{$room['id_house_room']}}" class=" box-success accordion">
                                            <span class="label label-primary pull-left">{{$room_counter}} </span>&nbsp Room {{$room['house_room_name']}}
                                            <small class="label pull-right bg-{{count($room['house_room_members']) > 0 ? 'green' : 'red'}}">{{count($room['house_room_members']) > 0 ? "Tenanted" : "Empty"}}</small>
                                            <small class="label pull-right bg-{{isset($meter) ? ($meter['contract_no'] ? 'green' : 'red') : 'red'}}">{{isset($meter) ? ($meter['contract_no'] ? 'Contact No. Completed' : 'Contact No. missing') : 'Contact No. missing'}}</small>
                                            <small class="label pull-right bg-green">{{$room['house_room_type']}}  </small>
                                            <small class="label pull-right bg-green">Id : {{$room['id_house_room']}}</small> &nbsp
                                            <small class="label pull-right bg-{{isset($meter) ? 'green' : 'red'}}">{{isset($meter) ? "Up" : "Down"}}</small>
                                        </button>

                                        <div class="panel">
                                            <hr>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <!-- /.box -->
                                                    <div class="box box-success box-solid">
                                                        <!-- /.box-header -->
                                                        <div class="box-header">
                                                            <h3 class="box-title">{{App\Language::trans('Tenant Detail')}}</h3>
                                                        </div>
                                                        <!-- /.box-header -->

                                                        <!-- /.box-body -->
                                                        <div class="box-body">
                                                            <?php  $i = 1; ?>
                                                                <table class="table table-hover">
                                                                    <tr>
                                                                        <th style="width: 10px">#</th>
                                                                        <th>{{App\Language::trans('Name')}}</th>
                                                                        <th>{{App\Language::trans('Check In Date')}}</th>
                                                                        <th>{{App\Language::trans('Total Consumption (kwh)')}}</th>
                                                                    </tr>
                                                                @if(isset(($room['house_room_members'])))
                                                                    @foreach($room['house_room_members'] as $member)
                                                                        <tr>
                                                                            <td><span class="label label-primary pull-left">{{$i}} </span> </td>
                                                                            <td>{{$member['house_member_name']}}</td>
                                                                            <td>{{$member['house_room_member_start_date']}}
                                                                            </td>
                                                                            <td><label id="lbl_{{$member['house_member_id_user']}}_total_usage_{{$room['id_house_room']}}"><img src="{{asset(App\Setting::LOADING_GIF)}}" alt=""/></label></td>
                                                                        </tr>
                                                                        <?php $i ++;?>
                                                                    @endforeach
                                                                @endif
                                                                </table>
                                                        </div>
                                                        <!-- /.box-body -->
                                                    </div>
                                                    <!-- /.box -->

                                                    <!-- /.box -->
                                                    <div class="box box-success box-solid">
                                                        <div class="box-header">
                                                            <h3 class="box-title">{{App\Language::trans('Reading Monitoring')}}</h3>
                                                        </div>
                                                        <!-- /.box-header -->

                                                        <!-- /.box-body -->
                                                        <div class="box-body">
                                                            <table class="table table-hover">
                                                                <tr>
                                                                    <th style="width: 10px">#</th>
                                                                    <th>{{App\Language::trans('Item')}}</th>
                                                                    <th></th>
                                                                </tr>

                                                                <tr>
                                                                    <td><span class="label label-primary pull-left">1</span> </td>
                                                                    <td>{{App\Language::trans('Status')}}</td>
                                                                    <td>{{isset($meter) ? App\Language::trans('Meter register set') : App\Language::trans('No meter register is set')}}</td>
                                                                </tr>

                                                                <tr>
                                                                    <td><span class="label label-primary pull-left">2</span> </td>
                                                                    <td>{{App\Language::trans('Last Reading At')}}</td>
                                                                    <td><label id="lbl_last_reading_at_{{$room['id_house_room']}}"><img src="{{asset(App\Setting::LOADING_GIF)}}" alt=""/></label><!-- {{$meter['last_reading_at']}} --></td>
                                                                </tr>

                                                                <tr>
                                                                    <td><span class="label label-primary pull-left">3</span> </td>
                                                                    <td> {{App\Language::trans('Last Current Reading')}}</td>
                                                                    <td><label id="lbl_last_reading_{{$room['id_house_room']}}"><img src="{{asset(App\Setting::LOADING_GIF)}}" alt=""/></label><!-- {{$meter['last_reading']}} --></td>
                                                                </tr>

                                                                <tr>
                                                                    <td><span class="label label-primary pull-left">4</span></td>
                                                                    <td> {{App\Language::trans('Monthly Usage Reading')}}</td>
                                                                    <td>{{$meter['monthly_usage']}}
                                                                          <label id="lbl_monthly_usage_{{$room['id_house_room']}}"><img src="{{asset(App\Setting::LOADING_GIF)}}" alt=""/></label>                                                                        
                                                                    </td>
                                                                </tr>

                                                            </table>
                                                        </div>
                                                        <!-- /.box-body -->
                                                    </div>
                                                    <!-- /.box -->
                                                </div>

                                                <!-- COL START -->
                                                <div class="col-md-6">
                                                    @if(isset($meter))
                                                        {!! Form::model($meter, ['class'=>'form-horizontal']) !!}
                                                        <input type="hidden" id="meter" name="meter" value="{{json_encode($meter)}}">
                                                            <div class="box box-success box-solid">
                                                                <!-- /.box-header -->
                                                                <div class="box-header">
                                                                    <h3 class="box-title">{{App\Language::trans('Meter register Detail')}}</h3>
                                                                </div>
                                                                <!-- /.box-header -->

                                                                <!-- /.box-body -->
                                                                <div class="box-body">

                                                                    <table class="table table-hover">
                                                                        <tr>
                                                                            <th style="width: 10px">#</th>
                                                                            <th>{{App\Language::trans('Item')}}</th>
                                                                            <th></th>
                                                                        </tr>

                                                                        <tr>
                                                                            <td><span class="label label-primary pull-left">1 </span></td>
                                                                            <td>{{App\Language::trans('Meter ID')}}</td>
                                                                            <td>{{$meter['id']}}
                                                                            </td>
                                                                        </tr>

                                                                        <tr>
                                                                            <td><span class="label label-primary pull-left">2 </span></td>
                                                                            <td>{{App\Language::trans('IP')}}</td>
                                                                            <td>{!! Form::text('ip_address', null, ['class'=>'form-control','required']) !!}
                                                                            </td>
                                                                        </tr>

                                                                        <tr>
                                                                            <td><span class="label label-primary pull-left">3 </span></td>
                                                                            <td>{{App\Language::trans('Contact No')}}</td>
                                                                            <td>{!! Form::text('contract_no', null, ['class'=>'form-control','required']) !!}
                                                                            </td>
                                                                        </tr>

                                                                        <tr>
                                                                            <td><span class="label label-primary pull-left">4 </span></td>
                                                                            <td>{{App\Language::trans('Created At')}}</td>
                                                                            <td>{{$meter['created_at']}}
                                                                            </td>
                                                                        </tr>

                                                                        <tr>
                                                                            <td><span class="label label-primary pull-left">5 </span></td>
                                                                            <td>{{App\Language::trans('Updated At')}}</td>
                                                                            <td>{{$meter['updated_at']}}
                                                                            </td>
                                                                        </tr>

                                                                    </table>
                                                                </div>
                                                                <!-- /.box-body -->
                                                            </div>
                                                            <!-- /.box -->

                                                            <div class="col-md-offset-2 col-md-10">
                                                                <button type="submit" class="btn btn-primary pull-right"><i class="fa fa-floppy-o fa-fw"></i>{{App\Language::trans('Save')}}</button>
                                                            </div>
                                                            {!! Form::close() !!}
                                                        @else
                                                            <div class="box box-success box-solid">
                                                                <!-- /.box-header -->
                                                                <div class="box-header">
                                                                    <h3 class="box-title">{{App\Language::trans('No Meter Found')}}</h3>
                                                                </div>
                                                                <!-- /.box-header -->

                                                                <!-- /.box-body -->
                                                                <div class="box-body">

                                                                    <table class="table table-hover">
                                                                        <tr>
                                                                            <div class="col-md-offset-6 col-md-10">                                                
                                                                                   <a href="{{action('UMeterRegistersController@getNew', $room)}}" class="btn btn-primary pull-rigth"><i class="fa fa-floppy-o fa-fw"></i> <span>{{App\Language::trans('Register New Meter')}}</span></a>
                                                                            </div>
                                                                        </tr>


                                                                    </table>
                                                                </div>
                                                                <!-- /.box-body -->
                                                            </div>
                                                            <!-- /.box -->

                                                        @endif
                                                </div>
                                                <!-- COL END -->
                                            </div>
                                            <hr>
                                            <?php $room_counter ++;?>
                                        </div>
                                        @endforeach
                                </td>
                            </tr>
                            <?php $house_counter ++;?>
                        @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <!-- /.box-body -->

    <!-- /.box-footer-->
    <div class="box-footer">
    </div>
    <!-- /.box-footer-->

</div>
<!-- /.box -->
@endsection
@section('script')
	var acc = document.getElementsByClassName("accordion");
	var i;
	for (i = 0; i < acc.length; i++) {
	    acc[i].addEventListener("click", function() {
	        this.classList.toggle("active");
	        var panel = this.nextElementSibling;
	        if (panel.style.display === "block") {
	            panel.style.display = "none";
	        } else {
	            panel.style.display = "block";
	        }
	    });
	}

@endsection