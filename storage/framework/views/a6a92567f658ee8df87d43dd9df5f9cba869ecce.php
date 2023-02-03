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
				                            		<?php
						                            	if($col == 'device_profile_id'){
						                            		continue;
						                            	}      
				                            		?>

													<?php if($col == 'id'): ?>
														<th>#</th>
													<?php elseif($col == 'dev_eui'): ?>
														<th>#</th>
														<th>Device Eui</th>
														<th>Name</th>
													<?php elseif(str_contains($col, '_id')): ?>
														<th><?php echo e(App\Language::trans(ucwords(str_replace('_id', '', $col)))); ?></th>
													<?php else: ?>
														<th><?php echo e(App\Language::trans(ucwords(str_replace('_', ' ', $col)))); ?></th>
													<?php endif; ?>
										
											<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
											<th class="text-center"><?php echo e(App\Language::trans('Action')); ?></th>
                                        </tr>
                                    </thead>

                            
							
                                    <tbody>
  											<?php
  												$counter = 0;
  											?>
					                        <?php $__currentLoopData = $model; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
					                        	<?php

					                        		$included = ['x24e124136c225107','x24e124141c147463','x24e124141c141557','x24e124148b495286','x24e124535b316056','x24e124538c019556','x24e124600c124993'];

					                        		if(isset($row['dev_eui']))
					                        		{
					                        			if(!in_array($row['dev_eui'],$included)){continue;}
					                        		}
												
													

												?>

					                         	<tr id="<?php echo e($row['id']); ?>">
													<td class="text-center"><?php echo e($counter+1); ?></td>

													<?php $__currentLoopData = $row->toArray(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

														<?php

															if(!in_array($key,$cols))
															{
																continue;
															}
														
							                            	if($key == 'device_profile_id'){
							                            		continue;
							                            	}      
				                            		

														?>
														<?php if($key == 'dev_eui'): ?>
															<?php
																$dp = App\Iot\DeviceProfile::getById($row['device_profile_id']);
															?>
															<td class="text-center"> <?php echo e(substr($value,1,strlen($value))); ?></td>
															<td class="text-center"> <?php echo e($dp['name']); ?></td>
											

														<?php elseif($key == 'photo'): ?>
															<!-- <td class="text-center"><img class="img-responsive" width="50" height="50" src="<?php echo e($row->profile_jpg()); ?>"></td> -->
														<?php elseif($key == 'status'): ?>
															<td class="text-center"><?php echo e($row->display_status_string($key)); ?></td>
														<?php elseif($key != 'user_id' && $key != 'id'): ?>
															<td class="text-center"><?php echo e($value); ?></td>
														<?php endif; ?>
													<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
													<td class="text-center">
														<a onclick="return confirm(confirmMsg)" class="loading-label" href="<?php echo e(action('IOTUniversalsController@getDeviceInfo', [$row['dev_eui']])); ?>"><?php echo e(App\Language::trans('View')); ?></a>
													</td>
												</tr>
												<?php
  													$counter ++;
  												?>
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