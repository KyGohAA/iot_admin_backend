<?php $__env->startSection('content'); ?>
<?php echo $__env->make('_version_02.commons.layouts.partials._alert', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

<div>
    <?php echo Form::model($model, ['class'=>'form-horizontal','id'=>'ar-payment-received-form']); ?>

         <h3>
            <span class="wizard-icon-wrap"><i class="ion ion-md-airplane"></i></span>
            <span class="wizard-head-text-wrap">
                <span class="step-head">Member Detail</span>
            </span> 
        </h3>
        <section>
            <h3 class="display-4 mb-40">Select User</h3>
            <div class="row">
                <div class="col-xl-4 mb-20">
                    <div class="card mt-30">
                        <div class="card-body bg-light">
                            <h5 class="card-title"><?php echo Form::select('customer_id', App\Customer::combobox(), null, ['class'=>'form-control','onchange'=>'init_customer_info_pmarr(this,"meter")']); ?></h5>
                            <p class="card-text"><label id="lbl_customer_detail"></label></p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-8 mb-20">
                
                        <div class="form-group<?php echo e($errors->has('name') ? ' has-error' : ''); ?>">
                            <?php echo Form::label('name', App\Language::trans('Name'), ['class'=>'control-label']); ?>

                            <?php echo Form::text('name', null, ['class'=>'form-control','required']); ?>

                            <?php echo $errors->first('name', '<label for="name" class="help-block error">:message</label>'); ?>

                        </div>

                        <div class="form-group<?php echo e($errors->has('contact_person') ? ' has-error' : ''); ?>">
                            <?php echo Form::label('contact_person', App\Language::trans('Contact Person'), ['class'=>'control-label','required']); ?>

                            <?php echo Form::text('contact_person', null, ['class'=>'form-control']); ?>

                            <?php echo $errors->first('contact_person', '<label for="contact_person" class="help-block error">:message</label>'); ?>

                        </div>
    
                        <h5 class="hk-sec-title mt-40"><?php echo e(App\Language::trans('Billing Information')); ?></h5><hr>

                        <div class="form-group">
                            <label for="email">Email</label>
                            <?php echo Form::email('email', null, ['class'=>'form-control','required']); ?>

                            <?php echo $errors->first('email', '<label for="email" class="help-block error">:message</label>'); ?>

                        </div>

                        <div class="form-group">
                            <label for="address">Address</label>
                            <?php echo Form::text('billing_address1', null, ['class'=>'form-control','required']); ?>

                            <?php echo $errors->first('billing_address1', '<label for="billing_address1" class="help-block error">:message</label>'); ?>

                        </div>

                        <div class="form-group">
                            <label for="address">Address 2 (Optional)</label>
                            <?php echo Form::text('billing_address2', null, ['class'=>'form-control','autofocus']); ?>

                            <?php echo $errors->first('billing_address2', '<label for="billing_address2" class="help-block error">:message</label>'); ?>

                        </div>

                        <div class="row">
                            <div class="col-md-5 mb-10">
                                <label for="country">Country</label>
                                <?php echo Form::select('billing_country_id', App\Country::combobox(), null, ['class'=>'form-control','onchange'=>'init_state_selectbox(this)']); ?>

                                <?php echo $errors->first('billing_country_id', '<label for="billing_country_id" class="help-block error">:message</label>'); ?>

                            </div>
                            <div class="col-md-4 mb-10">
                                <label for="state">State</label>
                                <?php echo Form::select('billing_state_id', App\State::combobox($model->billing_country_id), null, ['class'=>'form-control','onchange'=>'init_city_selectbox(this)']); ?>

                                <?php echo $errors->first('billing_state_id', '<label for="billing_state_id" class="help-block error">:message</label>'); ?>

                            </div>
                            <div class="col-md-3 mb-10">
                                <label for="zip">PostCode</label>
                                <?php echo Form::text('billing_postcode', null, ['class'=>'form-control']); ?>

                                <?php echo $errors->first('billing_postcode', '<label for="billing_postcode" class="help-block error">:message</label>'); ?>

                            </div>
                        </div>
                    
                </div>
            </div>
        </section>

        <h3>
            <span class="wizard-icon-wrap"><i class="ion ion-md-basket"></i></span>
            <span class="wizard-head-text-wrap">
                <span class="step-head">Payment History(s)</span>
            </span> 
        </h3>

        <section>
            <h3 class="display-4 mb-20">Payment History</h3>

           
       


        <div class="row">
            <div class="col-lg-12">
                <div class="card card-profile-feed">
                    <div class="card-header card-header-action">
                        <div class="media align-items-center">
                            <div class="media-img-wrap d-flex mr-10">
                                <div class="avatar avatar-sm">
                                    <!-- <img src="<?php echo e(Auth::user()->profile_jpg()); ?>" alt="user" class="avatar-img rounded-circle"> -->
                                </div>
                            </div>
                            <div class="media-body">
                                <div class="text-capitalize font-weight-500 text-dark"><label id="lbl_name" name="lbl_name" for="lbl_name"></label></div>
                                <div class="font-13"></div>
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
                        <h6><span><?php echo e(App\Language::trans('Account Information')); ?> <span class="badge badge-soft-primary ml-5"></span></span></h6>
                        <a href="#" class="font-14 ml-auto"></a>
                    </div>

                    <div class="row text-center">
                    <div class="col-4 border-right pr-0">
                        <div class="pa-15">
                            <span class="d-block display-6 text-dark mb-5"><label id="lbl_total_payable_amount"><img src="<?php echo e(asset(App\Setting::LOADING_GIF)); ?>" alt=""/></label></span>
                            <span class="d-block text-capitalize font-14"> <?php echo e(App\Language::trans('Payable Amount')); ?></span>
                        </div>
                    </div>
                    <div class="col-4 border-right px-0">
                        <div class="pa-15">
                            <span class="d-block display-6 text-dark mb-5"><label id="lbl_total_paid_amount"><img src="<?php echo e(asset(App\Setting::LOADING_GIF)); ?>" alt=""/></label></span>
                            <span class="d-block text-capitalize font-14"><?php echo e(App\Language::trans('Paid Amount')); ?></span>
                        </div>
                    </div>
                    <div class="col-4 pl-0">
                        <div class="pa-15">
                            <span class="d-block display-6 text-dark mb-5"><label id="lbl_total_refund_amount"><img src="<?php echo e(asset(App\Setting::LOADING_GIF)); ?>" alt=""/></label></span>
                            <span class="d-block text-capitalize font-14"><?php echo e(App\Language::trans('Refundable Amount')); ?></span>
                        </div>
                    </div>
                </div>


                    <div class="card-body pb-5">
                        <div class="hk-row text-center">
                            <!-- Apply membership to enjoy the facility in our club house. -->
                        </div>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item"><span><i class="ion ion-md-home font-18 text-light-20 mr-10"></i><span></span></span><span class="ml-5 text-dark" id="lbl_billing_address1" name="lbl_billing_address1" for="lbl_billing_address1"></span></li>
                            <li class="list-group-item"><span><i class="ion ion-md-pin font-18 text-light-20 mr-10"></i><span></span></span><span class="ml-5 text-dark" id="lbl_email" name="lbl_email" for="lbl_email"></span></li>
                       </ul>
                    </div>
                </div>

                  


            </div>
        </div>


      

            <div class="row">
                <div class="col-xl-12 mb-20">
                     <?php echo $__env->make('_version_02.utility_charges.meter_refunds_on_going.partials._list_by_payment_received', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
                </div>
            </div>
        </section>
      
        <h3>
            <span class="wizard-icon-wrap"><i class="ion ion-md-card"></i></span>
            <span class="wizard-head-text-wrap">
                <span class="step-head">Refund Detail</span>
            </span> 
        </h3>
        <section>
            <h3 class="display-4 mb-40">Refund Detail</h3>
            <div class="row">
                <div class="col-xl-8 mb-20">

                        <div class="form-group<?php echo e($errors->has('document_date') ? ' has-error' : ''); ?>">
                            <?php echo Form::label('document_date', App\Language::trans('Document Date'), ['class'=>'control-label','required']); ?>

                            <div class="col-md-12">
                                <?php echo Form::text('document_date', null, ['class'=>'form-control','required','readonly']); ?>

                                <?php echo $errors->first('document_date', '<label for="document_date" class="help-block error">:message</label>'); ?>

                            </div>
                        </div>

                        <div class="form-group<?php echo e($errors->has('document_no') ? ' has-error' : ''); ?>">
                            <?php echo Form::label('document_no', App\Language::trans('Document No.'), ['class'=>'control-label','required']); ?>

                            <div class="col-md-12">
                                <?php echo Form::text('document_no', null, ['class'=>'form-control','required','readonly']); ?>

                                <?php echo $errors->first('document_no', '<label for="document_no" class="help-block error">:message</label>'); ?>

                            </div>
                        </div>

                        <div class="form-group<?php echo e($errors->has('deposit_to_account') ? ' has-error' : ''); ?>">
                            <?php echo Form::label('deposit_to_account', App\Language::trans('Refund From'), ['class'=>'control-label col-md-4']); ?>

                            <div class="col-md-12">
                                <?php echo Form::select('deposit_to_account', App\Setting::bank_or_cash_combobox(), null, ['class'=>'form-control','autofocus','required']); ?> 
                                <?php echo $errors->first('deposit_to_account', '<label for="customer_id" class="help-block error">:message</label>'); ?>

                            </div>
                        </div>

                        <div class="form-group<?php echo e($errors->has('payment_method') ? ' has-error' : ''); ?>">
                            <?php echo Form::label('payment_method', App\Language::trans('Payment Method'), ['class'=>'control-label col-md-4']); ?>

                            <div class="col-md-12">
                                <?php echo Form::select('payment_method', App\Setting::payment_method(), null, ['class'=>'form-control','autofocus','required' , 'onchange'=>'change_payment_label_text_by_payment_method(this)']); ?> <?php echo $errors->first('payment_method', '
                                <label for="payment_method" class="help-block error">:message</label>'); ?>

                            </div>
                        </div>

                        <div class="form-group<?php echo e($errors->has('reference_no') ? ' has-error' : ''); ?>">
                            <?php echo Form::label('doc_payment_no_ref_no', App\Language::trans('Reference No.'), ['id'=>'doc_payment_no_ref_no', 'class'=>'control-label col-md-4']); ?>

                            <div class="col-md-12">
                                <?php echo $errors->first('reference_no', '<label for="reference_no" class="help-block error">:message</label>'); ?>   
                                 <?php echo Form::text('reference_no', null, ['class'=>'form-control','autofocus','required','onblur' => 'reference_no_format_checker(this)','readonly']); ?> 
                            </div>
                        </div>
                </div>

                <div class="col-xl-4 mb-20">
                    <div class="card">
                        <h6 class="card-header border-0">
                            <i class="ion ion-md-clipboard font-21 mr-10"></i>Summary
                        </h6>
                        <div class="card-body pa-0">
                            <div class="table-wrap">
                                <div class="table-responsive">
                                    <table class="table table-sm mb-0">
                                        <tbody>
                                            <tr>
                                                <th class="w-70" scope="row">Sub Total</th>
                                                <th class="w-30" scope="row"><label id="lbl_refund_amount"></label></th>
                                            </tr>
                      
                          
                                            <tr>
                                                <td class="w-70">Tax</td>
                                                <td class="w-30"><label id="lbl_trans_detail_tax"></label></td>
                                            </tr>
                            
                                        </tbody>
                                        <tfoot>
                                            <tr class="bg-light">
                                                <th class="text-dark text-uppercase" scope="row">Refund</th>
                                                <th class="text-dark font-18" scope="row"><label id="lbl_refund_amount_total"></label></th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <h3>
            <span class="wizard-icon-wrap"><i class="ion ion-md-checkmark-circle-outline"></i></span>
            <span class="wizard-head-text-wrap">
                <span class="step-head">Summary</span>
            </span> 
        </h3>
        <section>
            <h3 class="display-4 mb-40">Summary</h3>
            <div class="row">
                <div class="col-xl-8 mb-20">
                    <div class="table-wrap">
                        <div class="table-responsive">
                            <table class="table table-sm mb-0">
                                <tbody>
                                    <tr>
                                        <th class="w-70" scope="row">Sub Total</th>
                                        <th class="w-30" scope="row"><label id="lbl_summary_subtotal"></label></th>
                                    </tr>
                                    <tr>
                                        <td class="w-70">Tax</td>
                                        <td class="w-30"><label id="lbl_summary_tax"></label></td>
                                    </tr>
                                    
                                </tbody>
                                <tfoot>
                                    <tr class="bg-light">
                                        <th class="text-dark text-uppercase" scope="row">Refund</th>
                                        <th class="text-dark font-18" scope="row"><label id="lbl_summary_total"></label></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    <textarea id="remark" name="remark" class="form-control mt-35" rows="2" placeholder="Remarks"></textarea>
                </div>
                
                <div class="col-xl-4 mb-20">
                    <div class="alert alert-info mb-20" role="alert">
                       Please check again , click finish button to submit the form.
                    </div>
                        <p class="mb-10">Invoice correspond to the payment received will be auto-generate , please after submit . <br> Please go AR Invoice module to have the invoice</p>
                        <input type="hidden" id="amount" name="amount">
                        <!-- <a class="d-block mb-25" href="#">How are shipping cost calculated?</a> -->
                        <button class="btn btn-primary btn-block mb-10" type="submit">Submit</button>
                    </div>
                </div>
        </section>
    <?php echo Form::close(); ?>

</div>
<!-- END FORM BODY -->

<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
    var productInfoUrl = "<?php echo e(action('ProductsController@getInfo')); ?>";
    var productInfoByLeafProductIdUrl = "<?php echo e(action('ProductsController@getInfoByLeafProductId')); ?>";

	$( "input[type='text']" ).change(function() {
	  progression_checker_by_step_no();
	});

	$("select").change(function() {
	  progression_checker_by_step_no();
  	});


	$('#amount').focusout(function() {
			$('#amount').val(init_decimal_point($('#amount').val()));
	});

	var customerInfoUrl = "<?php echo e(action('CustomersController@getInfo')); ?>";	
	function init_customer_membership_info(me) {
		$.get(customerInfoUrl, {customer_id:$(me).val()}, function(fdata){
            init_loading_overlay();
            membership_period = "";
            membership_detail = fdata.data.membership_detail;
            console.log("first");
            console.log(JSON.stringify(membership_detail));
            membership_detail_personal_info = fdata.data.membership_detail_personal_info;
            console.log("second");
            console.log(JSON.stringify(membership_detail_personal_info));
            for (var key in  membership_detail_personal_info ) { 
              if(key == 'membership_period' ||  key == 'membership_extend_to_date'){
                 $("#" + key).html(membership_detail_personal_info[key]);
              } 
            }
           console.log("-----------------Start -------------");
            for (var key in  membership_detail) {
            console.log(key);
                if(key == "address") {
                    for(var key_item in membership_detail[key]){
                    console.log("hit-" + membership_detail[key][key_item]);
                      if(key_item == "unit_no"){
                        $("#"+key_item).html(membership_detail[key][key_item]);
                         $("#unit_no").html(membership_detail[key][key_item]);
                      }
                    }
                 }else if(key == "house_fee_items")
                 {
                    console.log("yes it is");
                    console.log(membership_detail[key]);
                    for (var key_item in  membership_detail[key]){
                           for (var key_item_chiled in  membership_detail[key][key_item]){
                            
                             if(key_item == "id_fee_type"){
                                 $('#payable_item_id').val(membership_detail[key]);
                                 alert($('#payable_item_id').val());
                             }
                          }
                    }
                    
                 }
            }

    		for (var key in fdata.data) {
    				if(key != "status") {
    					$("input[name="+key+"]").val(fdata.data[key]);
    				
    					if(key == "currency_label") {
    						$(".currency_label").html(fdata.data[key]);
    					}
    					if(key.match(/_id/g)) {
    						$("select[name="+key+"]").val(fdata.data[key]).trigger("change");
    					}       
    				}
    			}
                 init_hide_loading_overlay();
		},"json");
	
		getCustomerInvoiceById($(me).val(),'invoice_table');
	}
	

    


<?php $__env->stopSection(); ?>

<?php echo $__env->make('_version_02.commons.layouts.admin', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>