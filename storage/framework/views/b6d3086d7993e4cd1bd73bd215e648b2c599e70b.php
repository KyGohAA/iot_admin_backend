<?php $__env->startSection('content'); ?>
<?php echo $__env->make('_version_02.commons.layouts.partials._alert', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?> 
<section class="hk-sec-wrapper">
    <div class="row">
        <div class="col-sm">
            <div class="table-wrap">
                <table id="leaf_data_table" class="table tablesaw table-bordered table-hover mb-0 w-100 pb-30 table-overflow"  data-tablesaw-minimap data-tablesaw-mode-switch>
                    <thead>
                        <tr>
                            <?php $priority_counter = 1 ; ?>
                            <?php $__currentLoopData = $cols; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $col): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
								<?php if($col != 'store_id'): ?>
									<?php if($col == 'id'): ?>
										<th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="0">#</th>
									<?php elseif(str_contains($col, '_id')): ?>
										<th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="<?php echo e($priority_counter); ?>"><?php echo e(App\Language::trans(ucwords(str_replace('_id', '', $col)))); ?></th>
									<?php else: ?>
										<th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="<?php echo e($priority_counter); ?>"><?php echo e(App\Language::trans(ucwords(str_replace('_', ' ', $col)))); ?></th>
									<?php endif; ?>
								<?php endif; ?>
								<?php $priority_counter ++ ; ?>
							<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
							<th class="text-center"><?php echo e(App\Language::trans('Action')); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $priority_counter = 1 ; ?>
                        <?php $__currentLoopData = $model; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
						<tr>
							<td class="text-center"><?php echo e($index+1); ?></td>
							<?php $__currentLoopData = $row->toArray(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
								<?php if($key == 'status'): ?>
									<td class="text-center"><?php echo e($row->display_status_string($key)); ?></td>
								<?php elseif(str_contains($key, 'meter_register_id')): ?>
									<td class="text-center"><?php echo e($row->display_relationed($key, 'account_no')); ?></td>
								<?php elseif(str_contains($key, '_id')): ?>
									<td class="text-center"><?php echo e($row->display_relationed($key, 'name')); ?></td>
								<?php elseif(str_contains($key, 'amount')): ?>
									<td class="text-center"><?php echo e($row->getDouble($value)); ?></td>
								<?php elseif(str_contains($key, 'date') && $key !='implementation_date'): ?>
									<td class="text-center"><?php echo e($row->getDate($value)); ?></td>
								<?php elseif(str_contains($key, 'room_type')): ?>
								<td class="text-center"><?php echo e($row->display_room_type_string($key)); ?></td>
								<?php elseif($key != 'id'): ?>
									<td class="text-center"><?php echo e($value); ?></td>
								<?php endif; ?>
							<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
							<td class="text-center">
								<a onclick="return confirm(confirmMsg)" class="loading-label" href="<?php echo e(action('UMeterSubsidiariesController@getEdit', [$row->id])); ?>"><?php echo e(App\Language::trans('Edit')); ?></a> | 
								<a class="loading-label" href="<?php echo e(action('UMeterSubsidiariesController@getView', [$row->id])); ?>"><?php echo e(App\Language::trans('View')); ?></a>
							</td>
						</tr>
						<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

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