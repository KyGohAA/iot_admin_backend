<?php $__env->startSection('content'); ?>
      
            <div class="row clearfix g-3 mb-3">
                <div class="col-md-8 col-sm-12">
                    <div class="card">
                        <div class="header">
                            <h2>Weekly Daylight Report</h2>
                            <ul class="header-dropdown m-r--5">
                                <li class="dropdown"> <a href="javascript:void(0);" class="dropdown-toggle" data-bs-toggle="dropdown"
                                        role="button" aria-haspopup="true" aria-expanded="false"> <i
                                            class="zmdi zmdi-more-vert"></i> </a>
                                    <ul class="dropdown-menu pull-right">
                                        <li><a href="javascript:void(0);">All On</a></li>
                                        <li><a href="javascript:void(0);">All Off</a></li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                        <div class="body">
                            <div id="bar_chart" class="graph"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-sm-12">
                    <div class="card">
                        <div class="header">
                            <h2>Daylight Report</h2>
                            <ul class="header-dropdown m-r--5">
                                <li class="dropdown"> <a href="javascript:void(0);" class="dropdown-toggle" data-bs-toggle="dropdown"
                                        role="button" aria-haspopup="true" aria-expanded="false"> <i
                                            class="zmdi zmdi-more-vert"></i> </a>
                                    <ul class="dropdown-menu pull-right">
                                        <li><a href="javascript:void(0);">All On</a></li>
                                        <li><a href="javascript:void(0);">All Off</a></li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                        <div class="body">
                            <div id="donut_chart" class="graph"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row clearfix g-3 mb-3">
                <?php //dd($graph_info); ?>
            <?php if(count($graph_info) > 0): ?>
                <?php $__currentLoopData = $graph_info; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $info): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if($info['status_code'] == 1): ?>
                            <?php 
                                $graph_info_data = $info['data']['graph_info'];
                                $graph_keys = $info['data']['graph_keys'];

                                //dd($info);
                            ?>
                            <?php $__currentLoopData = $graph_info_data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $g_index => $g_data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>


                                 <?php 
                                    //dd( $g_key);
                                    //dd($info);
                                    //dd($info['data']['data'][$graph_keys[$index]]);

                                    //dd($graph_keys[$index]);

                                    $graph_data = isset($info['data']['data'][$graph_keys[$index]]) ? $info['data']['data'][$graph_keys[$index]] : false;
                                   
                                    $date_range = isset($info['data']['date_range']) ? $info['data']['date_range']: false;


                                    $avg = isset($info['data']['graph_average']) ? $info['data']['graph_average']: false;

                                    $symbol = isset($info['data']['symbols'][$graph_keys[$g_index]]) ? $info['data']['symbols'][$graph_keys[$g_index]]: '-';
                                    

                                    $avg_val = isset($avg[$graph_keys[$g_index]]) ? $avg[$graph_keys[$g_index]] : '-';
                                    if($graph_keys[$g_index] == 'temperature' || $graph_keys[$g_index] == 'humidity' ){
                                        $avg_val = number_format((float)$avg_val, 2, '.', '');
                                    }

                                    //dd($avg);
                                    //dd($avg[$graph_keys[$index]]);
                                 ?>
                                <div class="col-lg-4 col-md-4 col-sm-12">
                                    <div class="card overflowhidden number-chart">
                                        <div class="body pb-0">
                                            <div class="number">
                                              
                                                <h6><?php echo e($g_data['title']); ?></h6>
                                                <span> <?php echo e($avg_val); ?>  <?php echo e($symbol); ?></span>
                                            </div>
                                            <small class="text-muted"> <?php echo e($date_range['start']); ?> to <?php echo e($date_range['end']); ?></small>
                                        </div>
                                        <div id="<?php echo e($g_data['graph_id']); ?>" class="text-center"></div>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>  
            <?php endif; ?>
            </div>

            <div class="row clearfix g-3 mb-3">
               
                <div class="col-lg-6 col-md-6 col-md-12">
                    <div class="card">
                        <div class="body">
                            <div class="clearfix">
                                <div class="float-start">
                                    <h6 class="mb-0">Main Gate - <?php echo e($sensor['door']['name']); ?></h6>
                                </div>
                                <div class="float-end">                   
                                    <button class="btn btn-outline-success" type="button">Status : <?php echo e(ucfirst($sensor['door']['state'])); ?></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row clearfix g-3 mb-3">
                <div class="col-lg-4 col-md-4 col-sm-12">
                    <div class="card appliances-grp ng-star-inserted">
                        <div class="body clearfix">
                            <div class="icon"><img alt="" src="assets/images/air-conditioner.png"></div>
                            <div class="content">
                                <h6>Entrance Detector  <span class="text-success"> <?php echo e($sensor['entrance']['name']); ?></span></h6>
                                <p class="ng-star-inserted">In  :<span style='padding-left:15px;' class="text-warning"><?php echo e($sensor['entrance']['in']); ?></span></p>
                                <p class="ng-star-inserted">Out :<span style='padding-left:15px;' class="text-warning"><?php echo e($sensor['entrance']['out']); ?></span></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-12">
                    <div class="card appliances-grp ng-star-inserted">
                        <div class="body clearfix">
                            <div class="icon"><img alt="" src="assets/images/fridge.png"></div>
                            <div class="content">
                                <h6 _ngcontent-c23="">Room Environment <span class="text-success"> <?php echo e($sensor['environment']['name']); ?> </span></h6>
                                <p class="ng-star-inserted">Temprature <span class="text-primary"> <?php echo e($sensor['environment']['temperature']); ?>  ° C</span></p>
                                <p class="ng-star-inserted">Humidity <span class="text-success"> <?php echo e($sensor['environment']['humidity']); ?> </span></p>
                            </div>
                        </div>
                    </div>
                </div>
                 <div class="col-lg-4 col-md-4 col-sm-12">
                    <div class="card appliances-grp ng-star-inserted">
                        <div class="body clearfix">
                            <div class="icon"><img alt="" src="assets/images/fridge.png"></div>
                            <div class="content">
                                <h6 _ngcontent-c23="">Light Sensor<span class="text-success"> <?php echo e($sensor['environment']['name']); ?> </span></h6>
                                <p class="ng-star-inserted">Day Light <span class="text-primary"> <?php echo e($sensor['environment']['daylight']); ?></span></p>
                                <p class="ng-star-inserted">PIR <span class="text-success"> <?php echo e($sensor['environment']['pir']); ?> </span></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            

            <div class="row clearfix g-3 mb-3">

<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>


<?php $__env->stopSection(); ?>
<?php echo $__env->make('_version_02.iot.layouts.admin', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>