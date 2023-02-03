<?php if(session(App\Setting::session_alert_status)): ?>
	<div class="alert alert-<?php echo e(session(App\Setting::session_alert_status)); ?> alert-dismissible mt-5">
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
		<i id="alert_msg" class="icon fa fa-<?php echo e(session(App\Setting::session_alert_icon)); ?>"></i>
		<?php echo e(session(App\Setting::session_alert_msg)); ?>

	</div>
<?php endif; ?>

