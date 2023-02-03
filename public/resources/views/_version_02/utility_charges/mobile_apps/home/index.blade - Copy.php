@extends('_version_02.utility_charges.mobile_apps.layouts.main')
@section('content')
<!-- CATEGORY -->
<div class="section home-category">
  <div class="container fullscreen">

     <div class="row slick-product" style="margin-bottom:5px; padding-top:2%;">
          <div class="col s12" style="padding-top:17%;">
             <div class="featured-product">
                <div>
                   <div class="col-slick-product">
                      <div class="box-product" style="padding-bottom:15px;">
                        <div class="bp-top" style="padding-top:15px;">
                          <h5> Account Summary </h5>
                          <hr>
                            <div class="price">
                             <strong>{{App\Language::trans('Balance amount')}} :</strong> <span> RM {{$statistic['balanceAmount']}} : {{$statistic['currentBalanceKwh']}}  {{App\Language::trans('kWh')}} </span>
                          </div>

                          
                          <div class="price">
                            <strong>{{App\Setting::get_month_in_word(date('m'))}} {{App\Language::trans('usage')}} : </strong> <span> RM {{$statistic['currentUsageCharges']}} : {{$statistic['currentUsageKwh']}}  {{App\Language::trans('kWh')}} </span>
                          </div>

                      </div>
                    </div>
                  </div>
              </div>
            </div>
          </div>
      </div> 

    <div class="row slick-product">

        <div class="col s4 m4 l2"><a class="icon-content nav-link" href="{{action('AppsUtilityChargesController@getTransactionListing')}}">
          <div class="content fadetransition">
            <div class="in-content">
              <div class="in-in-content">
                <img src="{{asset('leaf_acconting_mobile/img/icon/transaction.png')}}" alt="category">
                <h5><br> Transaction </h5>
              </div>
            </div>
          </div></a>
        </div>

        <div class="col s4 m4 l2"><a class="icon-content nav-link" href="{{action('AppsUtilityChargesController@getUserHistory')}}">
          <div class="content fadetransition">
            <div class="in-content">
              <div class="in-in-content">
                <img src="{{asset('leaf_acconting_mobile/img/icon/bank.png')}}" alt="category">
                <h5><br> History  </h5>
              </div>
            </div>
          </div></a>
        </div>
        
        <div class="col s4 m4 l2"><a class="icon-content nav-link" href="{{action('AppsUtilityChargesController@getTopUp')}}?amount={{$statistic['currentUsageCharges']}}">
          <div class="content fadetransition">
            <div class="in-content">
              <div class="in-in-content">
                <img src="{{asset('leaf_acconting_mobile/img/icon/income.png')}}" alt="category">
                <h5><br> Top Up </h5>
              </div>
            </div>
          </div></a>
        </div>

        <div class="col s12 m12 l2">
          <div class="content fadetransition"  style="overflow-x:auto;">
            <div class="in-content">
              <div class="in-in-content">
                <!-- BAR CHART -->
                <img  id='statistic_icon' class="hide" src="{{asset('leaf_acconting_mobile/img/icon/graph.png')}}" alt="category">
                 <div class="chart">
                   <canvas id="barChart" style="height:230px"></canvas>
                  </div>
                <!-- /.box -->
                <h5><br> Statistic </h5>
              </div>
            </div>
          </div>
        </div>
    </div>
  </div>
  <br><br><br>
</div>
<!-- END CATEGORY -->




  


@endsection
@section('script')



@endsection
