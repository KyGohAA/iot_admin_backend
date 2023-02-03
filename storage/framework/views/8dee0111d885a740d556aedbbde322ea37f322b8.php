<?php $__env->startSection('content'); ?>
<?php echo Form::model($model, ['class'=>'form-horizontal']); ?>

<?php echo $__env->make('_version_02.commons.layouts.partials._alert', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<div class="box">
	<div class="box-header with-border">
		<h3 class="box-title"><?php echo e(App\Language::trans('Detail Form')); ?></h3>
		<div class="box-tools pull-right">
			<a href="<?php echo e(action('UserGroupsController@getNew')); ?>" class="btn btn-block btn-info">
				<i class="fa fa-file"></i> <?php echo e(App\Language::trans('New File')); ?>

			</a>
		</div>
	</div>
	<div class="box-body">
		<div class="row">
			<div class="col-md-12">
				<div class="form-group<?php echo e($errors->has('name') ? ' has-error' : ''); ?>">
					<?php echo Form::label('name', App\Language::trans('Name'), ['class'=>'control-label col-md-2']); ?>

					<div class="col-md-10">
						<p class="form-control-static"><?php echo e($model->name); ?></p>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="box">
	<div class="box-header with-border">
		<h3 class="box-title"><?php echo e(App\Language::trans('Description Form')); ?></h3>
		<div class="box-tools pull-right">
			<a href="<?php echo e(action('UserGroupsController@getNew')); ?>" class="btn btn-block btn-info">
				<i class="fa fa-file"></i> <?php echo e(App\Language::trans('New File')); ?>

			</a>
		</div>
	</div>
	<div class="box-body">
		<?php $__currentLoopData = $permissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $controller => $resources): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
			<h3 class="box-title"><?php echo e($controller); ?></h3>
			<div class="row">
				<?php $checked = true; ?>
				<?php $__currentLoopData = $resources; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
					<?php if(!$model->get_permissions($row->resource_controller, $row->resource_action)): ?>
						<?php $checked = false; ?>
					<?php endif; ?>
					<div class="col-md-3">
						<div class="checkbox">
							<label>
								<?php echo Form::checkbox('permissions['.$row->resource_controller.'][]', $row->resource_action, ($model->id ? $model->get_permissions($row->resource_controller, $row->resource_action):false), ['disabled']); ?> <?php echo e($row->resource_label); ?>

							</label>
						</div>
					</div>
				<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
			</div>
		<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
	</div>
</div>
<div class="box">
	<div class="box-header with-border">
		<h3 class="box-title"><?php echo e(App\Language::trans('Other Form')); ?></h3>
		<div class="box-tools pull-right">
			<a href="<?php echo e(action('UserGroupsController@getNew')); ?>" class="btn btn-block btn-info">
				<i class="fa fa-file"></i> <?php echo e(App\Language::trans('New File')); ?>

			</a>
		</div>
	</div>
	<div class="box-body">
		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					<?php echo Form::label('status', App\Language::trans('Status'), ['class'=>'control-label col-md-4']); ?>

					<div class="col-md-8">
						<p class="form-control-static"><?php echo e($model->display_status_string('status')); ?></p>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					<?php echo Form::label('remark', App\Language::trans('Remark'), ['class'=>'control-label col-md-4']); ?>

					<div class="col-md-8">
						<p class="form-control-static"><?php echo e(nl2br($model->remark)); ?></p>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="box-footer">
		<div class="row">
			<div class="col-md-offset-2 col-md-10">
				<a href="<?php echo e(action('UserGroupsController@getIndex')); ?>" class="btn btn-danger"><i class="fa fa-ban fa-fw"></i><?php echo e(App\Language::trans('Close')); ?></a>
			</div>
		</div>
	</div>
</div>
<?php echo Form::close(); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('_version_02.commons.layouts.admin', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>