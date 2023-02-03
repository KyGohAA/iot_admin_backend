<?php $__env->startSection('content'); ?>
<?php echo Form::model($model, ['class'=>'form-horizontal']); ?>

<?php echo $__env->make('_version_02.commons.layouts.partials._alert', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<section class="hk-sec-wrapper">
    <h5 class="hk-sec-title"><?php echo e(App\Language::trans('User Detail')); ?></h5><hr>

    	<div class="row">
			<div class="col-md-6">
				<div class="form-group<?php echo e($errors->has('status') ? ' has-error' : ''); ?>">
					<?php echo Form::label('status', App\Language::trans('Status'), ['class'=>'control-label col-md-4']); ?>

					<div class="col-md-12">
						 <div class="row">	
						 	<div class="col-md-3">
							    <div class="custom-control custom-radio">
							        <input type="radio" id="status_on" name="status" checked class="custom-control-input">
							        <label class="custom-control-label" for="status_on"><?php echo e(App\ExtendModel::status_true_word()); ?></label>
							    </div>
							</div>
							<div class="col-md-3">
							    <div class="custom-control custom-radio">
							        <input type="radio" id="status_off" name="status"  class="custom-control-input">
							        <label class="custom-control-label" for="status_off"><?php echo e(App\ExtendModel::status_false_word()); ?></label>
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
				<div class="form-group<?php echo e($errors->has('email') ? ' has-error' : ''); ?>">
					<?php echo Form::label('user_id', App\Language::trans('User Email'), ['class'=>'control-label col-md-4']); ?>

					<div class="col-md-<?php echo e(isset($model['email']) ? '4' : '12'); ?>">
						<?php echo Form::text('email', null, ['class'=>'form-control']); ?>

                        <?php echo $errors->first('email', '<label for="email" class="help-block error">:message</label>'); ?>

					</div>
				</div>
			</div>
		</div>


		<div class="row">
			<div class="col-md-12">
				<div class="form-group<?php echo e($errors->has('fullname') ? ' has-error' : ''); ?>">
					<?php echo Form::label('fullname', App\Language::trans('Name'), ['class'=>'control-label col-md-4']); ?>

					<div class="col-md-<?php echo e(isset($model['fullname']) ? '4' : '12'); ?>">
						<?php echo Form::text('fullname', null, ['class'=>'form-control']); ?>

                        <?php echo $errors->first('fullname', '<label for="fullname" class="help-block error">:message</label>'); ?>

					</div>
				</div>
			</div>

			<div class="col-md-12">
				<div class="form-group<?php echo e($errors->has('phone_number') ? ' has-error' : ''); ?>">
					<?php echo Form::label('phone_number', App\Language::trans('Phone Number'), ['class'=>'control-label col-md-4']); ?>

					<div class="col-md-<?php echo e(isset($model['phone_number']) ? '4' : '12'); ?>">
						<?php echo Form::text('phone_number', null, ['class'=>'form-control']); ?>

                        <?php echo $errors->first('phone_number', '<label for="phone_number" class="help-block error">:message</label>'); ?>

					</div>
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