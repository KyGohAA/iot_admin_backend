<?php echo Form::hidden('leaf_id_user', isset($user['leaf_id_user']) ? $user['leaf_id_user'] : '' ,['id'=>'leaf_id_user' , 'value'=>isset($user['leaf_id_user']) ? $user['leaf_id_user'] : '']); ?>

<?php echo $__env->make('_version_02.utility_charges.mobile_apps_light.layouts_home.partials._header', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
		<?php echo $__env->yieldContent('content'); ?>
<?php echo $__env->make('_version_02.utility_charges.mobile_apps_light.layouts_home.partials._footer', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>





 
