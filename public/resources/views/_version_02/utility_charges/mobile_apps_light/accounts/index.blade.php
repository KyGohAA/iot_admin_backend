@extends('_version_02.utility_charges.mobile_apps_light.layouts.main')
@section('content')
<!-- CONTENT -->
<div id="page-content">
  <div class="section gallery section section_team">
    <div class="container">
        <div class="row">
              <div class="wrap-gallery">
                <!-- item -->
                    <!-- member id  -->
                    <div class="gallery-img-box gallery-hospital">
                      <div class="row row-team">
                        <div class="col s12">
                          <div class="wrap-team">
                            <div class="wt-left">
                              <div class="wt-photo">
                                <img src="{{asset($user['photo'])}}" alt="doctor">
                              </div>
                            </div>
                            <div class="wt-right">
                              <div class="wtr-name"><i class="fa fa fa-id-card"></i> &nbsp; &nbsp; Personal Detail</div>
                              <div class="wtr-title">{{$user['fullname']}}</div>
                              <div class="wtr-info">{{$user['phone_number'] != '' ? $user['phone_number'] : $user['email']}}</div>
                              <div class="wtr-sosmed">
                                <div class="sosmed-icon">
                                 
                                <!--   <a href="#" class="linkedin-bg icon-round"> -->
                               
                                 <!--  </a> -->
                                  <div class="clear"></div>
                                </div>
                              </div>
                            </div>
                            <div class="clear"></div>
                          </div>
                        </div>
                      </div>
     
                        <div class="row row-team">
                          <div class="col s12">
                            <div class="wrap-team">
                              <div class="wtr-name" style="margin-left:20px;"><i class="fa fa fa-id-card"></i> &nbsp; &nbsp; Language Setting</div>
                              <div class="wtr-name" style="margin-left:20px;margin-right:20px;">{!! Form::select('language_code', App\Language::combo_box(), null, ['class'=>'form-control' , 'onchange'=>'init_language(this);']) !!}</div>
                              <div class="clear"></div>
                            </div>
                          </div>
                        </div>
              
                    </div>

                      <!-- end member id -->
                <!-- end item -->

              </div><!-- end wrap -->
            </div><!-- end row -->
    </div>
  </div>
</div>
<!-- END CONTENT -->

 
@endsection
@section('script')
@endsection