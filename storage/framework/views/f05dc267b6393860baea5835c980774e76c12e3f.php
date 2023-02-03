<?php $__env->startSection('content'); ?>
<?php echo Form::model($model, ['class'=>'form-horizontal',"files"=>true]); ?>


      <!-- Row -->
                <div class="row">
                    <div class="col-xl-12 pa-0">
                       
                        <div class="faq-content container-fluid">
                            <div class="hk-row">
                               
                                <div class="col-xl-8">
                                    <div class="card card-lg">
                                        <h5 class="card-header border-bottom-0">
                                            History
                                        </h5>
                                        <div class="accordion accordion-type-2 accordion-flush" id="accordion_2">
                                            
                                            <?php
                                                  $state = ' activestate';
                                                  $is_show = ' show';
                                            ?>

                                            <?php $__currentLoopData = $transaction_listing; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $transaction_item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                               
                                                <?php
                                                //dd($transaction_item);
                                                  $accordion_id =  strtolower( str_replace( ' ' , '_',$transaction_item['title']));
                                                ?>

                                                <div class="card">
                                                    <div class="card-header d-flex justify-content-between">
                                                        <a class="collapsed" role="button" data-toggle="collapse" href="#<?php echo e($accordion_id); ?>" aria-expanded="false"> <?php echo e($transaction_item['title']); ?></a>
                                                    </div>
                                                    <div id="<?php echo e($accordion_id); ?>" class="collapse" data-parent="#accordion_2">
                                                     
                                                           <div class="card-body pa-15">
                                                               <div class="row">
                                                                    <div class="col-sm">
                                                                        <div class="table-wrap" style="overflow-x:auto;">
                                                                          <?php if(isset($transaction_item['data'])): ?>
                                                                            <table id="datable_2" name="<?php echo e($transaction_item['key']); ?>" class="table table-hover w-100 display">
                                                                                <thead>
                                                                                    <tr>
                                                                                    <?php if(isset($transaction_item['table_mappers'])): ?>
                                                                                      <?php $__currentLoopData = $transaction_item['table_mappers']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $header => $mapper): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                                        <th><?php echo e($header); ?></th>
                                                                                      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?> 
                                                                                    <?php endif; ?>                                                  
                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                     
                                                                                     <?php if(isset($transaction_item['data'])): ?>
                                                                                         <?php $counter =0 ; ?>
                                                                                        <?php if(count($transaction_item['data']) > 0): ?>
                                                                                               <?php $__currentLoopData = $transaction_item['data']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                                                                                                  <tr>
                                                                                                      
                                                                                                    <?php $__currentLoopData = $transaction_item['table_mappers']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $column_key => $model_key): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                                                            <?php if($model_key == 'amount' || $model_key == 'total_payable_amount'  || $model_key == 'total_amount' ): ?>
                                                                                                              <td><?php echo e(number_format($row[$model_key],2,'.','')); ?></td>  
                                                                                                            <?php elseif($column_key == 'Month'): ?>
                                                                                                               <td><?php echo e(date('Y-m',strtotime($row[$model_key]))); ?></td>
                                                                                                            <?php elseif($column_key == 'Description'): ?>
                                                                                                               <td><?php echo e(App\Language::trans('Usage Summary - ')); ?> <?php echo e(App\Setting::get_month_in_word(date('m',strtotime($row[$model_key])))." ".date('Y',strtotime($row[$model_key]))); ?></td>
                                                                                                            <?php else: ?>
                                                                                                              <td> <?php echo e($row[$model_key]); ?> </td>
                                                                                                            <?php endif; ?>                      
                                                                                                      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                                                  </tr>

                                                                                               <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                                        <?php else: ?>
                                                                                         <tr>
                                                                                          <td colspan="<?php echo e(count($transaction_item['table_mappers'])); ?>" align="center"> No data found.</td>
                                                                                         </tr>
                                                                                        <?php endif; ?>
                                                                                     <?php endif; ?>

                                                                                   
                                                                                </tbody>
                                                                            </table>
                                                                            <?php endif; ?> 
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                          </div>
                                                    </div>
                                                </div>

                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                          
                                        
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /Row -->

<?php echo Form::close(); ?>

<br><br><br>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>


$( document ).ready(function() {

 
  <?php 
  //foreach($account_data->getAttributes() as $key => $value)
    foreach($account_data as $key => $value)
    {

      if($key == 'date_range')
      {
        foreach ($value as $date_range_key => $date_range_value)
        {
          $$date_range_key = $date_range_value;
        }

      }else{
        $$key = $value;
      }
      
    }
  ?>

  $.get(getUserMonthlyUsageUrl, { 'leaf_id_user' : <?php echo e($leaf_id_user); ?>, 'date_started' : '<?php echo e($date_started); ?>' , 'date_ended' : '<?php echo e($date_ended); ?>'  , 'leaf_room_id' : '<?php echo e($leaf_room_id); ?>' }, function(data) {
  console.log(data);
     if(data['status_code'] == true){
         console.log(data.data.month_usage_summary);


          var tbody = $('table[name ="monthly_usage_listing"]') .find("tbody");
          tbody.find("tr").remove();
          data = data.data.month_usage_summary;
          for(var i=0 ; i < data.length ; i ++)
          {
              index = i+1;
              var tr = "<tr>";
                <!-- tr += "<td class='text-center col-md-1'>"+index+"</td>"; -->
                tr += "<td class='text-left col-md-4'>"+data[i].date+"</td>";
                tr += "<td class='text-center col-md-4'>"+data[i].date+"</td>";
                tr += "<td class='text-center col-md-4'>"+data[i].total_usage_kwh+"</td>";
                tr += "<td class='text-center col-md-4'>"+data[i].total_payable_amount+"</td>";
                  tr += "<td class='text-center col-md-1'>"
                  if (data[i].response == 'success') 
                    { tr += ''; } else { tr += ''; }
                  tr += "</td>";
                tr += "</tr>";
              tbody.append(tr);
          }



     }
       
  });

});

<?php $__env->stopSection(); ?>

<?php echo $__env->make('_version_02.utility_charges.mobile_apps_light.layouts.main', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>