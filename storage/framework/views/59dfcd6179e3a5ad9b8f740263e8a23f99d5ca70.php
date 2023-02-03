	<hr>
    <h5 class="hk-sec-title"><?php echo e(App\Language::trans('Charges Setting')); ?></h5><hr>
   		<div id='utility_datas_section' name='utility_datas_section' class="row">
   			
					<div class="table-responsive">
						<div class="col-md-12">
							<table id="utility_datas" class="table table-bordered table-hover">
								<thead>
									<tr>
										<th class="text-center">#</th>
							
										<th class="text-center col-md-4">
										<?php echo e(App\Language::trans('Month Year')); ?><br>
										<?php echo e(App\Language::trans('Fee Type')); ?></th>
										<!-- <th class="text-center col-md-1"><?php echo e(App\Language::trans('GST')); ?></th> -->
										<th class="text-center col-md-1"><?php echo e(App\Language::trans('Charges (RM)')); ?></th>
										<th class="text-center col-md-1"><?php echo e(App\Language::trans('Action')); ?></th>
									</tr>
								</thead>
								<tbody>
									<?php 
										$i = 1;
										$rows = old('utility_datas') ? old('utility_datas'):$model->utility_datas; 


										$rows = App\Setting::array_msort($rows , array('bill_type'=>SORT_ASC , 'month_year'=>SORT_ASC ));
										//dd($rows);
										//dd(count($rows));

									?>
									<?php if($rows != null): ?>
										<?php if(count($rows) > 0): ?>
										
											<?php $__currentLoopData = $rows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
												<tr>
													<td class="text-center">
														<?php echo e($i); ?>

													</td>
										
													<td class="text-center">
														
														<?php echo Form::select('utility_datas['.$i.'][month_year]', App\Setting::month_year_combobox(6), $row['month_year'], ['class'=>'form-control','autofocus','required']); ?>

														<?php echo Form::select('utility_datas['.$i.'][bill_type]', App\UtilityKy\House::utility_combobox(), $row['bill_type'], ['class'=>'form-control','autofocus','required']); ?>

									
													</td>
													<!-- <td class="text-center">
														<?php echo e(Form::checkbox('utility_datas['.$i.'][is_gst]', 1, (isset($row['is_gst']) ? $row['is_gst']:false), ['class'=>''])); ?>

													</td> -->
													<td class="text-center">
														<?php echo e(Form::text('utility_datas['.$i.'][amount]', $row['amount'], ['class'=>'full-width'])); ?>

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
											<?php echo Form::select('utility_datas['.$i.'][month_year]', App\Setting::month_year_combobox(6), null, ['class'=>'form-control','autofocus','required']); ?>

											<?php echo Form::select('utility_datas['.$i.'][bill_type]', App\UtilityKy\House::utility_combobox(), null, ['class'=>'form-control','autofocus','required']); ?>

										</td>
										<!-- <td class="text-center">
											<?php echo e(Form::checkbox('utility_datas['.$i.'][is_gst]', 1, (is_array($rows) ? (count($rows) ? false:true) : false), ['class'=>''])); ?>

										</td> -->
										<td class="text-center">
											<?php echo e(Form::text('utility_datas['.$i.'][amount]', null, ['class'=>'full-width'])); ?>

										</td>
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
											<a class="btn btn-default" onclick="add_row('utility_datas')" href="javascript:void(0)"><i class="fa fa-plus-square fa-fw"></i> <?php echo e(App\Language::trans('Add Row')); ?></a>
										</td>
									</tr>
								</tfoot>
							</table>
				</div>
		</div>
	</div>


