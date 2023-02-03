<div id='footer'  class="box-footer">
	<div class="row">
		<div class="col-md-offset-4 col-md-10">
		<a href="<?php echo e(action($page_variables['return_url'])); ?>" class="btn btn-danger btn-wth-icon icon-wthot-bg btn-lg pull-right"><span class="icon-label"><i class="fa fa-close"></i> </span><span class="btn-text"><?php echo e(App\Language::trans('Close')); ?></span></a>
		<!-- loading-label -->
			<button  id="submit_button" class="btn btn-success btn-wth-icon icon-wthot-bg btn-lg pull-right "><span class="icon-label"><i class="fa fa-save"></i> </span><span class="btn-text"><?php echo e(App\Language::trans('Save')); ?></span></button>
			
		</div>
	</div>
</div>