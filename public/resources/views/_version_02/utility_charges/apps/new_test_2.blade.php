 <!-- Main Content -->
        <div class="hk-pg-wrapper">
            <!-- Container -->
            <div class="container-fluid mt-xl-50 mt-sm-30 mt-15">
                <!-- Row -->
                <div class="row">
                    <div class="col-xl-12">

                        <div class="hk-row">
                                 <section class="hk-sec-wrapper">
                                    <h5 class="hk-sec-title">FAQ</h5>
                                    <p class="mb-25">.....</p>
                                    <div class="row">
                                        <div class="col-sm">
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
                                    </div>
                                </section>
                        </div>


                    </div>
                </div>
                <!-- Row -->

</div>
</div>
