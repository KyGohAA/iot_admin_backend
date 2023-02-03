<?php $__env->startSection('content'); ?>
            
<?php
    $div_card_height = "style=height:120px;";
    $detail_div_height  = "style=height:520px;overflow-x:hidden; overflow-y:auto;";
?>
     <!--  <div class="row clearfix">            
                <iframe
                          width="600"
                          height="450"
                          style="border:0"
                          loading="lazy"
                          allowfullscreen
                          referrerpolicy="no-referrer-when-downgrade"
                          src="https://www.google.com/maps/embed/v1/place?key=AIzaSyCZqbYyeQk85WTg6IdQI6CZd8P8bsoooIs
                            &q=Space+Needle,Seattle+WA">
                </iframe>
      </div> -->

      <input type="hidden" id="dev_eui" name="dev_eui" value="<?php echo e($dev_eui); ?>">
         <h2><strong> <?php echo e($model['deviceProfileName']); ?></strong></h2><hr>

                        <div class="row">        
                             <?php if(isset($data['battery'])): ?>    
                            <div class="col-lg-3 col-md-6 col-sm-12">                 
                                            <div class="card" <?php echo e($div_card_height); ?>>
                                                <div class="body">
                                                    <div class="row">
                                                        <div class="col-7">
                                                            <h5 class="m-t-0">Battery</h5>
                                                           <!--  <small class="text-small">6% higher than last month</small> -->
                                                        </div>
                                                        <div class="col-5 text-right">
                                                            <h2 class="m-b-0"> <?php echo e($data['battery']); ?> %</h2>
                                                            <small class="info">of 100%</small>
                                                        </div>
                                                        <div class="col-12">
                                                            <div class="progress m-t-20">
                                                            <?php 
                                                                $battery_status = 'success';
                                                                if($data['battery'] > 75)
                                                                {
                                                                    $battery_status = 'success';
                                                                }else if($data['battery'] > 40)
                                                                {
                                                                    $battery_status = 'warning';
                                                                }else
                                                                {
                                                                    $battery_status = 'danger';
                                                                }

                                                            ?>
                                                            <div class="progress-bar progress-bar-<?php echo e($battery_status); ?>" role="progressbar" aria-valuenow="<?php echo e($data['battery']); ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo e($data['battery']); ?>%;"></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endif; ?>



                                    <?php if(isset($data['daylight'])): ?>   
                                     <div class="col-lg-3 col-md-6 col-sm-12">         
                                            <div class="card" <?php echo e($div_card_height); ?>>
                                                <div class="body">
                                                    <div class="row">
                                                        <div class="col-7">
                                                            <h5 class="m-t-0">Day Light</h5>
                                                           <!--  <small class="text-small">6% higher than last month</small> -->
                                                        </div>
                                                        <div class="col-5 text-right">
                                                            <h2 class="m-b-0"> <?php echo e(ucfirst($data['daylight'])); ?></h2>
                                                            <!-- <small class="info">of 100%</small> -->
                                                        </div>

                                                        <div class="col-7">
                                                            <h5 class="m-t-0">PIR</h5>
                                                           <!--  <small class="text-small">6% higher than last month</small> -->
                                                        </div>
                                                        <div class="col-5 text-right">
                                                            <h2 class="m-b-0"> <?php echo e(ucfirst($data['pir'])); ?></h2>
                                                            <!-- <small class="info">of 100%</small> -->
                                                        </div>
                                                      
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                     <?php endif; ?>

                                    <?php if(isset($data['people_counter_all'])): ?> 
                                     <div class="col-lg-3 col-md-6 col-sm-12">           
                                            <div class="card" <?php echo e($div_card_height); ?>>
                                                <div class="body">
                                                    <div class="row">
                                                        <div class="col-7">
                                                            <h5 class="m-t-0">No of people</h5>
                                                           <!--  <small class="text-small">6% higher than last month</small> -->
                                                        </div>
                                                        <div class="col-5 text-right">
                                                            <h2 class="m-b-0"> <?php echo e($data['people_counter_all']); ?></h2>
                                                            <!-- <small class="info">of 100%</small> -->
                                                        </div>

                                                        <div class="col-7">
                                                            <h5 class="m-t-0">Region Count</h5>
                                                           <!--  <small class="text-small">6% higher than last month</small> -->
                                                        </div>
                                                        <div class="col-5 text-right">
                                                            <h2 class="m-b-0"> <?php echo e($data['region_count']); ?></h2>
                                                            <!-- <small class="info">of 100%</small> -->
                                                        </div>
                                                      
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                     <?php endif; ?>

                                     <?php if(isset($data['install'])): ?>  
                                      <div class="col-lg-3 col-md-6 col-sm-12">          
                                            <div class="card" <?php echo e($div_card_height); ?>>
                                                <div class="body">
                                                    <div class="row">
                                                        <div class="col-7">
                                                            <h5 class="m-t-0">Is Installed</h5>
                                                           <!--  <small class="text-small">6% higher than last month</small> -->
                                                        </div>
                                                        <div class="col-5 text-right">
                                                            <h2 class="m-b-0"> <?php echo e(ucfirst($data['install'])); ?></h2>
                                                            <!-- <small class="info">of 100%</small> -->
                                                        </div>

                                                        <div class="col-7">
                                                            <h5 class="m-t-0">State</h5>
                                                           <!--  <small class="text-small">6% higher than last month</small> -->
                                                        </div>
                                                        <div class="col-5 text-right">
                                                            <h2 class="m-b-0"> <?php echo e(ucfirst($data['state'])); ?></h2>
                                                            <!-- <small class="info">of 100%</small> -->
                                                        </div>
                                                      
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                     <?php endif; ?>

                                      <?php if(isset($data['humidity'])): ?>   
                                       <div class="col-lg-3 col-md-6 col-sm-12">         
                                            <div class="card" <?php echo e($div_card_height); ?>>
                                                <div class="body">
                                                    <div class="row">
                                                        <div class="col-7">
                                                            <h5 class="m-t-0">Humidity</h5>
                                                           <!--  <small class="text-small">6% higher than last month</small> -->
                                                        </div>
                                                        <div class="col-5 text-right">
                                                            <h2 class="m-b-0"> <?php echo e($data['humidity']); ?></h2>
                                                            <!-- <small class="info">of 100%</small> -->
                                                        </div>

                                                        <div class="col-7">
                                                            <h5 class="m-t-0">Temperature</h5>
                                                           <!--  <small class="text-small">6% higher than last month</small> -->
                                                        </div>
                                                        <div class="col-5 text-right">
                                                            <h2 class="m-b-0"> <?php echo e($data['temperature']); ?> ℃</h2>
                                                            <!-- <small class="info">of 100%</small> -->
                                                        </div>
                                                      
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                     <?php endif; ?>
                                </div><!-- End of widget row -->


        <div class="row clearfix">
            <?php $__currentLoopData = $graph_info; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $info): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="col-lg-6 col-md-6">
                           <div class="card">
                                <div class="header">
                                    <h2><?php echo e($info['title']); ?> </h2>
                                   
                                </div>
                                <div class="body" style='overflow-x: scroll;'>
                                      <div class="chart" style="position: relative; height:40vh; width:80vw">
                                         <canvas id="<?php echo e($info['graph_id']); ?>" style="position: relative; max-height:40vh; max-width:40vw"></canvas>
                                         <!-- barChart -->
                                      </div>
                                </div>
                            </div>
                    </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
       </div><!--  End of Gragh Row -->



        <div class="row clearfix">
   
                <!--  End of device Data Div -->


                <div class="col-md-6 col-lg-3">
                            <div class="card"  <?php echo e($detail_div_height); ?>>
                                <div class="header"  style="padding-bottom:0px;">
                                    <h2>Device Feeds <small></small></h2>
                                </div>
                                <div class="body" style="padding-top:0px;">                            
                                    <ul class="list-unstyled feeds_widget">
                                        <?php $__currentLoopData = $feeds; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $feed): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php
                                                $data = isset($feed['reading_data']) ? json_decode($feed['reading_data'], true) : array();
                                                //dd($data);
                                            ?>
                                            <li>
                                                <!-- <div class="feeds-left"><i class="fa fa-check"></i></div>  -->
                                                <div class="feeds-body">
                                                    <h4 class="title"><small><?php echo e($feed['created_at']); ?></small></h4>
                                                    <small>
                                                             <?php if(isset($data['humidity'])): ?>   
                                                                  Humidity : <?php echo e($data['humidity']); ?> <br>
                                                                  Temperature : <?php echo e($data['temperature']); ?> ℃ <br>
                                                             <?php endif; ?>


                                                              <?php if(isset($data['install'])): ?>  
                                                                  Is Installed : <?php echo e(ucfirst($data['install'])); ?><br>
                                                                  State : <?php echo e(ucfirst($data['state'])); ?><br>

                                                             <?php endif; ?>


                                                             <?php if(isset($data['people_counter_all'])): ?> 
                                                                No of people : <?php echo e($data['people_counter_all']); ?><br>
                                                                Region Count :  <?php echo e($data['region_count']); ?> <br>
                                                             <?php endif; ?>

                                                            <?php if(isset($data['daylight'])): ?>   
                                                                Day Light : <?php echo e(ucfirst($data['daylight'])); ?><br>
                                                                PIR : <?php echo e(ucfirst($data['pir'])); ?> <br>
                                                            <?php endif; ?>

                                                            <?php if(isset($data['battery'])): ?>    
                                                                               
                                                                Battery : <?php echo e($data['battery']); ?>/100 %<br>

                                                            <?php endif; ?>

                                                            <?php if(isset($data['power_sum'])): ?>    
                                                                               
                                                                Power Sum : <?php echo e($data['power_sum']); ?><br>

                                                            <?php endif; ?>

                                                            <?php if(isset($data['voltage'])): ?>    
                                                                               
                                                                Voltage : <?php echo e($data['voltage']); ?><br>

                                                            <?php endif; ?>
                                                   
                                                    </small>
                                                 </div>
                                            </li>

                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                                                          
                                    </ul>
                                </div>
                            </div>
                </div>

      
        </div><!--  End of device Data Row -->

       


                
             
<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>


<?php $__env->stopSection(); ?>
<?php echo $__env->make('_version_02.iot.layouts.admin', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>