<?php $__env->startSection('content'); ?>
<?php echo Form::model($model, ['class'=>'form-horizontal','method'=>'get']); ?>

<section class="hk-sec-wrapper">
    <h5 class="hk-sec-title"><?php echo e(App\Language::trans('Filter By')); ?></h5><hr>

		<div class="row">
			<div class="col-md-12">
				<div class="form-group<?php echo e($errors->has('leaf_house_id') ? ' has-error' : ''); ?>">
					<?php echo Form::label('leaf_house_id', App\Language::trans('Date Range'), ['class'=>'control-label col-md-4']); ?>

					<div class="col-md-12">
						<input class="form-control" type="text" name="daterange"/>
                        <?php echo $errors->first('leaf_house_id', '<label for="daterange" class="help-block error">:message</label>'); ?>

					</div>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-md-12">
				<div class="form-group<?php echo e($errors->has('leaf_house_id') ? ' has-error' : ''); ?>">
					<?php echo Form::label('leaf_house_id', App\Language::trans('Unit No.'), ['class'=>'control-label col-md-4']); ?>

					<div class="col-md-12">
						<?php echo Form::select('leaf_house_id', App\PowerMeterModel\MeterRegister::houses_combobox(), null, ['class'=>'form-control','required','onchange'=>'init_room_combobox(this)']); ?>

                        <?php echo $errors->first('leaf_house_id', '<label for="leaf_house_id" class="help-block error">:message</label>'); ?>

					</div>
				</div>

			</div>
		</div>

		<div class="row">
			<div class="col-md-12">
				<div class="form-group<?php echo e($errors->has('leaf_room_id') ? ' has-error' : ''); ?>">
					<?php echo Form::label('leaf_room_id', App\Language::trans('Room No.'), ['class'=>'control-label col-md-4']); ?>

					<div class="col-md-12">
						<?php echo Form::select('leaf_room_id', App\PowerMeterModel\MeterRegister::rooms_combobox((old('leaf_house_id') ? old('leaf_house_id'):$model->leaf_house_id)), null, ['class'=>'form-control','onchange'=>'init_room_status(this)']); ?>

                        <?php echo $errors->first('leaf_room_id', '<label for="leaf_room_id" class="help-block error">:message</label>'); ?>

                        <!-- ,'required' -->
					</div>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-md-6">
				<div class="form-group<?php echo e($errors->has('export_by') ? ' has-error' : ''); ?>">
					<?php echo Form::label('export_by', App\Language::trans('Exported By'), ['class'=>'control-label col-md-4']); ?>

					<div class="col-md-12">
						 <div class="row">	
						 	
							<div class="col-md-3">
							    <div class="custom-control custom-radio">
							        <input type="radio" id="export_by_html" name="export_by" value="html"  class="custom-control-input">
							        <label class="custom-control-label" for="export_by_html"><?php echo e(App\Language::trans('HTML')); ?></label>
							    </div>
							</div>

							<div class="col-md-3">
							    <div class="custom-control custom-radio">
							        <input type="radio" id="export_by_pdf" name="export_by" value="pdf" checked class="custom-control-input">
							        <label class="custom-control-label" for="export_by_pdf"><?php echo e(App\Language::trans('PDF')); ?></label>
							    </div>
							</div>
							
							<div class="col-md-3">
							    <div class="custom-control custom-radio">
							        <input type="radio" id="export_by_excel" name="export_by" value="excel"  class="custom-control-input">
							        <label class="custom-control-label" for="export_by_excel"><?php echo e(App\Language::trans('Excel')); ?></label>
							    </div>
							</div>

						 </div>
						 <?php echo $errors->first('export_by', '<label for="export_by" class="help-block error">:message</label>'); ?>

					</div>
				</div>
			</div>
		</div>

