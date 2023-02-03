<?php $__env->startSection('content'); ?>
<?php echo $__env->make('_version_02.commons.layouts.partials._alert', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

<section class="hk-sec-wrapper">
    <div class="row">
        <div class="col-lg">
            <div class="table-wrap" style="overflow-x:auto;">
                <table id="leaf_data_table" class="table tablesaw table-bordered table-hover mb-0 w-100 pb-30" data-tablesaw-minimap data-tablesaw-mode-switch>
                    <thead>
                        <tr>
                            <?php $priority_counter = 1 ; ?>
                            <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="0">#</th>
                            <?php $__currentLoopData = $cols; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $col): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
								<?php if($col != 'store_id'): ?>
									<?php if($col == 'id'): ?>
										<th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="0">ID</th>
									<?php elseif(str_contains($col, 'leaf_')): ?>
										<th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="<?php echo e($priority_counter); ?>"><?php echo e(App\Language::trans(ucwords(str_replace('leaf_', ' ', str_replace('_id', '', $col))))); ?></th>
									<?php elseif(str_contains($col, '_id')): ?>
										<th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="<?php echo e($priority_counter); ?>"><?php echo e(App\Language::trans(ucwords(str_replace('_', ' ', str_replace('_id', '', $col))))); ?></th>
									<?php else: ?>
										<th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="<?php echo e($priority_counter); ?>"><?php echo e(App\Language::trans(ucwords(str_replace('_', ' ', $col)))); ?></th>
									<?php endif; ?>

									<?php if($col == 'ip_address'): ?>
										<th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="<?php echo e($priority_counter); ?>"><?php echo e(App\Language::trans('Unit No.')); ?></th>
									<?php endif; ?>
								<?php endif; ?>
								<?php $priority_counter ++ ; ?>
							<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
							<th class="text-center"><?php echo e(App\Language::trans('Action')); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                         <?php $priority_counter = 1 ;
                         	$no_reading_meters = array();
                         ?>
                         <?php $__currentLoopData = $model; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                         	<?php
                         		
                         		if($row->last_reading_at == '' || $row->last_reading_at == null)
                         		{//dd($row->last_reading_at);
                         			$no_reading_meters[$row->id] = $row;
                         		}
                         	?>
	                         <tr>
								<td class="text-center"><?php echo e($index+1); ?></td>
								<?php $__currentLoopData = $row->toArray(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
									<?php if($key == 'id'): ?>
										<td class="text-center"><?php echo e($value); ?></td>
									<?php elseif($key == 'is_power_supply_on'): ?>
										<td class="text-center"><small class="badge badge-<?php echo e($row->is_power_supply_on != 0  ? 'success' : 'danger'); ?> mt-15 mr-10"><?php echo e(App\Language::trans(($row->is_power_supply_on == '1' ? 'On' : 'Off'))); ?></small></td>
									<?php elseif($key == 'status'): ?>
										<td class="text-center"><?php echo e($row->display_status_string($key)); ?></td>
									<?php elseif(str_contains($key, 'leaf_room_id')): ?>
										<td class="text-center"><?php echo e($row->convert_room_no($value, $rooms)); ?></td>
									<?php elseif(str_contains($key, '_id') && $key != 'meter_id'): ?>
										<td class="text-center"><?php echo e($row->display_relationed($key, 'name')); ?></td>
									<?php elseif($key != 'id'): ?>
										<td class="text-center"><?php echo e($value); ?></td>
									<?php endif; ?>
									<?php if($key == 'ip_address'): ?>
										<td class="text-center"><?php echo e($row->convert_house_no($row->leaf_room_id, $rooms)); ?></td>
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

<?php if(isset($stop_meters)): ?>
	<?php $__currentLoopData = $stop_meters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
		No Reading IP : '<?php echo e($row['ip_address']); ?>' ,
	<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php endif; ?>

<section class="hk-sec-wrapper">

	<h5 class="hk-sec-title"><?php echo e(App\Language::trans('Meter Tag With No Reading')); ?></h5><hr>
    <div class="row">
        <div class="col-lg">
              <div class="table-wrap" style="overflow-x:auto;">

              		<table class="table tablesaw table-bordered table-hover mb-0 w-100 pb-30" data-tablesaw-minimap data-tablesaw-mode-switch>
                    <thead>
                        <tr>
                        	 <?php  
                        	 	$no_reading_headers = ['No.' ,'Unit No' ,'Room Name' , 'IP Address' , 'Meter Id'];
                        	 	$no_meter_columns = [ 'leaf_room_id' ,'ip_address' , 'meter_id'];
                        	 ?>

                        	 <?php $__currentLoopData = $no_reading_headers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $col): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        	 	<th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="0"> <?php echo e($col); ?></th>
                        	 <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tr>
                    </thead>

                    	<?php $index = 0; ?>
                     	<?php $__currentLoopData = $no_reading_meters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $meter): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
	                        <tr>
	                        	<td class="text-center"><?php echo e($index+1); ?></td>
	                        	<?php $__currentLoopData = $no_meter_columns; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $col): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
	                        		<?php if($col == 'leaf_room_id'): ?>
	                        				<td class="text-center"><?php echo e($row->convert_house_no($meter[$col], $rooms)); ?></td>
	                        				<td class="text-center"><?php echo e($row->convert_room_no($meter[$col], $rooms)); ?></td>
	                        		<?php else: ?>
	                        				<td class="text-center"><?php echo e($meter[$col]); ?></td>
	                        		<?php endif; ?>
	                        		<?php $index ++; ?>
	                        	<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
	                        </tr>
	                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                  </table>

                  <?php $no_reading_ips = array();
                  		foreach($no_reading_meters as $meter){
                  			 array_push ( $no_reading_ips , $meter['ip_address']);
                  		}
                  		echo json_encode($no_reading_ips);
                  ?>
       		 </div>
        </div>
    </div>
</section>


<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>

$(document).ready(function(){

	$(".room_range").ionRangeSlider({
		type: "double",
		min:   1  ,
		max:  <?php echo e(count($model)); ?> ,
		from :  1 ,
		to :  100  ,
		step: 0.01,
		grid: true,
	
	});
});

<?php $__env->stopSection(); ?>

<?php echo $__env->make('_version_02.commons.layouts.admin', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>