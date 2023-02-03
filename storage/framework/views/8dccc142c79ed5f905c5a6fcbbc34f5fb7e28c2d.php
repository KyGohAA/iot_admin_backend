<?php $__env->startSection('content'); ?>


	<?php if(isset($membership_detail['membership_start_date']) && $membership_detail['membership_start_date'] != ''): ?>
		<div class="row">
			<div class="col-lg-12">
				<div class="card card-profile-feed">
		            <div class="card-header card-header-action">
						<div class="media align-items-center">
							<div class="media-img-wrap d-flex mr-10">
								<div class="avatar avatar-sm">
									<img src="<?php echo e(Auth::user()->profile_jpg()); ?>" alt="user" class="avatar-img rounded-circle">
								</div>
							</div>
							<div class="media-body">
								<div class="text-capitalize font-weight-500 text-dark"><?php echo e(Auth::user()->fullname); ?></div>
								<div class="font-13"><?php echo e($membership_detail['membership_type']); ?></div>
							</div>
						</div>
						<div class="d-flex align-items-center card-action-wrap">
							<!-- <div class="inline-block dropdown">
								<a class="dropdown-toggle no-caret" data-toggle="dropdown" href="#" aria-expanded="false" role="button"><i class="ion ion-ios-settings"></i></a>
								<div class="dropdown-menu dropdown-menu-right">
									<a class="dropdown-item" href="#">Action</a>
									<a class="dropdown-item" href="#">Another action</a>
									<a class="dropdown-item" href="#">Something else here</a>
									<div class="dropdown-divider"></div>
									<a class="dropdown-item" href="#">Separated link</a>
								</div>
							</div> -->
						</div>
					</div>
					<div class="row text-center">
						<div class="col-4 border-right pr-0">
							<div class="pa-15">
								<?php
									$valid_period = App\Setting::get_date_different_in_day(date('Y-m-d', strtotime('now')),$membership_detail['membership_end_date']);
								?>
								<?php if($valid_period > 0): ?>
									<span class="d-block display-6 text-dark mb-5"><?php echo e($valid_period); ?></span>
									<span class="d-block text-capitalize font-14"> <?php echo e(App\Language::trans('Days Valid')); ?></span>		
								<?php else: ?>
									<span class="d-block display-6 text-dark mb-5"><?php echo e(abs($valid_period)); ?></span>
									<span class="d-block text-capitalize font-14"> <?php echo e(App\Language::trans('Days Expired')); ?></span>	
								<?php endif; ?>
											
							</div>
						</div>
						<div class="col-4 border-right px-0">
							<div class="pa-15">
								<span class="d-block display-6 text-dark mb-5"><?php echo e(count($membership_detail['members'])); ?></span>
								<span class="d-block text-capitalize font-14"> <?php echo e(App\Language::trans('Members')); ?></span>
							</div>
						</div>
						<div class="col-4 pl-0">
							<div class="pa-15">
								<span class="d-block display-6 text-dark mb-5"><?php echo e($membership_detail['is_payable_member'] == 'true' ? App\Language::trans('Approve') : App\Language::trans('Pending')); ?></span>
								<span class="d-block text-capitalize font-14">Payment Status</span>
							</div>
						</div>
					</div>
					<ul class="list-group list-group-flush">
						
			                <li class="list-group-item"><span><i class="ion ion-md-calendar font-18 text-light-20 mr-10"></i><span></span></span><span class="ml-5 text-dark"><?php echo e(App\Language::trans('Valid from')); ?> <?php echo e($membership_detail['membership_start_date']); ?> <?php echo e(App\Language::trans('till')); ?> <?php echo e($membership_detail['membership_end_date']); ?></span></li>
			                <li class="list-group-item"><span><i class="ion ion-md-briefcase font-18 text-light-20 mr-10"></i><span></span></span><span class="ml-5 text-dark"><?php echo e($membership_detail['member_detail']['house_member_phonenumber']); ?></span></li>
			                <li class="list-group-item"><span><i class="ion ion-md-home font-18 text-light-20 mr-10"></i><span></span></span><span class="ml-5 text-dark"><?php echo e($membership_detail['member_detail']['house_member_address']); ?></span></li>
			                <li class="list-group-item"><span><i class="ion ion-md-pin font-18 text-light-20 mr-10"></i><span></span></span><span class="ml-5 text-dark"><?php echo e($membership_detail['member_detail']['house_member_email']); ?></span></li>
		            </ul>
				 </div>
			
				<div class="card card-profile-feed">
					<div class="card-header card-header-action">
						<h6><span><?php echo e(App\Language::trans('Members')); ?> <span class="badge badge-soft-primary ml-5"></span></span></h6>
						<a href="#" class="font-14 ml-auto"></a>
					</div>
					<div class="card-body pb-5">
						<div class="hk-row text-center">
							<?php $__currentLoopData = $membership_detail['members']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $member): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
								<div class="col-3 mb-15">
									<div class="w-100">
										<img src=<?php echo e($member["house_member_photo"] == "" ?  asset('img/img-thumb.jpg')  : $member["house_member_photo"]); ?> alt="user" class="avatar avatar-md rounded-circle">
									</div>
									<span class="d-block font-14 text-truncate"><?php echo e($member['house_member_name']); ?></span>
								</div>
							<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
						</div>
					</div>
				</div>
				<?php if($is_allow_to_pay): ?>
				   <a href="<?php echo e(action('ARPaymentReceivedsController@getNewMembership')); ?>" class="btn btn-success btn-block btn-wth-icon mt-15">  
				   		<span class="icon-label"><span class="feather-icon"><i data-feather="credit-card"></i></span></span>
				   		<span class="btn-text"><?php echo e(App\Language::trans('Membership Renewal')); ?></span>
				   	</a>
				   	<br>
				<?php endif; ?>

			</div>
		</div>
	<?php else: ?>
		<?php
			$company = App\Company::get_model_by_leaf_group_id(App\Company::get_group_id());
		?>
		<div class="row">
			<div class="col-lg-12">
				<div class="card card-profile-feed">
		            <div class="card-header card-header-action">
						<div class="media align-items-center">
							<div class="media-img-wrap d-flex mr-10">
								<div class="avatar avatar-sm">
									<img src="<?php echo e(Auth::user()->profile_jpg()); ?>" alt="user" class="avatar-img rounded-circle">
								</div>
							</div>
							<div class="media-body">
								<div class="text-capitalize font-weight-500 text-dark"><?php echo e(Auth::user()->fullname); ?></div>
								<div class="font-13"><?php echo e($membership_detail['membership_type']); ?></div>
							</div>
						</div>
						<div class="d-flex align-items-center card-action-wrap">
							<!-- <div class="inline-block dropdown">
								<a class="dropdown-toggle no-caret" data-toggle="dropdown" href="#" aria-expanded="false" role="button"><i class="ion ion-ios-settings"></i></a>
								<div class="dropdown-menu dropdown-menu-right">
									<a class="dropdown-item" href="#">Action</a>
									<a class="dropdown-item" href="#">Another action</a>
									<a class="dropdown-item" href="#">Something else here</a>
									<div class="dropdown-divider"></div>
									<a class="dropdown-item" href="#">Separated link</a>
								</div>
							</div> -->
						</div>
					</div>
				
					
				 </div>
			
				<div class="card card-profile-feed">
					<div class="card-header card-header-action">
						<h6><span><?php echo e(App\Language::trans('Group Information')); ?> <span class="badge badge-soft-primary ml-5"></span></span></h6>
						<a href="#" class="font-14 ml-auto"></a>
					</div>
					<div class="card-body pb-5">
						<div class="hk-row text-center">
							<!-- Apply membership to enjoy the facility in our club house. -->
						</div>
						<ul class="list-group list-group-flush">
			                <li class="list-group-item"><span><i class="ion ion-md-home font-18 text-light-20 mr-10"></i><span></span></span><span class="ml-5 text-dark"><?php echo e($company['name']); ?></span></li>
			                <li class="list-group-item"><span><i class="ion ion-md-pin font-18 text-light-20 mr-10"></i><span></span></span><span class="ml-5 text-dark"><?php echo e($company->get_address()); ?></span></li>
		         	   </ul>
					</div>
				</div>

				<?php if(App\Company::is_allow_to_access_module(App\Setting::LABEL_MODULE_ACCOUNTING)): ?>
				   <a href="<?php echo e(action('ARPaymentReceivedsController@getNewMembership')); ?>" class="btn btn-success btn-block btn-wth-icon mt-15">  
				   		<span class="icon-label"><span class="feather-icon"><i data-feather="credit-card"></i></span></span>
				   		<span class="btn-text"><?php echo e(App\Language::trans('Apply Membership')); ?></span>
				   	</a>
				<?php endif; ?>
				   	<br>
			

			</div>
		</div>
	<?php endif; ?>




<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
$.get("<?php echo e(action('DashboardsController@getDashboardCount')); ?>", function(data){
	$(".outstanding_count").html(data.outstanding_count);
	$(".min_credit_count").html(data.min_credit_count);
},"json");

<?php $__env->stopSection(); ?>
<?php echo $__env->make('_version_02.commons.layouts.admin', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>