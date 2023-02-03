<?php $__env->startSection('content'); ?>
<?php if(App\Company::is_allow_to_access_module(App\Setting::LABEL_MODULE_POWER_MANAGEMENT)): ?>
	<div class="hk-row">
		<div class="col-lg-3 col-sm-6">
			<div class="card card-sm">
				<div class="card-body">
					<span class="d-block font-11 font-weight-500 text-dark text-uppercase mb-10"><?php echo e(App\Language::trans('Last update')); ?></span>
					<div class="d-flex align-items-center justify-content-between position-relative">
						<div>
							<small><span class="d-block display-7 font-weight-1200 text-success" id='last_update_at'><i id="last_update_at_loading" class="fas fa-circle-notch fa-spin"></i></span></small>
						</div>
						<!-- <div>
							<span class="text-success font-12 font-weight-600" id='last_update_at'>  ...</span>
						</div> -->
					</div>
				</div>
			</div>
		</div>


		<div class="col-lg-3 col-sm-6">
			<a class="loading-label" href="<?php echo e(action('DashboardsController@getCustomerPowerUsageSummary', ['type'=>'outstanding'])); ?>">
			<div class="card card-sm">				
				<div class="card-body">

					<?php
							$company            =   new App\Company();
					   		$company            =   $company->self_profile();
					?>
		
					<span class="d-block font-11 font-weight-500 text-dark text-uppercase mb-10"><?php echo e(App\Language::trans('Credit Below').' '.$company->getCurrenncyCode().' '.$company->getPMOperationalSetting()->credit_threshold); ?></span>
					<div class="d-flex align-items-center justify-content-between position-relative">
						<div>
							<span class="d-block">
								<span class="display-7 font-weight-400 text-dark"><span class="counter-anim" id='outstanding_count'><i class="fas fa-circle-notch fa-spin"></i></span></span>
							</span>
						</div>
						<div>
							<span class="text-success font-12 font-weight-600" id='today_new_safety_count'>  ...</span>
						</div>
					</div>
				</div>
			</div>
			</a>
		</div>
		<div class="col-lg-3 col-sm-6">
			<a class="loading-label" href="<?php echo e(action('DashboardsController@getCustomerPowerUsageSummary', ['type'=>'min_credit'])); ?>">
			<div class="card card-sm">
				<div class="card-body">
					<span class="d-block font-11 font-weight-500 text-dark text-uppercase mb-10"><?php echo e(App\Language::trans('Healthy credit').' ( > '.' '.$company->getCurrenncyCode().' '.$company->getPMOperationalSetting()->credit_threshold.' )'); ?></span>
					<div class="d-flex align-items-end justify-content-between">
						<div>
							<span class="d-block">
								<span class="display-7 font-weight-400 text-dark" id='min_credit_count'> <i id="min_credit_loading" class="fas fa-circle-notch fa-spin"></i></span>
								<small></small>
							</span>
						</div>
						<div>
							<span class="text-success font-12 font-weight-600" id=''>  ...</span>
						</div>
					</div>
				</div>
			</div>
			</a>
		</div>
		<div class="col-lg-3 col-sm-6">
			<a class="loading-label" href="<?php echo e(action('DashboardsController@getCustomerPowerUsageSummary', ['type'=>'recent_pay'])); ?>">
			<div class="card card-sm">
				<div class="card-body">
					<span class="d-block font-11 font-weight-500 text-dark text-uppercase mb-10"><?php echo e(App\Language::trans('Payment made')); ?></span>
					<div class="d-flex align-items-end justify-content-between">
						<div>
							<span class="d-block">
								<span class="display-7 font-weight-400 text-dark" id='meter_payment_received_count'> <i class="fas fa-circle-notch fa-spin"></i></span>
							</span>
						</div>
						<div>
							<span class="text-danger font-12 font-weight-600" id='today_meter_payment_received_count'>...</span>
						</div>
					</div>
				</div>
			</div>
			</a>
		</div>
	</div>

	<?php if(isset($company['system_alive_at'])): ?>
			<div class="hk-row">
				<div class="col-lg-12">
					<div class="card">
						<div class="card-header card-header-action">
							<h6 style=" text-transform: none;">Remote Server Monitoring</h6>
							<div class="d-flex align-items-center card-action-wrap">
								<span style=" text-transform: none; align:left;"><?php echo e(App\Language::trans('Last contact at :')); ?> <?php echo e($company['system_alive_at']); ?></span>

								<?php
									$alive_status = 'success';
									$origin = date_create($company['system_alive_at']);
									$target = date_create(date('Y-m-d H:i:s',strtotime('now')));
									$interval = date_diff($origin, $target);
									$interval_mins = App\Setting::getIntervalInMinutes($interval);	
									if($interval_mins > 360)
									{
										$alive_status = 'danger';
									}

									$lost_contact_period_mins = $interval->format('%a days %h hours %i minutes') ;

									
								?>
								<span style="margin-left:20px;" class="btn btn-sm btn-<?php echo e($alive_status); ?>"><?php echo e(App\Language::trans('')); ?> <?php echo e($lost_contact_period_mins); ?></span>
							</div>
						</div>
					</div>	
				</div>
			</div>
	<?php endif; ?>

	<div class="hk-row">
		<div class="col-lg-6">
			<div class="card card-refresh">
				<div class="refresh-container">
					<div class="loader-pendulums"></div>
				</div>
				<div class="card-header card-header-action">
					<h6><?php echo e(date('F-Y', strtotime('now'))); ?> <?php echo e(App\Language::trans('Daily Usage')); ?></h6>
					<div class="d-flex align-items-center card-action-wrap">
						<a class="loading-label" href="#" class="inline-block refresh mr-15">
							<i class="ion ion-md-radio-button-off"></i>
						</a>
						<a class="loading-label" href="#" class="inline-block full-screen">
							<i class="ion ion-md-expand"></i>
						</a>
					</div>
				</div>
				<div class="card-body">
						
						<img id='daily_usage_loading_bar' src="<?php echo e(asset(App\Setting::LOADING_GIF)); ?>" alt="" style="display: block;margin-left: auto;margin-right: auto;margin-top: auto;"/>
						<div class="chart hide" id="daily_usage_chart">
							<canvas id="lineChart" style="height:150px"></canvas>
			            </div>
				
				</div>
			</div>
		</div>
		<div class="col-lg-6">
			<div class="card">
				<div class="card-header card-header-action">
					<h6><?php echo e(date('Y', strtotime('now'))); ?> <?php echo e(App\Language::trans('Monthly Usage')); ?></h6>
					<div class="d-flex align-items-center card-action-wrap">
						<a class="loading-label" href="#" class="inline-block refresh mr-15">
							<i class="ion ion-md-radio-button-off"></i>
						</a>
						<a class="loading-label" href="#" class="inline-block full-screen">
							<i class="ion ion-md-expand"></i>
						</a>
					</div>
				</div>
				<div class="card-body">
						<img id='monthly_usage_loading_bar' src="<?php echo e(asset(App\Setting::LOADING_GIF)); ?>" alt="" style="display: block;margin-left: auto;margin-right: auto;margin-top: auto;"/>
						<div class="chart hide" id="monthly_usage_chart">
			                <canvas id="barChart" style="height:150px"></canvas>
			            </div>
				
				</div>
			</div>
		</div>
	</div>
