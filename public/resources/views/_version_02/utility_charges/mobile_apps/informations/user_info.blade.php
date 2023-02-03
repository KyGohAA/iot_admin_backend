@extends('_version_02.utility_charges.mobile_apps.layouts.main')
@section('content')

<!-- CONTENT -->
<div id="page-content">
  <div class="section news">
    <div class="container">
      <div class="row row-title">
        <div class="col s12">
          <div class="section-title">
            <span class="theme-secondary-color">{{$notice['title']}}</span>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col s12">
          <div class="news-content center">
            <div class="row">
            <img style="padding-top:5%;max-height: 150px;" src="{{asset($page_variables['logo_photo_path'])}}" alt="image-news">
          </div>
            <div class="news-detail center">
              <hr>
              <h5 class="news-title"></h5>
              <p>
                 {!!html_entity_decode($notice['detail'])!!}
              </p>
                           
            </div>
           
                  <div style="margin-top:40px;" class="center">
                    <a class="btn theme-btn-rounded nav-link" type="submit" href="{{action('AppsUtilityChargesController@getDashboard')}}">Go Home</a>
                  </div>
          </div>
        </div>

      </div>
       
    </div>
  </div>
</div>
<!-- END CONTENT -->



<br><br><br>
@endsection
@section('script')
@endsection