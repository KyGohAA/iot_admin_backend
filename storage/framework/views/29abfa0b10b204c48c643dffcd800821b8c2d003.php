<?php $__env->startSection('content'); ?>
<?php echo Form::model($model, ['class'=>'form-horizontal','method'=>'get']); ?>

<section class="hk-sec-wrapper">
    <h5 class="hk-sec-title"><?php echo e(App\Language::trans('Filter By')); ?></h5><hr>
   		

   		<div class="row">
			<div class="col-md-6">
				<div class="form-group<?php echo e($errors->has('month_started') ? ' has-error' : ''); ?>">
					<?php echo Form::label('month_started', App\Language::trans('From Month'), ['class'=>'control-label col-md-4']); ?>

					<div class="col-md-8">
						<?php echo Form::select('month_started', App\PowerMeterModel\MeterInvoice::previous_one_year_combobox(), (old('month_started') ? old('month_started'):$model->three_month_pass()), ['class'=>'form-control','autofocus']); ?>

                        <?php echo $errors->first('month_started', '<label for="month_started" class="help-block error">:message</label>'); ?>

					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group<?php echo e($errors->has('month_ended') ? ' has-error' : ''); ?>">
					<?php echo Form::label('month_ended', App\Language::trans('To Month'), ['class'=>'control-label col-md-4']); ?>

					<div class="col-md-8">
						<?php echo Form::select('month_ended', App\PowerMeterModel\MeterInvoice::previous_one_year_combobox(), (old('month_ended') ? old('month_ended'):$model->last_month()), ['class'=>'form-control']); ?>

                        <?php echo $errors->first('month_ended', '<label for="month_ended" class="help-block error">:message</label>'); ?>

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

<?php if(count($listing)): ?>

	<?php 
		$no_reading_arr = array();
		$tenant_header = ['#','Name','Move In Date' , 'Move Out Date'];
		$tenant_variables = ['house_member_name','house_room_member_start_date','house_room_member_end_date'];

	?>
	<section class="hk-sec-wrapper">
    <h5 class="hk-sec-title"><?php echo e(App\Language::trans('Result')); ?></h5><hr>

			<div class="table-responsive">
				<table class="table table-bordered table-hover">
					<thead>
						<tr>
							<th class="text-center"><?php echo e(App\Language::trans('Month')); ?></th>
							<th class="text-center"><?php echo e(App\Language::trans('Total Hours')); ?></th>
							<th class="text-center"><?php echo e(App\Language::trans('Avg. kW')); ?></th>
							<th class="text-center"><?php echo e(App\Language::trans('Max. kW')); ?></th>
							<th class="text-center"><?php echo e(App\Language::trans('Min. kW')); ?></th>
							<th class="text-center"><?php echo e(App\Language::trans('Total kWh')); ?></th>
							<th class="text-center"><?php echo e(App\Language::trans('Total Charges (RM)')); ?></th>
						</tr>
					</thead>
					<tbody>
					<?php  
						$x=0;
						$total_payable_amount = 0;
					?>
	
					<?php $__currentLoopData = $houses_detail; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $house): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
						<tr style="background-color: #ddd;">
							<td class="text-left" colspan="7"><?php echo e(App\Language::trans('Unit')); ?> : <?php echo e($house['house_unit']); ?></td>
						</tr>
						<?php $__currentLoopData = $house['house_rooms']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $room): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

							<?php  
								$isMeterRegister = false;
								$isFirstRoomHeader = true;
								$rowNo =0;
								$room_subtotal =0;
								$total_payable_amount = 0;
								$room_subtotal_total_usage  =0;
							?>
						
						

						

							<?php $__currentLoopData = $listing; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
								<?php if(!isset($room['meter']['id'])): ?>

										<tr>
											<td class="text-left" colspan="1"><?php echo e(App\Language::trans('Room')); ?> : <?php echo e($room['house_room_name']); ?>  </td>
											<td class="text-left" colspan="6"><span class='label label-danger'> <?php echo e(App\Language::trans(App\Setting::SUNWAY_NO_METER_FOUND_LABEL)); ?> </span></td>
										</tr>
										<?php   break;	?>

								<?php elseif($row->meter_register_id == $room['meter']['id']): ?>
									<?php if($isFirstRoomHeader == true): ?>
										<tr style="background-color: #FFFEFE;">
											<td class="text-left" colspan="1"><?php echo e(App\Language::trans('Room')); ?> : <?php echo e($room['house_room_name']); ?>  </td>
											<td  class="text-left"  colspan="6" ><?php echo e(App\Language::trans('Meter ID')); ?> : <span class='label label-success'> <?php echo e($listing[$rowNo]->meter_register_id); ?> </span></td>
										</tr>

										<?php if($is_show_tenant): ?>
											
																					
													
											<?php if(count($room['house_room_members']) > 0): ?>
													<tr>
														<th colspan="7" class="text-left"><?php echo e(App\Language::trans('Tenant Detail')); ?></th>								
													
													</tr>
													<tr>
														<?php $__currentLoopData = $tenant_header; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $h_key): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> 
															<?php $extra_html = ""; ?>
															<?php if($h_key == 'Move Out Date'): ?>
																<?php $extra_html = " colspan=4 "; ?>
																
															<?php endif; ?>
															<th class="text-left" <?php echo e($extra_html); ?>> <?php echo e(App\Language::trans($h_key)); ?></th>
														
														<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
													</tr>

													<?php $t_counter =1 ; $extra_html = ""; ?>
													<?php $__currentLoopData = $room['house_room_members']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $member): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> 
													<tr>
														<td class="text-left" colspan="1"><?php echo e($t_counter); ?>  </td>
														<?php $__currentLoopData = $tenant_variables; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t_variable): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> 
															<?php $extra_html = ""; ?>
															<?php if($t_variable == 'house_room_member_end_date'): ?>
																<?php
																	$extra_html = " colspan=4 ";
																	$member[$t_variable] = $member[$t_variable] == '0000-00-00 00:00:00' ? '-' : $member[$t_variable];
																?>							
															<?php endif; ?>
															
															<td class="text-left" <?php echo e($extra_html); ?>> <?php echo e(App\Language::trans($member[$t_variable])); ?>  </td>
															

														<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
														<?php $t_counter ++ ; ?>
													</tr>	
													<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

											<?php else: ?>
													<tr>
														<th colspan="7" class="text-center"><?php echo e(App\Language::trans('No tenanted')); ?></th>								
													
													</tr>
													
											<?php endif; ?>
									<?php endif; ?>

										<tr>
											<th class="text-center"><?php echo e(App\Language::trans('Month')); ?></th>
											<th class="text-center"><?php echo e(App\Language::trans('Total Hours')); ?></th>
											<th class="text-center"><?php echo e(App\Language::trans('Avg. kW')); ?></th>
											<th class="text-center"><?php echo e(App\Language::trans('Max. kW')); ?></th>
											<th class="text-center"><?php echo e(App\Language::trans('Min. kW')); ?></th>
											<th class="text-center"><?php echo e(App\Language::trans('Total kWh')); ?></th>
											<th class="text-center"><?php echo e(App\Language::trans('Total Charges (RM)')); ?></th>
										</tr>
									<?php endif; ?>
									
									


									<?php  				
										$payable_amount = App\Setting::calculate_utility_fee($row->total_usage);
										$isFirstRoomHeader = false;			
										$isMeterRegister = true;
										$total += $row->total_usage; 
										$room_subtotal += $payable_amount;
										$room_subtotal_total_usage += $row->total_usage;
