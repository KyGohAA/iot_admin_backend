<?php $__env->startSection('content'); ?>
<?php echo Form::model($model, ['class'=>'form-horizontal']); ?>

<?php echo $__env->make('_version_02.commons.layouts.partials._alert', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

<section class="hk-sec-wrapper">
    <h5 class="hk-sec-title"><?php echo e(App\Language::trans('Information')); ?></h5>
   		<?php
   			$message = $model->getSubsidyEffectiveDescription();
   		?>
		<div class="row mb-15">
		    <div class="col-sm">
		        <div class="media pa-20 border border-2 border-light rounded">
		            <img class="mr-15 circle d-74" src="<?php echo e(asset('img/red_information.png')); ?>" alt="Generic placeholder image">
		            <div class="media-body">
		                <h6 class="mb-5"><?php echo e(App\Language::trans('Information')); ?></h6>
		                 <?php echo e(App\Language::trans($message)); ?>

		            </div>
		        </div>
		    </div>
		</div>

		<div class="row">
			<div class="col-md-6">
				<div class="form-group<?php echo e($errors->has('status') ? ' has-error' : ''); ?>">
					<?php echo Form::label('status', App\Language::trans('Status'), ['class'=>'control-label col-md-4', 'style' => 'margin-bottom:0px;' ]); ?>

					<label style="margin-bottom:0px;"  class="form-control-static">
							<small class="badge badge-<?php echo e($model->status != 0  ? 'success' : 'danger'); ?> mt-15 mr-10"><?php echo e($model->display_status_string('status')); ?></small>
						


					</label>				
					</div>
				</div>
		</div>

		<div class="row">
			<div class="col-md-6">
				<div class="form-group<?php echo e($errors->has('code') ? ' has-error' : ''); ?>">
					<?php echo Form::label('code', App\Language::trans('Code'), ['class'=>'control-label col-md-4', 'style' => 'margin-bottom:0px;' ]); ?>

					<label style="margin-bottom:0px;"  class="form-control-static"><?php echo e($model->code); ?></label>
					
				</div>
			</div>

			<div class="col-md-6">
				<div class="form-group<?php echo e($errors->has('name') ? ' has-error' : ''); ?>">
					<?php echo Form::label('text', App\Language::trans('Name'), ['class'=>'control-label col-md-4', 'style' => 'margin-bottom:0px;' ]); ?>

					<label style="margin-bottom:0px;"  class="form-control-static"><?php echo e($model->name); ?></label>
				</div>
			</div>
		</div>


		<div class="row">
			<div class="col-md-6">
				<div class="form-group<?php echo e($errors->has('amount') ? ' has-error' : ''); ?>">
					<?php echo Form::label('amount', App\Language::trans('Amount'), ['class'=>'control-label col-md-4', 'style' => 'margin-bottom:0px;' ]); ?>

					<label style="margin-bottom:0px;"  class="form-control-static"><?php echo e($model->amount); ?></label>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-md-6">
				<div class="form-group<?php echo e($errors->has('room_type') ? ' has-error' : ''); ?>">
					<?php echo Form::label('room_type', App\Language::trans('Room Type'), ['class'=>'control-label col-md-4', 'style' => 'margin-bottom:0px;' ]); ?>

					<label style="margin-bottom:0px;"  class="form-control-static"><?php echo e($model->display_room_type_string('room_type')); ?></label>
				</div>
			</div>	
		</div>


		<div class="row">
			<div class="col-md-6">
				<div class="form-group<?php echo e($errors->has('implementation_date') ? ' has-error' : ''); ?>">
					<?php echo Form::label('implementation_date', App\Language::trans('Implementation Day'), ['class'=>'control-label col-md-4', 'style' => 'margin-bottom:0px;' ]); ?>

					<label style="margin-bottom:0px;"  class="form-control-static"><?php echo e($model->getDayInWord($model->implementation_date)); ?></label>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-md-6">
				<div class="form-group<?php echo e($errors->has('starting_date') ? ' has-error' : ''); ?>">
					<?php echo Form::label('starting_date', App\Language::trans('From Month'), ['class'=>'control-label col-md-4', 'style' => 'margin-bottom:0px;' ]); ?>

					<label style="margin-bottom:0px;"  class="form-control-static"><?php echo e($model->setDate($model->starting_date)); ?></label>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group<?php echo e($errors->has('month_ended') ? ' has-error' : ''); ?>">
					<?php echo Form::label('ending_date', App\Language::trans('To Month'), ['class'=>'control-label col-md-4', 'style' => 'margin-bottom:0px;' ]); ?>

					<label style="margin-bottom:0px;"  class="form-control-static"><?php echo e($model->setDate($model->ending_date)); ?></label>
				</div>
			</div>
		</div>

		
		
		

		<div class="row">
			<div class="col-md-6">
				<div class="form-group<?php echo e($errors->has('remark') ? ' has-error' : ''); ?>">
					<?php echo Form::label('remark', App\Language::trans('Remark'), ['class'=>'control-label col-md-4', 'style' => 'margin-bottom:0px;' ]); ?>

					<label class="form-control-static"><?php echo e($model->remark); ?></label>
				</div>
			</div>
		</div>


	<!-- Plugin: Dual Select List -->
	<?php 
		$grand_total = 0;
		$listing_index = 1;
		$ids  = strlen($model->subsidize_tenant_id) >  1 ? json_decode($model->subsidize_tenant_id,true):null;
		$member_list = App\Customer::get_leaf_member_status_list();
		$member_detail = array();
		foreach($member_list['non_exist_member_listing'] as $member)
		{
			if(in_array( $member['id_house_member'] , $ids))
			{
				$member['room_name'] = App\LeafAPI::get_room_name_by_leaf_room_id($member['id_house_room']);
				$member_detail[$member['id_house_member']] = $member;
			}
		}

		$user_subsidiary_listing = App\PowerMeterModel\MeterPaymentReceived::getSubsidyBySudsidyId($model->id);
		//echo $model->id;
		//dd($user_subsidiary_listing);
	?>


<?php if(count($member_detail) > 0): ?>
	<section class="hk-sec-wrapper">
   		 <h5 class="hk-sec-title" style="margin-bottom:0px;"><?php echo e(App\Language::trans('Recipient List')); ?></h5>
			<div class="table-responsive">
				<table class="table table-bordered table-hover">
					<thead>
						<tr  style="background-color: #ddd;">
							<th class="text-center">#</th>
							<th class="text-center"><?php echo e(App\Language::trans('Room Name')); ?></th>
							<th class="text-center"><?php echo e(App\Language::trans('Name')); ?></th>		
							<th class="text-center"><?php echo e(App\Language::trans('Check In Date')); ?></th>	
							<th class="text-center" colspan="2"><?php echo e(App\Language::trans('Check Out Date')); ?></th>
						</tr>
					</thead>
					<tbody>

						<?php $__currentLoopData = $member_detail; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $member): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
					            <tr>
					            	<td class="text-right"><?php echo e($listing_index); ?></td>
									<td class="text-center"><?php echo e($member['room_name']); ?></td>
									<td class="text-center"><?php echo e($member['house_member_name']); ?></td>
									<td class="text-center"><?php echo e($member['house_room_member_start_date']); ?></td>
									<td class="text-center" colspan="2"><?php echo e($member['house_room_member_end_date'] == '0000-00-00 00:00:00' ? '-' : $member['house_room_member_end_date']); ?></td>
								</tr>
								
								<?php 
									$listing_index ++;
									$subsidy_index = 1;
									//  style="background-color: #ddd;"
								?>
								<tr>
									<th class="text-center">#</th>
									<th class="text-center"><?php echo e(App\Language::trans('Document No')); ?></th>
									<th class="text-center"><?php echo e(App\Language::trans('Document Date')); ?></th>
									<th class="text-center"><?php echo e(App\Language::trans('Description')); ?></th>
									<th class="text-center"  colspan="2"><?php echo e(App\Language::trans('Amount')); ?></th>
								</tr>

								<?php if(count($user_subsidiary_listing) > 0): ?>
										<?php $__currentLoopData = $user_subsidiary_listing; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user_subsidy): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

											<tr>
								            	<td class="text-right"><?php echo e($subsidy_index); ?></td>
												
												<td class="text-center"><?php echo e($user_subsidy['document_no']); ?></td>
												<td class="text-center"><?php echo e($user_subsidy['document_date']); ?></td>
												<td class="text-center"><?php echo e($user_subsidy['remark']); ?></td>
												<td class="text-right" colspan="2"><?php echo e($user_subsidy['total_amount']); ?></td>
											
											</tr>

											<?php 
												$grand_total += $user_subsidy['total_amount'];
												$subsidy_index ++;
											?>

										<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
								<?php else: ?>
									<tr>
										<td colspan="6" class="text-center">  No record found. </td>
									</tr>

								<?php endif; ?>
								<tr> <td colspan="6"></td> </tr>
								
						<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
					            


					</tbody>
					<tfoot>
						<hr>
						<br>
						<tr>
							<td class="text-right" colspan="5"><?php echo e(App\Language::trans('Total')); ?>:</td>
							<td class="text-right"><?php echo e(number_format($grand_total, 2)); ?></td>
						</tr>
					</tfoot>
				</table>
			</div>
	</section>

<?php endif; ?>

</section>

<?php echo Form::close(); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>

$(".input-daterange").datepicker({
	format: "dd-mm-yyyy",
});
<?php $__env->stopSection(); ?>
<?php echo $__env->make('_version_02.commons.layouts.admin', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>