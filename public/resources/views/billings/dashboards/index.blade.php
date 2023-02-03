@extends('billings.layouts.admin')
@section('content')
@if(App\LeafAPI::get_module_status([App\LeafAPI::label_power_meter]))
	<div class="row">
		<div class="col-md-3">
			<!-- small box -->
			<a href="">
				<div class="small-box bg-blue">
					<div class="inner">
						<h3 class="last_update_at">{{App\Language::trans('Last update')}}</h3>
						<p>{{$last_update_at}}</p>
					</div>
					<div class="icon">
						<i class="fa fa-clock-o fa-fw"></i>
					</div>
				</div>
			</a>
		</div>

		<div class="col-md-3">
			<!-- small box -->
			<a href="{{action('DashboardsController@getCreditListing', ['type'=>'outstanding'])}}">
				<div class="small-box bg-red">
					<div class="inner">
						<h3 class="outstanding_count">{{$outstanding_count}}</h3>

						<p>{{App\Language::trans('Credit at zero or > ')}}</p>
					</div>
					<div class="icon">
						<i class="fa fa-file-o fa-fw"></i>
					</div>
				</div>
			</a>
		</div>
		<div class="col-md-3">
			<!-- small box -->
			<a href="{{action('DashboardsController@getCreditListing', ['type'=>'min_credit'])}}">
				<div class="small-box bg-green">
					<div class="inner">
						<h3 class="min_credit_count">{{$min_credit_count}}</h3>

						<p>{{App\Language::trans('Healthy credit')}}</p>
					</div>
					<div class="icon">
						<i class="fa fa-file-o fa-fw"></i>
					</div>
				</div>
			</a>
		</div>
		<div class="col-md-3">
			<!-- small box -->
			<a href="{{action('DashboardsController@getCreditListing', ['type'=>'recent_pay'])}}">
				<div class="small-box bg-yellow">
					<div class="inner">
						<h3>{{$recent_pay_count}}</h3>

						<p>{{App\Language::trans('Payment made')}}</p>
					</div>
					<div class="icon">
						<i class="fa fa-file-o fa-fw"></i>
					</div>
				</div>
			</a>
		</div>
	</div>
@endif

<!-- Main content -->
<section class="content">
@if(App\LeafAPI::get_module_status([App\LeafAPI::label_accounting]))
<div class="row">
	<div class="col-md-4">
		<a href="{{action('CustomersController@getIndex')}}">
			<div class="small-box bg-blue">
				<div class="inner">
					<h3>{{App\Customer::total_count()}}</h3>
					<p>{{App\Language::trans('Total Customer')}}</p>
				</div>
				<div class="icon">
					<i class="fa fa-users fa-fw"></i>
				</div>
			</div>
		</a>
	</div>
	<div class="col-md-4">
		<a href="{{action('DashboardsController@getCreditListing', ['type'=>'outstanding'])}}">
			<div class="small-box bg-red">
				<div class="inner">
					<h3>{{App\MembershipModel\ARInvoice::total_count()}}</h3>

					<p>{{App\Language::trans('Total Invoice')}}</p>
				</div>
				<div class="icon">
					<i class="fa fa-file-o fa-fw"></i>
				</div>
			</div>
		</a>
	</div>
	<div class="col-md-4">
		<a href="{{action('DashboardsController@getCreditListing', ['type'=>'outstanding'])}}">
			<div class="small-box bg-green">
				<div class="inner">
					<h3>{{App\MembershipModel\ARPaymentReceived::total_count()}}</h3>

					<p>{{App\Language::trans('Total Payment Received')}}</p>
				</div>
				<div class="icon">
					<i class="fa fa-file-o fa-fw"></i>
				</div>
			</div>
		</a>
	</div>
