<div class="row">
  <div class="col-md-12" style="padding-bottom:50px;">
    <div class="box box-info box-solid" style="background-color:#b3d9fc;">
        <div class="box-header with-border" style="background-color:#59abf7">
            <h3 class="box-title">{{App\Language::trans('History')}}</h3>
        </div>
          <!-- Custom Tabs -->
          <div class="box-footer">
            <div class="nav-tabs-custom">
              <ul class="nav nav-tabs">
                <li class="active"><a href="#tab_history" data-toggle="tab">{{App\Language::trans('Payment History')}}</a></li>
                <li><a href="#tab_subsidy" data-toggle="tab">{{App\Language::trans('Subsidy')}}</a></li>   
                <li><a href="#tab_usage" data-toggle="tab">{{App\Language::trans('Monthly Usage')}}</a></li>    
              </ul>
               <div class="tab-content">
                    <div class="tab-pane active" id="tab_history">
                         <table class="table table-bordered">
                                      <tr>
                                        <th>{{App\Language::trans('Document Date')}}</th>
                                        <th>{{App\Language::trans('Refence No.')}}</th>
                                        <th>{{App\Language::trans('Amount (RM)')}}</th>
                                      </tr>
                                       @if(isset($payment_received_listing))
                                           @foreach($payment_received_listing as $row)
                                               <tr>
                                                   <td>{{$row['document_date']}}</td>
                                                   <td>{{$row['document_no']}}</td>
                                                   <td>{{number_format($row['total_amount'],2,'.','')}}</td>
                                               </tr>
                                           @endforeach
                                      @endif
                           </table>
                     </div>
                     <!-- /.tab-pane -->

                    <div class="tab-pane" id="tab_subsidy">
                          <table class="table table-bordered">
                                      <tr>
                                          <th>{{App\Language::trans('Subsidy Date')}}</th>
                                          <th>{{App\Language::trans('Description')}}</th>
                                          <th>{{App\Language::trans('Amount (RM)')}}</th>
                                      </tr>
                                       @if(isset($subsidy_listing))
                                           @foreach($subsidy_listing as $row)
                                               <tr>
                                                   <td>{{$row['document_date']}}</td>
                                                   <td>{{$row['remark']}}</td>
                                                   <td>{{number_format($row['total_amount'],2,'.','')}}</td>
                                               </tr>
                                           @endforeach
                                      @endif
                           </table>
                    </div>
                    <!-- /.tab-pane -->

                    <div class="tab-pane" id="tab_usage">
                          <table class="table table-bordered">
                                      <tr>
                                          <th>{{App\Language::trans('Month')}}</th>
                                          <th>{{App\Language::trans('Description')}}</th>
                                          <th>{{App\Language::trans('Usage (kWh)')}}</th>
                                          <th>{{App\Language::trans('Charges (RM)')}}</th>
                                      </tr>
                                       @if(isset($month_usage_listing))
                                           @foreach($month_usage_listing as $row)
                                               <tr>
                                                   <td>{{date('Y-m',strtotime($row['date']))}}</td>
                                                   <td>{{App\Language::trans('Usage Summary - ')}} {{App\Setting::get_month_in_word(date('m',strtotime($row['date'])))." ".date('Y',strtotime($row['date']))}}</td>
                                                    <td>{{$row['total_usage_kwh']}}</td>
                                                   <td>{{number_format($row['total_payable_amount'],2,'.','')}}</td>
                                       
                                               </tr>
                                           @endforeach
                                      @endif
                           </table>
                    </div>
                    <!-- /.tab-pane -->
              </div>
              <!-- /.tab-content -->
            </div>
          <!-- nav-tabs-custom -->
          </div>
        <!-- /.col -->
        </div>
    </div>
</div>
