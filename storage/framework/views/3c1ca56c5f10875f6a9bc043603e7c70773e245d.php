	<hr>
    <h5 class="hk-sec-title"><?php echo e(App\Language::trans('House Room Member')); ?></h5><hr>
   		<div id='house_room_members_section' name='house_room_members_section' class="row">
   			
					<div class="table-responsive">
						<div class="col-md-12">
							<table id="house_room_members" class="table table-bordered table-hover">
								<thead style="background-color: #ddd;">
									<tr>
										<th class="text-center">#</th>
							
										<th class="text-center col-md-11"><?php echo e(App\Language::trans('Tenant')); ?> <br> <?php echo e(App\Language::trans('Check In - Check Out Date')); ?></th>
								
										<th class="text-center col-md-1"><?php echo e(App\Language::trans('Action')); ?></th>
									</tr>
								</thead>
								<tbody>
									<?php 
										$i = 1;
									
										$rows = strlen($model['house_room_members']) > 3 ? json_decode($model['house_room_members'] , true ) :  array();
										foreach ($rows as $index => $row)	
										{
												if(!isset($row['user_id']))
												{
													unset($rows[$index]);
												}
										}
							
									?>


									<?php if($rows != null): ?>
										<?php if(count($rows) > 0): ?>
					
											<?php $__currentLoopData = $rows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
												<tr style="background-color: #A3EBB1;">
													<td class="text-center">
														<?php echo e($i); ?>

													</td>
										
													<td class="text-center">

															 <div class="row">	
															 	<?php echo Form::label('status', App\Language::trans('Wifi'), ['class'=>'control-label col-md-4']); ?>

															 	<div class="col-md-8">
																    <?php echo Form::select('house_room_members['.$i.'][wifi]', App\Setting::status_combobox(), (isset($row['wifi']) ? $row['wifi'] : 0), ['class'=>'form-control','autofocus']); ?>

																</div>
															 </div>

															  <div class="row">	
															 	<?php echo Form::label('status', App\Language::trans('Air Cond.'), ['class'=>'control-label col-md-4']); ?>

															 	<div class="col-md-8">
																    <?php echo Form::select('house_room_members['.$i.'][air_cond]', App\Setting::status_combobox(), (isset($row['air_cond']) ? $row['air_cond'] : 0), ['class'=>'form-control','autofocus']); ?>

																</div>
															 </div>

															 <div class="row">	
															 	<?php echo Form::label('status', App\Language::trans('Others'), ['class'=>'control-label col-md-4']); ?>

															 	<div class="col-md-8">
																    <?php echo Form::select('house_room_members['.$i.'][is_others]', App\Setting::status_combobox(), (isset($row['is_others']) ? $row['is_others'] : 0), ['class'=>'form-control','autofocus']); ?>

																</div>
															 </div>


															<?php echo e(Form::text('house_room_members['.$i.'][check_in_date]', $row['check_in_date'] , ['placeholder' => 'Check In Date',	'class'=>'form-control full-width'])); ?>

															<?php echo e(Form::text('house_room_members['.$i.'][check_out_date]', $row['check_out_date'] , ['placeholder' => 'Check Out Date',	'class'=>'form-control full-width'])); ?>

															<?php echo Form::select('house_room_members['.$i.'][user_id]', App\User::user_combobox($row['user_id']), $row['user_id'], ['class'=>'form-control','autofocus']); ?>

											
													</td>
										
													<td class="text-center">
														<a onclick="remove_row(this)" href="javascript:void(0)">
															<i class="fa fa-trash-o fa-fw icon-size"></i>
														</a>
													</td>
												</tr>
												<?php $i++; ?>
											<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
										<?php endif; ?>
									<?php endif; ?>
									<tr>
										<td class="text-center">
											<?php echo e($i); ?>

										</td>
								
										<td class="text-center">

						
											<div class="row">	
											 	<?php echo Form::label('status', App\Language::trans('Wifi'), ['class'=>'control-label col-md-4']); ?>

											 	<div class="col-md-8">
												    <?php echo Form::select('house_room_members['.$i.'][wifi]', App\Setting::status_combobox(), null, ['class'=>'form-control','autofocus']); ?>

												</div>
											 </div>

											  <div class="row">	
											 	<?php echo Form::label('status', App\Language::trans('Air Cond.'), ['class'=>'control-label col-md-4']); ?>

											 	<div class="col-md-8">
												    <?php echo Form::select('house_room_members['.$i.'][air_cond]', App\Setting::status_combobox(), null, ['class'=>'form-control','autofocus']); ?>

												</div>
											 </div>

											 <div class="row">	
											 	<?php echo Form::label('status', App\Language::trans('Others'), ['class'=>'control-label col-md-4']); ?>

											 	<div class="col-md-8">
												    <?php echo Form::select('house_room_members['.$i.'][is_others]', App\Setting::status_combobox(), null, ['class'=>'form-control','autofocus']); ?>

												</div>
											 </div>
											


											<?php echo e(Form::text('house_room_members['.$i.'][check_in_date]', null, ['placeholder' => 'Check In Date',	'class'=>'form-control full-width'])); ?>

											<?php echo e(Form::text('house_room_members['.$i.'][check_out_date]', null, ['placeholder' => 'Check Out Date',	'class'=>'form-control full-width'])); ?>

											<?php echo Form::select('house_room_members['.$i.'][user_id]', App\User::user_combobox(), null, ['class'=>'form-control','autofocus']); ?>


											
										</td>
										<!-- <td class="text-center">
											<?php echo e(Form::checkbox('house_room_members['.$i.'][is_gst]', 1, (is_array($rows) ? (count($rows) ? false:true) : false), ['class'=>''])); ?>

										</td> -->
										
										<td class="text-center">
											<a onclick="remove_row(this)" href="javascript:void(0)">
												<i class="fa fa-trash-o fa-fw icon-size"></i>
											</a>
										</td>
									</tr>
								</tbody>
								<tfoot>
									<tr>
										<td colspan="6" class="text-left">
											<a class="btn btn-default" onclick="add_row('house_room_members')" href="javascript:void(0)"><i class="fa fa-plus-square fa-fw"></i> <?php echo e(App\Language::trans('Add Row')); ?></a>
										</td>
									</tr>
								</tfoot>
							</table>
				</div>
		</div>
	</div>


