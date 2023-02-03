   <section class="hk-sec-wrapper"  style="max-height: 80%; height: auto\9; width: auto">
                <!-- Row -->
                <div class="row">
                    <div class="col-xl-12">

                        <div class="hk-row">
                            <div class="col-sm-12">
                                <div class="card-group hk-dash-type-2">
                                   
                                    <!-- Start -->
                                    <div class="card card-sm">

                                         <div class="card-header card-header-action">
                                                    <h6>Device Stats</h6>
                                                    <div class="d-flex align-items-center card-action-wrap">
                                                        <div class="inline-block dropdown">
                                                            <a class="dropdown-toggle no-caret" data-toggle="dropdown" href="#" aria-expanded="false" role="button"><i class="ion ion-ios-more"></i></a>
                                                            <div class="dropdown-menu dropdown-menu-right">
                                                                <a class="dropdown-item" href="#">Action</a>
                                                                <a class="dropdown-item" href="#">Another action</a>
                                                                <a class="dropdown-item" href="#">Something else here</a>
                                                                <div class="dropdown-divider"></div>
                                                                <a class="dropdown-item" href="#">Separated link</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                        <div class="card-body">
                                            
                                               

                                                    <div class="row">
                                                        <div class="col-sm">
                                                            <div id="e_chart_11" class="echart" style="height:400px;"></div>
                                                        </div>
                                                    </div>



                                        </div>
                                    </div>
                                    <!-- End -->


                                    <div class="card card-sm" style="background-color:#b3d9fc;">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between mb-5">
                                                <div>
                                                    <span class="d-block font-15 text-dark font-weight-500">{{App\Setting::get_month_in_word(date('m'))}} {{App\Language::trans('usage')}}</span>
                                                </div>
                                                 <div>
                                                    <span class="text-primary font-14 font-weight-500">+15%</span>
                                                </div> 
                                            </div>
                                            <div>
                                                <span class="d-block display-4 text-dark mb-5">RM {{$statistic['currentUsageCharges']}} </span>
                                                <small class="d-block">{{$statistic['currentUsageKwh']}} {{App\Language::trans('kWh')}}</small>
                                            </div>
                                        </div>
                                    </div>
                                
                                    <div class="card card-sm" style="background-color:#b3d9fc;">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between mb-5">
                                                <div>
                                                    <span class="d-block font-15 text-dark font-weight-500">{{App\Language::trans('Balance')}}</span>
                                                </div>
                                                <!-- <div>
                                                    <span class="text-primary font-14 font-weight-500">+15.5%</span>
                                                </div> -->
                                            </div>
                                            <div>
                                                <span class="d-block display-4 text-dark mb-5"><span class="counter-anim">RM {{$statistic['balanceAmount']}}</span></span>
                                                <small class="d-block">{{$statistic['currentBalanceKwh']}}  {{App\Language::trans('kWh')}}</small>
                                            </div>
                                        </div>
                                    </div>



                                </div>
                            </div>
                        </div>


                    </div>
                </div>
                <!-- Row -->
</section>
<!-- </div> -->
