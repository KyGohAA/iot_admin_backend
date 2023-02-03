<?php $__env->startSection('content'); ?>
<input type="hidden" id='account_data' value="<?php echo e($account_data['is_created']); ?>">
<section class="hk-sec-wrapper fullscreen" style="height:100%;">
       
        
            <!-- CATEGORY -->
            <div class="section home-category  flex-ppal-setup" style="margin:2% 2% -35% 2%;">
              <!-- <div class="container"> -->
              <div> 
                 <!-- padding-top:0px; -->
                 <div class="row slick-product" style="margin-bottom:5px;">
                      <div class="col s12"  >
                         <div class="featured-product">
                            <div>
                               <div class="col-slick-product">
                                  <div class="box-product shadow-hover-lg" style="padding-bottom:10px;">
                                    <div class="bp-top" style="padding-top:15px;">
                                          <h5 class="text-center" style="display: inline;"> <i style="padding-bottom:2px; margin-right: :3px;" class="fas fa-house-user"></i> <?php echo e($house_room_name); ?><!-- Account Status -->  </h5> 
                                          <span style="display: inline; margin-left:3px;padding-bottom:2px;" class="badge badge-soft-<?php echo e($account_data['power_supply_class']); ?>"><?php echo e($account_data['power_supply_state']); ?></span>
                                      <hr>
                                      

                                      <!-- <div class="price">
                                         <strong><?php echo e(App\Language::trans('Balance amount')); ?> :</strong> <span> RM <?php echo e($account_data['current_credit_amount']); ?> <label id="balance_amount"></label>: <?php echo e($account_data['current_balance_kwh']); ?>  <?php echo e(App\Language::trans('kWh')); ?> </span>
                                      </div> -->

                                       
                                      <!-- <div class="price" style="margin-bottom:15px;">
                                        <strong><?php echo e(App\Setting::get_month_in_word(date('m'))); ?> <?php echo e(App\Language::trans('usage')); ?> : </strong> <span> RM <?php echo e($account_data['current_usage_amount']); ?><label id="current_usage"></label>: <?php echo e($account_data['current_usage_kwh']); ?>  <?php echo e(App\Language::trans('kWh')); ?> </span>
                                      </div> -->

                                      <div class="price">
                                         <strong><?php echo e(App\Language::trans('Balance amount')); ?> :</strong> <span> RM <?php echo e($account_data['current_credit_amount']); ?> <label id="balance_amount"></label>: <?php echo e($account_data['current_balance_kwh']); ?>  <?php echo e(App\Language::trans('kWh')); ?> </span>
                                      </div>

                                      <div class="price" style="margin-bottom:15px;">
                                        <strong><?php echo e(App\Setting::get_month_in_word(date('m'))); ?> <?php echo e(App\Language::trans('usage')); ?> : </strong> <span> RM <?php echo e($account_data['total_payable_amount']); ?><label id="current_usage"></label>: <?php echo e($account_data['total_usage_kwh']); ?>  <?php echo e(App\Language::trans('kWh')); ?> </span>
                                      </div>

                                       <div class="in-content">
                                       <div class="in-in-content">
                                      <div class="chart" style="position: relative; height:40vh; width:80vw">
                                         <canvas id="barChart" style="position: relative; height:40vh; width:80vw"></canvas>
                                      </div>
                                    </div>
                                  </div>

                                       <h7 style="text-align: left;"><br> Last update at : <?php echo e($account_data['last_meter_reading']); ?> </h7>

                                  </div>
                                </div>
                              </div>
                          </div>
                        </div>
                      </div>
                  </div> 
                  
                <div class="row slick-product">

                    <div class="col s4 m4 l2"><a class="icon-content shadow-bottom shadow-hover-lg" onclick="initialize_usage_line_chart('barChart');">
                      <div class="content fadetransition">
                        <div class="in-content">
                          <div class="in-in-content">
                            <img src="<?php echo e(asset('leaf_acconting_mobile/img/icon/transaction.png')); ?>" alt="category">
                            <h5><br> Daily Usage </h5>
                          </div>
                        </div>
                      </div></a>
                    </div>

                    <div class="col s4 m4 l2"><a class="icon-content  shadow-bottom shadow-hover-lg" onclick="initialize_mobile_app_report('barChart');">
                      <div class="content fadetransition">
                        <div class="in-content">
                          <div class="in-in-content">
                            <img src="<?php echo e(asset('leaf_acconting_mobile/img/icon/statistic.png')); ?>" alt="category">
              
                            <h5><br> Summary  </h5>
                          </div>
                        </div>
                      </div></a>
                    </div>
                    
                    <div class="col s4 m4 l2"><a class="icon-content shadow-right shadow-hover-lg" onclick=" init_loading_overlay();" href="<?php echo e(action('AppsUtilityChargesController@getTopUp')); ?>?amount=<?php echo e($power_meter_operational_setting->top_up_min_amount); ?>&session_token=<?php echo e($session_token); ?>">
                      <div class="content fadetransition">
                        <div class="in-content">
                          <div class="in-in-content">
                            <img src="<?php echo e(asset('leaf_acconting_mobile/img/icon/top_up_electric.png')); ?>" alt="category">
                            <h5><br> Top Up </h5>
                          </div>
                        </div>
                      </div></a>
                    </div>

                   
                </div>
              </div>

            </div>
            <!-- END CATEGORY -->
    </section>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>


$( document ).ready(function() {

  initialize_mobile_app_report('barChart');
});

<?php $__env->stopSection(); ?>

<?php echo $__env->make('_version_02.utility_charges.mobile_apps_light.layouts_home.main', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>