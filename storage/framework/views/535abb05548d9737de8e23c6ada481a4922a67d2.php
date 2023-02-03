<?php $__env->startSection('content'); ?>
<?php echo Form::model($model, ['class'=>'form-horizontal',"files"=>true]); ?>

<?php echo $__env->make('_version_02.commons.layouts.partials._alert', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

<?php
	$backend_data_model  = $model->backend_data ;
?>

 <!-- ION CSS -->
 <link rel="stylesheet" href="<?php echo e(asset('version_2/vendors/ion-rangeslider/css/ion.rangeSlider.css')); ?>">
 <link rel="stylesheet" href="<?php echo e(asset('version_2/vendors/ion-rangeslider/css/ion.rangeSlider.skinHTML5.css')); ?>">

 <!-- ION CSS -->

<section class="hk-sec-wrapper">
<div class="box">

	 <!-- Nav tabs -->
	<ul class="nav nav-light nav-tabs bo" role="tablist">
	
		<li role="presentation" class="nav-item active">
			<a href="#general_setting" aria-controls="general_setting" class="d-flex align-items-center nav-link active" role="tab" data-toggle="tab"><h5><?php echo e(App\Language::trans('General Setting')); ?></h5></a>
		</li>

		<?php if(App\Company::is_allow_to_access_module(App\Setting::LABEL_MODULE_POWER_MANAGEMENT)): ?>
			<li role="presentation" class="nav-item">
				<a href="#power_management" aria-controls="power_management" class="d-flex align-items-center nav-link" role="tab" data-toggle="tab"><h5><?php echo e(App\Language::trans('Power Management')); ?></h5></a>
			</li>
		<?php endif; ?>

		<?php if(App\Company::is_allow_to_access_module(App\Setting::LABEL_MODULE_ACCOUNTING)): ?>
			<li role="presentation" class="nav-item">
				<a href="#club_house" aria-controls="club_house" class="d-flex align-items-center nav-link" role="tab" data-toggle="tab"><h5><?php echo e(App\Language::trans('Club House')); ?></h5></a>
			</li>
		<?php endif; ?>

		<?php if(App\Company::is_allow_to_access_module(App\Setting::LABEL_MODULE_ACCOUNTING)): ?>
			<li role="presentation" class="nav-item">
				<a href="#leaf_accounting" aria-controls="leaf_accounting" class="d-flex align-items-center nav-link" role="tab" data-toggle="tab"><h5><?php echo e(App\Language::trans('Leaf Accounting')); ?></h5></a>
			</li>
		<?php endif; ?>
	</ul>
	<hr>
		
	<!-- Tab panes -->
	<div class="tab-content">	
	    <div role="tabpanel" class="tab-pane active" id="general_setting">
	    	
	    	 <h6 class="hk-sec-title"><?php echo e(App\Language::trans('Company Information')); ?></h6><hr>
	    	 <div class="row">
			    <div class="col-sm">
			        <div class="form-row">
			            <div class="col-md-12 mb-15">
			               <?php if($model->logo_photo_path): ?>
			               <div class="col-md-4">
			                  <img class="img-fluid img-thumbnail img-responsive" width="150" height ="150" src="<?php echo e(asset($model->logo_photo_path)); ?>">
			                  <!--    <div class="text-center checkbox-custom checkbox-danger mb5">
			                     <?php echo Form::checkbox("company_logo_del", $model->id_company, false, array("id"=>"company_logo_del")); ?>

			                     <label for="company_logo_del"><?php echo e(App\Language::trans('Remove file')); ?></label>
			                     </div> -->
			               </div>
			               <?php endif; ?>
			            </div>
			        </div>
			    </div>
			</div>	

	   		<div class="row mb-15">
					<div class="col-md-6">
					 	<div class="form-group <?php echo $errors->first('logo_photo_path') ? 'has-error' : ''; ?>">
		                  <label for="logo_photo_path" class="control-label col-md-12"><?php echo e(App\Language::trans('Company Logo')); ?></label>
		                  <div class="col-md-8">
		                     <?php echo Form::file("logo_photo_path", array("id"=>"logo_photo_path","class"=>"form-control")); ?>

		                     <?php echo $errors->first('logo_photo_path', '<span for="logo_photo_path" class="help-block error">:message</span>'); ?>

		                  </div>
		               </div>
					</div>

					<div class="col-md-6">
						<div class="form-group<?php echo e($errors->has('name') ? ' has-error' : ''); ?>">
							<?php echo Form::label('name', App\Language::trans('Name'), ['class'=>'control-label col-md-12']); ?>

							<div class="col-md-8">
								<?php echo Form::text('name', null, ['class'=>'form-control','autofocus','required']); ?>

		                        <?php echo $errors->first('name', '<label for="name" class="help-block error">:message</label>'); ?>

							</div>
						</div>
					</div>
			</div> 

		
			<h6 class="hk-sec-title"><?php echo e(App\Language::trans('System Information')); ?></h6><hr>


	    	 <div class="row">
			    <div class="col-sm">
			        <div class="form-row">
			            <div class="col-md-12 mb-15">
			               <?php if($model->favicon_photo_path): ?>
			               <div class="col-md-4">
			                  <img class="img-fluid img-thumbnail img-responsive" width="150" height ="150" src="<?php echo e(asset($model->favicon_photo_path)); ?>">
			                  <!--    <div class="text-center checkbox-custom checkbox-danger mb5">
			                     <?php echo Form::checkbox("company_logo_del", $model->id_company, false, array("id"=>"company_logo_del")); ?>

			                     <label for="company_logo_del"><?php echo e(App\Language::trans('Remove file')); ?></label>
			                     </div> -->
			               </div>
			               <?php endif; ?>
			            </div>
			        </div>
			    </div>
			</div>	

			<div class="row mb-15">
					<div class="col-md-6">
					 	<div class="form-group <?php echo $errors->first('favicon_photo_path') ? 'has-error' : ''; ?>">
		                  <label for="favicon_photo_path" class="control-label col-md-12"><?php echo e(App\Language::trans('System Favicon')); ?></label>
		                  <div class="col-md-8">
		                     <?php echo Form::file("favicon_photo_path", array("id"=>"favicon_photo_path","class"=>"form-control")); ?>

		                     <?php echo $errors->first('favicon_photo_path', '<span for="favicon_photo_path" class="help-block error">:message</span>'); ?>

		                  </div>
		               </div>
					</div>

					<div class="col-md-6">
						<div class="form-group<?php echo e($errors->has('system_name') ? ' has-error' : ''); ?>">
							<?php echo Form::label('system_name', App\Language::trans('System Name'), ['class'=>'control-label col-md-12']); ?>

							<div class="col-md-8">
								<?php echo Form::text('system_name', null, ['class'=>'form-control','required']); ?>

		                        <?php echo $errors->first('system_name', '<label for="system_name" class="help-block error">:message</label>'); ?>

							</div>
						</div>
					</div>
			</div> 

			<h6 class="hk-sec-title"><?php echo e(App\Language::trans('Address Information')); ?></h6><hr>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group<?php echo e($errors->has('address') ? ' has-error' : ''); ?>">
							<?php echo Form::label('address', App\Language::trans('Address'), ['class'=>'control-label col-md-12']); ?>

							<div class="col-md-8">
								<?php echo Form::text('address', null, ['class'=>'form-control']); ?>

		                        <?php echo $errors->first('address', '<label for="address" class="help-block error">:message</label>'); ?>

							</div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group<?php echo e($errors->has('postcode') ? ' has-error' : ''); ?>">
							<?php echo Form::label('postcode', App\Language::trans('Postcode'), ['class'=>'control-label col-md-12']); ?>

							<div class="col-md-8">
								<?php echo Form::text('postcode', null, ['class'=>'form-control']); ?>

		                        <?php echo $errors->first('postcode', '<label for="postcode" class="help-block error">:message</label>'); ?>

							</div>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-md-6">
						<div class="form-group<?php echo e($errors->has('country_id') ? ' has-error' : ''); ?>">
							<?php echo Form::label('country_id', App\Language::trans('Country'), ['class'=>'control-label col-md-12']); ?>

							<div class="col-md-8">
								<?php echo Form::select('country_id', App\Country::combobox(), null, ['class'=>'form-control','onchange'=>'init_state_selectbox(this)']); ?>

		                        <?php echo $errors->first('country_id', '<label for="country_id" class="help-block error">:message</label>'); ?>

							</div>
						</div>
					</div>

					<div class="col-md-6">
						<div class="form-group<?php echo e($errors->has('state_id') ? ' has-error' : ''); ?>">
							<?php echo Form::label('state_id', App\Language::trans('State'), ['class'=>'control-label col-md-12']); ?>

							<div class="col-md-8">
								<?php echo Form::select('state_id', App\State::combobox(old('country_id') ? old('country_id'):$model->country_id), null, ['class'=>'form-control','onchange'=>'init_city_selectbox(this)']); ?>

		                        <?php echo $errors->first('state_id', '<label for="state_id" class="help-block error">:message</label>'); ?>

							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group<?php echo e($errors->has('city_id') ? ' has-error' : ''); ?>">
							<?php echo Form::label('city_id', App\Language::trans('City'), ['class'=>'control-label col-md-12']); ?>

							<div class="col-md-8">
								<?php echo Form::select('city_id', App\City::combobox(old('state_id') ? old('state_id'):$model->state_id), null, ['class'=>'form-control']); ?>

		                        <?php echo $errors->first('city_id', '<label for="city_id" class="help-block error">:message</label>'); ?>

							</div>
						</div>
					</div>
				</div>

				<br>
				<h6 class="hk-sec-title"><?php echo e(App\Language::trans('Contact Information')); ?></h6> <hr>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group<?php echo e($errors->has('contact_person') ? ' has-error' : ''); ?>">
							<?php echo Form::label('contact_person', App\Language::trans('Contact Person'), ['class'=>'control-label col-md-12']); ?>

							<div class="col-md-8">
								<?php echo Form::text('contact_person', null, ['class'=>'form-control']); ?>

		                        <?php echo $errors->first('contact_person', '<label for="contact_person" class="help-block error">:message</label>'); ?>

							</div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group<?php echo e($errors->has('email') ? ' has-error' : ''); ?>">
							<?php echo Form::label('email', App\Language::trans('Email'), ['class'=>'control-label col-md-12']); ?>

							<div class="col-md-8">
								<?php echo Form::text('email', null, ['class'=>'form-control']); ?>

		                        <?php echo $errors->first('email', '<label for="email" class="help-block error">:message</label>'); ?>

							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group<?php echo e($errors->has('tel') ? ' has-error' : ''); ?>">
							<?php echo Form::label('tel', App\Language::trans('Tel'), ['class'=>'control-label col-md-12']); ?>

							<div class="col-md-8">
								<?php echo Form::text('tel', null, ['class'=>'form-control']); ?>

		                        <?php echo $errors->first('tel', '<label for="tel" class="help-block error">:message</label>'); ?>

							</div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group<?php echo e($errors->has('mobile') ? ' has-error' : ''); ?>">
							<?php echo Form::label('mobile', App\Language::trans('Mobile'), ['class'=>'control-label col-md-12']); ?>

							<div class="col-md-8">
								<?php echo Form::text('mobile', null, ['class'=>'form-control']); ?>

		                        <?php echo $errors->first('mobile', '<label for="mobile" class="help-block error">:message</label>'); ?>

							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group<?php echo e($errors->has('website') ? ' has-error' : ''); ?>">
							<?php echo Form::label('website', App\Language::trans('Website'), ['class'=>'control-label col-md-12']); ?>

							<div class="col-md-8">
								<?php echo Form::text('website', null, ['class'=>'form-control']); ?>

		                        <?php echo $errors->first('website', '<label for="website" class="help-block error">:message</label>'); ?>

							</div>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-md-6">
						<div class="form-group<?php echo e($errors->has('system_live_date') ? ' has-error' : ''); ?>">
							<?php echo Form::label('system_live_date', App\Language::trans('System Live Date'), ['class'=>'control-label col-md-12']); ?>

							<div class="col-md-8">
								<?php echo Form::text('system_live_date', null, ['class'=>'form-control']); ?>

		                        <?php echo $errors->first('system_live_date', '<label for="system_live_date" class="help-block error">:message</label>'); ?>

							</div>
						</div>
					</div>

					<?php if(Auth::User()->is_super_admin): ?>
						<div class="col-md-6">
							<div class="form-group<?php echo e($errors->has('selected_module') ? ' has-error' : ''); ?>">
								<?php echo Form::label('selected_module', App\Language::trans('Modules'), ['class'=>'control-label col-md-12']); ?>

								<div class="col-md-8">
									<?php echo Form::select('selected_module[]', App\Setting::module_combobox(), strlen($model->selected_module) >  1 ? json_decode($model->selected_module,true):null, ['class'=>'form-control select2 chosen-select',"multiple"=>true,"id"=>"selected_module"]); ?>

			                        <?php echo $errors->first('selected_module', '<label for="country_id" class="help-block error">:message</label>'); ?>

								</div>
							</div>
						</div>
					<?php endif; ?>

				</div>

				<div class="row">
					<div class="col-md-6">
						<div class="form-group<?php echo e($errors->has('system_live_date') ? ' has-error' : ''); ?>">
							<?php echo Form::label('default_admin_user_group_id', App\Language::trans('Default Admin Group'), ['class'=>'control-label col-md-12']); ?>

							<div class="col-md-8">
								<?php echo Form::select('default_admin_user_group_id', App\UserGroup::combobox(), null, ['class'=>'form-control']); ?>

	                            	<?php echo $errors->first('payment_gateway', '<label for="payment_gateway" class="help-block error">:message</label>'); ?>

							</div>
						</div>
					</div>
				</div>

			<!-- End Tab Panel -->	
			</div>


		<div role="tabpanel" class="tab-pane" id="power_management">
			 <!-- Nav tabs -->
			<ul class="nav nav-light nav-tabs bo" role="tablist">

				<li role="presentation" class="nav-item">
					<a href="#pm_billing_setting" aria-controls="pm_billing_setting" class="d-flex align-items-center nav-link fs-16 zero-padding" role="tab" data-toggle="tab"><?php echo e(App\Language::trans('Mobile App Pagement Page')); ?></a>
				</li>

				<li role="presentation" class="nav-item">
					<a href="#pm_mobile_app_payment" aria-controls="pm_mobile_app_payment" class="d-flex align-items-center nav-link fs-16 zero-padding" role="tab" data-toggle="tab"><?php echo e(App\Language::trans('Billing Setup')); ?></a>
				</li>

				<li role="presentation" class="nav-item">
					<a href="#pm_operational_setting" aria-controls="pm_operational_setting" class="d-flex align-items-center nav-link fs-16 zero-padding" role="tab" data-toggle="tab"><?php echo e(App\Language::trans('Payment Reminder Setting')); ?></a>
				</li>

				<li role="presentation" class="nav-item">
					<a href="#pm_msg_and_email_content" aria-controls="pm_msg_and_email_content" class="d-flex align-items-center nav-link fs-16 zero-padding" role="tab" data-toggle="tab"><?php echo e(App\Language::trans('Mobile App Msg and Email Content Setting')); ?></a>
				</li>

				<li role="presentation" class="nav-item">
					<a href="#pm_admin_operational_setting" aria-controls="pm_admin_operational_setting" class="d-flex align-items-center nav-link fs-16 zero-padding" role="tab" data-toggle="tab"><?php echo e(App\Language::trans('Operational Setting')); ?></a>
				</li>

				<li role="presentation" class="nav-item">
					<a href="#pm_uat_setting" aria-controls="pm_uat_setting" class="d-flex align-items-center nav-link fs-16 zero-padding" role="tab" data-toggle="tab"><?php echo e(App\Language::trans('UAT Setup')); ?></a>
				</li>
				<li role="presentation" class="nav-item">
					<a href="#pm_onoff_setting" aria-controls="pm_onoff_setting" class="d-flex align-items-center nav-link fs-16 zero-padding" role="tab" data-toggle="tab"><?php echo e(App\Language::trans('Power On/Off Setup')); ?></a>
				</li>
			</ul>		
			<hr>


			<!-- Tab panes -->
	<div class="tab-content">	
	    <div role="tabpanel" class="tab-pane active" id="pm_billing_setting">

	    		<!-- <h6 class="hk-sec-title"><?php echo e(App\Language::trans('Top Up Setting')); ?></h6> <hr> -->

				<div class="row">
					<div class="col-md-6">
	                		<div class="form-group<?php echo e($errors->has('payment_gateway') ? ' has-error' : ''); ?>">
	                            <?php echo Form::label('payment_gateway', App\Language::trans('Payment Gateway'), ['class'=>'control-label col-md-12']); ?>

	                            <div class="col-md-8">
	                            	<?php echo Form::select('payment_gateway', App\Setting::payment_gateway_combobox(), null, ['class'=>'form-control','required' , 'disabled'=>'true']); ?>

	                            	<?php echo $errors->first('payment_gateway', '<label for="payment_gateway" class="help-block error">:message</label>'); ?>

	                        	</div>
	                        </div>
	                </div>
	            </div>

				<div class="row">

					<div class="col-md-6">
						<div class="form-group<?php echo e($errors->has('is_mobile_app_maintenance') ? ' has-error' : ''); ?>">
							<?php echo Form::label('is_mobile_app_maintenance', App\Language::trans('Is Maintenance Mode'), ['class'=>'control-label col-md-12']); ?>

							<div class="col-md-12">
								 <div class="row">	
								 	<div class="col-md-3">
									    <div class="custom-control custom-radio">
									        <input type="radio" value=1 <?php echo e(isset($model->is_mobile_app_maintenance) == true ? ($model->is_mobile_app_maintenance == true ? 'checked' : '') : 'checked'); ?>  value=1 id="is_mobile_app_maintenance_on" name="is_mobile_app_maintenance" checked class="custom-control-input">
									        <label class="custom-control-label" for="is_mobile_app_maintenance_on"><?php echo e(App\Language::trans('Enabled')); ?></label>
									    </div>
									</div>
									<div class="col-md-3">
									    <div class="custom-control custom-radio">
									        <input type="radio" value=0 <?php echo e(isset($model->is_mobile_app_maintenance) == true ? ($model->is_mobile_app_maintenance == false ? 'checked' : '') : ''); ?>  id="is_mobile_app_maintenance_off" name="is_mobile_app_maintenance"  class="custom-control-input">
									        <label class="custom-control-label" for="is_mobile_app_maintenance_off"><?php echo e(App\Language::trans('Disabled')); ?></label>
									    </div>
									</div>
								 </div>
								 <?php echo $errors->first('is_mobile_app_maintenance', '<label for="is_mobile_app_maintenance" class="help-block error">:message</label>'); ?>

							</div>
						</div>
					</div>

					<div class="col-md-6">
						<div class="form-group<?php echo e($errors->has('is_mobile_app_allow_payment') ? ' has-error' : ''); ?>">
							<?php echo Form::label('is_mobile_app_allow_payment', App\Language::trans('Is Mobile Apps Allow Payment'), ['class'=>'control-label col-md-12']); ?>

							<div class="col-md-12">
								 <div class="row">	
								 	<div class="col-md-3">
									    <div class="custom-control custom-radio">
									        <input type="radio" value=1 <?php echo e(isset($model->is_mobile_app_allow_payment) == true ? ($model->is_mobile_app_allow_payment == true ? 'checked' : '') : 'checked'); ?>  value=1 id="is_mobile_app_allow_payment_on" name="is_mobile_app_allow_payment" checked class="custom-control-input">
									        <label class="custom-control-label" for="is_mobile_app_allow_payment_on"><?php echo e(App\Language::trans('Enabled')); ?></label>
									    </div>
									</div>
									<div class="col-md-3">
									    <div class="custom-control custom-radio">
									        <input type="radio" value=0 <?php echo e(isset($model->is_mobile_app_allow_payment) == true ? ($model->is_mobile_app_allow_payment == false ? 'checked' : '') : ''); ?>  value=1 id="is_mobile_app_allow_payment_off" name="is_mobile_app_allow_payment"  class="custom-control-input">
									        <label class="custom-control-label" for="is_mobile_app_allow_payment_off"><?php echo e(App\Language::trans('Disabled')); ?></label>
									    </div>
									</div>
								 </div>
								 <?php echo $errors->first('is_mobile_app_allow_payment', '<label for="is_mobile_app_allow_payment" class="help-block error">:message</label>'); ?>

							</div>
						</div>
					</div>
				</div>
				
				<div class="row">

			
					<div class="col-md-6">
						<div class="form-group<?php echo e($errors->has('is_top_up_with_predefined_value') ? ' has-error' : ''); ?>">
							<?php echo Form::label('is_top_up_with_predefined_value', App\Language::trans('Top Up With Predefined Value'), ['class'=>'control-label col-md-12']); ?>

							<div class="col-md-12">
								 <div class="row">	
								 	<div class="col-md-3">
									    <div class="custom-control custom-radio">
									        <input type="radio" value=1 <?php echo e(isset($model->is_top_up_with_predefined_value) == true ? ($model->is_top_up_with_predefined_value == true ? 'checked' : '') : 'checked'); ?>  value=1 id="is_top_up_with_predefined_value_on" name="is_top_up_with_predefined_value" checked class="custom-control-input">
									        <label class="custom-control-label" for="is_top_up_with_predefined_value_on"><?php echo e(App\Language::trans('Yes')); ?></label>
									    </div>
									</div>
									<div class="col-md-3">
									    <div class="custom-control custom-radio">
									        <input type="radio" value=0 <?php echo e(isset($model->is_top_up_with_predefined_value) == true ? ($model->is_top_up_with_predefined_value == false ? 'checked' : '') : ''); ?> id="is_top_up_with_predefined_value_off" name="is_top_up_with_predefined_value"  class="custom-control-input">
									        <label class="custom-control-label" for="is_top_up_with_predefined_value_off"><?php echo e(App\Language::trans('No')); ?></label>
									    </div>
									</div>
								 </div>
								 <?php echo $errors->first('is_top_up_with_predefined_value', '<label for="is_top_up_with_predefined_value" class="help-block error">:message</label>'); ?>

							</div>
						</div>
					</div>

					<div class="col-md-6">
						<div class="form-group<?php echo e($errors->has('power_meter_top_up_predefined_value') ? ' has-error' : ''); ?>">
							<?php echo Form::label('power_meter_top_up_predefined_value', App\Language::trans('Top Up Predefined Value').'', ['class'=>'control-label col-md-12']); ?>

							
							<div class="col-md-8">
								<?php echo Form::text('power_meter_top_up_predefined_value', null, ['class'=>'form-control','required']); ?>

		                        <?php echo $errors->first('power_meter_top_up_predefined_value', '<label for="power_meter_top_up_predefined_value" class="help-block error">:message</label>'); ?>

							</div>
							<div class="col-md-12">
									<small><?php echo e(App\Language::trans('Separate by comma , e.g. 5,10,15 .')); ?></small>
							</div>
						</div>
					</div>


				</div>

                <div class="row">
	                <div class="col-md-12">
	                	<?php echo Form::label('power_meter_operational_setting[top_up_amount_range]', App\Language::trans('Top Up Amount Range'), ['class'=>'control-label col-md-4']); ?>

						<div class="col-md-8">
							<input class="power_meter_top_up_range" name="power_meter_operational_setting[top_up_amount_range]" id="power_meter_operational_setting[top_up_amount_range]" data-extra-classes="irs-sm" />

						</div>
					</div>
				</div>

	    </div>

	    <div role="tabpanel" class="tab-pane" id="pm_mobile_app_payment">
	    	<!-- <h6 class="hk-sec-title mt-15"><?php echo e(App\Language::trans('Billing Setting')); ?></h6> <hr> -->

				<div class="row">
					<div class="col-md-6">
						<div class="form-group<?php echo e($errors->has('is_prepaid') ? ' has-error' : ''); ?>">
							<?php echo Form::label('is_prepaid', App\Language::trans('Payment setting'), ['class'=>'control-label col-md-12']); ?>

							<div class="col-md-12">
								 <div class="row">	
								 	<div class="col-md-3">
									    <div class="custom-control custom-radio">
									        <input type="radio"   value=1 <?php echo e(isset($model->is_prepaid) == true ? ($model->is_prepaid == true ? 'checked' : '') : 'checked'); ?> id="is_prepaid_on" name="is_prepaid"  class="custom-control-input">
									        <label class="custom-control-label" for="is_prepaid_on"><?php echo e(App\Language::trans('Prepaid')); ?></label>
									    </div>
									</div>
									<div class="col-md-3">
									    <div class="custom-control custom-radio">
									        <input type="radio" value=0 <?php echo e(isset($model->is_prepaid) == true ? ($model->is_prepaid == false ? 'checked' : '') : ''); ?> id="is_prepaid_off" name="is_prepaid"  class="custom-control-input">
									        <label class="custom-control-label" for="is_prepaid_off"><?php echo e(App\Language::trans('Postpaid')); ?></label>
									    </div>
									</div>
								 </div>
								 <?php echo $errors->first('is_prepaid', '<label for="is_prepaid" class="help-block error">:message</label>'); ?>

							</div>
						</div>
					</div>
				</div>

	    	 <div class="row">
					<div class="col-md-6">
						<div class="form-group<?php echo e($errors->has('is_min_credit') ? ' has-error' : ''); ?>">
							<?php echo Form::label('is_min_credit', App\Language::trans('Min. Credit'), ['class'=>'control-label col-md-12']); ?>

							<div class="col-md-12">
								 <div class="row">	
								 	<div class="col-md-3">
									    <div class="custom-control custom-radio">
									        <input type="radio" value=1 <?php echo e(isset($model->is_min_credit) == true ? ($model->is_min_credit == true ? 'checked' : '') : 'checked'); ?>  value=1 id="is_min_credit_on" name="is_min_credit" checked class="custom-control-input">
									        <label class="custom-control-label" for="is_min_credit_on"><?php echo e(App\Language::trans('Enabled')); ?></label>
									    </div>
									</div>
									<div class="col-md-3">
									    <div class="custom-control custom-radio">
									        <input type="radio" value=0 <?php echo e(isset($model->is_min_credit) == true ? ($model->is_min_credit == false ? 'checked' : '') : ''); ?>  id="is_min_credit_off" name="is_min_credit"  class="custom-control-input">
									        <label class="custom-control-label" for="is_min_credit_off"><?php echo e(App\Language::trans('Disabled')); ?></label>
									    </div>
									</div>
								 </div>
								 <?php echo $errors->first('is_min_credit', '<label for="is_min_credit" class="help-block error">:message</label>'); ?>

							</div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group<?php echo e($errors->has('min_credit') ? ' has-error' : ''); ?>">
							<?php echo Form::label('min_credit', App\Language::trans('Min. Credit'), ['class'=>'control-label col-md-12']); ?>

							<div class="col-md-8">
								<?php echo Form::number('min_credit', null, ['class'=>'form-control','step'=>'.01','min'=>'0']); ?>

		                        <?php echo $errors->first('min_credit', '<label for="min_credit" class="help-block error">:message</label>'); ?>

							</div>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-md-6">
						<div class="form-group<?php echo e($errors->has('is_transaction_charge') ? ' has-error' : ''); ?>">
							<?php echo Form::label('is_transaction_charge', App\Language::trans('Transaction Charge'), ['class'=>'control-label col-md-12']); ?>

							<div class="col-md-12">
								 <div class="row">	
								 	<div class="col-md-3">
									    <div class="custom-control custom-radio">
									        <input type="radio" value=1 <?php echo e(isset($model->is_transaction_charge) == true ? ($model->is_transaction_charge == true ? 'checked' : '') : 'checked'); ?>  value=1 id="is_transaction_charge_on" name="is_transaction_charge" checked class="custom-control-input">
									        <label class="custom-control-label" for="is_transaction_charge_on"><?php echo e(App\Language::trans('Enabled')); ?></label>
									    </div>
									</div>
									<div class="col-md-3">
									    <div class="custom-control custom-radio">
									        <input type="radio" value=0 <?php echo e(isset($model->is_transaction_charge) == true ? ($model->is_transaction_charge == false ? 'checked' : '') : ''); ?> id="is_transaction_charge_off" name="is_transaction_charge"  class="custom-control-input">
									        <label class="custom-control-label" for="is_transaction_charge_off"><?php echo e(App\Language::trans('Disabled')); ?></label>
									    </div>
									</div>
								 </div>
								 <?php echo $errors->first('is_transaction_charge', '<label for="is_transaction_charge" class="help-block error">:message</label>'); ?>

							</div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group<?php echo e($errors->has('transaction_percent') ? ' has-error' : ''); ?>">
							<?php echo Form::label('transaction_percent', App\Language::trans('Transaction Percent'), ['class'=>'control-label col-md-12']); ?>

							<div class="col-md-8">
								<div class="input-group">
	  								<?php echo Form::number('transaction_percent', null, ['class'=>'form-control','step'=>'.01','min'=>'0']); ?>

									<div class="input-group-append">
                                        <span class="input-group-text" id="basic-addon2">%</span>
                                    </div>
								</div>
		                        <?php echo $errors->first('transaction_percent', '<label for="transaction_percent" class="help-block error">:message</label>'); ?>

							</div>
						</div>
					</div>
				</div>

				
				
				<div class="row">
					<div class="col-md-6">
						<div class="form-group<?php echo e($errors->has('is_inclusive') ? ' has-error' : ''); ?>">
							<?php echo Form::label('is_inclusive', App\Language::trans('SST Setting'), ['class'=>'control-label col-md-12']); ?>

							<div class="col-md-12">
								 <div class="row">	
								 	<div class="col-md-3">
									    <div class="custom-control custom-radio">
									        <input type="radio" value=1 <?php echo e(isset($model->is_inclusive) == true ? ($model->is_inclusive == true ? 'checked' : '') : 'checked'); ?>  value=1 id="is_inclusive_on" name="is_inclusive" checked class="custom-control-input">
									        <label class="custom-control-label" for="is_inclusive_on"><?php echo e(App\Language::trans('Inclusive')); ?></label>
									    </div>
									</div>
									<div class="col-md-3">
									    <div class="custom-control custom-radio">
									        <input type="radio" value=0 <?php echo e(isset($model->is_inclusive) == true ? ($model->is_inclusive == false ? 'checked' : '') : ''); ?> id="is_inclusive_off" name="is_inclusive"  class="custom-control-input">
									        <label class="custom-control-label" for="is_inclusive_off"><?php echo e(App\Language::trans('Exclusive')); ?></label>
									    </div>
									</div>
								 </div>
								 <?php echo $errors->first('is_inclusive', '<label for="is_inclusive" class="help-block error">:message</label>'); ?>

							</div>
						</div>
					</div>

					<div class="col-md-6">
						<div class="form-group<?php echo e($errors->has('is_pay_by_accumulate') ? ' has-error' : ''); ?>">
							<?php echo Form::label('is_pay_by_accumulate', App\Language::trans('Mobile App Usage Display Setting'), ['class'=>'control-label col-md-12']); ?>			
							<div class="col-md-12">
								 <div class="row">	
								 	<div class="col-md-3">
									    <div class="custom-control custom-radio">
									        <input type="radio" value=1 <?php echo e(isset($model->is_pay_by_accumulate) == true ? ($model->is_pay_by_accumulate == true ? 'checked' : '') : 'checked'); ?>  value=1 id="is_pay_by_accumulate_on" name="is_pay_by_accumulate" checked class="custom-control-input">
									        <label class="custom-control-label" for="is_pay_by_accumulate_on"><?php echo e(App\Language::trans('Accumulate')); ?></label>
									    </div>
									</div>
									<div class="col-md-3">
									    <div class="custom-control custom-radio">
									        <input type="radio" value=0 <?php echo e(isset($model->is_pay_by_accumulate) == true ? ($model->is_pay_by_accumulate == false ? 'checked' : '') : ''); ?> id="is_pay_by_accumulate_off" name="is_pay_by_accumulate"  class="custom-control-input">
									        <label class="custom-control-label" for="is_pay_by_accumulate_off"><?php echo e(App\Language::trans('Monthly')); ?></label>
									    </div>
									</div>
								 </div>
								 <?php echo $errors->first('is_pay_by_accumulate', '<label for="is_pay_by_accumulate" class="help-block error">:message</label>'); ?>

							</div>
						</div>
					</div>
				</div>

				<div class="row mb-15">
					<div class="col-md-6">
						<div class="form-group<?php echo e($errors->has('monthly_cut_off_day') ? ' has-error' : ''); ?>">
							<?php echo Form::label('monthly_cut_off_day', App\Language::trans('Monthly cut off date'), ['class'=>'control-label col-md-12']); ?>

							<div class="col-md-8">
								<?php echo Form::number('monthly_cut_off_day', null, ['class'=>'form-control','min'=>'1','max'=>'31','autofocus']); ?>

				                <?php echo $errors->first('monthly_cut_off_day', '<label for="monthly_cut_off_day" class="help-block error">:message</label>'); ?>

							</div>
						</div>
					</div>

					<div class="col-md-6">
						<div class="form-group<?php echo e($errors->has('due_date_duration') ? ' has-error' : ''); ?>">
							<?php echo Form::label('due_date_duration', App\Language::trans('Due Date Duration'), ['class'=>'control-label col-md-12']); ?>

							<div class="col-md-8">
								<?php echo Form::number('due_date_duration', null, ['class'=>'form-control','min'=>'0']); ?>

				                <?php echo $errors->first('due_date_duration', '<label for="due_date_duration" class="help-block error">:message</label>'); ?>

							</div>
						</div>
					</div>
				</div>

	    </div>

	    <div role="tabpanel" class="tab-pane" id="pm_operational_setting">
	    			<?php
			 			if(isset($model->id)){
			 				$company = new App\Company();
			 				$company = $company->self_profile();
			 				$message ='';
							$power_meter_operational_setting = (array) json_decode($model->power_meter_operational_setting);
							$power_meter_mailbox_setting = (array) json_decode($model->power_meter_mailbox_setting);
							//dd($power_meter_operational_setting);

							$message .= '<br>'.'User room will';
							if(!$power_meter_operational_setting['is_auto_turn_off_meter'])
							{
								$message .= ' not ';
							}

							$message .= 'stop power supply when account credit is below <strong>'.$company->getCurrenncyCode().' '.$power_meter_operational_setting['is_auto_turn_off_meter'].'</strong>';

							
							
							$message = '';
						}
					?>

						<!-- <div class="row mb-15">
						    <div class="col-sm">
						        <div class="media pa-20 border border-2 border-light rounded">
						            <img class="mr-15 circle d-74" src="<?php echo e(asset('img/red_information.png')); ?>" alt="Generic placeholder image">
						            <div class="media-body">
						                <h6 class="mb-5"><?php echo e(App\Language::trans('Automation Setting Description')); ?></h6>
						                 <?php echo e(App\Language::trans($message)); ?>

						            </div>
						        </div>
						    </div>
						</div> -->


					<!-- <h6 class="hk-sec-title mt-15"><?php echo e(App\Language::trans('Power Meter Operational Setting')); ?></h6> <hr> -->
						<div class="row">
							<div class="col-md-6">
								<div class="form-group<?php echo e($errors->has('power_meter_operational_setting[power_supply_on_off_automation]') ? ' has-error' : ''); ?>">
									<?php echo Form::label('power_meter_operational_setting[power_supply_on_off_automation]', App\Language::trans('Power Supply On/Off Automation'), ['class'=>'control-label col-md-12']); ?>

									<div class="col-md-12">
										 <div class="row">	
										 	<div class="col-md-3">
											    <div class="custom-control custom-radio">
											        <input type="radio" value=1 <?php echo e(isset($power_meter_operational_setting['power_supply_on_off_automation']) == true ? ($power_meter_operational_setting['power_supply_on_off_automation'] == true ? 'checked' : '') : 'checked'); ?>  value=1 id="power_meter_operational_setting[power_supply_on_off_automation]_on" name="power_meter_operational_setting[power_supply_on_off_automation]" checked class="custom-control-input">
											        <label class="custom-control-label" for="power_meter_operational_setting[power_supply_on_off_automation]_on"><?php echo e(App\Language::trans('Yes')); ?></label>
											    </div>
											</div>
											<div class="col-md-3">
											    <div class="custom-control custom-radio">
											        <input type="radio" value=0 <?php echo e(isset($power_meter_operational_setting['power_supply_on_off_automation']) == true ? ($power_meter_operational_setting['power_supply_on_off_automation'] == false ? 'checked' : '') : ''); ?> id="power_meter_operational_setting[power_supply_on_off_automation]_off" name="power_meter_operational_setting[power_supply_on_off_automation]"  class="custom-control-input">
											        <label class="custom-control-label" for="power_meter_operational_setting[power_supply_on_off_automation]_off"><?php echo e(App\Language::trans('No')); ?></label>
											    </div>
											</div>
										 </div>
										 <?php echo $errors->first('power_meter_operational_setting[power_supply_on_off_automation]', '<label for="power_meter_operational_setting[power_supply_on_off_automation]" class="help-block error">:message</label>'); ?>

									</div>
								</div>
							</div>
						</div>

					
						<div class="row">
							<div class="col-md-6">
								<div class="form-group<?php echo e($errors->has('power_meter_operational_setting[is_auto_turn_off_meter]') ? ' has-error' : ''); ?>">
									<?php echo Form::label('power_meter_operational_setting[is_auto_turn_off_meter]', App\Language::trans('Turn Off Meter When No Credit '), ['class'=>'control-label col-md-12']); ?>

									<div class="col-md-12">
										 <div class="row">	
										 	<div class="col-md-3">
											    <div class="custom-control custom-radio">
											        <input type="radio" value=1 <?php echo e(isset($power_meter_operational_setting['is_auto_turn_off_meter']) == true ? ($power_meter_operational_setting['is_auto_turn_off_meter'] == true ? 'checked' : '') : 'checked'); ?>  value=1 id="power_meter_operational_setting[is_auto_turn_off_meter]_on" name="power_meter_operational_setting[is_auto_turn_off_meter]" checked class="custom-control-input">
											        <label class="custom-control-label" for="power_meter_operational_setting[is_auto_turn_off_meter]_on"><?php echo e(App\Language::trans('Yes')); ?></label>
											    </div>
											</div>
											<div class="col-md-3">
											    <div class="custom-control custom-radio">
											        <input type="radio" value=0 <?php echo e(isset($power_meter_operational_setting['is_auto_turn_off_meter']) == true ? ($power_meter_operational_setting['is_auto_turn_off_meter'] == false ? 'checked' : '') : ''); ?> id="power_meter_operational_setting[is_auto_turn_off_meter]_off" name="power_meter_operational_setting[is_auto_turn_off_meter]"  class="custom-control-input">
											        <label class="custom-control-label" for="power_meter_operational_setting[is_auto_turn_off_meter]_off"><?php echo e(App\Language::trans('No')); ?></label>
											    </div>
											</div>
										 </div>
										 <?php echo $errors->first('power_meter_operational_setting[is_auto_turn_off_meter]', '<label for="power_meter_operational_setting[is_auto_turn_off_meter]" class="help-block error">:message</label>'); ?>

									</div>
								</div>
							</div>
						</div>


				   		 <div class="row">
							<div class="col-md-6">
								<div class="form-group<?php echo e($errors->has('power_meter_operational_setting[credit_threshold]') ? ' has-error' : ''); ?>">
									<?php echo Form::label('power_meter_operational_setting[credit_threshold]', App\Language::trans('Credit Threshold (RM)'), ['class'=>'control-label col-md-12']); ?>

									<div class="col-md-8">
										<?php echo Form::number('power_meter_operational_setting[credit_threshold]', (isset($model->id) ? (isset($power_meter_operational_setting['credit_threshold']) ? $power_meter_operational_setting['credit_threshold'] : '') : null), ['class'=>'form-control','min'=>'0.01','max'=>'999999','step'=>'0.01','autofocus']); ?>

						                <?php echo $errors->first('power_meter_operational_setting[credit_threshold]', '<label for="power_meter_operational_setting[credit_threshold]" class="help-block error">:message</label>'); ?>

									</div>
								</div>
							</div>

							<div class="col-md-6">
								<div class="form-group<?php echo e($errors->has('power_meter_operational_setting[grace_period_before_stop_supply]') ? ' has-error' : ''); ?>">
									<?php echo Form::label('power_meter_operational_setting[grace_period_before_stop_supply]', App\Language::trans('Grace Peiod (minutes) After Credit Below Threshold'), ['class' => 'control-label col-md-12']); ?>

									<div class="col-md-8">
										<?php echo Form::text('power_meter_operational_setting[grace_period_before_stop_supply]', (isset($model->id) ? (isset($power_meter_operational_setting['grace_period_before_stop_supply']) ? $power_meter_operational_setting['grace_period_before_stop_supply'] : '') : null), ['class'=>'form-control','min'=>'1','max'=>'999999','autofocus']); ?>

						                <?php echo $errors->first('power_meter_operational_setting[grace_period_before_stop_supply]', '<label for="power_meter_operational_setting[grace_period_before_stop_supply]" class="help-block error">:message</label>'); ?>

									</div>
								</div>
							</div>
						</div>


						 <div class="row">
							<div class="col-md-6">
								<div class="form-group<?php echo e($errors->has('power_meter_operational_setting[warning_email_interval]') ? ' has-error' : ''); ?>">
									<?php echo Form::label('power_meter_operational_setting[warning_email_interval]', App\Language::trans('Interval (minutes) To Send Warning Email'), ['class'=>'control-label col-md-12']); ?>

									<div class="col-md-8">
										<?php echo Form::number('power_meter_operational_setting[warning_email_interval]', (isset($model->id) ? (isset($power_meter_operational_setting['warning_email_interval']) ? $power_meter_operational_setting['warning_email_interval'] : '') : null), ['class'=>'form-control','min'=>'1','max'=>'999999','autofocus']); ?>

						                <?php echo $errors->first('power_meter_operational_setting[warning_email_interval]', '<label for="power_meter_operational_setting[warning_email_interval]" class="help-block error">:message</label>'); ?>

									</div>
								</div>
							</div>

							<div class="col-md-6">
								<div class="form-group<?php echo e($errors->has('power_meter_operational_setting[warning_email_number]') ? ' has-error' : ''); ?>">
									<?php echo Form::label('power_meter_operational_setting[warning_email_number]', App\Language::trans('Number Of Warning Email To Be Sent'), ['class'=>'control-label col-md-12']); ?>

									<div class="col-md-8">
										<?php echo Form::number('power_meter_operational_setting[warning_email_number]', (isset($model->id) ? (isset($power_meter_operational_setting['warning_email_number']) ? $power_meter_operational_setting['warning_email_number'] : '') : null), ['class'=>'form-control','min'=>'0','max'=>'99999','autofocus']); ?>

						                <?php echo $errors->first('power_meter_operational_setting[warning_email_number]', '<label for="power_meter_operational_setting[warning_email_number]" class="help-block error">:message</label>'); ?>

									</div>
								</div>
							</div>
						</div>

					 <hr><h6 class="hk-sec-title mt-15"><?php echo e(App\Language::trans('MailBox Setting')); ?></h6> <hr>
				   		
						<div class="row">
							<div class="col-md-6">
								<div class="form-group<?php echo e($errors->has('power_meter_mailbox_setting[mail_engine]') ? ' has-error' : ''); ?>">
									<?php echo Form::label('power_meter_mailbox_setting[mail_engine]', App\Language::trans('Mail Engine'), ['class'=>'control-label col-md-12']); ?>

									<div class="col-md-12">
										 <div class="row">	
										 	<?php echo Form::select('power_meter_mailbox_setting[mail_engine]', App\Setting::mail_engine(), null, ['class'=>'form-control','required']); ?>

											<?php echo $errors->first('power_meter_mailbox_setting[mail_engine]', '<label for="power_meter_mailbox_setting[mail_engine]" class="help-block error">:message</label>'); ?>

									</div>
								</div>
							</div>
						</div>
					</div>


					<div class="row">
						<div class="col-md-6">
							<div class="form-group<?php echo e($errors->has('power_meter_mailbox_setting[smtp_hostname]') ? ' has-error' : ''); ?>">
								<?php echo Form::label('power_meter_mailbox_setting[smtp_hostname]', App\Language::trans('SMTP Hostname'), ['class'=>'control-label col-md-12']); ?>

								<div class="col-md-8">
									<?php echo Form::text('power_meter_mailbox_setting[smtp_hostname]', (isset($model->id) ? (isset($power_meter_mailbox_setting['smtp_hostname']) ? $power_meter_mailbox_setting['smtp_hostname'] : '') : null), ['class'=>'form-control']); ?>

			                        <?php echo $errors->first('power_meter_mailbox_setting[smtp_hostname]', '<label for="power_meter_mailbox_setting[smtp_hostname]" class="help-block error">:message</label>'); ?>

								</div>
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group<?php echo e($errors->has('power_meter_mailbox_setting[smtp_username]') ? ' has-error' : ''); ?>">
								<?php echo Form::label('power_meter_mailbox_setting[smtp_username]', App\Language::trans('SMTP Username'), ['class'=>'control-label col-md-12']); ?>

								<div class="col-md-8">
									<?php echo Form::text('power_meter_mailbox_setting[smtp_username]', (isset($model->id) ? (isset($power_meter_mailbox_setting['smtp_username']) ? $power_meter_mailbox_setting['smtp_username'] : '') : null), ['class'=>'form-control']); ?>

			                        <?php echo $errors->first('power_meter_mailbox_setting[smtp_username]', '<label for="power_meter_mailbox_setting[smtp_username]" class="help-block error">:message</label>'); ?>

								</div>
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-md-6">
							<div class="form-group<?php echo e($errors->has('power_meter_mailbox_setting[smtp_password]') ? ' has-error' : ''); ?>">
								<?php echo Form::label('power_meter_mailbox_setting[smtp_password]', App\Language::trans('SMTP Password'), ['class'=>'control-label col-md-12']); ?>

								<div class="col-md-8">
									<?php echo Form::text('power_meter_mailbox_setting[smtp_password]', (isset($model->id) ? (isset($power_meter_mailbox_setting['smtp_hostname']) ? $power_meter_mailbox_setting['smtp_password'] : '') : null), ['class'=>'form-control']); ?>

			                        <?php echo $errors->first('power_meter_mailbox_setting[smtp_password]', '<label for="power_meter_mailbox_setting[smtp_password]" class="help-block error">:message</label>'); ?>

								</div>
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group<?php echo e($errors->has('power_meter_mailbox_setting[smtp_port]') ? ' has-error' : ''); ?>">
								<?php echo Form::label('power_meter_mailbox_setting[smtp_port]', App\Language::trans('SMTP Port'), ['class'=>'control-label col-md-12']); ?>

								<div class="col-md-8">
									<?php echo Form::number('power_meter_mailbox_setting[smtp_port]', (isset($model->id) ? (isset($power_meter_mailbox_setting['smtp_port']) ? $power_meter_mailbox_setting['smtp_port'] : '') : null), ['class'=>'form-control','step'=>'1','min'=>'1']); ?>


								
			                        <?php echo $errors->first('power_meter_mailbox_setting[smtp_port]', '<label for="power_meter_mailbox_setting[smtp_password]" class="help-block error">:message</label>'); ?>

								</div>
							</div>
						</div>

						
					</div>

					<div class="row">

						<div class="col-md-6">
							<div class="form-group<?php echo e($errors->has('power_meter_mailbox_setting[smtp_timeout]') ? ' has-error' : ''); ?>">
								<?php echo Form::label('power_meter_mailbox_setting[smtp_timeout]', App\Language::trans('SMTP Timeout'), ['class'=>'control-label col-md-12']); ?>

								<div class="col-md-8">
									<?php echo Form::number('power_meter_mailbox_setting[smtp_timeout]', (isset($model->id) ? (isset($power_meter_mailbox_setting['smtp_timeout']) ? $power_meter_mailbox_setting['smtp_timeout'] : '') : null), ['class'=>'form-control','step'=>'1','min'=>'1']); ?>

			                        <?php echo $errors->first('power_meter_mailbox_setting[smtp_timeout]', '<label for="power_meter_mailbox_setting[smtp_timeout]" class="help-block error">:message</label>'); ?>

								</div>
							</div>
						</div>
						
					</div>

	    </div>

	    <div role="tabpanel" class="tab-pane" id="pm_msg_and_email_content">
	    		<!-- <h6 class="hk-sec-title mt-15"><?php echo e(App\Language::trans('Power Meter Mobile App Email and Message Setting')); ?></h6> <hr> -->	
		

			<?php 
					$language_listing = $page_variables['language_listing'];
					$tab_status = ' active';

			?>
			 <!-- Nav tabs -->
			<ul class="nav nav-light nav-tabs bo" role="tablist">
			
				<?php $__currentLoopData = $language_listing; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $language): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

					<li role="presentation" class="nav-item">
						<a href="#<?php echo e($language); ?>" aria-controls="<?php echo e($language); ?>" class="d-flex align-items-center nav-link<?php echo e($tab_status); ?> fs-16 zero-padding" role="tab" data-toggle="tab"><h5><?php echo e(App\Language::trans(ucfirst($language))); ?></h5></a>
					</li>
					<?php $tab_status =''; ?>
				<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>


			</ul>
			<hr>


			<!-- Tab panes -->
			<?php $tab_status = ' active'; ?>
			<div class="tab-content">	
					<?php
					//dd($backend_data_model);

					if(isset($backend_data_model['id'])){

							$pm_mobile_msg = ['payment_success_msg' , 'system_maintenance_msg' , 'no_room_register_msg','no_access_right', 'no_allow_payment_msg', 'no_reading_notification_email','invalid_email_msg'];
							$pm_email_msg = ['power_meter_payment_success_email','power_meter_power_supply_restore_email','power_meter_low_credit_reminder' , 'power_meter_payment_reminder_email' , 'power_meter_turn_off_meter_email' ];
							$power_meter_mobile_app_msg_arr = (array) json_decode($backend_data_model['power_meter_mobile_app_msg']);

							foreach($pm_mobile_msg as $key)
							{
								//dd($backend_data_model['power_meter_mobile_app_msg']);
								$temp_key = 'temp_'.$key;
								$$temp_key = isset($power_meter_mobile_app_msg_arr[$key]) ? ( array ) $power_meter_mobile_app_msg_arr[$key] : array();
								$$key = $$temp_key != null ? ( count($$temp_key) > 0 ?  $$temp_key : array() ) :array() ;


							}	

							foreach($pm_email_msg as $key)
							{
								$temp_key = 'temp_'.$key;
								$$temp_key = (array) json_decode($backend_data_model[$key]);
								//dd($$temp_key);
								$$key = $$temp_key != null ? ( isset($$temp_key) ? ( count($$temp_key) > 0 ?  $$temp_key : array() ) : array() ) : array() ;

			//dd($$key);
							}						
					}

				?>
					
					<?php $__currentLoopData = $language_listing; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $language): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
					    <div role="tabpanel" class="tab-pane<?php echo e($tab_status); ?>" id="<?php echo e($language); ?>">
					    		<h6 class="hk-sec-title mt-15"><?php echo e(App\Language::trans('Mobile Apps Message Setting')); ?></h6> <hr>	
					    	    <div class="row">
										 <div class="col-md-12">
									        	<div class="form-group<?php echo e($errors->has('power_meter_mobile_app_msg[payment_success_msg]['.$language.']') ? ' has-error' : ''); ?>">
													<?php echo Form::label('power_meter_mobile_app_msg[payment_success_msg]['.$language.']', App\Language::trans('Payment Success Message'), ['class'=>'control-label col-md-12']); ?>

													<div class="col-md-12">
														<?php echo Form::text('power_meter_mobile_app_msg[payment_success_msg]['.$language.'][title]', (isset($payment_success_msg[$language]->title) ? $payment_success_msg[$language]->title : null), ['class'=>'form-control','placeholder'=>'Title']); ?>

														<?php echo $errors->first('power_meter_mobile_app_msg[payment_success_msg]['.$language.'][title]', '<label for="power_meter_mobile_app_msg[payment_success_msg]['.$language.'][title]" class="help-block error">:message</label>'); ?>

													</div>
													<div class="col-md-12">
														<?php echo Form::textarea('power_meter_mobile_app_msg[payment_success_msg]['.$language.'][content]', (isset($payment_success_msg[$language]->content) ? $payment_success_msg[$language]->content : null), ['id'=>'power_meter_mobile_app_msg[payment_success_msg]['.$language.'][content]', 'rows'=>'10' , 'cols'=>'80','class'=>'form-control tinymce']); ?>

									                    <?php echo $errors->first('power_meter_mobile_app_msg[payment_success_msg]['.$language.'][content]', '<label for="power_meter_mobile_app_msg[payment_success_msg]['.$language.'][content]" class="help-block error">:message</label>'); ?>

													</div>
												</div>
									       </div>
								   </div>

								   <div class="row">
										 <div class="col-md-12">
									        	<div class="form-group<?php echo e($errors->has('power_meter_mobile_app_msg[invalid_email_msg]['.$language.']') ? ' has-error' : ''); ?>">
													<?php echo Form::label('power_meter_mobile_app_msg[invalid_email_msg]['.$language.']', App\Language::trans('Invalide Email Message'), ['class'=>'control-label col-md-12']); ?>

													<div class="col-md-12">
														<?php echo Form::text('power_meter_mobile_app_msg[invalid_email_msg]['.$language.']', (isset($invalid_email_msg[$language]->title) ? $invalid_email_msg[$language]->title : null), ['class'=>'form-control','placeholder'=>'Title']); ?>

														<?php echo $errors->first('power_meter_mobile_app_msg[invalid_email_msg]['.$language.']', '<label for="power_meter_mobile_app_msg[invalid_email_msg]['.$language.']" class="help-block error">:message</label>'); ?>

													</div>
													<div class="col-md-12">
														<?php echo Form::textarea('power_meter_mobile_app_msg[invalid_email_msg]['.$language.'][content]', (isset($invalid_email_msg[$language]->content) ? $invalid_email_msg[$language]->content : null), ['id'=>'power_meter_mobile_app_msg[invalid_email_msg]['.$language.'][content]', 'rows'=>'10' , 'cols'=>'80','class'=>'form-control tinymce']); ?>

									                    <?php echo $errors->first('power_meter_mobile_app_msg[invalid_email_msg]['.$language.'][content]', '<label for="power_meter_mobile_app_msg[invalid_email_msg]['.$language.'][content]" class="help-block error">:message</label>'); ?>

													</div>
												</div>
									       </div>
								   </div>

								   <div class="row">
										 <div class="col-md-12">
									        	<div class="form-group<?php echo e($errors->has('power_meter_mobile_app_msg[no_room_register_msg]['.$language.']') ? ' has-error' : ''); ?>">
													<?php echo Form::label('power_meter_mobile_app_msg[no_room_register_msg]['.$language.']', App\Language::trans('Room Setup In Progress Message'), ['class'=>'control-label col-md-12']); ?>

													<div class="col-md-12">
														<?php echo Form::text('power_meter_mobile_app_msg[no_room_register_msg]['.$language.'][title]', (isset($no_room_register_msg[$language]->title) ? $no_room_register_msg[$language]->title : null), ['class'=>'form-control','placeholder'=>'Title']); ?>

														<?php echo $errors->first('power_meter_mobile_app_msg[no_room_register_msg]['.$language.'][title]', '<label for="power_meter_mobile_app_msg[no_room_register_msg]['.$language.'][title]" class="help-block error">:message</label>'); ?>

													</div>
													<div class="col-md-12">
														<?php echo Form::textarea('power_meter_mobile_app_msg[no_room_register_msg]['.$language.'][content]', (isset($no_room_register_msg[$language]->content) ? $no_room_register_msg[$language]->content : null), ['id'=>'power_meter_mobile_app_msg[no_room_register_msg]['.$language.'][content]', 'rows'=>'10' , 'cols'=>'80','class'=>'form-control tinymce']); ?>

									                    <?php echo $errors->first('power_meter_mobile_app_msg[no_room_register_msg]['.$language.'][content]', '<label for="power_meter_mobile_app_msg[no_room_register_msg]['.$language.'][content]" class="help-block error">:message</label>'); ?>

													</div>
												</div>
									       </div>
								   </div>


								   <div class="row">
										 <div class="col-md-12">
									        	<div class="form-group<?php echo e($errors->has('power_meter_mobile_app_msg[system_maintenance_msg]['.$language.']') ? ' has-error' : ''); ?>">
													<?php echo Form::label('power_meter_mobile_app_msg[system_maintenance_msg]['.$language.']', App\Language::trans('System Maintenance Message'), ['class'=>'control-label col-md-12']); ?>

													<div class="col-md-12">
														<?php echo Form::text('power_meter_mobile_app_msg[system_maintenance_msg]['.$language.'][title]', (isset($system_maintenance_msg[$language]->title) ? $system_maintenance_msg[$language]->title : null), ['class'=>'form-control','placeholder'=>'Title']); ?>

														<?php echo $errors->first('power_meter_mobile_app_msg[system_maintenance_msg]['.$language.'][title]', '<label for="power_meter_mobile_app_msg[system_maintenance_msg]['.$language.'][title]" class="help-block error">:message</label>'); ?>

													</div>
													<div class="col-md-12">
														<?php echo Form::textarea('power_meter_mobile_app_msg[system_maintenance_msg]['.$language.'][content]', (isset($system_maintenance_msg[$language]->content) ? $system_maintenance_msg[$language]->content : null), ['id'=>'power_meter_mobile_app_msg[system_maintenance_msg]['.$language.'][content]', 'rows'=>'10' , 'cols'=>'80','class'=>'form-control tinymce']); ?>

									                    <?php echo $errors->first('power_meter_mobile_app_msg[system_maintenance_msg]['.$language.'][content]', '<label for="power_meter_mobile_app_msg[system_maintenance_msg]['.$language.'][content]" class="help-block error">:message</label>'); ?>

													</div>
												</div>
									       </div>
								   </div>


								   <div class="row">
										 <div class="col-md-12">
									        	<div class="form-group<?php echo e($errors->has('power_meter_mobile_app_msg[no_allow_payment_msg]['.$language.']') ? ' has-error' : ''); ?>">
													<?php echo Form::label('power_meter_mobile_app_msg[no_allow_payment_msg]['.$language.']', App\Language::trans('Not Allow Payment Message'), ['class'=>'control-label col-md-12']); ?>

													<div class="col-md-12">
														<?php echo Form::text('power_meter_mobile_app_msg[no_allow_payment_msg]['.$language.'][title]', (isset($no_allow_payment_msg[$language]->title) ? $no_allow_payment_msg[$language]->title : null), ['class'=>'form-control','placeholder'=>'Title']); ?>

														<?php echo $errors->first('power_meter_mobile_app_msg[no_allow_payment_msg]['.$language.'][title]', '<label for="power_meter_mobile_app_msg[no_allow_payment_msg]['.$language.'][title]" class="help-block error">:message</label>'); ?>

													</div>
													<div class="col-md-12">
														<?php echo Form::textarea('power_meter_mobile_app_msg[no_allow_payment_msg]['.$language.'][content]', (isset($no_allow_payment_msg[$language]->content) ? $no_allow_payment_msg[$language]->content : null), ['id'=>'power_meter_mobile_app_msg[no_allow_payment_msg]['.$language.'][content]', 'rows'=>'10' , 'cols'=>'80','class'=>'form-control tinymce']); ?>

									                    <?php echo $errors->first('power_meter_mobile_app_msg[no_allow_payment_msg]['.$language.'][content]', '<label for="power_meter_mobile_app_msg[no_allow_payment_msg]['.$language.'][content]" class="help-block error">:message</label>'); ?>

													</div>
												</div>
									       </div>
								   </div>


								   <div class="row">
										 <div class="col-md-12">
									        	<div class="form-group<?php echo e($errors->has('power_meter_mobile_app_msg[no_access_right]['.$language.']') ? ' has-error' : ''); ?>">
													<?php echo Form::label('power_meter_mobile_app_msg[no_access_right]['.$language.']', App\Language::trans('No Access Right Message'), ['class'=>'control-label col-md-12']); ?>

													<div class="col-md-12">
														<?php echo Form::text('power_meter_mobile_app_msg[no_access_right]['.$language.'][title]', (isset($no_access_right[$language]->title) ? $no_access_right[$language]->title : null), ['class'=>'form-control','placeholder'=>'Title']); ?>

														<?php echo $errors->first('power_meter_mobile_app_msg[no_access_right]['.$language.'][title]', '<label for="power_meter_mobile_app_msg[no_access_right]['.$language.'][title]" class="help-block error">:message</label>'); ?>

													</div>
													<div class="col-md-12">
														<?php echo Form::textarea('power_meter_mobile_app_msg[no_access_right]['.$language.'][content]', (isset($no_access_right[$language]->content) ? $no_access_right[$language]->content : null), ['id'=>'power_meter_mobile_app_msg[no_access_right]['.$language.'][content]', 'rows'=>'10' , 'cols'=>'80','class'=>'form-control tinymce']); ?>

									                    <?php echo $errors->first('power_meter_mobile_app_msg[no_access_right]['.$language.'][content]', '<label for="power_meter_mobile_app_msg[no_access_right]['.$language.'][content]" class="help-block error">:message</label>'); ?>

													</div>
												</div>
									       </div>
								   </div>

								   <h6 class="hk-sec-title mt-15"><?php echo e(App\Language::trans('Email Notification Template')); ?></h6> <hr>	

								   <div class="row">
										 <div class="col-md-12">
									        	<div class="form-group<?php echo e($errors->has('power_meter_payment_success_email['.$language.']') ? ' has-error' : ''); ?>">
													<?php echo Form::label('power_meter_payment_success_email['.$language.']', App\Language::trans('Payment Success Email'), ['class'=>'control-label col-md-12']); ?>

													<div class="col-md-12">
														<?php echo Form::text('power_meter_payment_success_email['.$language.'][title]', (isset($power_meter_payment_success_email[$language]->title) ? $power_meter_payment_success_email[$language]->title : null), ['class'=>'form-control','placeholder'=>'Title']); ?>

														<?php echo $errors->first('power_meter_payment_success_email['.$language.'][title]', '<label for="power_meter_payment_success_email['.$language.'][title]" class="help-block error">:message</label>'); ?>

													</div>
													<div class="col-md-12">
														<?php echo Form::textarea('power_meter_payment_success_email['.$language.'][content]', (isset($power_meter_payment_success_email[$language]->content) ? $power_meter_payment_success_email[$language]->content : null), ['id'=>'power_meter_payment_success_email['.$language.'][content]', 'rows'=>'10' , 'cols'=>'80','class'=>'form-control tinymce']); ?>

									                    <?php echo $errors->first('power_meter_payment_success_email['.$language.'][content]', '<label for="power_meter_payment_success_email['.$language.'][content]" class="help-block error">:message</label>'); ?>

													</div>
												</div>
									       </div>
								   </div>
								   
								   <div class="row">
										 <div class="col-md-12">
									        	<div class="form-group<?php echo e($errors->has('power_meter_low_credit_reminder['.$language.']') ? ' has-error' : ''); ?>">
													<?php echo Form::label('power_meter_low_credit_reminder['.$language.']', App\Language::trans('Low Credit Remainder Email'), ['class'=>'control-label col-md-12']); ?>

													<div class="col-md-12">
														<?php echo Form::text('power_meter_low_credit_reminder['.$language.'][title]', (isset($power_meter_low_credit_reminder[$language]->title) ? $power_meter_low_credit_reminder[$language]->title : null), ['class'=>'form-control','placeholder'=>'Title']); ?>

														<?php echo $errors->first('power_meter_low_credit_reminder['.$language.'][title]', '<label for="power_meter_low_credit_reminder['.$language.'][title]" class="help-block error">:message</label>'); ?>

													</div>
													<div class="col-md-12">
														<?php echo Form::textarea('power_meter_low_credit_reminder['.$language.'][content]', (isset($power_meter_low_credit_reminder[$language]->content) ? $power_meter_low_credit_reminder[$language]->content : null), ['id'=>'power_meter_low_credit_reminder['.$language.'][content]', 'rows'=>'10' , 'cols'=>'80','class'=>'form-control tinymce']); ?>

									                    <?php echo $errors->first('power_meter_low_credit_reminder['.$language.'][content]', '<label for="power_meter_low_credit_reminder['.$language.'][content]" class="help-block error">:message</label>'); ?>

													</div>
												</div>
									       </div>
								   </div>

								    <div class="row">
										 <div class="col-md-12">
									        	<div class="form-group<?php echo e($errors->has('power_meter_payment_reminder_email['.$language.']') ? ' has-error' : ''); ?>">
													<?php echo Form::label('power_meter_payment_reminder_email['.$language.']', App\Language::trans('Payment Remainder Email'), ['class'=>'control-label col-md-12']); ?>

													<div class="col-md-12">
														<?php echo Form::text('power_meter_payment_reminder_email['.$language.'][title]', (isset($power_meter_payment_reminder_email[$language]->title) ? $power_meter_payment_reminder_email[$language]->title : null), ['class'=>'form-control','placeholder'=>'Title']); ?>

														<?php echo $errors->first('power_meter_payment_reminder_email['.$language.'][title]', '<label for="power_meter_payment_reminder_email['.$language.'][title]" class="help-block error">:message</label>'); ?>

													</div>
													<div class="col-md-12">
														<?php echo Form::textarea('power_meter_payment_reminder_email['.$language.'][content]', (isset($power_meter_payment_reminder_email[$language]->content) ? $power_meter_payment_reminder_email[$language]->content : null), ['id'=>'power_meter_payment_reminder_email['.$language.'][content]', 'rows'=>'10' , 'cols'=>'80','class'=>'form-control tinymce']); ?>

									                    <?php echo $errors->first('power_meter_payment_reminder_email['.$language.'][content]', '<label for="power_meter_payment_reminder_email['.$language.'][content]" class="help-block error">:message</label>'); ?>

													</div>
												</div>
									       </div>
								   </div>

								   <div class="row">
										 <div class="col-md-12">
									        	<div class="form-group<?php echo e($errors->has('power_meter_turn_off_meter_email['.$language.']') ? ' has-error' : ''); ?>">
													<?php echo Form::label('power_meter_turn_off_meter_email['.$language.']', App\Language::trans('Terminate Power Supply Email'), ['class'=>'control-label col-md-12']); ?>

													<div class="col-md-12">
														<?php echo Form::text('power_meter_turn_off_meter_email['.$language.'][title]', (isset($power_meter_turn_off_meter_email[$language]->title) ? $power_meter_turn_off_meter_email[$language]->title : null), ['class'=>'form-control','placeholder'=>'Title']); ?>

														<?php echo $errors->first('power_meter_turn_off_meter_email['.$language.'][title]', '<label for="power_meter_turn_off_meter_email['.$language.'][title]" class="help-block error">:message</label>'); ?>

													</div>
													<div class="col-md-12">
														<?php echo Form::textarea('power_meter_turn_off_meter_email['.$language.'][content]', (isset($power_meter_turn_off_meter_email[$language]->content) ? $power_meter_turn_off_meter_email[$language]->content : null), ['id'=>'power_meter_turn_off_meter_email['.$language.'][content]', 'rows'=>'10' , 'cols'=>'80','class'=>'form-control tinymce']); ?>

									                    <?php echo $errors->first('power_meter_turn_off_meter_email['.$language.'][content]', '<label for="power_meter_turn_off_meter_email['.$language.'][content]" class="help-block error">:message</label>'); ?>

													</div>
												</div>
									       </div>
								   </div>


								     <div class="row">
										 <div class="col-md-12">
									        	<div class="form-group<?php echo e($errors->has('power_meter_power_supply_restore_email['.$language.']') ? ' has-error' : ''); ?>">
													<?php echo Form::label('power_meter_power_supply_restore_email['.$language.']', App\Language::trans('Power Supply Restore Email'), ['class'=>'control-label col-md-12']); ?>

													<div class="col-md-12">
														<?php echo Form::text('power_meter_power_supply_restore_email['.$language.'][title]', (isset($power_meter_power_supply_restore_email[$language]->title) ? $power_meter_power_supply_restore_email[$language]->title : null), ['class'=>'form-control','placeholder'=>'Title']); ?>

														<?php echo $errors->first('power_meter_power_supply_restore_email['.$language.'][title]', '<label for="power_meter_power_supply_restore_email['.$language.'][title]" class="help-block error">:message</label>'); ?>

													</div>
													<div class="col-md-12">
														<?php echo Form::textarea('power_meter_power_supply_restore_email['.$language.'][content]', (isset($power_meter_power_supply_restore_email[$language]->content) ? $power_meter_power_supply_restore_email[$language]->content : null), ['id'=>'power_meter_power_supply_restore_email['.$language.'][content]', 'rows'=>'10' , 'cols'=>'80','class'=>'form-control tinymce']); ?>

									                    <?php echo $errors->first('power_meter_power_supply_restore_email['.$language.'][content]', '<label for="power_meter_power_supply_restore_email['.$language.'][content]" class="help-block error">:message</label>'); ?>

													</div>
												</div>
									       </div>
								   </div>


								  
							<!-- End Tab Panel -->	
						</div>
						<?php $tab_status =''; ?>
					<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
				</div>
			

	    </div>


	 <div role="tabpanel" class="tab-pane" id="pm_admin_operational_setting">
	    		<!-- <h6 class="hk-sec-title mt-15"><?php echo e(App\Language::trans('Operational Setting')); ?></h6> <hr>	 -->
		

			<?php 
					$language_listing = $page_variables['language_listing'];
					$tab_status = ' active';

			?>

			<div class="row">
					<div class="col-md-6">
						<div class="form-group<?php echo e($errors->has('is_auto_on_off_on') ? ' has-error' : ''); ?>">
							<?php echo Form::label('is_auto_on_off_on', App\Language::trans('Auto On Off Module'), ['class'=>'control-label col-md-12']); ?>

							<div class="col-md-12">
								 <div class="row">	
								 	<div class="col-md-3">
									    <div class="custom-control custom-radio">
									        <input onchange="init_auto_on_off_section();" type="radio"   value=1 <?php echo e(isset($model->is_auto_on_off) == true ? ($model->is_auto_on_off == true ? 'checked' : '') : 'checked'); ?> id="is_auto_on_off_on" name="is_auto_on_off"  class="custom-control-input">
									        <label class="custom-control-label" for="is_auto_on_off_on"><?php echo e(App\Language::trans('On')); ?></label>
									    </div>
									</div>
									<div class="col-md-3">
									    <div class="custom-control custom-radio">
									        <input onchange="init_auto_on_off_section();" type="radio" value=0 <?php echo e(isset($model->is_auto_on_off) == true ? ($model->is_auto_on_off == false ? 'checked' : '') : ''); ?> id="is_auto_on_off_off" name="is_auto_on_off"  class="custom-control-input">
									        <label class="custom-control-label" for="is_auto_on_off_off"><?php echo e(App\Language::trans('Off')); ?></label>
									    </div>
									</div>
								 </div>
								 <?php echo $errors->first('is_auto_on_off_on', '<label for="is_auto_on_off_on" class="help-block error">:message</label>'); ?>

							</div>
						</div>
					</div>
				</div>

			<div class="row" id='auto_module_div1'>
				<div class="col-md-12">
					<div class="form-group<?php echo e($errors->has('power_meter_operational_setting[remote_relay_api_url]') ? ' has-error' : ''); ?>">
						<?php echo Form::label('remote_relay_api_url', App\Language::trans('Remote Relay Control API URL'), ['class'=>'control-label col-md-12']); ?>

						<div class="col-md-12">
							<?php echo Form::text('power_meter_operational_setting[remote_relay_api_url]', (isset($model->id) ? (isset($power_meter_operational_setting['remote_relay_api_url']) ? $power_meter_operational_setting['remote_relay_api_url'] : '') : 0), ['class'=>'form-control','min'=>'1','max'=>'999999','autofocus']); ?>

			                <?php echo $errors->first('power_meter_operational_setting[remote_relay_api_url]', '<label for="power_meter_operational_setting[remote_relay_api_url]" class="help-block error">:message</label>'); ?>

						</div>
					</div>
				</div>
			</div>

			<div class="row" id='auto_module_div2'>
				<div class="col-md-12">
					<div class="form-group<?php echo e($errors->has('power_meter_operational_setting[remote_relay_program_api_url]') ? ' has-error' : ''); ?>">
						<?php echo Form::label('remote_relay_program_api_url', App\Language::trans('Remote Relay Control Program API URL'), ['class'=>'control-label col-md-12']); ?>

						<div class="col-md-12">
							<?php echo Form::text('power_meter_operational_setting[remote_relay_program_api_url]', (isset($model->id) ? (isset($power_meter_operational_setting['remote_relay_program_api_url']) ? $power_meter_operational_setting['remote_relay_program_api_url'] : '') : 0), ['class'=>'form-control','min'=>'1','max'=>'999999','autofocus']); ?>

			                <?php echo $errors->first('power_meter_operational_setting[remote_relay_program_api_url]', '<label for="power_meter_operational_setting[remote_relay_program_api_url]" class="help-block error">:message</label>'); ?>

						</div>
					</div>
				</div>
			</div>

			<div class="row">
				<div class="col-md-12">
					<div class="form-group<?php echo e($errors->has('power_meter_operational_setting[no_reading_alert_percent]') ? ' has-error' : ''); ?>">
						<?php echo Form::label('membership_payment_allow_day', App\Language::trans('Notify If reading received less than [%]'), ['class'=>'control-label col-md-12']); ?>

						<div class="col-md-12">
							<?php echo Form::number('power_meter_operational_setting[no_reading_alert_percent]', (isset($model->id) ? (isset($power_meter_operational_setting['warning_email_interval']) ? $power_meter_operational_setting['warning_email_interval'] : '') : 0), ['class'=>'form-control','min'=>'1','max'=>'999999','autofocus']); ?>

			                <?php echo $errors->first('power_meter_operational_setting[no_reading_alert_percent]', '<label for="power_meter_operational_setting[no_reading_alert_percent]" class="help-block error">:message</label>'); ?>

						</div>
					</div>
				</div>
			</div>


			<div class="row">
				<div class="col-md-12">
				        <div class="form-group <?php echo $errors->first('code') ? 'has-error' : ''; ?>">
				          <label for="code" class="control-label col-sm-2"><?php echo e(App\Language::trans('Notification List')); ?></label>
					          <div class="col-sm-12">
					           <?php echo Form::select("power_meter_mobile_app_msg[no_reading_notification_list][]", App\User::combobox_email_vs_email(), isset($power_meter_mobile_app_msg['no_reading_notification_list']) ? ( strlen($power_meter_mobile_app_msg['no_reading_notification_list']) >  1 ? json_decode($power_meter_mobile_app_msg['no_reading_notification_list'] ,true):null ) : null , array(  "style"=>"width: 100%;","class"=>"form-control 3col active","id"=>"power_meter_mobile_app_msg[no_reading_notification_list][]","multiple"=>"multiple")); ?>

					          </div>
				        </div>
			    </div>
			</div>


			<div class="row">

				<div class="col-md-12">
						<div class="form-group<?php echo e($errors->has('server_available_check_period_mins') ? ' has-error' : ''); ?>">
							<?php echo Form::label('server_available_check_period_mins', App\Language::trans('Server Availability Check Period [mins]'), ['class'=>'control-label col-md-12']); ?>

							<div class="col-md-12">
							<?php echo Form::number('power_meter_operational_setting[server_available_check_period_mins]', (isset($model->id) ? (isset($power_meter_operational_setting['warning_email_interval']) ? $power_meter_operational_setting['warning_email_interval'] : '') : 0), ['class'=>'form-control','min'=>'5','max'=>'999999','autofocus']); ?>

			                <?php echo $errors->first('power_meter_operational_setting[server_available_check_period_mins]', '<label for="power_meter_operational_setting[server_available_check_period_mins]" class="help-block error">:message</label>'); ?>

						</div>
						</div>
					</div>

				<div class="col-md-12">
				        <div class="form-group <?php echo $errors->first('code') ? 'has-error' : ''; ?>">
				          <label for="code" class="control-label col-sm-12"><?php echo e(App\Language::trans('Remote Server Break Down Notification List')); ?></label>
					          <div class="col-sm-12">
					           <?php echo Form::select("power_meter_mobile_app_msg[remote_server_break_down_notification_list][]", App\User::combobox_email_vs_email(), isset($power_meter_mobile_app_msg['remote_server_break_down_notification_list']) ? ( strlen($power_meter_mobile_app_msg['remote_server_break_down_notification_list']) >  1 ? json_decode($power_meter_mobile_app_msg['remote_server_break_down_notification_list'] ,true):null ) : null , array(  "style"=>"width: 100%;","class"=>"form-control 3col active","id"=>"power_meter_mobile_app_msg[remote_server_break_down_notification_list][]","multiple"=>"multiple")); ?>

					          </div>
				        </div>
			    </div>
			</div>

			<hr>

			 <!-- Nav tabs -->
			<ul class="nav nav-light nav-tabs bo" role="tablist">
			
				<?php $__currentLoopData = $language_listing; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $language): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

					<li role="presentation" class="nav-item">
						<a href="#<?php echo e($language); ?>_operational" aria-controls="<?php echo e($language); ?>_operational" class="d-flex align-items-center nav-link<?php echo e($tab_status); ?> fs-16 zero-padding" role="tab" data-toggle="tab"><h5><?php echo e(App\Language::trans(ucfirst($language))); ?></h5></a>
					</li>
					<?php $tab_status =''; ?>
				<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>


			</ul>
			<hr>


			<!-- Tab panes -->
			<?php $tab_status = ' active'; ?>
			<div class="tab-content">			
					<?php $__currentLoopData = $language_listing; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $language): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
					    <div role="tabpanel" class="tab-pane<?php echo e($tab_status); ?>" id="<?php echo e($language); ?>_operational">
					    		<h6 class="hk-sec-title mt-15"><?php echo e(App\Language::trans('Mobile Apps Message Setting')); ?></h6> <hr>	
					    	    <div class="row">
										 <div class="col-md-12">
									        	<div class="form-group<?php echo e($errors->has('power_meter_mobile_app_msg[no_reading_notification_email]['.$language.']') ? ' has-error' : ''); ?>">
													<?php echo Form::label('power_meter_mobile_app_msg[no_reading_notification_email]['.$language.']', App\Language::trans('No Reading Notification Email'), ['class'=>'control-label col-md-12']); ?>

													<div class="col-md-12">
														<?php echo Form::textarea('power_meter_mobile_app_msg[no_reading_notification_email]['.$language.']', (isset($no_reading_notification_email[$language]) ? $no_reading_notification_email[$language] : null), ['id'=>'power_meter_mobile_app_msg[no_reading_notification_email]['.$language.']', 'rows'=>'10' , 'cols'=>'80','class'=>'form-control tinymce']); ?>

									                    <?php echo $errors->first('power_meter_mobile_app_msg[no_reading_notification_email]['.$language.']', '<label for="power_meter_mobile_app_msg[no_reading_notification_email]['.$language.']" class="help-block error">:message</label>'); ?>

													</div>
												</div>
									       </div>
								   </div>
							<!-- End Tab Panel -->	
						</div>
						<?php $tab_status =''; ?>
					<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
				</div>
			</div>



		 <div role="tabpanel" class="tab-pane" id="pm_uat_setting">

		 			<div class="row">
							<div class="col-md-6">
								<div class="form-group<?php echo e($errors->has('power_meter_operational_setting[is_auto_turn_off_meter]') ? ' has-error' : ''); ?>">
									<?php echo Form::label('power_meter_operational_setting[is_auto_turn_off_meter]', App\Language::trans('Is In UAT'), ['class'=>'control-label col-md-12']); ?>

									<div class="col-md-12">
										 <div class="row">	
										 	<div class="col-md-3">
											    <div class="custom-control custom-radio">
											        <input type="radio" value=1 <?php echo e(isset($power_meter_operational_setting['is_in_uat']) == true ? ($power_meter_operational_setting['is_in_uat'] == true ? 'checked' : '') : 'checked'); ?>  value=1 id="power_meter_operational_setting[is_in_uat]_on" name="power_meter_operational_setting[is_in_uat]" checked class="custom-control-input">
											        <label class="custom-control-label" for="power_meter_operational_setting[is_in_uat]_on"><?php echo e(App\Language::trans('Yes')); ?></label>
											    </div>
											</div>
											<div class="col-md-3">
											    <div class="custom-control custom-radio">
											        <input type="radio" value=0 <?php echo e(isset($power_meter_operational_setting['is_in_uat']) == true ? ($power_meter_operational_setting['is_in_uat'] == false ? 'checked' : '') : ''); ?> id="power_meter_operational_setting[is_in_uat]_off" name="power_meter_operational_setting[is_in_uat]"  class="custom-control-input">
											        <label class="custom-control-label" for="power_meter_operational_setting[is_in_uat]_off"><?php echo e(App\Language::trans('No')); ?></label>
											    </div>
											</div>
										 </div>
										 <?php echo $errors->first('power_meter_operational_setting[is_in_uat]', '<label for="power_meter_operational_setting[is_in_uat]" class="help-block error">:message</label>'); ?>

									</div>
								</div>
							</div>
						</div>

						<div class="row">
							<div class="col-md-6">
			                		<div class="form-group<?php echo e($errors->has('power_meter_operational_setting[testing_payment_gateway]') ? ' has-error' : ''); ?>">
			                            <?php echo Form::label('power_meter_operational_setting[testing_payment_gateway]', App\Language::trans('Testing Payment Gateway'), ['class'=>'control-label col-md-12']); ?>

			                            <div class="col-md-8">
			                            	<?php echo Form::select('power_meter_operational_setting[testing_payment_gateway]', App\Setting::payment_gateway_combobox(), isset($power_meter_operational_setting['testing_payment_gateway']) ? $power_meter_operational_setting['testing_payment_gateway'] : null, ['class'=>'form-control','required']); ?>

			                            	<?php echo $errors->first('power_meter_operational_setting[testing_payment_gateway]', '<label for="power_meter_operational_setting[testing_payment_gateway]" class="help-block error">:message</label>'); ?>

			                        	</div>
			                        </div>
			                </div>
			            </div>



					<?php  

							$power_meter_operational_setting['uat_tester_list'] = isset($power_meter_operational_setting['uat_tester_list']) ? json_encode($power_meter_operational_setting['uat_tester_list']) : '' ;
					?>
				 	<div class="row">
						<div class="col-md-12">
						        <div class="form-group <?php echo $errors->first('code') ? 'has-error' : ''; ?>">
						          <label for="code" class="control-label col-sm-2"><?php echo e(App\Language::trans('UAT Tester')); ?></label>
							          <div class="col-sm-12">
							           <?php echo Form::select("power_meter_operational_setting[uat_tester_list][]", App\PowerMeterModel\CustomerPowerUsageSummary::combobox(), isset($power_meter_operational_setting['uat_tester_list']) ? ( strlen($power_meter_operational_setting['uat_tester_list']) >  1 ? json_decode($power_meter_operational_setting['uat_tester_list'] ,true):null ) : null , array(  "style"=>"width: 100%;","class"=>"form-control 3col active","id"=>"power_meter_operational_setting[uat_tester_list][]","multiple"=>"multiple")); ?>

							          </div>
						        </div>
					    </div>
					</div>
		 </div>
		 <div role="tabpanel" class="tab-pane" id="pm_onoff_setting">

			<?php  
			//MOICW - auto_on_off
			$power_meter_operational_setting['exclude_list'] = isset($power_meter_operational_setting['exclude_list']) ? json_encode($power_meter_operational_setting['exclude_list']) : '' ;
			?>
			<div class="row">
				<div class="col-md-12">
						<div class="form-group <?php echo $errors->first('code') ? 'has-error' : ''; ?>">
							<label for="code" class="control-label col-sm-2"><?php echo e(App\Language::trans('Exclude List')); ?></label>
								<div class="col-sm-12">
								<?php echo Form::select("power_meter_operational_setting[exclude_list][]", App\PowerMeterModel\MeterRegister::house_rooms_combobox(), isset($power_meter_operational_setting['exclude_list']) ? ( strlen($power_meter_operational_setting['exclude_list']) >  1 ? json_decode($power_meter_operational_setting['exclude_list'] ,true):null ) : null , array(  "style"=>"width: 100%;","class"=>"form-control 3col active","id"=>"power_meter_operational_setting[exclude_list][]","multiple"=>"multiple")); ?>

								</div>
						</div>
				</div>
			</div>
		</div> 


	</div><!-- End of Power Management Tab -->


			
			<!-- <h6 class="hk-sec-title"><?php echo e(App\Language::trans('Refund Process Setting')); ?></h6> <hr>
		    	 
	    	 <div class="row">
					<div class="col-md-6">
						<div class="form-group<?php echo e($errors->has('selected_module') ? ' has-error' : ''); ?>">
							<?php echo Form::label('selected_module', App\Language::trans('Refund Report To'), ['class'=>'control-label col-md-12']); ?>

							<div class="col-md-8">
								 <?php echo Form::select("tester_id[]", App\Customer::combobox_from_leaf(), strlen($model->tester_id) >  1 ? json_decode($model->tester_id,true):null, array("style"=>"width: 100%;", "multiple class"=>"chosen-select","class"=>"form-control select2","id"=>"tester_id","multiple"=>true)); ?>

		                        <?php echo $errors->first('selected_module', '<label for="country_id" class="help-block error">:message</label>'); ?>

							</div>
						</div>
					</div>
			</div>
 -->
 			
	
			<!-- End Tab Panel -->	       
		</div>

		<div role="tabpanel" class="tab-pane" id="club_house">
			<h6 class="hk-sec-title"><?php echo e(App\Language::trans('Membership Setting')); ?></h6> <hr>
			<div class="row">
			 <div class="col-md-6">
						<div class="form-group<?php echo e($errors->has('is_period_item_to_monthly_break_down') ? ' has-error' : ''); ?>">
							<?php echo Form::label('is_period_item_to_monthly_break_down', App\Language::trans('Invoice Generated By Periodic Item'), ['class'=>'control-label col-md-12']); ?>			
							<div class="col-md-12">
								 <div class="row">	
								 	<div class="col-md-3">
									    <div class="custom-control custom-radio">
									        <input type="radio" value=1 <?php echo e(isset($model->is_period_item_to_monthly_break_down) == true ? ($model->is_period_item_to_monthly_break_down == true ? 'checked' : '') : 'checked'); ?>  value=1 id="is_period_item_to_monthly_break_downon" name="is_pay_by_accumulate" checked class="custom-control-input">
									        <label class="custom-control-label" for="is_period_item_to_monthly_break_downon"><?php echo e(App\Language::trans('Per Item')); ?></label>
									    </div>
									</div>
									<div class="col-md-3">
									    <div class="custom-control custom-radio">
									        <input type="radio" value=0 <?php echo e(isset($model->is_period_item_to_monthly_break_down) == true ? ($model->is_period_item_to_monthly_break_down == false ? 'checked' : '') : ''); ?> id="is_period_item_to_monthly_break_downoff" name="is_pay_by_accumulate"  class="custom-control-input">
									        <label class="custom-control-label" for="is_period_item_to_monthly_break_downoff"><?php echo e(App\Language::trans('Monthly')); ?></label>
									    </div>
									</div>
								 </div>
								 <?php echo $errors->first('is_inclusive', '<label for="is_period_item_to_monthly_break_down" class="help-block error">:message</label>'); ?>

							</div>
						</div>
					</div>
			 </div>

	    	 <div class="row">
					<div class="col-md-6">
						<div class="form-group<?php echo e($errors->has('is_direct_allow_to_payment') ? ' has-error' : ''); ?>">
							<?php echo Form::label('is_direct_allow_to_payment', App\Language::trans('Membership Verification Process?'), ['class'=>'control-label col-md-12']); ?>

							<div class="col-md-12">
								 <div class="row">	
								 	<div class="col-md-3">
									    <div class="custom-control custom-radio">
									        <input type="radio" value=1 <?php echo e(isset($model->is_direct_allow_to_payment) == true ? ($model->is_direct_allow_to_payment == true ? 'checked' : '') : 'checked'); ?>  value=1 id="is_direct_allow_to_payment_on" name="is_direct_allow_to_payment" checked class="custom-control-input">
									        <label class="custom-control-label" for="is_direct_allow_to_payment_on"><?php echo e(App\Language::trans('Enabled')); ?></label>
									    </div>
									</div>
									<div class="col-md-3">
									    <div class="custom-control custom-radio">
									        <input type="radio" value=0 <?php echo e(isset($model->is_direct_allow_to_payment) == true ? ($model->is_direct_allow_to_payment == false ? 'checked' : '') : ''); ?>  value=1 <?php echo e(isset($model->is_direct_allow_to_payment) == true ? ($model->is_direct_allow_to_payment == true ? 'checked' : '') : 'checked'); ?>  id="is_direct_allow_to_payment_off" name="is_direct_allow_to_payment"  class="custom-control-input">
									        <label class="custom-control-label" for="is_direct_allow_to_payment_off"><?php echo e(App\Language::trans('Disabled')); ?></label>
									    </div>
									</div>
								 </div>
								 <?php echo $errors->first('is_direct_allow_to_payment', '<label for="is_direct_allow_to_payment" class="help-block error">:message</label>'); ?>

							</div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group<?php echo e($errors->has('membership_payment_allow_day') ? ' has-error' : ''); ?>">
							<?php echo Form::label('membership_payment_allow_day', App\Language::trans('Membership Renewal Period'), ['class'=>'control-label col-md-12']); ?>

							<div class="col-md-8">
								<?php echo Form::number('membership_payment_allow_day', null, ['class'=>'form-control','step'=>'.01','min'=>'0']); ?>

		                        <?php echo $errors->first('membership_payment_allow_day', '<label for="membership_payment_allow_day" class="help-block error">:message</label>'); ?>

							</div>
						</div>
					</div>
				</div>
		

			<h6 class="hk-sec-title"><?php echo e(App\Language::trans('Accounting System Integration')); ?></h6> <hr>
			<div class="row">
				<div class="col-md-6">
					<div class="form-group<?php echo e($errors->has('integrated_accounting_sytem') ? ' has-error' : ''); ?>">
						<?php echo Form::label('integrated_accounting_sytem', App\Language::trans('Integrate To'), ['class'=>'control-label col-md-12']); ?>

						<div class="col-md-8">
							<?php echo Form::select('integrated_accounting_sytem[]', App\Setting::integrated_accounting_system_combobox(), strlen($model->integrated_accounting_sytem) >  1 ? json_decode($model->integrated_accounting_sytem,true):null, ['class'=>'form-control select2 chosen-select integrated_accounting_sytem', "multiple"=>true, "id"=>"integrated_accounting_sytem",'onchange'=>'init_integrated_accounting_system_component(this)']); ?>

	                        <?php echo $errors->first('integrated_accounting_sytem', '<label for="country_id" class="help-block error">:message</label>'); ?>

						</div>
					</div>
				</div>

				<div class="col-md-6">
					<div class="form-group<?php echo e($errors->has('accounting_ncl_id') ? ' has-error' : ''); ?>">
						<?php echo Form::label('accounting_ncl_id', App\Language::trans('NCL ID'), ['class'=>'control-label col-md-12']); ?>

						<div class="col-md-8">
							<?php echo Form::text('accounting_ncl_id', null, ['class'=>'form-control']); ?>

	                        <?php echo $errors->first('accounting_ncl_id', '<label for="accounting_ncl_id" class="help-block error">:message</label>'); ?>

						</div>
					</div>
				</div>
			</div>

			<div class="row">
				<div class="col-md-6">
					<div class="form-group<?php echo e($errors->has('accounting_winz_id') ? ' has-error' : ''); ?>">
						<?php echo Form::label('accounting_winz_id', App\Language::trans('Winz Net ID'), ['class'=>'control-label col-md-12']); ?>

						<div class="col-md-8">
							<?php echo Form::text('accounting_winz_id', null, ['class'=>'form-control']); ?>

	                        <?php echo $errors->first('accounting_winz_id', '<label for="accounting_winz_id" class="help-block error">:message</label>'); ?>

						</div>
					</div>
				</div>
			</div>

			<h6 class="hk-sec-title"><?php echo e(App\Language::trans('Default Setting')); ?></h6> <hr>
			<div class="row">
				<div class="col-md-6">
                		<div class="form-group<?php echo e($errors->has('bank_account') ? ' has-error' : ''); ?>">
                            <?php echo Form::label('bank_account', App\Language::trans('Bank Account'), ['class'=>'control-label col-md-12']); ?>

                            <div class="col-md-8">
                            	<?php echo Form::select('bank_account', App\Setting::bank_or_cash_combobox(), null, ['class'=>'form-control']); ?>

                            	<?php echo $errors->first('bank_account', '<label for="bank_account" class="help-block error">:message</label>'); ?>

                        	</div>
                        </div>
                </div>
            </div>

			<div class="row">
				<div class="col-md-6">					
						<div class="form-group<?php echo e($errors->has('currency_id') ? ' has-error' : ''); ?>">
                            <?php echo Form::label('currency_id', App\Language::trans('Currency'), ['class'=>'control-label col-md-12','required']); ?>

                            <div class="col-md-8">
                            	<?php echo Form::select('currency_id', App\Currency::combobox(), null, ['class'=>'form-control','onchange'=>'init_currency_rate(this)']); ?>

                            	<?php echo $errors->first('currency_id', '<label for="currency_id" class="help-block error">:message</label>'); ?>

                        	</div>
                        </div>
                </div>
                <!-- ,'required' -->

                <!-- ,'required' -->
                <div class="col-md-6">
                		<div class="form-group<?php echo e($errors->has('payment_term_id') ? ' has-error' : ''); ?>">
                            <?php echo Form::label('payment_term_id', App\Language::trans('Payment Term'), ['class'=>'control-label col-md-12']); ?>

                            <div class="col-md-8">
                            	<?php echo Form::select('payment_term_id', App\PaymentTerm::combobox(), null, ['class'=>'form-control']); ?>

                            	<?php echo $errors->first('payment_term_id', '<label for="payment_term_id" class="help-block error">:message</label>'); ?>

                        	</div>
                        </div>
                </div>
            </div>

			<!-- End Tab Panel -->	 	   
		</div>

		<div role="tabpanel" class="tab-pane" id="leaf_accounting">		
			<h6 class="hk-sec-title"><?php echo e(App\Language::trans('API Setting')); ?></h6> <hr>
	    	 <div class="row">
					<div class="col-md-6">
						<div class="form-group<?php echo e($errors->has('is_on_accounting_api') ? ' has-error' : ''); ?>">
							<?php echo Form::label('is_on_accounting_api', App\Language::trans('Status'), ['class'=>'control-label col-md-12']); ?>

							<div class="col-md-12">
								 <div class="row">	
								 	<div class="col-md-3">
									    <div class="custom-control custom-radio">
									        <input type="radio" value=1 <?php echo e(isset($model->is_on_accounting_api) == true ? ($model->is_on_accounting_api == true ? 'checked' : '') : 'checked'); ?>  value=1 id="is_on_accounting_api_on" name="is_on_accounting_api" checked class="custom-control-input">
									        <label class="custom-control-label" for="is_on_accounting_api_on"><?php echo e(App\Language::trans('Enabled')); ?></label>
									    </div>
									</div>
									<div class="col-md-3">
									    <div class="custom-control custom-radio">
									        <input type="radio" value=0 <?php echo e(isset($model->is_on_accounting_api) == true ? ($model->is_on_accounting_api == false ? 'checked' : '') : ''); ?>  id="is_on_accounting_api_off" name="is_on_accounting_api"  class="custom-control-input">
									        <label class="custom-control-label" for="is_on_accounting_api_off"><?php echo e(App\Language::trans('Disabled')); ?></label>
									    </div>
									</div>
								 </div>
								 <?php echo $errors->first('is_on_accounting_api', '<label for="is_on_accounting_api" class="help-block error">:message</label>'); ?>

							</div>
						</div>
					</div>	
				</div>

				<div class="row">
					<div class="col-md-6">
						<div class="form-group<?php echo e($errors->has('monthly_cut_off_day') ? ' has-error' : ''); ?>">
							<?php echo Form::label('monthly_cut_off_day', App\Language::trans('API Key'), ['class'=>'control-label col-md-12']); ?>

							<div class="col-md-8">
								<?php echo Form::text('monthly_cut_off_day', null, ['class'=>'form-control','min'=>'1','max'=>'31','autofocus']); ?>

				                <?php echo $errors->first('monthly_cut_off_day', '<label for="monthly_cut_off_day" class="help-block error">:message</label>'); ?>

							</div>
						</div>
					</div>
				</div>
			<!-- End Tab Panel -->	       
		</div>

	</div>
