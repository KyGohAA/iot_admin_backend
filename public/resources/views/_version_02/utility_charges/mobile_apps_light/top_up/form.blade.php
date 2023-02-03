@extends('_version_02.utility_charges.mobile_apps_light.layouts.main')
@section('content')
{!! Form::model($model, ['class'=>'form-horizontal',"files"=>true]) !!}

    <section class="hk-sec-wrapper" style="height:90%vh;">
        <h5 class="hk-sec-title">Bill Payment</h5>
        <hr class="hr-soft-success">
        <!-- <p class="mb-25"></p> -->
        <div class="row">
        	<div class="col-sm-12">
                        <div class="alert alert-info alert-wth-icon alert-dismissible fade show" role="alert">
                            <span class="alert-icon-wrap"><i class="fas fa-battery-quarter"></i></span>
                            <!-- <p>Below is the amount payable , please enter the amount you wish to top up :</p> -->
                            <p>Please enter the amount you wish to top up :</p>
                           <!--  <strong> RM 100 </strong> -->

                            


                            <!-- <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button> -->
                        </div>
                        <hr class="hr-soft-success">
                            <p>Payment receipt will be sent to your mailbox.</p>
                    </div>

            <div class="col-sm">
                <form>
                	<div class="form-group mt-10" style="height:20vh;">
                       
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="icon-envelope-open"></i></span>
                            </div>
                            <input value={{$user['email']}} type="email" class="form-control" id="exampleInputEmail_1" placeholder="Enter email">
                        </div>

                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-wallet"></i></span>
                            </div>
                            <input id="top_up_amount_txt" name="top_up_amount_txt" value="{{ number_format(($model['amount'] >= 1 ? $model['amount'] : 1),2,'.','') }}" type="number" min='2' max='200' step='0.01' class="form-control" id="exampleInputEmail_1" placeholder="Payment Amount">
                             <!-- onkeyup="checkMaxNumInputAndDisableTarget('top_up_amount_txt','btn_pay_now',99999);" -->
                        </div>

                    </div>


                
                    <div class="custom-control custom-checkbox">
                        <input class="custom-control-input" id="same-address" type="checkbox" checked>
                        <label class="custom-control-label" for="same-address">I have read and agree to the Privacy Policy</label>
                    </div>

                    <div class="custom-control custom-checkbox mb-15">
                        <input class="custom-control-input" id="same-address" type="checkbox" checked>
                        <label class="custom-control-label" for="same-address">I have read and agree to the TERMS OF SERVICE</label>
                    </div>
                    <div class="form-group row mb-0">
                        <div class="col-sm-12">
                            <button onclick=" init_loading_overlay();" href="{{action('AppAccountingDashboardsController@getPayment')}}" id='btn_pay_now' name='btn_pay_now' class="btn btn-success btn-block btn-wth-icon mt-10"> <span class="icon-label"><i class="fas fa-charging-station"></i> </span><span class="btn-text">Proceed To Payment</span></button>

                            <input type="hidden" id="leaf_group_id" name="leaf_group_id" value="{{App\Company::get_group_id()}}">
				            <input type="hidden" id="leaf_room_id" name="leaf_room_id" value="{{$user_profile['leaf_room_id']}}">
				            <input type="hidden" id="user" name="user" value="{{$user_profile_string}}">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>

{!! Form::close() !!}

@endsection
@section('script')
@endsection



              