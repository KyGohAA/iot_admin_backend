<?php $__env->startSection('content'); ?>
<?php echo Form::model($model, ['class'=>'form-horizontal']); ?>

<?php echo $__env->make('_version_02.commons.layouts.partials._alert', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

<section class="hk-sec-wrapper">
    <h5 class="hk-sec-title"><?php echo e(App\Language::trans('User Group Information')); ?></h5><hr>
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



 <h5 class="hk-sec-title"><?php echo e(App\Language::trans('User')); ?></h5><hr>
 <div class="row">
						<div class="col-md-12">
						        <div class="form-group <?php echo $errors->first('code') ? 'has-error' : ''; ?>">
						          <label for="code" class="control-label col-sm-2"><?php echo e(App\Language::trans('User')); ?></label>
							          <div class="col-sm-12">
							           <?php echo Form::select("user_assign[user_list][]", App\User::user_assign_combobox($model['id']), isset($user_assign['user_list']) ? ( strlen($user_assign['user_list']) >  1 ? json_decode($user_assign['user_list'] ,true):null ) : null , array(  "style"=>"width: 100%;","class"=>"form-control 3col active","id"=>"user_assign[user_list][]","multiple"=>"multiple")); ?>

							          </div>
						        </div>
					    </div>
					</div>



		<div class="row">
			<div class="col-md-6">
				<div class="form-group<?php echo e($errors->has('is_admin') ? ' has-error' : ''); ?>">
					<?php echo Form::label('is_admin', App\Language::trans('Is Admin'), ['class'=>'control-label col-md-4']); ?>

					<div class="col-md-12">
						 <div class="row">	
						 	<div class="col-md-3">
							    <div class="custom-control custom-radio">
							        <input type="radio" id="is_admin_on" name="is_admin" checked class="custom-control-input">
							        <label class="custom-control-label" for="is_admin_on"><?php echo e(App\ExtendModel::answer_true_word()); ?></label>
							    </div>
							</div>
							<div class="col-md-3">
							    <div class="custom-control custom-radio">
							        <input type="radio" id="is_admin_off" name="is_admin"  class="custom-control-input">
							        <label class="custom-control-label" for="is_admin_off"><?php echo e(App\ExtendModel::answer_false_word()); ?></label>
							    </div>
							</div>
						 </div>
						 <?php echo $errors->first('is_admin', '<label for="is_admin" class="help-block error">:message</label>'); ?>

					</div>
				</div>
			</div>
		</div>

    <div class="row">
			<div class="col-md-12">
				<div class="form-group<?php echo e($errors->has('name') ? ' has-error' : ''); ?>">
					<?php echo Form::label('name', App\Language::trans('Name'), ['class'=>'control-label col-md-2']); ?>

					<div class="col-md-10">
						<?php echo Form::text('name', null, ['class'=>'form-control','autofocus','required']); ?>

                        <?php echo $errors->first('name', '<label for="name" class="help-block error">:message</label>'); ?>

					</div>
				</div>
			</div>
		</div>



	<br><h5 class="hk-sec-title"><?php echo e(App\Language::trans('Access Right')); ?></h5>
    <hr>
    		  	<?php $__currentLoopData = $permissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $controller => $resources): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
				<h6 class="hk-sec-title"><?php echo e($controller); ?></h6><hr>
				<div class="row">
					<?php $checked = true; ?>
					<?php $__currentLoopData = $resources; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
						<?php if(!$model->get_permissions($row->resource_controller, $row->resource_action)): ?>
							<?php $checked = false; ?>
						<?php endif; ?>
						<div class="col-md-3">
							<div class="checkbox">
								<label>
									<?php echo Form::checkbox('permissions['.$row->resource_controller.'][]', $row->resource_action, ($model->id ? $model->get_permissions($row->resource_controller, $row->resource_action):false)); ?> <?php echo e($row->resource_label); ?>

								</label>
							</div>
						</div>
					<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
					<?php if(count($resources) > 1): ?>
						<div class="col-md-3">
							<div class="checkbox">
								<label>
									<?php echo Form::checkbox('select_all', null, $checked, ['class'=>'select_all']); ?> <?php echo e(App\Language::trans('All')); ?>

								</label>
							</div>
						</div>
					<?php endif; ?>
				</div>
				<br>
			<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

	<div class="box-header with-border">
		 <h5 class="hk-sec-title"><?php echo e(App\Language::trans('Remarks')); ?></h5><hr>
	</div>

		<div class="row">
			<div class="col-md-12">
				<div class="form-group<?php echo e($errors->has('remark') ? ' has-error' : ''); ?>">
					<?php echo Form::label('remark', App\Language::trans('Remark'), ['class'=>'control-label col-md-2']); ?>

					<div class="col-md-10">
						<?php echo Form::textarea('remark', null, ['rows'=>'5','class'=>'form-control']); ?>

                        <?php echo $errors->first('remark', '<label for="remark" class="help-block error">:message</label>'); ?>

					</div>
				</div>
			</div>
		</div>
	
</section>
<?php echo $__env->make('_version_02.commons.layouts.partials._form_floaring_footer_standard', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>


<?php echo Form::close(); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
$("input").not(".select_all").on("click", function(){
	var row = $(this).closest(".row");
	var checked = true;
	row.find("input").not(".select_all").each(function(){
		if(!$(this).prop("checked")) {
			checked = false;
		}
	})
	row.find(".select_all").prop("checked", checked);
});
$(".select_all").on("click", function(){
	var row = $(this).closest(".row");
	var checked = $(this).prop("checked");
	row.find("input").each(function(){
		$(this).prop("checked", checked);
	});
});
<?php $__env->stopSection(); ?>
<?php echo $__env->make('_version_02.commons.layouts.admin', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>