<?php $__env->startSection('content'); ?>
<section class="hk-sec-wrapper">
    <div class="row">
        <div class="col-sm">
            <div class="table-wrap" style="overflow-x:auto;">
                <table id="leaf_data_table" class="table tablesaw table-bordered table-hover mb-0 w-100 pb-30">
                    <thead>
					<tr>
						<th>#</th>
						<th><?php echo e(('Doc No.')); ?></th>
						<th><?php echo e(('Ref No.')); ?></th>
						<th><?php echo e(('Payment Gateway Ref No.')); ?></th>
						<th><?php echo e(('Unit Room No.')); ?></th>
						<th><?php echo e(('Customer Name')); ?></th>
						<th><?php echo e(('Document Date')); ?></th>
						<th class="text-center"><?php echo e(('Total Amount')); ?></th>

					</tr>
				</thead>
				<tbody>
					<?php
						$grant_total = 0;
					?>
					<?php if(count($listing) > 0): ?>
							<?php $__currentLoopData = $listing; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
								<tr>
									<td><?php echo e($index+1); ?>.</td>
									<td><?php echo e($row['document_no']); ?></td>
									<td><?php echo e($row['reference_no']); ?></td>
									<td><?php echo e($row['payment_gateway_reference_no']); ?></td>
									<td><?php echo e($row['house_name']); ?></td>
									<td><?php echo e($row['customer_name']); ?></td>
									<td><?php echo e($row['document_date']); ?></td>
									<td class="text-right"> <?php echo e($row->setDouble($row['total_amount'])); ?> </td>

									<?php
										$grant_total +=$row->setDouble($row['total_amount']);
									?>
								</tr>
							<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

							<tr>
								<td colspan="7" class="text-right"> <strong> <?php echo e(('Total :')); ?> </strong></td>
								<td class="text-right"> <?php echo e($row->setDouble($grant_total)); ?> </td>
							</tr>
					<?php else: ?>
							<td colspan="8" class="text-center">  No record found. </td>
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