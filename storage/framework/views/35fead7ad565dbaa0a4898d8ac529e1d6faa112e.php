<?php $__env->startSection('content'); ?>


<input type="hidden" id="id_house" name="id_house" value="<?php echo e($syn_data_vars['id_house']); ?>">
<input type="hidden" id="id_house_room" name="id_house_room" value="<?php echo e($syn_data_vars['id_house_room']); ?>">
<input type="hidden" id="leaf_id_user" name="leaf_id_user" value="<?php echo e($syn_data_vars['leaf_id_user']); ?>">

<section class="hk-sec-wrapper" style="height:90%vh;">
        <h5 class="hk-sec-title">Synchronizing User Data</h5>
        <hr class="hr-soft-success">
        <!-- <p class="mb-25"></p> -->

        <div class="card overflow-hide border-0">
            <div class="card-body pa-0">
                <div id="loading_splash" class="owl-carousel dots-on-item owl-theme">
                    <div class="fadeOut item img-background overlay-wrap" style="background-image:url(<?php echo e(asset($page_variables['logo_photo_path'])); ?>);">
                        <div class="position-relative z-index-2 pa-20">
                            <div class="position-relative text-dark mnh-225p">
                                <p id="msg_1"><?php echo e($msg_1); ?></p>
                               
                            </div>
                        </div>
                        <div class="bg-overlay bg-trans-light-80"></div>
                    </div>
                    <div class="fadeOut item img-background overlay-wrap" style="background-image:url(<?php echo e(asset($page_variables['logo_photo_path'])); ?>);">
                        <div class="position-relative z-index-2 pa-20">
                            <div class="position-relative text-dark mnh-225p">
                                <p id="msg_2">It might take a moment.</p>
                            
                            </div>
                        </div>
                        <div class="bg-overlay bg-trans-light-80"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card overflow-hide border-0">
            <div class="card-body pa-0">
                <div id="after_loading_splash" class="owl-carousel dots-on-item owl-theme">
                    <div class="fadeOut item img-background overlay-wrap" style="background-image:url(<?php echo e(asset($page_variables['logo_photo_path'])); ?>);">
                        <div class="position-relative z-index-2 pa-20">
                            <div class="position-relative text-dark mnh-225p">
                                <label id="return_msg" name="return_msg"></label>
                               
                            </div>
                        </div>
                        <div class="bg-overlay bg-trans-light-80"></div>
                    </div>
                    
                </div>
            </div>
        </div>



        <form action="<?php echo e(action('AppsUtilityChargesController@getDashboard')); ?>?session_token=<?php echo e($session_token); ?>">
                <div class="form-group row mb-0">
                    <div class="col-sm-12">
                        <button disabled id='btn_next' name='btn_next' class="btn btn-success btn-block btn-wth-icon mt-10"> <span class="icon-label"><i class="fas fa-charging-station"></i> </span><span class="btn-text">Next</span></button>
                    </div>
                </div>
        </form>

</section>



<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>

$(document).ready(function(){


    $('#loading_splash').owlCarousel({
        items: 1,
        animateOut: 'fadeOut',
        loop: true,
        margin: 10,
        autoplay: true,
        mouseDrag: false

    });

    init_loading_overlay();

    $.get(generatePowerMeterAccountUrl, { id_house_room : $('#id_house_room').val(), id_house : $('#id_house').val(), leaf_id_user : $('#leaf_id_user').val() }, function(data) {
     console.log('x gene result');
     console.log(data);
     if(data['status_code'] == true){

        $('#loading_splash').addClass('hide').fadeIn();
        //$('#after_loading_splash').removeClass('hide');
        $('#after_loading_splash').removeClass('hide');
        $('#return_msg').append('sss');
        document.getElementById("return_msg").innerText = data['status_msg'];
        document.getElementById("btn_next").disabled = false;
     }else{
        $('#loading_splash').addClass('hide').fadeIn();
        document.getElementById("return_msg").innerText = data['status_msg'];
        document.getElementById("btn_next").disabled = true;
     }
     //console.log(data['status_msg']);

     $('#after_loading_splash').owlCarousel({
                items: 1,
                animateOut: 'fadeOut',
                loop: true,
                margin: 10,
                autoplay: true,
                mouseDrag: false

        });
       //console.log(data);
       init_hide_loading_overlay();
      
    });

 console.log('x gene to end');


});

<?php $__env->stopSection(); ?>



              
<?php echo $__env->make('_version_02.utility_charges.mobile_apps_light.layouts.main', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>