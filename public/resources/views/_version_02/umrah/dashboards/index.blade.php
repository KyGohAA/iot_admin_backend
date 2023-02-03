@extends('umrah.layouts.admin')
@section('content')
<div class="row">
	<div class="col-xs-6">
		<!-- small box -->
		<div class="small-box bg-red">
			<div class="inner">
				<h3>134</h3>

				<p>Kemalangan</p>
			</div>
			<div class="icon">
				<i class="fa fa-heartbeat"></i>
			</div>
			<a href="{{action('DashboardsController@getReports')}}" class="small-box-footer">
				Maklumat lanjut <i class="fa fa-arrow-circle-right"></i>
			</a>
		</div>
	</div>
	<div class="col-xs-6">
		<!-- small box -->
		<div class="small-box bg-green">
			<div class="inner">
				<h3>163</h3>

				<p>Makan Malam</p>
			</div>
			<div class="icon">
				<i class="fa fa-cutlery"></i>
			</div>
			<a href="{{action('DashboardsController@getReports')}}" class="small-box-footer">
				Maklumat lanjut <i class="fa fa-arrow-circle-right"></i>
			</a>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-xs-6">
		<!-- small box -->
		<div class="small-box bg-yellow">
			<div class="inner">
				<h3>132</h3>

				<p>Di Arafah</p>
			</div>
			<div class="icon">
				<i class="fa fa-home"></i>
			</div>
			<a href="{{action('DashboardsController@getReports')}}" class="small-box-footer">
				Maklumat lanjut <i class="fa fa-arrow-circle-right"></i>
			</a>
		</div>
	</div>
	<div class="col-xs-6">
		<!-- small box -->
		<div class="small-box bg-aqua">
			<div class="inner">
				<h3>12</h3>

				<p>Jumlah Mutawiff</p>
			</div>
			<div class="icon">
				<i class="fa fa-users"></i>
			</div>
			<a href="{{action('DashboardsController@getReports')}}" class="small-box-footer">
				Maklumat lanjut <i class="fa fa-arrow-circle-right"></i>
			</a>
		</div>
	</div>
</div>
<hr>
<div class="row">
	<div class="col-md-6">
		<!-- Bar chart -->
		<div class="box box-primary">
			<div class="box-header with-border">
				<i class="fa fa-bar-chart-o"></i>

				<h3 class="box-title">Kemalangan</h3>

				<div class="box-tools pull-right">
					<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
					</button>
					<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
				</div>
			</div>
			<div class="box-body">
				<div id="bar-chart" style="height: 300px;"></div>
			</div>
			<!-- /.box-body-->
		</div>
		<!-- /.box -->
	</div>
	<div class="col-md-6">
		<!-- Donut chart -->
		<div class="box box-primary">
			<div class="box-header with-border">
				<i class="fa fa-bar-chart-o"></i>

				<h3 class="box-title">Makan Malam</h3>

				<div class="box-tools pull-right">
					<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
					</button>
					<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
				</div>
			</div>
			<div class="box-body">
				<div id="donut-chart" style="height: 300px;"></div>
			</div>
		<!-- /.box-body-->
		</div>
	</div>
</div>
@endsection
@section('script')
	/*
	* BAR CHART
	* ---------
	*/

    var bar_data = {
      data : [['27/08', 20], ['28/08', 8], ['29/08', 4], ['30/08', 13], ['31/08', 17], ['01/09', 134]],
      color: '#3c8dbc'
    }
    $.plot('#bar-chart', [bar_data], {
      grid  : {
        borderWidth: 1,
        borderColor: '#f3f3f3',
        tickColor  : '#f3f3f3'
      },
      series: {
        bars: {
          show    : true,
          barWidth: 0.5,
          align   : 'center'
        }
      },
      xaxis : {
        mode      : 'categories',
        tickLength: 0
      }
    })
    /* END BAR CHART */

    /*
     * DONUT CHART
     * -----------
     */

    var donutData = [
      { label: 'Sudah', data: 30, color: '#3c8dbc' },
      { label: 'Belum', data: 50, color: '#00c0ef' }
    ]
    $.plot('#donut-chart', donutData, {
      series: {
        pie: {
          show       : true,
          radius     : 1,
          innerRadius: 0.5,
          label      : {
            show     : true,
            radius   : 2 / 3,
            formatter: labelFormatter,
            threshold: 0.1
          }

        }
      },
      legend: {
        show: false
      }
    })
    /*
     * END DONUT CHART
     */


	/*
	* Custom Label formatter
	* ----------------------
	*/
	function labelFormatter(label, series) {
		return '<div style="font-size:13px; text-align:center; padding:2px; color: #fff; font-weight: 600;">'
		  + label
		  + '<br>'
		  + Math.round(series.percent) + '%</div>'
	}
@endsection