</div>
</section>
<?php echo $__env->make('_version_02.commons.layouts.partials._form_floaring_footer_standard', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>



<?php echo Form::close(); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
init_floating_footer();
//init_date_date_picker_new_ui_by_id("input[name=system_live_date]");

<?php
	$min = 0;
	$max = 1000;
	if($model->id){
		$power_meter_operational_setting = (array) json_decode($model->power_meter_operational_setting);
		$from = isset($power_meter_operational_setting['top_up_min_amount']) ? $power_meter_operational_setting['top_up_min_amount'] : $min;
		$to = isset($power_meter_operational_setting['top_up_max_amount']) ? $power_meter_operational_setting['top_up_max_amount'] : $max;		
	}



?>
$(document).ready(function(){

	$(".power_meter_top_up_range").ionRangeSlider({
		type: "double",
		min:  <?php echo e($min); ?> ,
		max:  <?php echo e($max); ?> ,
		from : <?php echo e($from); ?>,
		to : <?php echo e($to); ?> ,
		step: 0.01,
		grid: true,
	
	});

	init_auto_on_off_section();
});

function init_auto_on_off_section()
{
	cValue = document.querySelector('input[name="is_auto_on_off"]:checked').value;
	if(cValue ==1 )
	{
		$('[id^=auto_module_div]').show();
	}else{
		$('[id^=auto_module_div]').hide();
	}
}

<?php if(!$model->is_min_credit): ?>
	$("input[name=min_credit]").closest(".form-group").hide();
<?php endif; ?>
$("#is_min_credit_on").on("click", function(){
	$("input[name=min_credit]").closest(".form-group").show("slow");
})
$("#is_min_credit_off").on("click", function(){
	$("input[name=min_credit]").closest(".form-group").hide("slow");
})

<?php if(!$model->membership_payment_allow_day): ?>
	$("input[name=membership_payment_allow_day]").closest(".form-group").hide();
<?php endif; ?>
$("#is_direct_allow_to_payment_off").on("click", function(){
	$("input[name=membership_payment_allow_day]").closest(".form-group").show("slow");
})
$("#is_direct_allow_to_payment_on").on("click", function(){
	$("input[name=membership_payment_allow_day]").closest(".form-group").hide("slow");
})

<?php if(!$model->is_transaction_charge): ?>
	$("input[name=transaction_percent]").closest(".form-group").hide();
<?php endif; ?>
$("#is_transaction_charge_on").on("click", function(){
	$("input[name=transaction_percent]").closest(".form-group").show("slow");
})
$("#is_transaction_charge_off").on("click", function(){
	$("input[name=transaction_percent]").closest(".form-group").hide("slow");
})

init_select2($("select[name=selected_module]"));
<?php $__env->stopSection(); ?>
<?php echo $__env->make('_version_02.commons.layouts.admin', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>