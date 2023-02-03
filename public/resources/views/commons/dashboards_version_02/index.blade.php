@extends('commons.layouts_version_02.admin')
@section('content')
@if(App\LeafAPI::get_module_status([App\LeafAPI::label_power_meter]))
	<div class="hk-row">
		<div class="col-lg-3 col-sm-6">
			<div class="card card-sm">
				<div class="card-body">
					<span class="d-block font-11 font-weight-500 text-dark text-uppercase mb-10 last_update_at">{{App\Language::trans('Last update')}}</span>
					<div class="d-flex align-items-center justify-content-between position-relative">
						<div>
							<span class="d-block display-6 font-weight-400 text-dark">{{$last_update_at}}</span>
						</div>
						<div class="position-absolute r-0">
							<span id="pie_chart_1" class="d-flex easy-pie-chart" data-percent="86">
								<span class="percent head-font">86</span>
							</span>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-lg-3 col-sm-6">
			<a href="{{action('DashboardsController@getCustomerPowerUsageSummary', ['type'=>'outstanding'])}}">
			<div class="card card-sm">
				<div class="card-body">
					<span class="d-block font-11 font-weight-500 text-dark text-uppercase mb-10">{{App\Language::trans('Credit at zero or > ')}}</span>
					<div class="d-flex align-items-center justify-content-between position-relative">
						<div>
							<span class="d-block">
								<span class="display-6 font-weight-400 text-dark"><span class="counter-anim">{{$outstanding_count}}</span></span>
							</span>
						</div>
						<div class="position-absolute r-0">
							<span id="pie_chart_2" class="d-flex easy-pie-chart" data-percent="75">
								<span class="percent head-font">75</span>
							</span>
						</div>
					</div>
				</div>
			</div>
			</a>
		</div>
		<div class="col-lg-3 col-sm-6">
			<a href="{{action('DashboardsController@getCustomerPowerUsageSummary', ['type'=>'min_credit'])}}">
			<div class="card card-sm">
				<div class="card-body">
					<span class="d-block font-11 font-weight-500 text-dark text-uppercase mb-10">{{App\Language::trans('Healthy credit')}}</span>
					<div class="d-flex align-items-end justify-content-between">
						<div>
							<span class="d-block">
								<span class="display-6 font-weight-400 text-dark">{{$min_credit_count}}</span>
								<small></small>
							</span>
						</div>
						<div>
							<span class="text-success font-12 font-weight-600">+5%</span>
						</div>
					</div>
				</div>
			</div>
			</a>
		</div>
		<div class="col-lg-3 col-sm-6">
			<a href="{{action('DashboardsController@getCustomerPowerUsageSummary', ['type'=>'recent_pay'])}}">
			<div class="card card-sm">
				<div class="card-body">
					<span class="d-block font-11 font-weight-500 text-dark text-uppercase mb-10">{{App\Language::trans('Payment made')}}</span>
					<div class="d-flex align-items-end justify-content-between">
						<div>
							<span class="d-block">
								<span class="display-6 font-weight-400 text-dark">{{$recent_pay_count}}</span>
							</span>
						</div>
						<div>
							<span class="text-danger font-12 font-weight-600">-12%</span>
						</div>
					</div>
				</div>
			</div>
			</a>
		</div>
	</div>

	<div class="hk-row">
		<div class="col-lg-6">
			<div class="card card-refresh">
				<div class="refresh-container">
					<div class="loader-pendulums"></div>
				</div>
				<div class="card-header card-header-action">
					<h6>{{date('F-Y', strtotime('now'))}} {{App\Language::trans('Daily Usage')}}</h6>
					<div class="d-flex align-items-center card-action-wrap">
						<a href="#" class="inline-block refresh mr-15">
							<i class="ion ion-md-radio-button-off"></i>
						</a>
						<a href="#" class="inline-block full-screen">
							<i class="ion ion-md-expand"></i>
						</a>
					</div>
				</div>
				<div class="card-body">
					
						<div class="chart">
			                <canvas id="lineChart" style="height:150px"></canvas>
			            </div>
				
				</div>
			</div>
		</div>
		<div class="col-lg-6">
			<div class="card">
				<div class="card-header card-header-action">
					<h6>{{date('Y', strtotime('now'))}} {{App\Language::trans('Monthly Usage')}}</h6>
					<div class="d-flex align-items-center card-action-wrap">
						<a href="#" class="inline-block refresh mr-15">
							<i class="ion ion-md-radio-button-off"></i>
						</a>
						<a href="#" class="inline-block full-screen">
							<i class="ion ion-md-expand"></i>
						</a>
					</div>
				</div>
				<div class="card-body">
					
						<div class="chart">
			                <canvas id="barChart" style="height:150px"></canvas>
			            </div>
				
				</div>
			</div>
		</div>
	</div>
@endif

<div class="card">
	<div class="card-header card-header-action">
		<h6>{{App\Language::trans('Modules Status Report')}}</h6>
		<div class="d-flex align-items-center card-action-wrap">
			<div class="toggle toggle-sm toggle-simple toggle-light toggle-bg-primary risk-switch"></div>
		</div>
	</div>
	<div class="card-body pa-0">
		<div class="table-wrap">
			<div class="table-responsive">
				<table class="table table-sm table-hover mb-0">
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
		</div>
	</div>
</div>	
{!!Form::hidden('area_chart_data', $area_chart_data, ['id'=>'area_chart_data' , 'value'=>'area_chart_data']) !!}
@endsection
@section('script')
$.get("{{action('DashboardsController@getDashboardCount')}}", function(data){
	$(".outstanding_count").html(data.outstanding_count);
	$(".min_credit_count").html(data.min_credit_count);
},"json");

@endsection