</div>
<div class="row">
	<div class="col-md-6">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h4 class="panel-title">{{App\Language::trans('Recently Ticket Complain')}}</h4>
			</div>
			<div class="panel-body">
				<div class="table-responsive">
					<table class="table table-bordered table-hover">
						<thead>
							<tr>
								<th class="text-center">#</th>
								<th class="text-center">{{App\Language::trans('Date')}}</th>
								<th class="text-center">{{App\Language::trans('Description')}}</th>
								<th class="text-center">{{App\Language::trans('Action')}}</th>
							</tr>
						</thead>
						<tbody>
							@foreach($complains as $index => $row)
								<tr>
									<td>{{$index+1}}</td>
									<td>{{$row->display_date($row->document_date)}}</td>
									<td>{{$row->display_substr($row->complaint, 50)}}</td>
									<td>
										<a target="_blank" href="{{action('TicketsController@getSolve', [$row->id])}}">{{App\Language::trans('Solve')}}</a>
									</td>
								</tr>
							@endforeach
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
@endif

 <div class="row">
    <div class="col-md-12">
    <!-- TABLE: LATEST ORDERS -->
          <div class="box box-danger">
            <div class="box-header with-border">
              <h3 class="box-title">{{App\Language::trans('Modules Status Report')}}</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <div class="table-responsive">
                <table class="table no-margin">
                  <thead>
                  <tr>
                    <th>{{App\Language::trans('Modules')}}</th>
                    <th>{{App\Language::trans('Description')}}</th>
                    <th>{{App\Language::trans('Solution')}}</th>
                    <th>{{App\Language::trans('Action')}}</th>
                  </tr>
                  </thead>
                  <tbody>
                  @foreach($module_status_listing as $row)
	                  <tr>
	                    <td>{{App\Language::trans($row['module_name'])}}</td>
	                    <td>{{App\Language::trans($row['description'])}}</td>
	                    <td><span class="btn btn-sm btn-danger">{{App\Language::trans($row['solution'])}}</span></td>
	                    <td>
	                     <a href="{{action($row['controller'])}}" class="btn btn-sm btn-success">{{App\Language::trans('Fix now')}}</a>
	                    </td>
	                  </tr>
                  @endforeach
                  </tbody>
                </table>
              </div>
              <!-- /.table-responsive -->
            </div>
            <!-- /.box-body -->
            <div class="box-footer clearfix">
             <p><small>{{App\Language::trans('Please ensure all modules are errors free.')}}</small></p>
            </div>
            <!-- /.box-footer -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
	</div>
 <!-- /.row -->
 
      <div class="row">
        <div class="col-md-6">

         <!-- LINE CHART -->
          <div class="box box-info">
            <div class="box-header with-border">
              <i class="fa fa-bar-chart-o"></i>
              <h3 class="box-title">{{date('F-Y', strtotime('now'))}} {{App\Language::trans('Daily Transaction')}}</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <div class="box-body">
              <div class="chart">
                <canvas id="lineChart" style="height:250px"></canvas>
              </div>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box --> 
        </div>
        <!-- /.col (LEFT) -->
        <div class="col-md-6">
        
          <!-- BAR CHART -->
          <div class="box box-success">
            <div class="box-header with-border">
              <i class="fa fa-bar-chart-o"></i>
              <h3 class="box-title">{{date('Y', strtotime('now'))}} {{App\Language::trans('Monthly Transaction')}}</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <div class="box-body">
              <div class="chart">
                <canvas id="barChart" style="height:250px"></canvas>
              </div>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col (RIGHT) -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->

	{!!Form::hidden('area_chart_data', $area_chart_data, ['id'=>'area_chart_data' , 'value'=>'area_chart_data']) !!}
<br>

@endsection
@section('script')
$.get("{{action('DashboardsController@getDashboardCount')}}", function(data){
	$(".outstanding_count").html(data.outstanding_count);
	$(".min_credit_count").html(data.min_credit_count);
},"json");
@if(App\LeafAPI::get_module_status([App\LeafAPI::label_power_meter]))
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

	/*
	* BAR CHART
	* ---------
	*/

    var bar_data = {
      data : {!!$total_usage!!},
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

@endif
@endsection