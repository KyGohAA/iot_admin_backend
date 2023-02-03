@extends('umrah.layouts.admin')
@section('content')
<div class="row">
	<div class="col-md-12">
		<!-- Bar chart -->
		<div class="box box-primary">
			<div class="box-header with-border">
				<i class="fa fa-bar-chart-o"></i>

				<h3 class="box-title">Carta Bar</h3>

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
</div>
@endsection
@section('script')
    /*
     * BAR CHART
     * ---------
     */

    var bar_data = {
      data : [['27/08', 20], ['28/08', 8], ['29/08', 4], ['30/08', 13], ['31/08', 17], ['01/09', 150]],
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

@endsection