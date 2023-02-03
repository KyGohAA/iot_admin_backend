<?php echo $__env->make('_version_02.commons.layouts.partials._header', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<?php echo $__env->make('_version_02.commons.layouts.partials._left_sidebar', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <!-- Main Content -->
    <div class="hk-pg-wrapper">

    	<!-- Container mt-xl-50 mt-sm-30-->
        <div class="container-fluid  mt-5 content">
        	<!-- Breadcrumb -->
    			<!-- Title -->
    			
    			<!-- /Title -->
                <!-- /Breadcrumb -->
                <div class="row">
                    <div class="col-xl-12" id='main_content'>
                              <?php echo $__env->make('_version_02.commons.layouts.partials._content_title', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>  
          				      <?php echo $__env->yieldContent('content'); ?>
          			</div>
          		 </div>
        </div>
        <!-- /Container -->
        <?php echo $__env->make('_version_02.commons.layouts.partials._content_footer', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
        <!-- /Footer -->
    </div>
    <!-- /Main Content -->
<?php echo $__env->make('_version_02.commons.layouts.partials._footer', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<?php echo $__env->make('_version_02.commons.layouts.partials._table_plugin', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<?php echo $__env->make('_version_02.commons.layouts.partials._datatable_plugin', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>