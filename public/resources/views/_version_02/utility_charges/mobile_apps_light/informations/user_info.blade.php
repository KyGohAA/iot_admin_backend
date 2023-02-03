@extends('_version_02.utility_charges.mobile_apps_light.layouts.main')
@section('content')

      <section class="hk-sec-wrapper">
          <h5 class="hk-sec-title">{{$notice['title']}}</h5>
           <div class="row">
              <div class="col-sm">
                  <div class="row">
                      <div class="col-lg-12 col-md-12 col-sm-12">
                          <div class="card">
                              <img class="card-img-top" src="{{asset($page_variables['logo_photo_path'])}}" alt="Card image cap" style="padding-top:5%;max-height: 30%;">
                              <div class="card-body">
                                  <!-- <h5 class="card-title"></h5> -->
                                  <p class="card-text">{!!html_entity_decode($notice['detail'])!!}</p>
                                  <!-- <p class="card-text"><small class="text-muted"></small></p> -->
                              </div>
                          </div>
                      </div>
                      
                  </div>
              </div>
          </div>
      </section>

@endsection
@section('script')
@endsection