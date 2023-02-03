<?php $__env->startSection('content'); ?>
<?php echo $__env->make('_version_02.commons.layouts.partials._alert', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

<section class="hk-sec-wrapper">
    <div class="row">
        <div class="col-sm">
            <div class="table-wrap" style="overflow-x:auto;">
                <table style="overflow-x:auto;" id="leaf_data_table" class="table tablesaw table-bordered table-hover mb-0 w-100 pb-30" data-tablesaw-minimap data-tablesaw-mode-switch>
                    <thead>
                        <tr>
                        	<th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="0">#</th>
                            <?php $priority_counter = 1 ;
                            	//$cols = ["id","unit_id","reference_no","function_name","relay_switch_status","reading_data","meter_data","operation_type","status","relay_ip","meter_ip"];
                            	$cols = ["Room Name","Reading Data","Electric Supply Status"];
                            ?>
                            <?php $__currentLoopData = $cols; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $col): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            	
								<?php if($col != 'store_id'): ?>
									<?php if($col == 'id'): ?>
										
									<?php elseif(str_contains($col, '_id')): ?>
										<th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="<?php echo e($priority_counter); ?>"><?php echo e(App\Language::trans(ucwords(str_replace('_id', '', $col)))); ?></th>
									<?php else: ?>
										<th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="<?php echo e($priority_counter); ?>"><?php echo e(App\Language::trans(ucwords(str_replace('_', ' ', $col)))); ?></th>
									<?php endif; ?>
								<?php endif; ?>
								<?php $priority_counter ++ ; ?>
							<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
							
                        </tr>
                    </thead>
                    <tbody>
                        <?php $priority_counter = 1 ;
                        	 $exclude_key = ['leaf_group_id','action_result'];
							 $data_key = ['ip_address','meter_id' , 'time_started', 'time_ended', 'current_meter_reading']; 
						?>

                        <?php $__currentLoopData = $model; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
						<tr>
						<?php //dd($row);
							$cols = ['room_name' , 'reading_data', 'is_power_supply_on'];
						?>
							<td class="text-center"><?php echo e($index+1); ?></td>
							
							<?php $__currentLoopData = $cols; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
									<?php 
										$meter_register_model = App\PowerMeterModel\MeterRegister::find($row['meter_register_id']);
										$value = $row[$key];
									?>

									<?php if($key == 'reading_data'): ?>
									<?php		
					
											$meter_listing = [$meter_register_model['modbus_address']];

											echo '<td><table class="table" style="overflow-x:auto;">';
													echo '<thead><tr>';
														echo '<th> Meter </th>';
								
		                							foreach($meter_listing as $d_key){
		                								if($d_key ==0){continue;};
		                								echo '<th>'.ucwords(str_replace('_', ' ', (int) $d_key)).'</th>';
		                							}
		                							echo '</tr>';
		                							echo '</thead>';

												
												$temp_arr = json_decode($value , true);
										
												foreach($temp_arr as $r_value)
												{
													echo '<tr>';
													echo '<td>'.$r_value[(int)$meter_listing[0]]['time_started'].'</td>';	
													foreach($meter_listing as $d_key)
													{	
														$d_key =  (int) $d_key;
														if(!isset($r_value[$d_key])){continue;}
															echo '<td>'.  substr ( $r_value[$d_key]['current_meter_reading'] , 0 , 14)  .'</td>';
														}
													echo '</tr>';
												}

												echo '</table> </td>';
											
										?>
								<?php elseif($key == 'is_power_supply_on'): ?>
									<td class="text-center"><small class="badge badge-<?php echo e($row->is_power_supply_on != 0  ? 'success' : 'danger'); ?> mt-15 mr-10"><?php echo e(App\Language::trans( ($row->is_power_supply_on ? 'On' : 'Off') )); ?></small></td>
								<?php elseif(str_contains($key, '_id')): ?>
									<?php if($key == 'unit_id'): ?>
										<td class="text-center"><?php echo e($value); ?></td>
									<?php else: ?>
										<td class="text-center"><?php echo e($row->display_relationed($key, 'name')); ?></td>
									<?php endif; ?>

									
								<?php elseif($key != 'id'): ?>
									<td class="text-center"><?php echo e($value); ?></td>
								<?php endif; ?>
							<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
							
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