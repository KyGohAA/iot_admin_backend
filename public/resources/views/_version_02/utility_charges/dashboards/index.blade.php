@extends('commons.layouts.admin')
@section('content')
<div class="row">
	<div class="col-xs-6 col-md-4">
		<!-- small box -->
		<div class="small-box bg-red">
			<div class="inner">
				<h3>{{App\MeterInvoice::total_invoices()}}</h3>

				<p>{{App\Language::trans('Total Invoices')}}</p>
			</div>
			<div class="icon">
				<i class="fa fa-file-o fa-fw"></i>
			</div>
		</div>
	</div>

	<div class="col-xs-6 col-md-4">
		<!-- small box -->
		<div class="small-box bg-green">
			<div class="inner">
				<h3>{{App\MeterInvoice::total_invoices(true)}}</h3>

				<p>{{App\Language::trans('Paid Invoices')}}</p>
			</div>
			<div class="icon">
				<i class="fa fa-file-o fa-fw"></i>
			</div>
		</div>
	</div>

	<div class="col-xs-6 col-md-4">
		<!-- small box -->
		<div class="small-box bg-yellow">
			<div class="inner">
				<h3>{{App\MeterInvoice::total_invoices(false)}}</h3>

				<p>{{App\Language::trans('Outstanding Invoices')}}</p>
			</div>
			<div class="icon">
				<i class="fa fa-file-o fa-fw"></i>
			</div>
		</div>
	</div>

</div>

<hr>

<div class="row">
	<div class="col-md-6">
		<!-- Donut chart -->
		<div class="box box-primary">
			<div class="box-header with-border">
				<i class="fa fa-bar-chart-o"></i>

				<h3 class="box-title">{{App\Language::trans('Invoices')}}</h3>

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
     * DONUT CHART
     * -----------
     */

    var donutData = [
      { label: 'Paid', data: {{App\MeterInvoice::total_invoices(true)}}, color: '#3c8dbc' },
      { label: 'Unpaid', data: {{App\MeterInvoice::total_invoices(false)}}, color: '#00c0ef' }
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