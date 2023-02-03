   <section class="hk-sec-wrapper">
                <div class="row">
                    <div class="col-xl-12">

                        <div class="hk-row">
                            <section class="hk-sec-wrapper">
                                <h3 class="display-4 mb-20">Payment</h3>

                                    <div class="table-wrap mt-40">
                                        <table class="table table-bordered mb-0">
                                            <tbody>
                                                <tr>
                                                    <td><code class="pa-0 bg-transparent">{{App\Language::trans('Account')}}</code></td>
                                                    <td class="font-14">{{$user_profile['account_no']}}</td>
                                                </tr>
                                                <tr>
                                                    <td><code class="pa-0 bg-transparent">{{App\Language::trans('Name')}}</code></td>
                                                    <td class="font-14">{{$user_profile['address']}}</td>
                                                </tr>
                                                <tr>
                                                    <td><code class="pa-0 bg-transparent">{{App\Language::trans('Contact')}}</code></td>
                                                    <td class="font-14">{{$user_profile['phone_number']}}</td>
                                                </tr>
                                                <tr>
                                                    <td><code class="pa-0 bg-transparent">{{App\Language::trans('Email')}}</code></td>
                                                    <td class="font-14">{{$user_profile['email']}}</td>
                                                </tr>
                                                 <tr>
                                                    <td><code class="pa-0 bg-transparent">{{App\Language::trans('Address')}}</code></td>
                                                    <td class="font-14">{{$user_profile['address']}}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>


                                <div class="row">

                                    <div class="col-xl-12 mt-20">
                                        <form>
                                  

                                           <!--  <div class="row">
                                                <div class="col-md-12 form-group">
                                                    <label for="cc-name">Name</label>
                                                    <input class="form-control" id="cc-name" placeholder="" type="text">
                                                    <small class="form-text text-muted">Full name as displayed on card</small>
                                                </div>
                                             
                                            </div> -->
                                          

                                        <!-- /.box-body -->
                                            @if(!$company['is_mobile_app_allow_payment'] && $is_allow_to_pay == false)
                                                <div class="box-footer">
                                                    <p class="text-muted well well-sm no-shadow" style="margin-top: 10px;">
                                                     {{App\Language::trans('The top up is not allowed until receive management accouncement.')}}<br>
                                                       <!--{{App\Language::trans('Maximum prepaid amount for an account is RM 200.00 per transaction.')}}-->
                                                    </p>
                                                </div>
                                            @else
                                                 <div class="box-footer">
                                                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                                                            {!! Form::label('top_up_amount_txt', App\Language::trans('Payment Amount (RM)'), ['class'=>'control-label col-md-4']) !!}
                                                            <div class="col-md-8">
                                                                {!! Form::number('top_up_amount_txt', number_format(($statistic['currentUsageCharges'] >= 1 ? $statistic['currentUsageCharges'] : 1),2,'.',''), ['min'=>1,'max'=>200,'step'=>'0.01', 'id'=>'top_up_amount_txt' ,'class'=>'form-control','required','onkeyup'=>"checkMaxNumInputAndDisableTarget('top_up_amount_txt','btn_pay_now',99999);"]) !!}
                                                                {!!$errors->first('top_up_amount_txt', '<label for="top_up_amount_txt" class="help-block error">:message</label>')!!}
                                                            </div>
                                                        </div>
                                                        <br>
                                                        
                                                        <button type="submit" id="btn_pay_now" class="btn btn-default btn-block" style="background-color:#48b8ce;" href="{{action('AppAccountingDashboardsController@getPayment')}}"  target='_blank'><font color="white">{{App\Language::trans('Pay Now')}}</font></button>
                                                        <input type="hidden" id="leaf_group_id" name="leaf_group_id" value="{{$leaf_group_id}}">
                                                        <input type="hidden" id="leaf_room_id" name="leaf_room_id" value="{{$meter_register_model->leaf_room_id}}">
                                                        <input type="hidden" id="user" name="user" value="{{$user_profile_string}}">
                                                 </div>
                                            @endif


                                            <div class="custom-control custom-checkbox checkbox-success mb-15">
                                                <input class="custom-control-input" id="same-card" type="checkbox" checked>
                                                <label class="custom-control-label" for="same-card">You are agreed with the Term and Conditions.</label>
                                            </div>
                                            <button class="btn btn-primary btn-block" type="submit">Pay</button>
                                            <small class="form-text text-muted">....</small>
                                        </form>
                                    </div>
                                </div>
                            </section>
                        </div>


                    </div>
                </div>
                <!-- Row -->
</section>