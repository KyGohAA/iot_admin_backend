<?php $__env->startSection('content'); ?>
<?php echo $__env->make('_version_02.iot.layouts.partials._alert', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<div class="row clearfix">
                <div class="col-lg-12">
                    <div class="card">
                        <?php echo $__env->make('_version_02.iot.layouts.partials._index_header', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
                        <div class="body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                                    <thead>
                                        <tr>
				                            <?php $__currentLoopData = $cols; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $col): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
												<?php if($col != 'store_id'): ?>
													<?php if($col == 'id'): ?>
														<th>#</th>
													<?php elseif(str_contains($col, '_id')): ?>
														<th"><?php echo e(App\Language::trans(ucwords(str_replace('_id', '', $col)))); ?></th>
													<?php else: ?>
														<th><?php echo e(App\Language::trans(ucwords(str_replace('_', ' ', $col)))); ?></th>
													<?php endif; ?>
												<?php endif; ?>
											<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
											<th class="text-center"><?php echo e(App\Language::trans('Action')); ?></th>
                                        </tr>
                                    </thead>

                            
							
                                    <tbody>
  
					                        <?php $__currentLoopData = $model; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
					                         	<tr id="<?php echo e($row['id']); ?>">
													<td class="text-center"><?php echo e($index+1); ?></td>
													<?php $__currentLoopData = $row->toArray(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
														<?php
															if(!in_array($key,$cols))
															{
																continue;
															}
														?>
														<?php if($key == 'photo'): ?>
															<!-- <td class="text-center"><img class="img-responsive" width="50" height="50" src="<?php echo e($row->profile_jpg()); ?>"></td> -->
														<?php elseif($key == 'status'): ?>
															<td class="text-center"><?php echo e($row->display_status_string($key)); ?></td>
														<?php elseif($key != 'user_id' && $key != 'id'): ?>
															<td class="text-center"><?php echo e($value); ?></td>
														<?php endif; ?>
													<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
													<td class="text-center">
														<a onclick="return confirm(confirmMsg)" class="loading-label" href="<?php echo e(action('UsersController@getEdit', [$row->id])); ?>"><?php echo e(App\Language::trans('Edit')); ?></a>
													</td>
												</tr>
					                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                           
                                                                           
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
</div>


<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('_version_02.iot.layouts.admin', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>