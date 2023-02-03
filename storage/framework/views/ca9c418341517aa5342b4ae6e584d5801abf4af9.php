<div class="modal fade" id="add_nem_member_modal" tabindex="-1" role="dialog" aria-labelledby="add_nem_member_modal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">New Member Application</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            <?php echo Form::model($model, ['class'=>'form-horizontal','id'=>'member-application-form','name'=>'member-application-form',"files"=>true]); ?>

                <div class="form-group<?php echo e($errors->has('name') ? ' has-error' : ''); ?> row">
                    <?php echo Form::label('name', App\Language::trans('Name'), ['class'=>'col-sm-2 col-form-label']); ?>

                    <div class="col-sm-10">
                        <?php echo Form::text('name', null, ['class'=>'form-control']); ?>

                        <?php echo $errors->first('name', '<label for="name" class="help-block error">:message</label>'); ?>

                    </div>
                </div>

                <div class="form-group<?php echo e($errors->has('ic_no') ? ' has-error' : ''); ?> row">
                    <?php echo Form::label('ic_no', App\Language::trans('IC/Passport'), ['class'=>'col-sm-2 col-form-label']); ?>

                    <div class="col-sm-10">
                        <?php echo Form::text('ic', null, ['class'=>'form-control']); ?>

                        <?php echo $errors->first('ic', '<label for="ic_no" class="help-block error">:message</label>'); ?>

                    </div>
                </div>

                 <div class="form-group<?php echo e($errors->has('ic_photo') ? ' has-error' : ''); ?> row">
                    <?php echo Form::label('ic_photo', App\Language::trans('IC/Passport Photo'), ['class'=>'col-sm-2 col-form-label']); ?>

                    <div class="col-sm-10">
                        <?php echo Form::file("ic_photo", array("id"=>"ic_photo","class"=>"form-control")); ?>

                        <?php echo $errors->first('ic_photo', '<label for="ic_no" class="help-block error">:message</label>'); ?>

                    </div>
                </div>

                <div class="form-group<?php echo e($errors->has('email') ? ' has-error' : ''); ?> row">
                    <?php echo Form::label('email', App\Language::trans('Email'), ['class'=>'col-sm-2 col-form-label']); ?>

                    <div class="col-sm-10">
                        <?php echo Form::email('email', null, ['class'=>'form-control']); ?>

                        <?php echo $errors->first('email', '<label for="email" class="help-block error">:message</label>'); ?>

                    </div>
                </div>

                <div class="form-group<?php echo e($errors->has('dob') ? ' has-error' : ''); ?> row">
                    <?php echo Form::label('dob', App\Language::trans('Date Of Birth'), ['class'=>'col-sm-2 col-form-label']); ?>

                    <div class="col-sm-10">
                        <?php echo Form::text('dob', null, ['class'=>'form-control']); ?>

                        <?php echo $errors->first('dob', '<label for="email" class="help-block error">:message</label>'); ?>

                    </div>
                </div>

                <div class="form-group<?php echo e($errors->has('phonenumber') ? ' has-error' : ''); ?> row">
                    <?php echo Form::label('phonenumber', App\Language::trans('Mobile No.'), ['class'=>'col-sm-2 col-form-label']); ?>

                    <div class="col-sm-10">
                        <?php echo Form::text('phonenumber', null, ['class'=>'form-control']); ?>

                        <?php echo $errors->first('phonenumber', '<label for="phonenumber" class="help-block error">:message</label>'); ?>

                    </div>
                </div>

                <div class="form-group<?php echo e($errors->has('home_phonenumber') ? ' has-error' : ''); ?> row">
                    <?php echo Form::label('home_phonenumber', App\Language::trans('Home Phone No.'), ['class'=>'col-sm-2 col-form-label']); ?>

                    <div class="col-sm-10">
                        <?php echo Form::text('home_phonenumber', null, ['class'=>'form-control']); ?>

                        <?php echo $errors->first('home_phonenumber', '<label for="home_phonenumber" class="help-block error">:message</label>'); ?>

                    </div>
                </div>

                <div class="form-group<?php echo e($errors->has('office_phonenumber') ? ' has-error' : ''); ?> row">
                    <?php echo Form::label('office_phonenumber', App\Language::trans('Office Phone No.'), ['class'=>'col-sm-2 col-form-label']); ?>

                    <div class="col-sm-10">
                        <?php echo Form::text('office_phonenumber', null, ['class'=>'form-control']); ?>

                        <?php echo $errors->first('office_phonenumber', '<label for="office_phonenumber" class="help-block error">:message</label>'); ?>

                    </div>
                </div>

                <div class="form-group<?php echo e($errors->has('address') ? ' has-error' : ''); ?> row">
                    <?php echo Form::label('address', App\Language::trans('Address'), ['class'=>'col-sm-2 col-form-label']); ?>

                    <div class="col-sm-10">
                        <?php echo Form::text('address', null, ['class'=>'form-control']); ?>

                        <?php echo $errors->first('address', '<label for="email" class="help-block error">:message</label>'); ?>

                    </div>
                </div>

                <div class="form-group<?php echo e($errors->has('nationality') ? ' has-error' : ''); ?> row">
                    <?php echo Form::label('nationality', App\Language::trans('Nationality'), ['class'=>'col-sm-2 col-form-label']); ?>

                    <div class="col-sm-10">
                        <?php echo Form::text('nationality', null, ['class'=>'form-control']); ?>

                        <?php echo $errors->first('nationality', '<label for="nationality" class="help-block error">:message</label>'); ?>

                    </div>
                </div>



                <div class="form-group<?php echo e($errors->has('gender') ? ' has-error' : ''); ?> row">
                    <?php echo Form::label('gender', App\Language::trans('Gender'), ['class'=>'col-sm-2 col-form-label']); ?>

                    <div class="row">
                       
                            <div class="col-sm-6">
                                <div class="custom-control custom-radio">
                                    <input type="radio" id="gender_on" name="gender" checked class="custom-control-input">
                                    <label class="custom-control-label" for="gender_on">Male</label>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="custom-control custom-radio">
                                    <input type="radio" id="gender_off" name="gender"  class="custom-control-input">
                                    <label class="custom-control-label" for="gender_off">Female</label>
                                </div>
                            </div>
                         </div>
                  
                </div>

                 <h5 class="hk-sec-title"><?php echo e(App\Language::trans('Other Documents')); ?></h5><hr>

                 <div class="form-group<?php echo e($errors->has('ic_no') ? ' has-error' : ''); ?> row">
                    <?php echo Form::label('ic_no', App\Language::trans('Documents'), ['class'=>'col-sm-2 col-form-label']); ?>

                    <div class="col-sm-10 append_field">
                        <?php echo Form::file("logo_photo_path", array("id"=>"logo_photo_path","class"=>"form-control")); ?>

                        <?php echo $errors->first('ic_no', '<label for="ic_no" class="help-block error">:message</label>'); ?>

                    </div>
                    <div class="col-sm-offset-3 col-sm-12 mt-10">
                        <a href="javascript:void(0)" onclick="append_file_upload_field(this, 'logo_photo_path')" class="btn btn-primary">
                            <span class="feather-icon"><i data-feather="file-plus"></i>Add Dodument</span>
                        </a>
                    </div>
                </div>


            </div>
     
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="post_member_detail('membership_item_table');">Submit</button>
                <?php echo Form::hidden('id_house_member', null, ['id'=>'id_house_member']); ?>

            </div>
            <?php echo Form::close(); ?>

        </div>
    </div>
</div>



<?php $__env->startSection('script'); ?>
init_age_control_single_date_time_picker("input[name=dob]");
<?php $__env->stopSection(); ?>