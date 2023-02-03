<?php $__env->startSection('content'); ?>

      <?php
          $notice = is_array($notice) ?  $notice : (array) $notice;
      ?>
      <section class="hk-sec-wrapper">
          <h5 class="hk-sec-title"><?php echo e(isset($notice['title']) ? $notice['title'] : ''); ?></h5>
           <div class="row">
              <div class="col-sm">
                  <div class="row">
                      <div class="col-lg-12 col-md-12 col-sm-12">
                          <div class="card">
                              <img class="card-img-top" src="<?php echo e(asset($page_variables['logo_photo_path'])); ?>" alt="Card image cap" style="padding-top:5%;height:50vh;">
                              <div class="card-body">
                                  <!-- <h5 class="card-title"></h5> -->
                                  <p class="card-text"><?php echo html_entity_decode(isset($notice['content']) ? $notice['content'] : ''); ?></p>
                                  <!-- <p class="card-text"><small class="text-muted"></small></p> -->
                              </div>
                          </div>
                      </div>
                      
                  </div>
              </div>
          </div>
      </section>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('_version_02.utility_charges.mobile_apps_light.layouts.main', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>