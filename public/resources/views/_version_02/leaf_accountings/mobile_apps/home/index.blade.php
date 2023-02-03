@extends('_version_02.leaf_accountings.mobile_apps.layouts.main')
@section('content')

<!-- CATEGORY -->
<div class="section home-category">
  <div class="container fullscreen">
  
    <div class="row icon-service">


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

      <div class="col s4 m4 l2"><a class="icon-content nav-link" href="{{action('AppsIETransactionsController@getExpenses')}}">
        <div class="content fadetransition">
          <div class="in-content">
            <div class="in-in-content">
              <img src="{{asset('leaf_acconting_mobile/img/icon/transaction.png')}}" alt="category">
              <h5><br> Expenses </h5>
            </div>
          </div>
        </div></a>
      </div>

      <div class="col s4 m4 l2"><a class="icon-content nav-link" href="{{action('AppsIETransactionsController@getIncome')}}">
        <div class="content fadetransition">
          <div class="in-content">
            <div class="in-in-content">
              <img src="{{asset('leaf_acconting_mobile/img/icon/income.png')}}" alt="category">
              <h5><br> Income </h5>
            </div>
          </div>
        </div></a>
      </div>

      <div class="col s4 m4 l2"><a class="icon-content nav-link" href="{{action('AppsIETransactionsController@getRecurringTransaction')}}">
        <div class="content fadetransition">
          <div class="in-content">
            <div class="in-in-content">
              <img src="{{asset('leaf_acconting_mobile/img/icon/item.png')}}" alt="category">
              <h5><br> Recurring Transaction </h5>
            </div>
          </div>
        </div></a>
      </div>

      <div class="col s4 m4 l2"><a class="icon-content nav-link" href="{{action('AppsIETransactionsController@getUserAccount')}}">
        <div class="content fadetransition">
          <div class="in-content">
            <div class="in-in-content">
              <img src="{{asset('leaf_acconting_mobile/img/icon/bank.png')}}" alt="category">
              <h5><br> Bank Setup  </h5>
            </div>
          </div>
        </div></a>
      </div>

      <div class="col s4 m4 l2"><a class="icon-content nav-link" href="{{action('AppsIETransactionsController@getProduct')}}">
        <div class="content fadetransition">
          <div class="in-content">
            <div class="in-in-content">
              <img src="{{asset('leaf_acconting_mobile/img/icon/document.png')}}" alt="category">
              <h5><br> Item Setup </h5>
            </div>
          </div>
        </div></a>
      </div>

      <div class="col s4 m4 l2"><a class="icon-content nav-link" href="{{action('AppsIETransactionsController@getReport')}}">
        <div class="content fadetransition">
          <div class="in-content">
            <div class="in-in-content">
              <img src="{{asset('leaf_acconting_mobile/img/icon/report.png')}}" alt="category">
              <h5><br> Report </h5>
            </div>
          </div>
        </div></a>
      </div>

    </div>
  </div>
  <br><br><br>
</div>
<!-- END CATEGORY -->




  


@endsection
@section('script')



@endsection
