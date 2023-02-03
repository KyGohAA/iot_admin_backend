<?php $__env->startSection('content'); ?>
<?php echo Form::model($model, ['class'=>'form-horizontal']); ?>

<?php echo $__env->make('_version_02.commons.layouts.partials._alert', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<section class="hk-sec-wrapper">
    <h5 class="hk-sec-title"><?php echo e(App\Language::trans('Information')); ?></h5><hr>
   
	<div class="row">
	   	<div class="col-md-6">
			<div class="form-group<?php echo e($errors->has('status') ? ' has-error' : ''); ?>">
				<?php echo Form::label('status', App\Language::trans('Status'), ['class'=>'control-label col-md-4']); ?>

				<div class="col-md-12">
					 <div class="row">	
					 	<div class="col-md-3">
						    <div class="custom-control custom-radio">
						        <input type="radio" id="status_on" name="status" checked class="custom-control-input">
						        <label class="custom-control-label" for="status_on"><?php echo e(App\ExtendModel::status_true_word()); ?></label>
						    </div>
						</div>
						<div class="col-md-3">
						    <div class="custom-control custom-radio">
						        <input type="radio" id="status_off" name="status"  class="custom-control-input">
						        <label class="custom-control-label" for="status_off"><?php echo e(App\ExtendModel::status_false_word()); ?></label>
						    </div>
						</div>
					 </div>
					 <?php echo $errors->first('status', '<label for="status" class="help-block error">:message</label>'); ?>

				</div>
			</div>
		</div>
	</div>

	<!-- <div class="row">
	   	<div class="col-md-6">
			<div class="form-group<?php echo e($errors->has('is_sudsidy_distribute_directly') ? ' has-error' : ''); ?>">
				<?php echo Form::label('is_sudsidy_distribute_directly', App\Language::trans('Is Distribute Complementary Directly For First Month ?'), ['class'=>'control-label col-md-12']); ?>

				<div class="col-md-12">
					 <div class="row">	
					 	<div class="col-md-3">
						    <div class="custom-control custom-radio">
						        <input type="radio" id="is_sudsidy_distribute_directly_on" name="is_sudsidy_distribute_directly" checked class="custom-control-input">
						        <label class="custom-control-label" for="is_sudsidy_distribute_directly_on"><?php echo e(App\ExtendModel::status_true_word()); ?></label>
						    </div>
						</div>
						<div class="col-md-3">
						    <div class="custom-control custom-radio">
						        <input type="radio" id="is_sudsidy_distribute_directly_off" name="is_sudsidy_distribute_directly"  class="custom-control-input">
						        <label class="custom-control-label" for="is_sudsidy_distribute_directly_off"><?php echo e(App\ExtendModel::status_false_word()); ?></label>
						    </div>
						</div>
					 </div>
					 <?php echo $errors->first('is_sudsidy_distribute_directly', '<label for="is_sudsidy_distribute_directly" class="help-block error">:message</label>'); ?>

				</div>
			</div>
		</div>
	</div> -->

    <div class="row">
			<div class="col-md-6">
				<div class="form-group<?php echo e($errors->has('code') ? ' has-error' : ''); ?>">
					<?php echo Form::label('code', App\Language::trans('Code'), ['class'=>'control-label col-md-4']); ?>

					<div class="col-md-8">
						<?php echo Form::text('code', null, ['class'=>'form-control']); ?>

                        <?php echo $errors->first('code', '<label for="code" class="help-block error">:message</label>'); ?>

					</div>
				</div>
			</div>

			<div class="col-md-6">
				<div class="form-group<?php echo e($errors->has('name') ? ' has-error' : ''); ?>">
					<?php echo Form::label('text', App\Language::trans('Name'), ['class'=>'control-label col-md-4']); ?>

					<div class="col-md-8">
						<?php echo Form::text('name', null, ['class'=>'form-control']); ?>

                        <?php echo $errors->first('name', '<label for="name" class="help-block error">:message</label>'); ?>

					</div>
				</div>
			</div>
	</div>


		<div class="row">
			<div class="col-md-6">
				<div class="form-group<?php echo e($errors->has('amount') ? ' has-error' : ''); ?>">
					<?php echo Form::label('amount', App\Language::trans('Amount'), ['class'=>'control-label col-md-4']); ?>

					<div class="col-md-8">
						<?php echo Form::number('amount', null, ['min'=>1,'max'=>9999,'step'=>'0.01','class'=>'form-control']); ?>

                        <?php echo $errors->first('amount', '<label for="amount" class="help-block error">:message</label>'); ?>

					</div>
				</div>
			</div>

			<div class="col-md-6">
				<div class="form-group<?php echo e($errors->has('implementation_date') ? ' has-error' : ''); ?>">
					<?php echo Form::label('implementation_date', App\Language::trans('Implementation Date'), ['class'=>'control-label col-md-4']); ?>

					<div class="col-md-8">
						<?php echo Form::select('implementation_date', App\Setting::select_days_combobox(),null, ['class'=>'form-control']); ?>

                        <?php echo $errors->first('implementation_date', '<label for="month_ended" class="help-block error">:message</label>'); ?>

					</div>
				</div>
			</div>
		</div>

		
		<div class="row">
			<div class="col-md-6">
				<div class="form-group<?php echo e($errors->has('starting_date') ? ' has-error' : ''); ?>">
					<?php echo Form::label('starting_date', App\Language::trans('From Month'), ['class'=>'control-label col-md-4']); ?>

					<div class="col-md-8">
						<?php echo Form::select('starting_date', App\PowerMeterModel\MeterInvoice::previous_one_year_combobox(), null, ['class'=>'form-control','autofocus']); ?>

                        <?php echo $errors->first('starting_date', '<label for="starting_date" class="help-block error">:message</label>'); ?>

					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group<?php echo e($errors->has('month_ended') ? ' has-error' : ''); ?>">
					<?php echo Form::label('ending_date', App\Language::trans('To Month'), ['class'=>'control-label col-md-4']); ?>

					<div class="col-md-8">
						<?php echo Form::select('ending_date', App\PowerMeterModel\MeterInvoice::next_one_year_combobox(), null, ['class'=>'form-control']); ?>

                        <?php echo $errors->first('ending_date', '<label for="ending_date" class="help-block error">:message</label>'); ?>

					</div>
				</div>
			</div>
		</div>

		
		
		<h5 class="hk-sec-title mt-20"><?php echo e(App\Language::trans('Beneficial Selection')); ?></h5><hr>

		<div class="row">
			<div class="col-md-12">
				<div class="form-group<?php echo e($errors->has('room_type') ? ' has-error' : ''); ?>">
					<?php echo Form::label('room_type', App\Language::trans('Room Type'), ['class'=>'control-label col-md-4']); ?>

					<div class="col-md-8">
						<?php echo Form::select('room_type', App\Setting::room_type_combobox(), null, ['class'=>'form-control','autofocus','onchange'=>'init_room_type_subsidize_handle(this)']); ?>

                        <?php echo $errors->first('room_type', '<label for="room_type" class="help-block error">:message</label>'); ?>

					</div>
				</div>
			</div>	
		</div>


	<!-- Plugin: Dual Select List -->
	<?php 
		$ids  = strlen($model->subsidize_tenant_id) >  1 ? json_decode($model->subsidize_tenant_id,true):null;
		
	?>
    <div class="row">
		<div class="col-md-12">
		        <div class="form-group <?php echo $errors->first('code') ? 'has-error' : ''; ?>">
		          <label for="code" class="control-label col-md-12"><?php echo e(App\Language::trans('Subsidize Tenant List')); ?></label>
			          <div class="col-sm-10" id="single_room_div">
			           <?php echo Form::select("subsidize_tenant_id[]", App\Customer::combobox_from_leaf_by_room_type_member_id('single'), $ids,  ["style"=>"width: 100%;","class"=>"form-control 3col active","id"=>"subsidize_tenant_id_single","multiple"=>"multiple" ]); ?>

			          </div>

			           <div class="col-md-10 hide" id="twin_room_div">
			           <?php echo Form::select("subsidize_tenant_id[]", App\Customer::combobox_from_leaf_by_room_type_member_id('twin'), $ids, ["style"=>"width: 100%;","class"=>"form-control 3col active","id"=>"subsidize_tenant_id_twin","multiple"=>"multiple" ]); ?>

			           </div>
		        </div>
	    </div>
	</div>        

	<div class="row">       
		<div class="col-md-12">
			<div class="form-group<?php echo e($errors->has('remark') ? ' has-error' : ''); ?>">
				<?php echo Form::label('remark', App\Language::trans('Remark'), ['class'=>'control-label col-md-12']); ?>

				<div class="col-md-12">
					<?php echo Form::text('remark', null, ['class'=>'form-control']); ?>

                    <?php echo $errors->first('remark', '<label for="remark" class="help-block error">:message</label>'); ?>

				</div>
			</div>
		</div>


    </div>  
</section>
<?php echo $__env->make('_version_02.commons.layouts.partials._form_floaring_footer_standard', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>


<?php echo Form::close(); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>


$(".input-daterange").datepicker({
	format: "dd-mm-yyyy",
});

$('select[multiple]').multiselect({

  columns: 1,     // how many columns should be use to show options
  search : false, // include option search box

  // plugin texts
  texts: {
      placeholder    : 'Select options', // text to use in dummy input
      search         : 'Search',         // search input placeholder text
      selectedOptions: ' selected',      // selected suffix text
      selectAll      : 'Select all',     // select all text
      unselectAll    : 'Unselect all',   // unselect all text
      noneSelected   : 'None Selected'   // None selected text
  },

  // general options
  selectAll          : true, // add select all option
  selectGroup        : true, // select entire optgroup
  minHeight          : 200,   // minimum height of option overlay
  maxHeight          : null,  // maximum height of option overlay
  maxWidth           : null,  // maximum width of option overlay (or selector)
  maxPlaceholderWidth: null, // maximum width of placeholder button
  maxPlaceholderOpts : 10, // maximum number of placeholder options to show until "# selected" shown instead
  showCheckbox       : true,  // display the checkbox to the user
  optionAttributes   : [],  // attributes to copy to the checkbox from the option element

  // @NOTE: these are for future development
  minSelect: false, // minimum number of items that can be selected
  maxSelect: false, // maximum number of items that can be selected

});

<?php $__env->stopSection(); ?>
<?php echo $__env->make('_version_02.commons.layouts.admin', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>