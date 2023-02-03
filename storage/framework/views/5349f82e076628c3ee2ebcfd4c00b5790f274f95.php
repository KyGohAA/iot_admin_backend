<?php $__env->startSection('content'); ?>
<?php echo $__env->make('_version_02.commons.layouts.partials._alert', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

<?php echo Form::model($model, ['class'=>'form-horizontal','method'=>'get']); ?>

<section class="hk-sec-wrapper">
    <h5 class="hk-sec-title"><?php echo e(App\Language::trans('Filter By')); ?></h5><hr>
    	
    	<div class="row">
			<div class="col-md-6">
				<div class="form-group<?php echo e($errors->has('month_started') ? ' has-error' : ''); ?>">
					<?php echo Form::label('month_started', App\Language::trans('From Month'), ['class'=>'control-label col-md-4']); ?>

					<div class="col-md-8">
						<?php echo Form::select('month_started', App\Setting::month_year_combobox(6), null, ['class'=>'form-control','autofocus']); ?>

                        <?php echo $errors->first('month_started', '<label for="month_started" class="help-block error">:message</label>'); ?>

					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group<?php echo e($errors->has('month_ended') ? ' has-error' : ''); ?>">
					<?php echo Form::label('month_ended', App\Language::trans('To Month'), ['class'=>'control-label col-md-4']); ?>

					<div class="col-md-8">
						<?php echo Form::select('month_ended', App\Setting::month_year_combobox(6), null, ['class'=>'form-control']); ?>

                        <?php echo $errors->first('month_ended', '<label for="month_ended" class="help-block error">:message</label>'); ?>

					</div>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-md-12">
				<div class="form-group<?php echo e($errors->has('id_house') ? ' has-error' : ''); ?>">
					<?php echo Form::label('id_house', App\Language::trans('Unit No.'), ['class'=>'control-label col-md-4']); ?>

					<div class="col-md-12">
						<?php echo Form::select('id_house', App\PowerMeterModel\MeterRegister::houses_combobox(), null, ['class'=>'form-control','required','onchange'=>'init_room_combobox(this)']); ?>

                        <?php echo $errors->first('id_house', '<label for="id_house" class="help-block error">:message</label>'); ?>

					</div>
				</div>
			</div>
		</div>

		
		<div class="row">
			<div class="col-md-6">
				<div class="form-group<?php echo e($errors->has('export_by') ? ' has-error' : ''); ?>">
					<?php echo Form::label('export_by', App\Language::trans('Exported By'), ['class'=>'control-label col-md-4']); ?>

					<div class="col-md-12">
						 <div class="row">	
						 	
							<div class="col-md-3">
							    <div class="custom-control custom-radio">
							        <input type="radio" id="export_by_html" name="export_by" value="html"  class="custom-control-input">
							        <label class="custom-control-label" for="export_by_html"><?php echo e(App\Language::trans('HTML')); ?></label>
							    </div>
							</div>

							<div class="col-md-3">
							    <div class="custom-control custom-radio">
							        <input type="radio" id="export_by_pdf" name="export_by" value="pdf" checked class="custom-control-input">
							        <label class="custom-control-label" for="export_by_pdf"><?php echo e(App\Language::trans('PDF')); ?></label>
							    </div>
							</div>
							
							<div class="col-md-3">
							    <div class="custom-control custom-radio">
							        <input type="radio" id="export_by_excel" name="export_by" value="excel"  class="custom-control-input">
							        <label class="custom-control-label" for="export_by_excel"><?php echo e(App\Language::trans('Excel')); ?></label>
							    </div>
							</div>

						 </div>
						 <?php echo $errors->first('export_by', '<label for="export_by" class="help-block error">:message</label>'); ?>

					</div>
				</div>
			</div>
		</div>
</section>

<div id='footer'  class="box-footer">
	<div class="row">
		<div class="col-md-offset-4 col-md-10">
		<a href="<?php echo e(action($page_variables['return_url'])); ?>" class="btn btn-danger btn-wth-icon icon-wthot-bg btn-lg pull-right"><span class="icon-label"><i class="fa fa-close"></i> </span><span class="btn-text"><?php echo e(App\Language::trans('Close')); ?></span></a>
		<!--  loading-label -->
			<button type="submit" class="btn btn-primary btn-wth-icon icon-wthot-bg btn-lg pull-right"><i class="fa fa-search fa-fw"></i><?php echo e(App\Language::trans('Search')); ?></button>			
		</div>
	</div>
</div>


<?php echo Form::close(); ?>

<?php if(isset($report_data['house'])): ?>
		
<?php
	//dd($report_data);
	$renting_head_count = array();
	$house_other_information = strlen($report_data['house']['house_other_information']) > 0 ? json_decode($report_data['house']['house_other_information'],true) : array();
	//dd($house_other_information); 
	$total_year = 6 ;
	$total_months = $total_year * 12;
    $start = 0 - $total_months/3;
    $end = $total_months/3;
    for ($i=$start; $i<=$end; $i++) { 
    	//dd($i);
        $action = $i < 0 ? '- '.abs($i) : '+ '.$i;
        $string = date('m-Y', strtotime($action.' month'));
        $month_years[(string) $string] = (string) $string;
    }
    //dd($month_year);
    foreach($report_data['rooms'] as $room)
    {
    	$temp_house_room_members  = strlen($room['house_room_members']) > 3 ? json_decode($room['house_room_members'], true): array();  
    	if(count($temp_house_room_members ) == 0)
    	{
    		continue;
    	}
    	foreach ($temp_house_room_members  as $index => $row)	
		{
				if(!isset($row['user_id']))
				{
					unset($temp_house_room_members [$index]);
				}
		}
		//dd($temp_house_room_member);
		foreach ($temp_house_room_members as $index => $member)
		{//dd($member);
			foreach ($month_years as $month_year)
			{//dd($member['check_in_date'].' = '.$month_year);
				if(!isset($member['check_in_date']))
				{
					continue;
				}

				if($member['check_in_date'] == '')
				{
					continue;
				}
				if(date('Y-m' ,strtotime($member['check_in_date'])) >= date('Y-m' ,strtotime($month_year)))
	            {

	            	$renting_head_count[$month_year]['air_cond'] = isset($renting_head_count[$month_year]['air_cond']) ? $renting_head_count[$month_year]['air_cond'] : 0;
	            	$renting_head_count[$month_year]['wifi'] = isset($renting_head_count[$month_year]['wifi']) ? $renting_head_count[$month_year]['wifi'] : 0;
	            	$renting_head_count[$month_year]['utility'] = isset($renting_head_count[$month_year]['utility']) ? $renting_head_count[$month_year]['utility'] : 0;

	            	if(isset($member['others']))
					{
						if($member['others'] == true)
						{
							$renting_head_count[$month_year]['utility'] ++;
						}
					}else{
						$renting_head_count[$month_year]['utility'] ++;
					}

					if(isset($member['air_cond'])){
							if($member['air_cond'] == 1)
					        {
					        	
					        	$renting_head_count[$month_year]['air_cond'] ++;
					        }
					}
						                
	                
					if(isset($member['wifi'])){
						if($member['wifi'] == 1)
					    {
					    	
					    	$renting_head_count[$month_year]['wifi'] ++;
					    }	
					}
	                
	                

	                
	                

	            }
	            //dd($renting_head_count);
			}
			

			
		}//end generate head count
		//dd($renting_head_count);
    }


	$house_fee_items = json_decode($report_data['house']['house_fee_items'],true);
	//=dd($house_fee_items); 

	//Calculate fee rule
	$utilityData_my = array();
	$utilityData = $report_data['utilityData'];
	foreach($utilityData as $utility_data)
	{
		//dd($utility_data);
		$utilityData_my[$utility_data['month_year']][$utility_data['bill_type']] = isset($utilityData_my[$utility_data['month_year']][$utility_data['bill_type']]) ? $utilityData_my[$utility_data['month_year']][$utility_data['bill_type']] : array();
		array_push( $utilityData_my[$utility_data['month_year']][$utility_data['bill_type']] , $utility_data);
	}
	$average_datas = array();
	foreach($utilityData_my as $month_year => $utility_datas)
	{
		
		$head_count = isset($renting_head_count[$month_year]['utility']) ? $renting_head_count[$month_year]['utility'] : false;
		
		
		if($head_count == false)
		{
			dd( $month_year);
			continue;
		}
		
		foreach ($utility_datas as $fee_type => $utility_data_model)
		{
			if($fee_type == 'wifi')
			{
				$head_count = isset($renting_head_count[$month_year]['wifi']) ? $renting_head_count[$month_year]['wifi'] : false;
			
			}else{

				$head_count = isset($renting_head_count[$month_year]['utility']) ? $renting_head_count[$month_year]['utility'] : false;
			}

			//dd($utility_data_model);

			$utility_data_model = isset($utility_data_model[0]) ? $utility_data_model[0] : false;
			if(!isset($utility_data_model['amount']))
			{
				//dd($utility_data_model);
			}

			if($house_other_information['is_aircond_unit'] == true && $fee_type == 'electric')
			{
				$aircond_head_count = isset($renting_head_count[$month_year]['air_cond']) ? $renting_head_count[$month_year]['air_cond'] : 0;

				if($aircond_head_count == 0)
				{
					$average_fee = $utility_data_model['amount']/$head_count;
					$average_datas[$month_year][$fee_type] = isset($average_data[$month_year][$fee_type]) ? $average_data[$month_year][$fee_type] : array();
					$average_datas[$month_year][$fee_type] = ['total'=> $utility_data_model['amount'] , 'head_count' => $head_count, 'amount' => $average_fee];
					
				}else{

					$temp_average = (($utility_data_model['amount'] - ($head_count * $house_other_information['fix_aircond_electric_fee_charges'])))/$aircond_head_count ;
					$average_datas[$month_year][$fee_type] = isset($average_data[$month_year][$fee_type]) ? $average_data[$month_year][$fee_type] : array();
					$average_datas[$month_year][$fee_type] = ['total'=> $utility_data_model['amount'] , 'head_count' => $head_count, 'amount' => $house_other_information['fix_aircond_electric_fee_charges']];

					$average_datas[$month_year][$fee_type.'_air_cond'] = isset($average_data[$month_year][$fee_type]) ? $average_data[$month_year][$fee_type] : array();
					$average_datas[$month_year][$fee_type.'_air_cond'] = ['total'=> $utility_data_model['amount'] , 'head_count' => $head_count, 'amount' => $temp_average];

				}


			}else{
				$average_fee = $utility_data_model['amount']/$head_count;
				$average_datas[$month_year][$fee_type] = isset($average_data[$month_year][$fee_type]) ? $average_data[$month_year][$fee_type] : array();
				$average_datas[$month_year][$fee_type] = ['total'=> $utility_data_model['amount'] , 'head_count' => $head_count, 'amount' => $average_fee];
			}
			
		}
	}
	ksort($average_datas);
	//dd($average_datas);

?>
		
	

		<section class="hk-sec-wrapper">
   		 <h5 class="hk-sec-title" style="margin-bottom:0px;"><?php echo e(App\Language::trans('Recipient List')); ?></h5>
			<div class="table-responsive">
				<table class="table table-bordered table-hover">
						<thead>
							<tr  style="background-color: #ddd;">
								<th class="text-center">Month</th>
								<?php $__currentLoopData = $house_fee_items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $fee_item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
										<th class="text-center"><?php echo e(App\Language::trans(ucfirst(str_replace('_', ' ',	$fee_item)))); ?></th>
								<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>	
								<th class="text-center"><?php echo e(App\Language::trans('Subtotal')); ?></th>
							</tr>
						</thead>

						<?php 
							$fee_subtotal = array();
							$utilityData_my = App\Setting::sortByDateColumn($utilityData_my)
					
						?>
						<?php $__currentLoopData = $utilityData_my; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $month_year => $u_model): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
							<tr>
								<td class="text-right"><?php echo e($month_year); ?></td>
								<?php $__currentLoopData = $house_fee_items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $fee_item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
									<?php 
										$fee = isset($u_model[$fee_item][0]) ? $u_model[$fee_item][0]['amount'] : 0 ;
										$fee_subtotal[$month_year]['subtotal'] = isset($fee_subtotal[$month_year]['subtotal']) ? $fee_subtotal[$month_year]['subtotal'] + $fee : $fee;
										$fee_subtotal[$fee_item] = isset($fee_subtotal[$fee_item]) ? $fee_subtotal[$fee_item] + $fee : $fee;
									?>

									<td class="text-right"><?php echo e($fee); ?></td>
								<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
								<td class="text-right"><?php echo e($fee_subtotal[$month_year]['subtotal']); ?></td>
							</tr>
							
							
						<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
						<?php 
							//dd($fee_subtotal);
						?>


				</table>
			</div>

			<div class="table-responsive">
				<table class="table table-bordered table-hover">
					<!-- <thead>
						<tr  style="background-color: #ddd;">
							<th class="text-center">#</th>
							<th class="text-center"><?php echo e(App\Language::trans('Room Name')); ?></th>
							<th class="text-center"><?php echo e(App\Language::trans('Name')); ?></th>		
							<th class="text-center"><?php echo e(App\Language::trans('Check In Date')); ?></th>	
							<th class="text-center" colspan="2"><?php echo e(App\Language::trans('Check Out Date')); ?></th>
						</tr>
					</thead> -->
					<tbody>

				<?php $__currentLoopData = $report_data['rooms']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $room): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>					
							
								<tr  style="background-color: #ddd;">
									<th class="text-left" colspan="5"> <?php echo e(App\Language::trans('Room : ')); ?> <?php echo e($room['house_room_name']); ?></th>
								</tr>

								<tr  style="background-color: #ddd;">
									<th class="text-center">#</th>
									<!-- <th class="text-center"><?php echo e(App\Language::trans('Room Name')); ?></th> -->
									<th class="text-center"><?php echo e(App\Language::trans('Name')); ?></th>		
									<th class="text-center"><?php echo e(App\Language::trans('Phone No')); ?></th>	
									<th class="text-center" colspan=2><?php echo e(App\Language::trans('Check Out Date')); ?></th>
								</tr>
						<?php 
						///dd($report_data['members']);
								///dd($room); 
								$listing_index = 1 ;
								$room_members = json_decode($room['house_room_members'] , true);
								$is_aircond = false;
								$is_wifi = false;
								$is_others = true;

								foreach($room_members as $r_member)
								{
									$is_aircond = isset($r_member['air_cond']) ? $r_member['air_cond'] : false;
									$is_wifi = isset($r_member['wifi']) ? $r_member['wifi'] : false;

									if($is_others  == true)
									{
										$is_others = isset($r_member['is_others']) ? $r_member['is_others'] : true;
									}
									
									if(isset($r_member['is_others']))
									{
										//dd($is_others);
										//dd($room);
									}
								}

						
								
						?>
						<?php $__currentLoopData = $room_members; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $member_info): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>	
				
							<?php 
								if(!isset($member_info['user_id']))
								{
									continue;
								}
								$member = isset($report_data['members'][$member_info['user_id']]) ? $report_data['members'][$member_info['user_id']]: false;
								if($member == false)
								{
									continue;
								}
								//dd($room);
								//$is_aircond = 
							?>		

					            <tr>
					            	<td class="text-center"><?php echo e($listing_index); ?></td>
									<!-- <td class="text-center"><?php echo e($member['room_name']); ?></td> -->
									<td class="text-center"><?php echo e($member['fullname']); ?></td>
									<td class="text-center"><?php echo e($member['email']); ?></td>
									<td class="text-center"><?php echo e($member['phone_number']); ?></td>
									<td class="text-center"></td>
									
								</tr>
					<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
								<?php 
									$fee_index = 1;
									$listing_index ++;
									$grand_total =  0;
								?>
								<!-- <tr>
									<th class="text-center">#</th>
									<th class="text-center"><?php echo e(App\Language::trans('Document No')); ?></th>
									<th class="text-center"><?php echo e(App\Language::trans('Document Date')); ?></th>
									<th class="text-center"><?php echo e(App\Language::trans('Fee Type')); ?></th>
									<th class="text-center"  colspan="1"><?php echo e(App\Language::trans('Amount')); ?></th>
								</tr> -->

							
								<?php if(count($average_datas) > 0): ?>
					
										<?php $subtotal_datas = array(); ?>
										<!-- //first row month year -->
										<tr style="background-color: #ddd;">
											<td class="text-center"><?php echo e(App\Language::trans('Month')); ?></td>	
											<?php $__currentLoopData = $average_datas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $month_year => $average_data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>									
									            	<td class="text-center"><?php echo e($month_year); ?></td>								         
											<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
										 </tr>

										 <?php $__currentLoopData = $house_fee_items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $house_fee_item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
										 	<?php
										 		if($house_fee_item == 'wifi' && $is_wifi == false){
										 			continue;
										 		}

										 		$other_utilities = ['electric','indah_water', 'water_fee'];
										 		if(in_array($house_fee_item , $other_utilities) == true && $is_others == 0){
										 			continue;
										 		}

										 		
										 	?>

										 	<tr>
												<td class="text-center" style="background-color: #ddd;"><?php echo e(App\Language::trans(ucwords(str_replace('_', ' ', $house_fee_item)))); ?></td>	
												<?php $__currentLoopData = $average_datas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $month_year => $average_data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>		
													<?php 
														$fee_data = isset($average_data[$house_fee_item]) ? $average_data[$house_fee_item] : '-';
													?>							
										            	<td class="text-center"><?php echo e(isset($fee_data['amount']) ? number_format($fee_data['amount'],2) : $fee_data); ?></td>				

										            	 <?php
														 		$subtotal_datas[$month_year] = isset($subtotal_datas[$month_year]) ? $subtotal_datas[$month_year] :0 ;
														 		$subtotal_datas[$month_year] =  $subtotal_datas[$month_year]  + (isset($fee_data['amount']) ? $fee_data['amount'] : 0) ;
							
														 ?>

												<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
											 </tr>		

											 <?php if($house_fee_item == 'electric' && $is_aircond == true): ?>
											 	<?php
											 		$house_fee_item = 'electric_air_cond';
											 		//dd($member );
											 	?>
											 	<tr>
													<td class="text-center" style="background-color: #ddd;"><?php echo e(App\Language::trans(ucwords(str_replace('_', ' ', $house_fee_item)))); ?></td>	
													<?php $__currentLoopData = $average_datas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $month_year => $average_data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>		
														<?php 
															$fee_data = isset($average_data[$house_fee_item]) ? $average_data[$house_fee_item] : '-';
														?>							
											            	<td class="text-center"><?php echo e(isset($fee_data['amount']) ? number_format($fee_data['amount'],2) : $fee_data); ?></td>				

											            	 <?php
															 		$subtotal_datas[$month_year] = isset($subtotal_datas[$month_year]) ? $subtotal_datas[$month_year] :0 ;
															 		$subtotal_datas[$month_year] =  $subtotal_datas[$month_year]  + (isset($fee_data['amount']) ? $fee_data['amount'] : 0) ;
								
															 ?>

													<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
												 </tr>	

											 <?php endif; ?>

											
										 <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
									

										 <tr>
											<td class="text-center" style="background-color: #ddd;"><?php echo e(App\Language::trans('Sub-Total')); ?></td>	
											<?php $__currentLoopData = $subtotal_datas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $month_year => $amount): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>									
									            	<td class="text-center"><?php echo e(number_format($amount,2)); ?></td>								         
											<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
										 </tr>

												
								<?php else: ?>
									<tr>
										<td colspan="5" class="text-center">  No record found. </td>
									</tr>

								<?php endif; ?>
								<tr> <td colspan="5"></td> </tr>

			<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?> 
			<!-- //end of master foreach -->


					            


					</tbody>
					<tfoot>
						<hr>
						<br>
						<tr>
							<td class="text-right" colspan="4"><?php echo e(App\Language::trans('Total')); ?>:</td>
							<td class="text-right"><?php echo e(number_format($grand_total, 2)); ?></td>
						</tr>
					</tfoot>
				</table>
			</div>
	</section>


	<?php else: ?>
	<?php endif; ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('_version_02.commons.layouts.admin', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>