<?php endif; ?>

<?php if(App\Company::is_allow_to_access_module(App\Setting::LABEL_MODULE_ACCOUNTING)): ?>
<div class="hk-row">
		<div class="col-lg-3 col-sm-6">
			<div class="card card-sm">
				<div class="card-body">
					<span class="d-block font-11 font-weight-500 text-dark text-uppercase mb-10"><?php echo e(App\Language::trans('Total Member')); ?></span>
					<div class="d-flex align-items-center justify-content-between position-relative">
						<div>
							<span class="d-block display-7 font-weight-400 text-dark" id="customer_count"><i id="customer_loading" class="fas fa-circle-notch fa-spin"></i></span>
						</div>
						<div>
							<span class="text-success font-12 font-weight-600" id='today_customer_count'>  ...</span>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-lg-3 col-sm-6">
			<a class="loading-label" href="<?php echo e(action('DashboardsController@getCustomerPowerUsageSummary', ['type'=>'outstanding'])); ?>">
			<div class="card card-sm">
				<div class="card-body">
					<span class="d-block font-11 font-weight-500 text-dark text-uppercase mb-10"><?php echo e(App\Language::trans('Total Invoice')); ?></span>
					<div class="d-flex align-items-center justify-content-between position-relative">
						<div>
							<span class="d-block">
								<span class="display-7 font-weight-400 text-dark" id="invoice_count" ><i id="invoice_loading"  class="fas fa-circle-notch fa-spin"></i></span>
							</span>
						</div>
						<div>
							<span class="text-success font-12 font-weight-600" id='today_invoice_count'>  ...</span>
						</div>
					</div>
				</div>
			</div>
			</a>
		</div>
		<div class="col-lg-3 col-sm-6">
			<a class="loading-label" href="<?php echo e(action('DashboardsController@getCustomerPowerUsageSummary', ['type'=>'min_credit'])); ?>">
			<div class="card card-sm">
				<div class="card-body">
					<span class="d-block font-11 font-weight-500 text-dark text-uppercase mb-10"><?php echo e(App\Language::trans('Total Payment Received')); ?></span>
					<div class="d-flex align-items-end justify-content-between">
						<div>
							<span class="d-block">
								<span class="display-7 font-weight-400 text-dark" id="payment_received_count"><i id="payment_received_count_loading" class="fas fa-circle-notch fa-spin"></i></span>
								<small></small>
							</span>
						</div>
						<div>
							<span class="text-success font-12 font-weight-600" id='today_payment_received_count'>  ...</span>
						</div>
					</div>
				</div>
			</div>
			</a>
		</div>
		<div class="col-lg-3 col-sm-6">
			<a class="loading-label" href="<?php echo e(action('DashboardsController@getCustomerPowerUsageSummary', ['type'=>'recent_pay'])); ?>">
			<div class="card card-sm">
				<div class="card-body">
					<span class="d-block font-11 font-weight-500 text-dark text-uppercase mb-10"><?php echo e(App\Language::trans('Ticket Complaints')); ?></span>
					<div class="d-flex align-items-end justify-content-between">
						<div>
							<span class="d-block">
								<span class="display-7 font-weight-400 text-dark" id="ticker_count" ><i  id="ticker_loading" class="fas fa-circle-notch fa-spin"></i></span>
							</span>
						</div>
						<div>
							<span class="text-success font-12 font-weight-600" id='today_ticker_count'>  ...</span>
						</div>
					</div>
				</div>
			</div>
			</a>
		</div>

