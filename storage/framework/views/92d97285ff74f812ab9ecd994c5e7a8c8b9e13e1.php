<div id='footer'  class="box-footer">
	<div class="row">
		<div class="col-md-offset-4 col-md-10">
		<a href="<?php echo e(action($page_variables['return_url'])); ?>" class="btn btn-danger btn-wth-icon icon-wthot-bg btn-lg pull-right"><span class="icon-label"><i class="fa fa-close"></i> </span><span class="btn-text"><?php echo e(App\Language::trans('Close')); ?></span></a>
		<!--  loading-label -->
			<button  target="_blank" id="submit_button" type="submit" class="btn btn-primary btn-wth-icon icon-wthot-bg btn-lg pull-right" onclick="generate_report_msg(this);"><i class="fa fa-search fa-fw"></i><?php echo e(App\Language::trans('Search')); ?></button>			
		</div>
	</div>
</div>