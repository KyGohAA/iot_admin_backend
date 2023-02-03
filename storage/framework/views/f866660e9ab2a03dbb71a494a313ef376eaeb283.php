<?php $__env->startSection('content'); ?>
<?php echo $__env->make('_version_02.commons.layouts.partials._alert', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<section class="hk-sec-wrapper">
    <div class="row">
        <div class="col-sm">
            <div class="table-wrap" style="overflow-x: auto;overflow-y: visible;">
                <table id="leaf_data_table" class="table tablesaw table-bordered table-hover mb-0 w-100 pb-30" data-tablesaw-minimap >
                    <thead>
                        <tr>
                            <?php $priority_counter = 1 ; ?>
                            <?php 
                            	$info_cols = ['house_fee_items','landlord_information','house_other_information'];
                            ?>
                            <?php $__currentLoopData = $cols; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $col): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
								<?php if($col != 'store_id'): ?>
									<?php if($col == 'id'): ?>
										<th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="0">#</th>
									<?php elseif(in_array($col,$info_cols)): ?>
										<th style="width:350px;' scope="col" data-tablesaw-sortable-col data-tablesaw-priority="<?php echo e($priority_counter); ?>"><?php echo e(App\Language::trans(ucwords(str_replace('_', ' ', $col)))); ?></th>
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
								<?php elseif(str_contains($key, '_id')): ?>
									<td class="text-center"><?php echo e($row->display_relationed($key, 'name')); ?></td>
								<?php elseif($key == 'house_fee_items'): ?>
									<td class="text-center">
										<table>
									<?php
										$items = json_decode($value,true);
										foreach($items as $item)
										{
											echo '<tr><td><strong>'.ucwords(str_replace('_', ' ',$item)).'</strong></td></tr>';
										}
										//dd($value);
									?>
										</table>
									</td>

								<?php elseif($key == 'landlord_information' || $key == 'house_other_information'): ?>
									<td class="text-center">
										<table>
												<?php
													$items = json_decode($value,true);
													foreach($items as $key => $value)
													{
														echo '<tr><td><strong>'.ucwords(str_replace('_', ' ',$key)).'</strong></td><td>'.$value.'</tr>';
													}
													//dd($value);
												?>
										</table>
									</td>
								<?php elseif($key != 'id'): ?>

									<td class="text-center"><?php echo e($value); ?></td>
								<?php endif; ?>
							<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
							<?php echo $__env->make('_version_02.commons.layouts.partials._table_action_column', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
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