</section>
<?php echo $__env->make('_version_02.commons.layouts.partials._form_floating_footer_report', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<?php echo Form::close(); ?>

<?php if(count($listing) > 0): ?>

	<section class="hk-sec-wrapper">
   		 <h5 class="hk-sec-title"><?php echo e(App\Language::trans('Result')); ?></h5><hr>
			<div class="table-responsive">
				<table class="table table-bordered table-hover">
					<thead>
						<tr>
							<th class="text-center">#</th>
							<th class="text-center"><?php echo e(App\Language::trans('Date')); ?></th>
							<th class="text-center"><?php echo e(App\Language::trans('From Time')); ?></th>
							<th class="text-center"><?php echo e(App\Language::trans('To Time')); ?></th>
							<th class="text-center"><?php echo e(App\Language::trans('Last Meter Reading')); ?></th>
							<th class="text-center"><?php echo e(App\Language::trans('Current Meter Reading')); ?></th>
							<th class="text-center"><?php echo e(App\Language::trans('Current Usage')); ?></th>
						</tr>
					</thead>
					<tbody>
						    <?php  
						    	  ini_set('max_execution_time', 0);
						    	  $total = 0;
						    	  $sub_total = 0;
						    	  $grand_total = 0;
						    	  $total_payable_amount = 0 ;
						    	  $setting = new App\Setting();
						    ?>
		
					        <?php $__currentLoopData = $houses_detail; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $house): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
					            <?php  $total = 0; ?>
					            <tr style="background-color: #ddd;">
									<td class="text-left" colspan="7"><?php echo e(App\Language::trans('Unit')); ?> : <?php echo e($house['house_unit']); ?></td>
								</tr>
							
					            
					            <?php $__currentLoopData = $house['house_rooms']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $room): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

					                <?php if($leaf_room_id !=0): ?>
					                    <?php if($room['id_house_room'] != $leaf_room_id): ?>
					                    	<?php
					                     	    continue;
					                        ?>
					                    <?php endif; ?>
					                <?php endif; ?>

					                <?php
					                	$sub_total = 0;
						                $isMeterRegister   = false;
						                $isFirstRoomHeader = true;
						                $rowNo             = 0;
						                $index             = 0;
						                $reading_data = isset($listing[$room['meter']['id']]) ? $listing[$room['meter']['id']] : array();
						            ?>

					                <?php $__currentLoopData = $reading_data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> 
					                    
					                    <?php if($row->meter_register_id == $room['meter']['id']): ?> 
					                        <?php if($isFirstRoomHeader == true): ?> 
					                            <tr>
						                            <td class="text-center"><?php echo e(App\Language::trans('Room') . ' : ' . $room['house_room_name']); ?></td>
						                            <td colspan="6" class="text-left"><?php echo e(App\Language::trans('Meter Id').' : '.$room['meter']['id']); ?></td>
					                            </tr>
					                         
					                        <?php endif; ?>
					                        
					                        <?php
						                        $payable_amount    = App\Setting::calculate_utility_fee($row->total_usage);
						                        $isFirstRoomHeader = false;
						                        $isMeterRegister   = true;
						                        $total_payable_amount += $payable_amount;
						                    ?>
					                        
					                        <!-- table of the listing -->
					                        <!-- header of the table -->
					                        <tr>
												 <td class="text-center"><?php echo e(($index + 1)); ?></td>
									             <td class="text-center"><?php echo e($setting->convert_encoding($setting->getDate($row->current_date))); ?></td>
									             <td class="text-center"><?php echo e($setting->convert_encoding($row->time_started)); ?></td>
									             <td class="text-center"><?php echo e($setting->convert_encoding($row->time_ended)); ?></td>
									             <td class="text-right"><?php echo e($setting->convert_encoding($row->last_meter_reading)); ?></td>
									             <td class="text-right"><?php echo e($setting->convert_encoding($row->current_meter_reading)); ?></td>
									             <td class="text-right"><?php echo e($setting->convert_encoding($row->current_usage)); ?></td>
									        </tr>

					                        <?php
					                        	$total += $row->current_usage;
					                        	$sub_total += $row->current_usage;
					                        	
					                        	$grand_total +=  $row->current_usage;
					                        	$index++;
					                        ?>

					                    <?php endif; ?>
					                    
					                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
					                
					                <?php if($isMeterRegister == false): ?> 
					                    <tr>
					                    	<td class="text-center"><?php echo e(App\Language::trans('Room') . " " . $room['house_room_name']); ?></td>
					                    	<td class="text-center" colspan="6"><?php echo e(App\Language::trans(App\Setting::SUNWAY_NO_METER_FOUND_LABEL)); ?></td>
					                    </tr>         
					                <?php else: ?> 
					                	<tr>
					                    	<td colspan="6" class="text-right"><?php echo e($setting->convert_encoding(App\Language::trans('Sub-total')) . ' : '); ?></td>
					                   		<td class="text-right"><?php echo e($setting->convert_encoding($setting->getDouble($sub_total))); ?></td>
					                    </tr>
					                    <tr><tr>
					                <?php endif; ?>
					    
				                	<?php
				                		$isMeterRegister = false;
				                	?>
					        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
					    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>


					</tbody>
					<tfoot>
						<hr>
						<br>
						<tr>
							<td class="text-right" colspan="6"><?php echo e(App\Language::trans('Total')); ?>:</td>
							<td class="text-right"><?php echo e(number_format($grand_total, 2)); ?></td>
						</tr>
					</tfoot>
				</table>
			</div>
	</section>
<?php elseif(count($listing) == 0 && $is_search_result == true): ?>
	<?php echo $__env->make('_version_02.commons.report_modules.no_data_found', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<?php endif; ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('_version_02.commons.layouts.admin', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>