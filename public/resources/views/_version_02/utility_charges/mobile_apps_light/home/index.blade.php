@extends('_version_02.utility_charges.mobile_apps_light.layouts_home.main')
@section('content')

<section class="hk-sec-wrapper fullscreen" style="height:100%;">
       
        
            <!-- CATEGORY -->
            <div class="section home-category  flex-ppal-setup" style="margin:2% 2% -35% 2%;">
              <!-- <div class="container"> -->
              <div> 
                 <!-- padding-top:0px; -->
                 <div class="row slick-product" style="margin-bottom:5px;">
                      <div class="col s12"  >
                         <div class="featured-product">
                            <div>
                               <div class="col-slick-product">
                                  <div class="box-product" style="padding-bottom:10px;">
                                    <div class="bp-top" style="padding-top:15px;">
                                      <h5> Account Summary </h5>
                                      <hr>
                                        <div class="price">
                                         <strong>{{App\Language::trans('Balance amount')}} :</strong> <span> RM <label id="balance_amount"></label>  {{App\Language::trans('kWh')}} </span>
                                      </div>

                                       
                                      <div class="price" style="margin-bottom:15px;">
                                        <strong>{{App\Setting::get_month_in_word(date('m'))}} {{App\Language::trans('usage')}} : </strong> <span> RM <label id="current_usage"></label> {{App\Language::trans('kWh')}} </span>
                                      </div>

                                       <div class="in-content">
                                       <div class="in-in-content">
                                      <div class="chart" style="position: relative; height:40vh; width:80vw">
                                         <canvas id="barChart" style="position: relative; height:40vh; width:80vw"></canvas>
                                      </div>
                                    </div>
                                  </div>

                                       <h7 style="text-align: left;"><br> Last update at : {{$last_reading_date_time}} </h7>

                                  </div>
                                </div>
                              </div>
                          </div>
                        </div>
                      </div>
                  </div> 
                  
                <div class="row slick-product">

                    <div class="col s4 m4 l2"><a class="icon-content" onclick="initialize_usage_line_chart('barChart');">
                      <div class="content fadetransition">
                        <div class="in-content">
                          <div class="in-in-content">
                            <img src="{{asset('leaf_acconting_mobile/img/icon/transaction.png')}}" alt="category">
                            <h5><br> Daily Usage </h5>
                          </div>
                        </div>
                      </div></a>
                    </div>

                    <div class="col s4 m4 l2"><a class="icon-content" onclick="initialize_mobile_app_report('barChart');">
                      <div class="content fadetransition">
                        <div class="in-content">
                          <div class="in-in-content">
                            <img src="{{asset('leaf_acconting_mobile/img/icon/bank.png')}}" alt="category">
                            <h5><br> Usage  </h5>
                          </div>
                        </div>
                      </div></a>
                    </div>
                    
                    <div class="col s4 m4 l2"><a class="icon-content" onclick=" init_loading_overlay();" href="{{action('AppsUtilityChargesController@getTopUp', [$session_token])}}?amount=2">
                      <div class="content fadetransition">
                        <div class="in-content">
                          <div class="in-in-content">
                            <img src="{{asset('leaf_acconting_mobile/img/icon/income.png')}}" alt="category">
                            <h5><br> Top Up </h5>
                          </div>
                        </div>
                      </div></a>
                    </div>

                   
                </div>
              </div>

            </div>
            <!-- END CATEGORY -->
    </section>

@endsection
@section('script')



@endsection
