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

                                        <button id="{{$room['id_house_room']}}" class=" box-success accordion">
                                            <span class="label label-primary pull-left">{{$room_counter}} </span>&nbsp Room {{ucfirst($room['house_room_name'])}}
                                            <small class="label pull-right bg-{{count($room['house_room_members']) > 0 ? 'green' : 'red'}}">{{count($room['house_room_members']) > 0 ? "Tenanted" : "Empty"}}</small>
                                            <small class="label pull-right bg-green">{{ucfirst($room['house_room_type'])}}  </small>
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
                                                                    </tr>
                                                                @if(isset(($room['house_room_members'])))
                                                                    @foreach($room['house_room_members'] as $member)
                                                                        <tr>
                                                                            <td><span class="label label-primary pull-left">{{$i}} </span> </td>
                                                                            <td>{{$member['house_member_name']}}</td>
                                                                            <td>{{$member['house_room_member_start_date']}}
                                                                            </td>
                                                                        </tr>
                                                                        <?php $i ++;?>
                                                                    @endforeach
                                                                    <?php  $meter = $room['meter'];?>
                                                                    @if(isset($meter['id']))
                                                                        <?php   $is_loop = true;
                                                                                $is_reach = false; 
                                                                        ?>
                                                                        <tr>
                                                                            <th style="width: 10px">#</th>
                                                                            <th style="width: 10px">{{App\Language::trans('Time')}}</th>
                                                                            <th style="width: 10px">{{App\Language::trans('Reading')}}</th>
                                                                        </tr>
                                                                        @foreach($listing as $row)
                                                                            @if($row['meter_register_id'] == $meter['id'] && $is_loop == true)
                                                                                <tr>
                                                                                    <td></td>
                                                                                    <td>$row['current_meter_reading']</td>
                                                                                    <td>$row['created_at']</td>
                                                                                </tr>
                                                                                <?php  $is_reach = true;?>
                                                                            @elseif($is_reach == true  && $is_loop == true)
                                                                                 <?php  $is_loop = false;?>
                                                                            @endif
                                                                            @if($is_loop == false)
                                                                                continue;
                                                                            @endif
                                                                        @endforeach
                                                                    @endif
                                                                @endif
                                                                </table>
                                                        </div>
                                                        <!-- /.box-body -->
                                                    </div>
                                                    <!-- /.box -->

                                                    <!-- /.box -->
                                                
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