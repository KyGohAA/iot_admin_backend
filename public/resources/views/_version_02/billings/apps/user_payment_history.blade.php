<div class="row">
    <div class="col-md-12" >
        <!-- DIRECT CHAT PRIMARY -->
        <div class="box box-info box-solid" style="background-color:#b3d9fc;">
            <div class="box-header with-border" style="background-color:#59abf7;">
                <h3 class="box-title">{{App\Language::trans('Payment History')}}</h3>
            </div>
             <div class="box-footer">
               <table class="table table-bordered">
                            <tr>
                               <th>{{App\Language::trans('Document Date')}}</th>
                              <th>{{App\Language::trans('Refence No.')}}</th>
                              <th>{{App\Language::trans('Charges (RM)')}}</th>
                             <!--  <th>{{App\Language::trans('Action')}}</th> -->
                            </tr>
                           @if(isset($payment_received_listing))
                             @foreach($payment_received_listing as $row)
                             <tr>
                                 <td>{{$row['document_date']}}</td>
                                 <td>{{$row['document_no']}}</td>
                                 <td>{{number_format($row['total_amount'],2,'.','')}}</td>
                                <!--  <td>
                                     <a target="_blank" href="{{action('ARPaymentReceivedsController@getPrint', [$row->id])}}"><i class="fa fa-search" aria-hidden="true"></i></a> 
                                  </td> -->

                             </tr>
                             @endforeach
                            @endif
                     
                          </table>
            </div>
            <!-- /.box-footer-->
        </div>
        <!--/.direct-chat -->
    </div>
    <!-- /.col -->
</div>
<!-- /.row -->