</div>
<?php endif; ?>


<?php if(isset($super_version)): ?>
		<?php if(Auth::User()->is_super_admin): ?>
		<div class="card">
			<div class="card-header card-header-action">
				<h6><?php echo e(App\Language::trans('Modules Status Report')); ?></h6>
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
			                    <th><?php echo e(App\Language::trans('Modules')); ?></th>
			                    <th><?php echo e(App\Language::trans('Description')); ?></th>
			                    <th><?php echo e(App\Language::trans('Solution')); ?></th>
			                    <th><?php echo e(App\Language::trans('Action')); ?></th>
			                  </tr>
			                </thead>
							<tbody>
								<?php $__currentLoopData = $module_status_listing; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
					                  <tr>
					                    <td><?php echo e(App\Language::trans($row['module_name'])); ?></td>
					                    <td><?php echo e(App\Language::trans($row['description'])); ?></td>
					                    <td><span class="btn btn-sm btn-danger"><?php echo e(App\Language::trans($row['solution'])); ?></span></td>
					                    <td>
					                     <a class="loading-label" href="<?php echo e(action($row['controller'])); ?>" class="btn btn-sm btn-success"><?php echo e(App\Language::trans('Fix now')); ?></a>
					                    </td>
					                  </tr>
				                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>								
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>	
		<?php endif; ?>
<?php endif; ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>


<?php $__env->stopSection(); ?>
<?php echo $__env->make('_version_02.commons.layouts.admin', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>