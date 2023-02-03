<div class="row" style="padding-bottom:50px;">
    <div class="col-md-12">
        <!-- DIRECT CHAT PRIMARY -->
        <div class="box box-info box-solid" >
            <div class="box-header with-border" style="background-color:#59abf7">
                <h3 class="box-title">{{App\Language::trans('Membership Application')}}</h3>
            </div>

            <!-- /.box-header -->
             <form class="list" id="my-form" action="{{action('AppAccountingDashboardsController@getPayment')}}" method="get">
            <div class="box-body" id="payment_detail_div">
            <div class="row" style="margin:0px 0px 5px 0px;">
                <div class="col-md-12">
                            @include('billings.layouts.partials._progress_stepper')
                 </div>
                </div>

                 <table class="table">

                        <tr>
                            <td align="right">{{App\Language::trans('Renewal period')}}</td>
                            <td><strong> {{$membership_detail['membership_end_date']}}</strong>
                                <!-- <br> --> {{App\Language::trans('to')}}
                                <strong>  {{$membership_detail['membership_extend_to_date']}}</strong>
                            </td>
                        </tr>
                        
                        
                        <tr>
                            <td align="right">{{App\Language::trans('Item')}}</td>
                            <td> {!!Form::select('product_listing', App\Product::combobox(), null, ['id'=>'product_listing','class'=>'form-control'])!!} 
                            </td>
                        </tr>


                        <tr>
                            <td align="right">{{App\Language::trans('Amount (RM)')}}</td>
                            <td><strong><label id="amount">{{ number_format($membership_detail['membership_price'],2,'.','')}}</label></strong>
                                <br>
                            </td>
                        </tr>

                        <tr>
                            <td align="right">{{App\Language::trans('Name')}}</td>
                            <td><strong>{{$membership_detail['member_detail']['house_member_name']}}</strong>
                                <br>
                            </td>
                        </tr>
                     

                        <tr>
                            <td align="right">{{App\Language::trans('Address')}}</td>
                            <td><strong>{{$membership_detail['member_detail']['house_member_address']}}</strong>
                                <br>
                            </td>
                        </tr>

                          <tr>
                          <!-- <td colspan="2"> -->
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
                           <!-- </td> -->
                        </tr>

                        

                    </table>
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