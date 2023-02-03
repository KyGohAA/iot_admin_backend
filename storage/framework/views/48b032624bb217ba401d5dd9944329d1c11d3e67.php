<?php $__env->startSection('content'); ?>
<?php echo Form::model($model, ['class'=>'form-horizontal']); ?>

<?php echo $__env->make('_version_02.commons.layouts.partials._alert', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<section class="hk-sec-wrapper">
    <h5 class="hk-sec-title"><?php echo e(App\Language::trans('Room Detail')); ?></h5><hr>

    <div class="row">
		<div class="col-md-6">
			<div class="form-group<?php echo e($errors->has('status') ? ' has-error' : ''); ?>">	
				<?php echo Form::label('status', App\Language::trans('Status'), ['class'=>'control-label col-md-12']); ?>

				<div class="col-md-12">
					  <div class="row">	
					 	<div class="col-md-3">
						    <div class="custom-control custom-radio">
						        <input type="radio" id="status" name="status" value=1  class="custom-control-input" <?php echo e(isset($model->status) == true ? ($model->status == true ? 'checked' : '') : 'checked'); ?>>
						        <label class="custom-control-label" for="status"><?php echo e(App\Language::trans('Enabled')); ?></label>
						    </div>
						</div>
						<div class="col-md-3">
						    <div class="custom-control custom-radio">
						         <input type="radio" id="status_off" name="status" value=0 class="custom-control-input" <?php echo e(isset($model->status) == true ? ($model->status == false ? 'checked' : '') : ''); ?>>
						        <label class="custom-control-label" for="status_off"><?php echo e(App\Language::trans('Disabled')); ?></label>
						    </div>
						</div>
					 </div>
					 <?php echo $errors->first('status', '<label for="status" class="help-block error">:message</label>'); ?>

				</div>
			</div>
		</div>	
	</div>

   		<div class="row">
			<div class="col-md-12">
				<div class="form-group<?php echo e($errors->has('id_house') ? ' has-error' : ''); ?>">
					<?php echo Form::label('id_house', App\Language::trans('House'), ['class'=>'control-label col-md-4']); ?>

					<div class="col-md-8">
						<?php echo Form::select('id_house', App\UtilityKy\House::combobox(), null, ['class'=>'form-control','autofocus','required']); ?>

                        <?php echo $errors->first('id_house', '<label for="id_house" class="help-block error">:message</label>'); ?>

					</div>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-md-12">
				<div class="form-group<?php echo e($errors->has('house_room_type') ? ' has-error' : ''); ?>">
					<?php echo Form::label('house_room_type', App\Language::trans('Room Type'), ['class'=>'control-label col-md-4']); ?>

					<div class="col-md-8">
						<?php echo Form::select('house_room_type', App\UtilityKy\Room::combobox_room_type(), null, ['class'=>'form-control','autofocus','required']); ?>

                        <?php echo $errors->first('house_room_type', '<label for="house_room_type" class="help-block error">:message</label>'); ?>

					</div>
				</div>
			</div>
		</div>

		
		<div class="row">
			<div class="col-md-6">
				<div class="form-group<?php echo e($errors->has('house_room_floor') ? ' has-error' : ''); ?>">
					<?php echo Form::label('house_room_floor', App\Language::trans('House Room Floor'), ['class'=>'control-label col-md-4']); ?>

					<div class="col-md-8">
						<?php echo Form::text('house_room_floor', null, ['class'=>'form-control','required']); ?>

                        <?php echo $errors->first('house_room_floor', '<label for="house_room_floor" class="help-block error">:message</label>'); ?>

					</div>
				</div>
			</div>
	
			<div class="col-md-6">
				<div class="form-group<?php echo e($errors->has('house_room_name') ? ' has-error' : ''); ?>">
					<?php echo Form::label('house_room_name', App\Language::trans('Room Name'), ['class'=>'control-label col-md-4']); ?>

					<div class="col-md-8">
						<?php echo Form::text('house_room_name', null, ['class'=>'form-control','required']); ?>

                        <?php echo $errors->first('house_room_name', '<label for="house_room_name" class="help-block error">:message</label>'); ?>

					</div>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-md-6">
				<div class="form-group<?php echo e($errors->has('rental') ? ' has-error' : ''); ?>">
					<?php echo Form::label('rental', App\Language::trans('Rental'), ['class'=>'control-label col-md-4']); ?>

					<div class="col-md-8">
						<?php echo Form::number('rental', null, ['class'=>'form-control','required']); ?>

                        <?php echo $errors->first('rental', '<label for="rental" class="help-block error">:message</label>'); ?>

					</div>
				</div>
			</div>
	
			<div class="col-md-6">
				<div class="form-group<?php echo e($errors->has('rental_cost') ? ' has-error' : ''); ?>">
					<?php echo Form::label('rental_cost', App\Language::trans('Rental Cost'), ['class'=>'control-label col-md-4']); ?>

					<div class="col-md-8">
						<?php echo Form::number('rental_cost', null, ['class'=>'form-control','required']); ?>

                        <?php echo $errors->first('rental_cost', '<label for="rental_cost" class="help-block error">:message</label>'); ?>

					</div>
				</div>
			</div>
		</div>


		<div class="panel panel-primary">
      <div class="panel-heading">
        <span class="panel-title"><?php echo e(App\Language::trans('House Room Members')); ?></span>
      </div>
      <div class="panel-body p25">
        <div class="form-group <?php echo $errors->first('code') ? 'has-error' : ''; ?>">
          

           <?php echo $__env->make('_version_02.commons.utilityKy.rooms.partials._house_room_members', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>


     
        </div>
         
    
       </div>
    </div>  

	
		
</section>
<?php echo $__env->make('_version_02.commons.layouts.partials._form_floaring_footer_standard', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<?php echo Form::close(); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('_version_02.commons.layouts.admin', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>