<div class="hk-pg-header  mb-0">
    <h4 class="hk-pg-title mb-10"><span class="pg-title-icon"><span class="feather-icon"><i data-feather="server"></i></span></span><?php echo e($page_variables['page_title']); ?></h4>
    <?php if(isset($is_model_page)): ?>
	    <div class="box-tools pull-right">
			<div class="button-list">
				<?php if(isset($advance_search_status)): ?>
					<?php if($advance_search_status == true): ?>
						<a class="btn btn-primary loading-label" data-toggle="collapse" href="#advance_search" role="button" aria-expanded="false" aria-controls="advance_search"><i class="fa fa-glass"></i> <?php echo e(App\Language::trans('Advance Search')); ?>

						</a>
					<?php endif; ?>
				<?php endif; ?>

				<?php if(isset($is_bulk_upload)): ?>
						<a href="<?php echo e(action($page_variables['bulk_upload_link'])); ?>" class="btn btn-info loading-label">
							<i class="fa fa-file"></i> <?php echo e(App\Language::trans('Bulk Upload')); ?>

						</a>

						<a href="<?php echo e(action($page_variables['bulk_update_link'])); ?>" class="btn btn-info loading-label">
							<i class="fa fa-file"></i> <?php echo e(App\Language::trans('Update Room Charges')); ?>

						</a>

						<a href="<?php echo e(action($page_variables['bulk_remote_control_update_link'])); ?>" class="btn btn-info loading-label">
							<i class="fa fa-file"></i> <?php echo e(App\Language::trans('Room Power Remote Control Setting')); ?>

						</a>
				<?php endif; ?>

				<?php if(isset($page_variables['new_file_link'])): ?>
					<?php if($page_variables['new_file_link'] != ''): ?>
						<a href="<?php echo e(action($page_variables['new_file_link'])); ?>" class="btn btn-info loading-label">
							<i class="fa fa-file"></i> <?php echo e(App\Language::trans('New File')); ?>

						</a>
					<?php endif; ?>
				<?php endif; ?>	
			</div>       
	     </div>
	<?php endif; ?>
</div>