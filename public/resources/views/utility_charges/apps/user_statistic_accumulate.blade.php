<div class="row">
    <div class="col-md-12" style="padding-bottom:50px;">
        <!-- DIRECT CHAT PRIMARY -->
        <div class="box box-info box-solid" style="background-color:#b3d9fc;  height:100%;">
            <div class="box-header with-border" style="background-color:#59abf7">
                <h3 class="box-title">{{App\Language::trans('Statistics')}}</h3>

                <!-- <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                </div> -->
            </div>
            <!-- /.box-header -->
            <div class="box-body">
               <!--  <small>{{App\Language::trans('Until')}}</small> -->

                <div class="block">
                    {{App\Language::trans('Current usage amount')}} :
                    <br>
                    <strong>RM {{$statistic['currentUsageCharges']}}</strong> : <strong>{{$statistic['currentUsageKwh']}} {{App\Language::trans('kWh')}}</strong>
                    <br>
                    <br> {{App\Language::trans('Balance amount')}} :
                    <br>
                    <strong>RM {{$statistic['balanceAmount']}} </strong>: <strong>{{$statistic['currentBalanceKwh']}}  {{App\Language::trans('kWh')}}</strong>
                    <br>
                </div>

            </div>
            <!-- /.box-body -->
            <div class="box-footer">
                <div id="donut-chart" style="height: 300px;"></div>
                <p class="text-muted well well-sm no-shadow" style="margin-top: 10px;">
           {{App\Language::trans('Last update on')}} : {{$last_reading_date_time}}
          </p>
            </div>
            <!-- /.box-footer-->
        </div>
        <!--/.direct-chat -->
    </div>
    <!-- /.col -->
</div>
<!-- /.row -->

@section('script') 
    


@stop