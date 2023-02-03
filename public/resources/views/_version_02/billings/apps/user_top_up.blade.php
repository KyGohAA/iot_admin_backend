<div class="row" style="padding-bottom:50px;">
    <div class="col-md-12">
        <!-- DIRECT CHAT PRIMARY -->
        <div class="box box-info box-solid" >
            <div class="box-header with-border" style="background-color:#59abf7">
                <h3 class="box-title">{{App\Language::trans('Payment')}}</h3>
            </div>

            <!-- /.box-header -->
             <form class="list" id="my-form" action="{{action('AppAccountingDashboardsController@getPayment')}}" method="get">
            <div class="box-body" id="payment_detail_div">
            
         <table class="table">
                   
                     <tr>
                        <td align="right">{{App\Language::trans('Item')}}</td>
                        <td><strong>{{$membership_detail['membership_type']}}</strong>
                            <br>
                        </td>
                    </tr>
                    
                    <tr>
                        <td align="right">{{App\Language::trans('Renewal period')}}</td>
                        <td><strong> {{$membership_detail['membership_end_date']}}</strong>
                            <!-- <br> --> {{App\Language::trans('to')}}
                            <strong>  {{$membership_detail['membership_extend_to_date']}}</strong>
                        </td>
                    </tr>
                    
                    <tr>
                        <td align="right">{{App\Language::trans('Amount (RM)')}}</td>
                        <td><strong><label id="amount">{{ number_format($membership_detail['membership_price'],2,'.','')}}</label></strong>
                            <br>
                        </td>
                    </tr>

                    <tr>
                        <td align="right">{{App\Language::trans('Payee Name')}}</td>
                        <td><strong>{{$membership_detail['member_detail']['house_member_name']}}</strong>
                            <br>
                        </td>
                    </tr>

                    <tr>
                        <td colspan="2"> 
                      <div class="box">
                            <div class="box-header">
                              <h3 class="box-title">{{App\Language::trans('Members')}}
                              </h3>
                              <!-- tools box -->
                              <div class="pull-right box-tools">
                                 <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                                    Button with data-target
                                  </button>
                              </div>
                              <!-- /. tools -->
                            </div>
                            <!-- /.box-header -->
                          

                            <div class="box-body" id="collapseExample">
                              <!-- See dist/js/pages/dashboard.js to activate the todoList plugin -->
                              <ul class="todo-list">
                              @foreach($membership_detail['members'] as $member)
                                <li>
                                  <!-- drag handle -->
                                  <span class="handle">
                                        <i class="fa fa-ellipsis-v"></i>
                                        <i class="fa fa-ellipsis-v"></i>
                                      </span>
                                  <!-- checkbox -->
                                  <input type="checkbox" value="">
                                  <!-- todo text -->
                                  <span class="text">{{$member['house_member_name']}}</span>
                      
                                  <div class="tools">
                                    <i class="fa fa-edit"></i>
                                    <i class="fa fa-trash-o"></i>
                                  </div>
                                </li>
                                @endforeach
                              </ul>
                            </div>
                          </div>
                       </td> 
                    </tr>
                 

                        <!-- <tr>
                            <td align="right">{{App\Language::trans('Address')}}</td>
                            <td><strong>{{$membership_detail['member_detail']['house_member_address']}}</strong>
                                <br>
                            </td>
                        </tr> -->

                    </table>
            </div>
            <!-- /.box-body -->
                 <div class="box-footer">
                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            {!! Form::label('top_up_amount_txt', App\Language::trans('Payment Amount (RM)'), ['class'=>'control-label col-md-4']) !!}
                            <div class="col-md-8">
                                {!! Form::text('top_up_amount_txt', number_format($membership_detail['membership_price'],2,'.',''), ['id'=>'top_up_amount_txt' ,'class'=>'form-control','required','readonly','onkeyup'=>"checkMaxNumInputAndDisableTarget('top_up_amount_txt','btn_pay_now',99999);"]) !!}
                                {!!$errors->first('top_up_amount_txt', '<label for="top_up_amount_txt" class="help-block error">:message</label>')!!}
                            </div>
                        </div>
                        <br>
                        
                        <button type="submit" id="btn_pay_now" class="btn btn-default btn-block" style="background-color:#48b8ce;" href="{{action('AppAccountingDashboardsController@getPayment')}}"  target='_blank'><font color="white">{{App\Language::trans('Pay Now')}}</font></button>
                        <input type="hidden" id="invoice" name="invoice" value="{{json_encode($membership_detail)}}">
                        <input type="hidden" id="leaf_group_id" name="leaf_group_id" value="{{$leaf_group_id}}">
                 </div>
             </form>
            <!-- /.box-footer-->
            <!-- /.box-footer-->
        </div>
        <!--/.direct-chat -->
    </div>
    <!-- /.col -->
</div>
<!-- /.row -->