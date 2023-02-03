<div class="row">
    <div class="col-md-12" style="padding-bottom:50px;">
        <!-- DIRECT CHAT PRIMARY -->
        <div class="box box-info box-solid" style="background-color:#b3d9fc;">
            <div class="box-header with-border" style="background-color:#59abf7">
                <h3 class="box-title">{{App\Language::trans('Billing Info')}}</h3>

                <!-- <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                </div> -->
            </div>
            <!-- /.box-header -->
        <form class="list" id="my-form" action="{{action('AppsUtilityChargesController@getPayment')}}" method="get">
            <div class="box-body">
            
              <table class="table">
                    <tr>
                        <td align="right">{{App\Language::trans('Account')}}</td>
                        <td><strong>{{$user_profile['account_no']}}</strong>
                            <br>
                        </td>
                    </tr>
                    <tr>
                        <td align="right">{{App\Language::trans('Name')}}</td>
                        <td><strong>{{$user_profile['fullname']}}</strong>
                            <br>
                        </td>
                    </tr>
                    <tr>
                        <td align="right">{{App\Language::trans('Contact')}}</td>
                        <td><strong>{{$user_profile['phone_number']}}</strong>
                            <br>
                        </td>
                    </tr>
                    <tr>
                        <td align="right">{{App\Language::trans('Email')}}</td>
                        <td><strong>{{$user_profile['email']}}</strong>
                            <br>
                        </td>
                    </tr>
                  
                    <tr>
                        <td align="right">{{App\Language::trans('Address')}}</td>
                        <td><strong>{{$user_profile['address']}}</strong>
                            <br>
                        </td>
                    </tr>
               
                 </table>
     

            </div>

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
              </form>
            <!-- /.box-footer-->
            <!-- /.box-footer-->
        </div>
        <!--/.direct-chat -->
    </div>
    <!-- /.col -->
</div>
<!-- /.row -->

