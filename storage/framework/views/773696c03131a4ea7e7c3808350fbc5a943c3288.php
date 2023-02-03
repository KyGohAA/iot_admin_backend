<?php $__env->startSection('content'); ?>
<section class="hk-sec-wrapper">
    <div class="row">
        <div class="col-sm">
            <div class="table-wrap" style="overflow-x:auto;">
                <table id="leaf_data_table" class="table tablesaw table-bordered table-hover mb-0 w-100 pb-30" data-tablesaw-minimap data-tablesaw-mode-switch>
                    <thead>
					<tr>
						<th>#</th>
						
						

						<th><?php echo e(App\Language::trans('Unit Room No.')); ?></th>
						<th><?php echo e(App\Language::trans('Customer Name')); ?></th>
						<!-- <th><?php echo e(App\Language::trans('Total Payable Amount')); ?></th> -->
						<th><?php echo e(App\Language::trans('Total Paid Amount')); ?></th>
						<th><?php echo e(App\Language::trans('Total Subsidy Amount')); ?></th>
						<th><?php echo e(App\Language::trans('Total Outstanding Amount')); ?></th>
						<th><?php echo e(App\Language::trans('Total Usage (kWh)')); ?></th>
						<th><?php echo e(App\Language::trans('Current Credit')); ?></th>
						<th><?php echo e(App\Language::trans('Check In Date')); ?></th>
						<th><?php echo e(App\Language::trans('Check Out Date')); ?></th>
						<th><?php echo e(App\Language::trans('Last Update At')); ?></th>
						<th><?php echo e(App\Language::trans('Power Supply Status')); ?></th>
						<!-- <th><?php echo e(App\Language::trans('Is Mobile App User')); ?></th> -->

						<th><?php echo e(App\Language::trans('Current Warning No.')); ?></th>
						<th><?php echo e(App\Language::trans('Warning Email No.')); ?></th>
						<th><?php echo e(App\Language::trans('Last Warning At')); ?></th>
						<th><?php echo e(App\Language::trans('Terminate Power Supply At')); ?></th>

					</tr>
				</thead>
				<tbody>			
					<?php if(count($listing) > 0): ?>
						<?php $__currentLoopData = $listing; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
						<?php 
								//dd($row);
								$on_off_status_text = '';
								$on_off_status = $row['is_power_supply_on'] == null ? 2 : $row['is_power_supply_on'];
								if($on_off_status == 1)
								{
									$on_off_status_text = 'On';

								}else if($on_off_status == 0){
									$on_off_status_text = 'Off';

								}else if($on_off_status == 2)
								{
									$on_off_status_text = '-';

								}
								// <td class="text-center"><small class="badge badge-{{$row->leaf_id_user != 0  ? 'success' : 'danger'}} mt-15 mr-10">{{App\Language::trans($mobile_app_status)}}</small></td> 
								//$mobile_app_status = $row->leaf_id_user != 0 ? "Tenanted" : "Vacant" ;
						?>
						<tr>
							<td><?php echo e($index+1); ?>.</td>

							
							

							<td><?php echo e($row['house_name']); ?></td>
							<td><?php echo e($row['customer_name']); ?></td>
							<!-- <td class="text-right"><?php echo e($row->setDouble($row['total_payable_amount'])); ?></td> -->
							<td class="text-right"><?php echo e($row->setDouble($row['total_paid_amount'])); ?></td>
							<td class="text-right"><?php echo e($row->setDouble($row['total_subsidy_amount'])); ?></td>
							<td class="text-right"><?php echo e($row->setDouble($row['total_outstanding_amount'])); ?></td>
							<td class="text-center"><?php echo e($row['total_usage_kwh']); ?></td>
							<td class="text-right"><?php echo e($row->setDouble($row['current_credit_amount'])); ?></td>
							<td><?php echo e($row['check_in_date']); ?></td>
							<td><?php echo e($row['check_out_date']); ?></td>
							<td><?php echo e($row['updated_at']); ?></td>

							<td class="text-center"><small class="badge badge-<?php echo e($row->is_power_supply_on != 0  ? 'success' : 'danger'); ?> mt-15 mr-10"><?php echo e(App\Language::trans($on_off_status_text)); ?></small></td>

							<td><?php echo e($row->below_credit_notification_count); ?></td>
							<td><?php echo e($row->warning_email_number); ?></td>
							<td><?php echo e($row->last_below_credit_notification_email_at); ?></td>
							<td><?php echo e($row->stop_supply_termination_time); ?></td>

			
						</tr>
					<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
					<?php else: ?>
							<td colspan="13" class="text-center">  No record found. </td>
					<?php endif; ?>

					
				</tbody>
                </table>
            </div>
        </div>
    </div>
</section>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('_version_02.commons.layouts.admin', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>