// && $row->total_hours == 0
										if($row->total_usage == 0)
										{
											$temp_meter = App\PowerMeterModel\MeterRegister::find($row->meter_register_id);
											$no_reading_arr[$row->meter_register_id] = $temp_meter;
										}

									?>		
										<tr>
											<td class="text-center"><?php echo e(date('m-Y', strtotime($row->current_date))); ?></td>
											<td class="text-center"><?php echo e($row->total_hours); ?></td>
											<td class="text-center"><?php echo e(number_format($row->average_usage,9)); ?></td>
											<td class="text-center"><?php echo e(number_format($row->max_usage,9)); ?></td>
											<td class="text-center"><?php echo e(number_format($row->min_usage,9)); ?></td>
											<td class="text-center"><?php echo e(number_format($row->total_usage,9)); ?></td>
											<td class="text-right"><?php echo e(number_format($payable_amount,2)); ?></td>
										</tr>
										
								<?php endif; ?>
				
								<?php  
								$rowNo++;
								?>
								
								
							<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
							
							<?php if(isset($room['meter']['id'])): ?>
								<tr>
									<td class="text-right" colspan="5"><?php echo e(App\Language::trans('Room Subtotal')); ?></td>
									<td class="text-center"><?php echo e(number_format($room_subtotal_total_usage, 9)); ?></td>
									<td class="text-right"><?php echo e(number_format($room_subtotal, 2)); ?></td>
								</tr>
							<?php endif; ?>
									
							<?php  
								$x = $x + $room_subtotal;
								$isMeterRegister = false;
							?>
							
						<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
					<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

	
						
					</tbody>
					<tfoot>
						<tr style="background-color: #ddd;">
							<td class="text-right" colspan="5"><?php echo e(App\Language::trans('Total')); ?>:</td>
							<td class="text-center"><?php echo e(number_format($total, 9)); ?></td>
							<td class="text-right"><?php echo e(number_format($x,2)); ?></td>
						</tr>
					</tfoot>
				</table>


				<table class="table table-bordered table-hover">
					<thead>
						<tr>
							<th class="text-center"><?php echo e(App\Language::trans('ID')); ?></th>
							<th class="text-center"><?php echo e(App\Language::trans('Room')); ?></th>
							<th class="text-center"><?php echo e(App\Language::trans('IP Address')); ?></th>
						</tr>
					</thead>
					<tbody>
	
					<?php $__currentLoopData = $no_reading_arr; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $no_reading_meter): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

								<?php  
						//dd($no_reading_meter);
					?>

						<tr>

							<td class="text-center"><?php echo e($no_reading_meter->meter_id); ?></td>
							<td class="text-center"><?php echo e($no_reading_meter->ip_address); ?></td>
							<td class="text-center"><?php echo e(App\LeafAPI::get_room_name_by_leaf_room_id($no_reading_meter->leaf_room_id)); ?></td>
						</tr>
						
	
					<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
					</tbody>
				</table>
			</div>
			
	</section>

<?php elseif(count($listing) == 0 && $is_search_result == true): ?>
	<?php echo $__env->make('_version_02.commons.report_modules.no_data_found', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<?php endif; ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
$(".input-daterange").datepicker({
	format: "dd-mm-yyyy",
});
<?php $__env->stopSection(); ?>
<?php echo $__env->make('_version_02.commons.layouts.admin', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>